<?php
require_once 'config.php';
require_once 'fpdf/fpdf.php';

setlocale(LC_ALL, '');
$userID = $_SESSION['userid'];
$fecha_modificacion = date("Y-m-d H:i:s");
$area_bd_temuco = ( $_SESSION['emp'] == 'municipal' ) ? 'MUNICIPALIDAD' : strtoupper($_SESSION['emp']);

if( !empty($_GET) ){

$ids = $_GET["id"];

$sql = "SELECT * FROM param_decreto_baja LIMIT 1";
$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
$org = mysqli_fetch_assoc($res);
mysqli_free_result($res);


class PDF extends FPDF
{
    // Cabecera de p�gina
    function Header()
    {
        global $org, $opc;
        // L�nea
        // $this->Line(20, 3, 200, 3);
        // Logo
        if( !empty($org['deba_rutaLogo']) ){
            $this->Image($org['deba_rutaLogo'],25,10,20,0);
        }
        // Arial bold 15
        $this->SetFont('Arial','BU',12);
        // Movernos a la derecha
        // $this->Cell(0);
        // T�tulo        
        $this->Cell(0,20,'Ficha Detalle del Activo Fijo',0,0,'C');
        // Salto de l�nea
        $this->Ln(25);
    }
    
    // Pie de p�gina
    function Footer()
    {
        // Posici�n: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // N�mero de p�gina
        //$this->Cell(0,8,'P�gina '.$this->PageNo().'/{nb}',0,0,'C');
    }
}


// Obtiene el �ltimo folio de baja de la BD para crear el nuevo
$sql = "SELECT baj_folio FROM decretos_baja ORDER BY baj_folio DESC LIMIT 1";
$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
$num = mysqli_fetch_assoc($res);
$folio = ( empty($num['baj_folio']) ) ? 1 : ( (int) $num['baj_folio'] ) + 1;
mysqli_free_result($res);

$query = "SELECT act_codigo, act_codEstado, act_descripcion, act_descripcionDetallada, aneg_descripcion, ubi_codigo, 
         act_fechaIngreso, act_tipoControl, cing_descripcion, ubi_descripcion, 
         anac_descripcion, aux_razonSocial, act_valorResidual, 
         act_fechaAdquisicion, act_vidaUtilTributaria, act_valorAdquisicion, act_numDocCompra, 
         act_numOrdenCompra, act_presupuesto, act_rutaImagen,  
         tdoc_descripcion, cond_descripcion, act_fechaOC, act_marca, act_modelo, act_serie, act_patenteVehiculo, act_siglaVehiculo
         FROM activos_fijos AF
            JOIN areas_negocios AN
                ON AF.aneg_id=AN.aneg_id
                    JOIN ubicaciones UB
                        ON AF.ubi_id=UB.ubi_id
							LEFT JOIN conceptos_ingreso CI
                                ON AF.cing_id=CI.cing_id
                                    LEFT JOIN condiciones_activos CA
                                        ON AF.cond_id=CA.cond_id
											LEFT JOIN tipo_documento TD
                                                ON AF.tdoc_id=TD.tdoc_id
                                                    LEFT JOIN analiticos_cuentas AC
                                                        ON AF.anac_id=AC.anac_id                                                                        
                                                            LEFT JOIN proveedores AUX
																ON AF.aux_id=AUX.aux_id WHERE
																	AF.act_id=".$ids.
																		" ORDER BY AF.act_id ASC";



$resGeneral = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
$resDetalle = mysqli_query($conexion, $query) or die(mysqli_error($conexion));


// Creaci�n del objeto de la clase heredada
$pdf = new PDF('P','mm','Legal');
//Define m�rgen izq, sup, y der.
$pdf->SetMargins(25, 10, 20);
$pdf->AliasNbPages();


while( $data = mysqli_fetch_assoc($resDetalle) ){
    
    $pdf->AddPage();
    
    //$pdf->SetFont('Arial', 'B', 11);
    //$pdf->Cell(0, 15, 'Ficha Detalle del Activo Fijo', 0, 0, 'C');
    //$pdf->Ln(15);

    $wT=45; $wD=126; $h=5; $b=1;
    $pdf->SetFont('Arial','B',10);
    
    $pdf->Cell($wT, $h, 'Atributo', $b, 0, 'C');
    $pdf->Cell($wD, $h, 'Descripci�n', $b, 1, 'C');
   
    // T�tulos del Detalle
    $titulosActivo = array(                                                
                            "C�digo", 
                            "Descripci�n",
                            "Establecimiento",
                            "Direcci�n",
                            "Departamento",
                            "Ubicaci�n",
                            "Fecha Ingreso", 
                            "Concepto Ingreso",                            
                            "Valor Adquisici�n",
                            "Estado del Bien",
                            "Clasificaci�n del Bien",                            
                            "Proveedor",                            
                            "Tipo Documento",
                            "N� Documento",                            
                            "Fecha Adquisici�n",                            
                            "N� Orden Compra",
                            "Fecha OC",
                            "Marca",
                            "Modelo",
                            "Serie",
                            "Presupuesto",
                            "Patente",
                            "Sigla",
                            "Vida �til",
                            "Valor Residual",
                            "Estado Bien"                          
                          );

    /* Querys para obtener nivel 1 y 2 de ubicaci�n de manera indirecta a trav�s del c�d. de nivel 3 */
    $codUbi3 = $data['ubi_codigo'];
    
    list($nivel1, $nivel2, $nivel3) = explode('-', $codUbi3);
    
    $nv_1 = $nivel1.'-00-00';
    $nv_2 = $nivel1.'-'.$nivel2.'-00';
    //$nv_3 = $nivel1.'-'.$nivel2.'-'.$nivel3;
    
    $sql = "SELECT ubi_codigo, ubi_descripcion FROM ubicaciones WHERE ubi_codigo='".$nv_1."' LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $nv1 = mysqli_fetch_assoc($res);
    mysqli_free_result($res);

    $sql = "SELECT ubi_codigo, ubi_descripcion FROM ubicaciones WHERE ubi_codigo='".$nv_2."' LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $nv2 = mysqli_fetch_assoc($res);
    mysqli_free_result($res);

    $sql = "SELECT ubi_codigo, ubi_descripcion FROM ubicaciones WHERE ubi_codigo='".$codUbi3."' LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $nv3 = mysqli_fetch_assoc($res);
    mysqli_free_result($res);
        
    // Cuerpo...
    $pdf->SetFont('Arial','',10);
    
    $i=0;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_codigo'], $b, 1);
    $i++;
    $especificacion = ( !empty($data['anac_descripcion']) ) ? $data['anac_descripcion'] : $data['act_descripcion'];
    $descripcionDetallada = $especificacion.' '.$data['act_descripcionDetallada'];
    $descripcionDetallada = ( strlen($descripcionDetallada) <= 80 ) ? $descripcionDetallada : substr($descripcionDetallada, 0, 80).'...';
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $descripcionDetallada, $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['aneg_descripcion'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $nv1['ubi_descripcion'], $b, 1); // ubi_nivel_1 (Direcci�n)
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $nv2['ubi_descripcion'], $b, 1); // ubi_nivel_2 (Depto)
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $nv3['ubi_descripcion'], $b, 1); // ubi_nivel_3 (Ubicaci�n / Oficina)
    $i++;        
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, date('d/m/Y', strtotime($data['act_fechaIngreso'])), $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['cing_descripcion'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, '$ '.number_format($data['act_valorAdquisicion'], 0, ',', '.').'.-', $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['cond_descripcion'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_tipoControl'], $b, 1);
    $i++;    
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['aux_razonSocial'], $b, 1);
    $i++;        
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['tdoc_descripcion'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_numDocCompra'], $b, 1);
    $i++;
    $fechaDoc = explode('-', $data['act_fechaAdquisicion']);
    $fechaDoc = $fechaDoc[2].'/'.$fechaDoc[1].'/'.$fechaDoc[0];
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $fechaDoc, $b, 1);
    $i++;    
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_numOrdenCompra'], $b, 1);
    $i++;            
    $fechaOC = explode('-', $data['act_fechaOC']);
    $fechaOC = $fechaOC[2].'/'.$fechaOC[1].'/'.$fechaOC[0];
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $fechaOC, $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_marca'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_modelo'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_serie'], $b, 1);
    $i++;        
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_presupuesto'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_patenteVehiculo'], $b, 1);
    $i++; 
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_siglaVehiculo'], $b, 1);
    $i++;         
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_vidaUtilTributaria'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, '$ '.number_format($data['act_valorResidual'], 0, ',', '.'), $b, 1);
    $i++;
    $act_codEstado = ( $data['act_codEstado'] == 'B' ) ? 'BAJA' : 'VIGENTE';
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $act_codEstado, $b, 1);
    $i++;
     
    // Fin del Cuerpo (detalle del activo)    
    
    $pdf->Ln(7);
    
    if( $data['act_rutaImagen'] != '' ){
        $pdf->Image($data['act_rutaImagen'], 65, null, 80);
    }else{
        $pdf->Cell(0, $h, 'El activo no registra una imagen.', 0, 1, 'C');
    }     
}

// Libera recursos de $query
mysqli_free_result($resDetalle);

$nombreCompletoDecreto = $_SESSION['emp'].'_Detalle_Activo_N_'.$folio.'.pdf';
// Genera PDF

// Sale PDF con todos los decretos en "vista previa"
$pdf->Output('I', $nombreCompletoDecreto);

mysqli_close($conexion);
}
else
{
    exit("No existen datos para ser procesados...");
}
?>
<?php
require_once 'config.php';
require_once 'fpdf/fpdf.php';

setlocale(LC_ALL, '');
$userID = $_SESSION['userid'];
$fecha_modificacion = date("Y-m-d H:i:s");
$area_bd_temuco = ( $_SESSION['emp'] == 'municipal' ) ? 'MUNICIPALIDAD' : strtoupper($_SESSION['emp']);

if( !empty($_GET) ){

$opc = array_shift($_GET); // extrae la opción a realizar (ver/generar)

if(isset($_GET["opc2"]))
{
$opc2 = array_shift($_GET);	
}
else $opc2 = "";

$ids = $_GET;

$raiz = '';
$ruta = 'copias_pdf_generados/decretosBaja/';


if($opc2 == "")
{
	$sql = "SELECT * FROM param_decreto_baja LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$org = mysqli_fetch_assoc($res);
	mysqli_free_result($res);

	$sql = "SELECT vbaj_id, vbaj_descripcion FROM param_vistos_baja ORDER BY vbaj_id ASC";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

	$vistos = array();

	while( $visto = mysqli_fetch_assoc($res) ){
		$vistos[] = $visto['vbaj_descripcion'];
	}

	mysqli_free_result($res);

	$sql = "SELECT dbaj_id, dbaj_descripcion FROM param_distri_baja ORDER BY dbaj_id ASC";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

	$distribucion = array();

	while( $distri = mysqli_fetch_assoc($res) ){
		$distribucion[] = $distri['dbaj_descripcion'];
	}

	mysqli_free_result($res);


}else if($opc2 == "ReGenerarInforme")
{
		
	$sql = "SELECT * FROM datos_decreto_baja WHERE dadeba_id= " . $ids[0] . " LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$datosDecretoBajaRealizado = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	
	$vistos = explode(".|", $datosDecretoBajaRealizado["dadeba_descripVistos"]);
	
	$distribucion = explode(".|", $datosDecretoBajaRealizado["dadeba_descripDistri"]);
	
	$org["deba_rutaLogo"] = $datosDecretoBajaRealizado["dadeba_rutaLogo"];
	$org["deba_nombreOrg"] = $datosDecretoBajaRealizado["dadeba_nombreOrg"]; 
	$org["deba_nombreSecre"] = $datosDecretoBajaRealizado["dadeba_nombreSecre"];
	$org["deba_nombreAlcalde"] = $datosDecretoBajaRealizado["dadeba_nombreAlcalde"];   
	$org["deba_cargoFirma1"] = $datosDecretoBajaRealizado["dadeba_cargoFirma1"];
	$org["deba_cargoFirma2"] = $datosDecretoBajaRealizado["dadeba_cargoFirma2"];
	$org["deba_porOrdenFirma1"] = $datosDecretoBajaRealizado["dadeba_porOrdenFirma1"];
	$org["deba_porOrdenFirma2"] = $datosDecretoBajaRealizado["dadeba_porOrdenFirma2"];
	$org["deba_iniciales"] = $datosDecretoBajaRealizado["dadeba_iniciales"];
	$org["deba_nombreDireccion"] = $datosDecretoBajaRealizado["dadeba_nombreDireccion"];
}


class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        global $org, $opc;
        // Línea
        // $this->Line(20, 3, 200, 3);
        // Logo
        if( !empty($org['deba_rutaLogo']) ){
            $this->Image($org['deba_rutaLogo'],25,10,20,0);
        }
        // Arial bold 15
        $this->SetFont('Arial','BU',12);
        // Movernos a la derecha
        // $this->Cell(0);
        // Título        
        $this->Cell(0,20,( $opc=='preview' ) ? 'Borrador' : '',0,0,'C');
        // Salto de línea
        $this->Ln(25);
    }
    
    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        //$this->Cell(0,8,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

if($opc2 == "")
{
	
	// Obtiene el último folio de baja de la BD para crear el nuevo
	$sql = "SELECT baj_folio FROM decretos_baja ORDER BY baj_folio DESC LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$num = mysqli_fetch_assoc($res);
	$folio = ( empty($num['baj_folio']) ) ? 1 : ( (int) $num['baj_folio'] ) + 1;
	mysqli_free_result($res);

	$query = "SELECT AF.act_id, act_codigo, act_codEstado, act_descripcion, act_descripcionDetallada, aneg_descripcion, ccos_descripcion, ubi_codigo, 
         resp_nombre, act_fechaIngreso, act_codBarras, act_venceUltimaGarantia, act_tipoControl, ccos_descripcion, cing_descripcion, ubi_descripcion, 
         anac_descripcion, aux_razonSocial, act_revalorizable, act_depreciable, act_tipoDepreciacion, act_valorResidual, act_porLote, act_cantidadLote, 
         act_situacionDePartida, act_fechaAdquisicion, act_vidaUtilTributaria, act_vidaUtilFinanciera, act_valorAdquisicion, act_numDocCompra, 
         act_numOrdenCompra, act_inicioRevalorizacion, act_bajoNormaPublica, act_presupuesto, act_bajoNormaIFRS, act_rutaImagen, act_glosaDeBaja,  
         ftec_descripcion, tdoc_descripcion, cond_descripcion, act_fechaOC, act_marca, act_modelo, act_serie, act_patenteVehiculo, act_siglaVehiculo
         FROM activos_fijos AF
            JOIN areas_negocios AN
                ON AF.aneg_id=AN.aneg_id
                    JOIN centros_costo CC
                        ON AF.ccos_id=CC.ccos_id
                            JOIN ubicaciones UB
                                ON AF.ubi_id=UB.ubi_id
                                    JOIN responsables RE
                                        ON UB.resp_id=RE.resp_id
                                            JOIN fichas_tecnicas FT
                                                ON AF.act_id=FT.act_id
                                                    LEFT JOIN conceptos_ingreso CI
                                                        ON AF.cing_id=CI.cing_id
                                                            LEFT JOIN condiciones_activos CA
                                                                ON AF.cond_id=CA.cond_id
                                                                    LEFT JOIN tipo_documento TD
                                                                        ON AF.tdoc_id=TD.tdoc_id
                                                                            LEFT JOIN analiticos_cuentas AC
                                                                                ON AF.anac_id=AC.anac_id                                                                        
                                                                                    LEFT JOIN proveedores AUX
                                                                                        ON AF.aux_id=AUX.aux_id WHERE";

	foreach( $ids as $key => $actID ){
		if( $key == 0 ){
			$query.= " AF.act_id=".$actID;
		}else{
			$query.= " OR AF.act_id=".$actID;
		}
	}

	$query .= " ORDER BY AF.act_id ASC";

	$resGeneral = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
	$resDetalle = mysqli_query($conexion, $query) or die(mysqli_error($conexion));


}
elseif($opc2 == "ReGenerarInforme")
{
	
	$folio = $datosDecretoBajaRealizado["baj_folio"];
	
	
	$query = "SELECT AF.act_id, act_codigo, act_codEstado, act_descripcion, act_descripcionDetallada, aneg_descripcion, ccos_descripcion, ubi_codigo, 
         resp_nombre, act_fechaIngreso, act_codBarras, act_venceUltimaGarantia, act_tipoControl, ccos_descripcion, cing_descripcion, ubi_descripcion, 
         anac_descripcion, aux_razonSocial, act_revalorizable, act_depreciable, act_tipoDepreciacion, act_valorResidual, act_porLote, act_cantidadLote, 
         act_situacionDePartida, act_fechaAdquisicion, act_vidaUtilTributaria, act_vidaUtilFinanciera, act_valorAdquisicion, act_numDocCompra, 
         act_numOrdenCompra, act_inicioRevalorizacion, act_bajoNormaPublica, act_presupuesto, act_bajoNormaIFRS, act_rutaImagen, act_glosaDeBaja,  
         ftec_descripcion, tdoc_descripcion, cond_descripcion, act_fechaOC, act_marca, act_modelo, act_serie, act_patenteVehiculo, act_siglaVehiculo
			FROM datos_decreto_baja AS DDB  
			JOIN decretos_baja DB
				ON DDB.baj_folio = DB.baj_folio
					JOIN activos_fijos AF
						ON DB.act_id = AF.act_id
							JOIN areas_negocios AN
								ON AF.aneg_id=AN.aneg_id
									JOIN centros_costo CC
										ON AF.ccos_id=CC.ccos_id
											JOIN ubicaciones UB
												ON AF.ubi_id=UB.ubi_id
													JOIN responsables RE
														ON UB.resp_id=RE.resp_id
															JOIN fichas_tecnicas FT
																ON AF.act_id=FT.act_id
																	LEFT JOIN conceptos_ingreso CI
																		ON AF.cing_id=CI.cing_id
																			LEFT JOIN condiciones_activos CA
																				ON AF.cond_id=CA.cond_id
																					LEFT JOIN tipo_documento TD
																						ON AF.tdoc_id=TD.tdoc_id
                                                                                            LEFT JOIN analiticos_cuentas AC
                                                                                                ON AF.anac_id=AC.anac_id                                                                        
                                                                                                    LEFT JOIN proveedores AUX
																								        ON AF.aux_id=AUX.aux_id
																					                       WHERE dadeba_id=" . $ids[0] . "
																						                      ORDER BY AF.act_id ASC ; ";
		
		
	$resGeneral = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
	$resDetalle = mysqli_query($conexion, $query) or die(mysqli_error($conexion));	
}

// Creación del objeto de la clase heredada
$pdf = new PDF('P','mm','Legal');
//Define márgen izq, sup, y der.
$pdf->SetMargins(25, 10, 20);
$pdf->AliasNbPages();

// Página #1
$pdf->AddPage();

// Encabezado
$pdf->SetFont('Arial','B',10);
$pdf->Cell(131, 8, '', 0);
$pdf->Cell(40, 8, 'DECRETO N°_______________/', 0, 0, 'R');
$pdf->Ln();

$pdf->Cell(0, 8, 'Decreto Baja  N° '.$folio);
$pdf->Ln();

//$fecha_hoy = date('d ').ucwords(strftime('%b')).date(' Y');
$pdf->Cell(0, 8, $org['deba_nombreOrg'].', ', 0, 0, 'C');
$pdf->Ln();

// Enunciado
$pdf->Cell(0, 8, 'VISTOS:');
$pdf->Ln();

// Detalle enunciado
$w=0; $h=5; $b=0; $t=10;    
$pdf->SetFont('Arial','',10);

foreach( $vistos as $item ){
    $pdf->Cell($t);
    $pdf->MultiCell($w, $h, $item, $b, 'L'); // Con Cell, textos muy largos se desbordan del margen...
    //$pdf->Write($h, $item);
    //$pdf->Ln();
}

$pdf->SetFont('Arial','B',10);
$pdf->Cell(0, 8, 'CONSIDERANDO:');
$pdf->Ln();

$pdf->SetFont('Arial','',10);
$pdf->Write($h, '1.- La necesidad de dar de baja bienes con posterior enajenación, ya que no prestan utilidad a la institución por encontrarse en mal estado y su reparación resulta onerosa.');
$pdf->Ln();

$pdf->SetFont('Arial','B',10);    
$pdf->Cell(0, 8, 'DECRETO:');
$pdf->Ln();

$pdf->SetFont('Arial','',10);
$pdf->Write($h, '1.- Elimínese del inventario de '.$org['deba_nombreDireccion'].', las especies descritas en la siguiente. nómina:');
$pdf->Ln(8);

// Títulos del General
$wD=141; $wC=30; $h=5; $b=1;
$pdf->SetFont('Arial','B',10);
$pdf->Cell($wD, $h, 'Especificación', $b);
$pdf->Cell($wC, $h, 'Cód. Inventario', $b, 1);

$pdf->SetFont('Arial','',10);

// Dibuja tabla con datos generales de c/u activo 
while( $data = mysqli_fetch_assoc($resGeneral) ){
    // vars permiten hacer columna 2 de misma altura
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $especificacion = ( !empty($data['anac_descripcion']) ) ? $data['anac_descripcion'] : $data['act_descripcion'];
    $pdf->MultiCell($wD, $h, $especificacion.' '.$data['act_descripcionDetallada'].'', $b, 'L');
    $H = $pdf->GetY();
    $height = $H-$y;    
    $pdf->SetXY($x+$wD,$y);
    $pdf->MultiCell($wC, $height, $data['act_codigo'], $b);
}

mysqli_free_result($resGeneral);

$pdf->Ln(5);

$pdf->SetFont('Arial','',10);

$pdf->Write($h, '2.- Procédase a dar de baja del inventario del Municipio los bienes que se indican en el punto N° 1.');
$pdf->Ln(8);
$pdf->Write($h, '3.- Los bienes mensionados en el punto N° 1, quedarán en poder y resguardo de la bodega del Recinto Municipal, hasta el día de la subasta el cual será determinado por la administración de este.');
$pdf->Ln(8);
$pdf->Write($h, '4.- El Departamento de Gestión de Abastecimiento a través de la Unidad de Inventario, procederá a efectuar los ajustes en los registros que se originen por la aplicación del presente Decreto.');
$pdf->Ln(10);

$pdf->Cell(0, 5, 'ANOTESE, COMUNIQUESE Y ARCHIVESE.');
$pdf->Ln(20);

// Firmas y Timbres
$pdf->SetFont('Arial','B',10);
$h=5;
$pdf->Cell(85, $h, $org['deba_porOrdenFirma1'], 0, 0, 'C');
$pdf->Cell(85, $h, $org['deba_porOrdenFirma2'], 0, 1, 'C');        
$pdf->Cell(85, $h, $org['deba_nombreSecre'], 0, 0, 'C');
$pdf->Cell(85, $h, $org['deba_nombreAlcalde'], 0, 1, 'C');    
$pdf->Cell(85, $h, $org['deba_cargoFirma1'], 0, 0, 'C');
$pdf->Cell(85, $h, $org['deba_cargoFirma2'], 0, 0, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','I',9);
$pdf->Cell(0, 5, $org['deba_iniciales']);
$pdf->Ln(8);

$w=0; $h=4; $b=0;
$pdf->SetFont('Arial','I',9);
$pdf->Cell($w, $h, 'DISTRIBUCIÓN:', 0, 1);

$pdf->SetFont('Arial','',9);

foreach( $distribucion as $destino ){
    $pdf->Cell($w, $h, $destino, 0, 1);
}

/** DIBUJA TABLA DETALLE DE C/U ACTIVOS X HOJA **/
    
// Dibuja tabla con datos generales de c/u activo 
while( $data = mysqli_fetch_assoc($resDetalle) ){
    //1 Pág x Activo
    $pdf->AddPage();
    
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 15, 'Detalle Activo Fijo', 0, 0, 'C');
    $pdf->Ln(15);

    $wT=45; $wD=126; $h=5; $b=1;
    $pdf->SetFont('Arial','B',10);
    
    $pdf->Cell($wT, $h, 'Atributo', $b, 0, 'C');
    $pdf->Cell($wD, $h, 'Descripción', $b, 1, 'C');
   
    // Títulos del Detalle
    $titulosActivo = array(                                                
                            "Código", 
                            "Descripción",
                            "Establecimiento",
                            "Dirección",
                            "Departamento",
                            "Ubicación",
                            "Fecha Ingreso", 
                            "Concepto Ingreso",                            
                            "Valor Adquisición",
                            "Estado del Bien",
                            "Clasificación del Bien",                            
                            "Proveedor",                            
                            "Tipo Documento",
                            "N° Documento",                            
                            "Fecha Adquisición",                            
                            "N° Orden Compra",
                            "Fecha OC",
                            "Marca",
                            "Modelo",
                            "Serie",
                            "Presupuesto",
                            "Patente",
                            "Sigla",
                            "Vida Útil",
                            "Valor Residual",
                            "Estado Bien",
                            "Descripción de la Baja"                            
                          );

    /* Querys para obtener nivel 1 y 2 de ubicación de manera indirecta a través del cód. de nivel 3 */
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
    $pdf->Cell($wD, $h, $nv1['ubi_descripcion'], $b, 1); // ubi_nivel_1 (Dirección)
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $nv2['ubi_descripcion'], $b, 1); // ubi_nivel_2 (Depto)
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $nv3['ubi_descripcion'], $b, 1); // ubi_nivel_3 (Ubicación / Oficina)
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
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_glosaDeBaja'], $b, 1);
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

$nombreCompletoDecreto = $_SESSION['emp'].'_Decreto_de_Baja_N_'.$folio.'.pdf';
// Genera PDF según $opc
if( $opc == 'preview' ){
    // Sale PDF con todos los decretos en "vista previa"
    $pdf->Output('I', $nombreCompletoDecreto);
}else{
    
	if($opc2 == "")
	{    
		foreach( $ids as $key => $actID ){
			// Registra trazabilidad de núm. dectreto => activo
			$sql = "INSERT INTO decretos_baja(baj_id, baj_folio, act_id, baj_creador) VALUES(NULL, '".$folio."', '".$actID."', '".$userID."')";
			$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
        
			$sql = "UPDATE activos_fijos SET 
			act_decretoBaja=1, act_modificador='".$userID."', act_modificacion='".$fecha_modificacion."' WHERE act_id=".$actID." LIMIT 1";
			$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));    
		}

		//se unen todos los string que forman el arreglo $Vistos, estos seran separados con el elemento .|
		$stringVistos = "";
		foreach( $vistos as $item )
		{
			if($stringVistos == "") $stringVistos = $item;
			else{
				$stringVistos .= ".|" . $item;
			}	
		}	
	
		//se unen todos los string que forman el arreglo $Distribucion, estos seran separados con el elemento .|
		$stringDistrib = "";
		foreach( $distribucion as $destino )
		{
			if($stringDistrib == "") $stringDistrib = $destino;
			else{
				$stringDistrib .= ".|" . $destino;
			}	
		}	
	
		$sql = "INSERT INTO datos_decreto_baja (baj_folio, dadeba_nombreOrg, dadeba_nombreDireccion, 
											dadeba_nombreSecre, dadeba_porOrdenFirma1, dadeba_cargoFirma1,dadeba_nombreAlcalde,
											dadeba_porOrdenFirma2, dadeba_cargoFirma2, dadeba_iniciales, dadeba_rutaLogo,
											dadeba_descripDistri, dadeba_descripVistos, dadeba_creador)
									VALUES ('".$folio."', '".$org['deba_nombreOrg']."', '".$org['deba_nombreDireccion']."',
											'".$org['deba_nombreSecre']."', '".$org['deba_porOrdenFirma1']."', 
											'".$org['deba_cargoFirma1']."','".$org['deba_nombreAlcalde']."',
											'".$org['deba_porOrdenFirma2']."', '".$org['deba_cargoFirma2']."', 
											'".$org['deba_iniciales']."', '".$org['deba_rutaLogo']."',
											'".$stringDistrib."', '".$stringVistos."', '".$userID."')";
	
		$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    }
	
	// Sale PDF con todos los decrtetos en "modo descarga e impresión" 
    $pdf->Output('I', $nombreCompletoDecreto);
    // Guarda copia del PDF en "directorio local"
    $pdf->Output('F', $raiz.$ruta.$nombreCompletoDecreto);
	
}
mysqli_close($conexion);
}
else
{
    exit("No existen datos para ser procesados...");
}
?>
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
$ruta = 'copias_pdf_generados/decretosReincorporacion/';


if($opc2 == "")
{
	$sql = "SELECT * FROM param_decreto_reincorporacion LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$org = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	
	$sql = "SELECT vrei_id, vrei_descripcion FROM param_vistos_reincorporacion ORDER BY vrei_id ASC";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	
	$vistos = array();

	while( $visto = mysqli_fetch_assoc($res) ){
		$vistos[] = $visto['vrei_descripcion'];
	}

	mysqli_free_result($res);

	$sql = "SELECT drei_id, drei_descripcion FROM param_distri_reincorporacion ORDER BY drei_id ASC";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

	$distribucion = array();
	
	while( $distri = mysqli_fetch_assoc($res) ){
		$distribucion[] = $distri['drei_descripcion'];
	}	

	mysqli_free_result($res);
	
}else if($opc2 == "ReGenerarInforme")
{
		
	$sql = "SELECT * FROM datos_decreto_reincorporacion WHERE dadere_id= " . $ids[0] . " LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$datosDecretoBajaRealizado = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	
	$vistos = explode(".|", $datosDecretoBajaRealizado["dadere_descripVistos"]);
	
	$distribucion = explode(".|", $datosDecretoBajaRealizado["dadere_descripDistri"]);
	
	$org["derei_rutaLogo"] = $datosDecretoBajaRealizado["dadere_rutaLogo"];
	$org["derei_nombreOrg"] = $datosDecretoBajaRealizado["dadere_nombreOrg"]; 
	$org["derei_nombreSecre"] = $datosDecretoBajaRealizado["dadere_nombreSecre"];
	$org["derei_nombreAlcalde"] = $datosDecretoBajaRealizado["dadere_nombreAlcalde"];   
	$org["derei_cargoFirma1"] = $datosDecretoBajaRealizado["dadere_cargoFirma1"];
	$org["derei_cargoFirma2"] = $datosDecretoBajaRealizado["dadere_cargoFirma2"];
	$org["derei_porOrdenFirma1"] = $datosDecretoBajaRealizado["dadere_porOrdenFirma1"];
	$org["derei_porOrdenFirma2"] = $datosDecretoBajaRealizado["dadere_porOrdenFirma2"];
	$org["derei_iniciales"] = $datosDecretoBajaRealizado["dadere_iniciales"];
	$org["derei_nombreDireccion"] = $datosDecretoBajaRealizado["dadere_nombreDireccion"];
}

class PDF extends FPDF
{
    // Cabecera de p?gina
    function Header()
    {
        global $org, $opc;
        // L?nea
        // $this->Line(20, 3, 200, 3);
        // Logo
        if( !empty($org['derei_rutaLogo']) ){
            $this->Image($org['derei_rutaLogo'],25,10,20,0);
        }
        // Arial bold 15
        $this->SetFont('Arial','BU',12);
        // Movernos a la derecha
        // $this->Cell(0);
        // T?tulo
        $this->Cell(0,20,( $opc=='preview' ) ? 'Borrador' : '',0,0,'C');
        // Salto de l?nea
        $this->Ln(25);
    }

    // Pie de p?gina
    function Footer()
    {
        // Posici?n: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',7);
        // N?mero de p?gina
        //$this->Cell(0,8,'Página '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

if($opc2 == "")
{
	// Obtiene el ?ltimo folio de baja de la BD para crear el nuevo
	$sql = "SELECT derei_folio FROM decretos_reincorporacion ORDER BY derei_folio DESC LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$num = mysqli_fetch_assoc($res);
	$folio = ( empty($num['derei_folio']) ) ? 1 : ( (int) $num['derei_folio'] ) + 1;
	mysqli_free_result($res);

	$query = "SELECT AF.act_id, act_codigo, act_codEstado, act_descripcionDetallada, aneg_descripcion, ccos_descripcion, ubi_codigo, resp_nombre,
         act_fechaIngreso, act_codBarras, act_venceUltimaGarantia, act_tipoControl, ccos_descripcion, cing_descripcion, ubi_descripcion,
         aux_razonSocial, act_revalorizable, act_depreciable, act_tipoDepreciacion, act_valorResidual, act_porLote, act_cantidadLote,
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
	
	$folio = $datosDecretoBajaRealizado["derei_folio"];
	
	
	$query = "SELECT AF.act_id, act_codigo, act_codEstado, act_descripcion, act_descripcionDetallada, aneg_descripcion, ccos_descripcion, ubi_codigo, 
         resp_nombre, act_fechaIngreso, act_codBarras, act_venceUltimaGarantia, act_tipoControl, ccos_descripcion, cing_descripcion, ubi_descripcion, 
         anac_descripcion, aux_razonSocial, act_revalorizable, act_depreciable, act_tipoDepreciacion, act_valorResidual, act_porLote, act_cantidadLote, 
         act_situacionDePartida, act_fechaAdquisicion, act_vidaUtilTributaria, act_vidaUtilFinanciera, act_valorAdquisicion, act_numDocCompra, 
         act_numOrdenCompra, act_inicioRevalorizacion, act_bajoNormaPublica, act_presupuesto, act_bajoNormaIFRS, act_rutaImagen, act_glosaDeBaja,  
         ftec_descripcion, tdoc_descripcion, cond_descripcion, act_fechaOC, act_marca, act_modelo, act_serie, act_patenteVehiculo, act_siglaVehiculo
			FROM datos_decreto_reincorporacion AS DDR  
			JOIN decretos_reincorporacion DR
				ON DDR.derei_folio = DR.derei_folio
					JOIN activos_fijos AF
						ON DR.act_id = AF.act_id
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
																					                       WHERE dadere_id=" . $ids[0] . "
																						                      ORDER BY AF.act_id ASC ; ";
		
		
	$resGeneral = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
	$resDetalle = mysqli_query($conexion, $query) or die(mysqli_error($conexion));	
}



// Creaci?n del objeto de la clase heredada
$pdf = new PDF('P','mm','Legal');
//Define m?rgen izq, sup, y der.
$pdf->SetMargins(25, 10, 20);
$pdf->AliasNbPages();

// P?gina #1
$pdf->AddPage();

// Encabezado
$pdf->SetFont('Arial','B',9);
$pdf->Cell(125, 8, '', 0);
$pdf->Cell(40, 8, 'DECRETO N°_______________/', 0, 0, 'R');
$pdf->Ln();

$pdf->Cell(0, 8, 'Decreto Reincorporación  N° '.$folio);
$pdf->Ln();

//$fecha_hoy = date('d ').ucwords(strftime('%b')).date(' Y');
$pdf->Cell(0, 8, $org['derei_nombreOrg'].', ', 0, 0, 'C');
$pdf->Ln();

// Enunciado
$pdf->Cell(0, 8, 'VISTOS:');
$pdf->Ln();

// Detalle enunciado
$w=0; $h=5; $b=0; $t=1;
$pdf->SetFont('Arial','',9);

foreach( $vistos as $item ){
    $pdf->Cell($t);
    $pdf->MultiCell($w, $h, $item, $b, 'L'); // Con Cell, textos muy largos se desbordan del margen...
    //$pdf->Write($h, $item);
    //$pdf->Ln();
}

$pdf->SetFont('Arial','B',9);
$pdf->Cell(0, 8, 'CONSIDERANDO:');
$pdf->Ln();

$pdf->SetFont('Arial','',9);
$pdf->Write($h, '1.-La necesidad de incorporar los bienes a la unidad solicitante ya que aún prestan utilidad y existe presupuesto para reparar por parte de la unidad requirente.');
$pdf->Ln();

$pdf->SetFont('Arial','B',9);
$pdf->Cell(0, 8, 'DECRETO:');
$pdf->Ln();

$pdf->SetFont('Arial','',9);
$pdf->Write($h, '1.- Reincorpórese al inventario de: '.$org['derei_nombreDireccion'].', las especies descritas en la sgte. nómina:');
$pdf->Ln(8);

// T?tulos del General
$wD=125; $wC=40; $h=5; $b=1;
$pdf->SetFont('Arial','B',9);
$pdf->Cell($wD, $h, 'Especificación', $b);
$pdf->Cell($wC, $h, 'Cód. Inventario', $b, 1);

$pdf->SetFont('Arial','',9);

// Dibuja tabla con datos generales de c/u activo
while( $data = mysqli_fetch_assoc($resGeneral) ){
    $pdf->Cell($wD, $h, $data['act_descripcionDetallada'], $b);
    $pdf->Cell($wC, $h, $data['act_codigo'], $b, 1);
}

mysqli_free_result($resGeneral);

$pdf->Ln();

$pdf->SetFont('Arial','',9);

$pdf->Write($h, '2.- Procédase a incorporar al inventario del inventario del Municipio los bienes que se indican en el punto N° 1.');
$pdf->Ln(8);
//$pdf->Write($h, '3.- Los bienes mencionados en el punto N° 1, quedarón en poder y resguardo de la bodega del Recinto Municipal, hasta el día de la subasta el cual será determinado por la administración de este.');
//$pdf->Ln(8);
$pdf->Write($h, '3.- El Departamento de Gestión de Abastecimiento a través de la Unidad de Inventario, procederá a efectuar los ajustes en los registros que se originen por la aplicación del presente Decreto.');
$pdf->Ln(15);

$pdf->Cell(0, 5, 'ANOTESE, COMUNIQUESE Y ARCHIVESE.');
$pdf->Ln(20);

// Firmas y Timbres
$pdf->SetFont('Arial','B',9);
$h=5;
$pdf->Cell(80, $h, $org['derei_porOrdenFirma1'], 0, 0, 'C');
$pdf->Cell(85, $h, $org['derei_porOrdenFirma2'], 0, 1, 'C');
$pdf->Cell(80, $h, $org['derei_nombreSecre'], 0, 0, 'C');
$pdf->Cell(85, $h, $org['derei_nombreAlcalde'], 0, 1, 'C');
$pdf->Cell(80, $h, $org['derei_cargoFirma1'], 0, 0, 'C');
$pdf->Cell(85, $h, $org['derei_cargoFirma2'], 0, 0, 'C');
$pdf->Ln(8);

$pdf->SetFont('Arial','I',9);
$pdf->Cell(0, 5, $org['derei_iniciales']);
$pdf->Ln(8);

$w=0; $h=5; $b=0;
$pdf->SetFont('Arial','I',9);
$pdf->Cell($w, $h, 'DISTRIBUCIÓN:', 0, 1);

$pdf->SetFont('Arial','',9);

foreach( $distribucion as $destino ){
    $pdf->Cell($w, $h, $destino, 0, 1);
}



// Genera PDF seg?n $opc
if( $opc == 'preview' ){
    // Sale PDF con todos los decrtetos en "vista previa"
    $pdf->Output('I', 'Decreto_de_Reincorporacion_N_'.$folio.'.pdf');
}else{
	if($opc2 == "")
	{	

		foreach( $ids as $key => $actID ){
			// Registra trazabilidad de n?m. dectreto => activo
			$sql = "INSERT INTO decretos_reincorporacion(derei_id, derei_folio, act_id, derei_creador) VALUES(NULL, '".$folio."', '".$actID."', '".$userID."')";
			$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

			$sql = "UPDATE activos_fijos SET
			act_codEstado='V', act_decretoBaja=0, act_modificador='".$userID."', act_modificacion='".$fecha_modificacion."' WHERE act_id=".$actID." LIMIT 1";
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
	
		$sql = "INSERT INTO datos_decreto_reincorporacion (derei_folio, dadere_nombreOrg, dadere_nombreDireccion, 
											dadere_nombreSecre, dadere_porOrdenFirma1, dadere_cargoFirma1,dadere_nombreAlcalde,
											dadere_porOrdenFirma2, dadere_cargoFirma2, dadere_iniciales, dadere_rutaLogo,
											dadere_descripDistri, dadere_descripVistos, dadere_creador)
									VALUES ('".$folio."', '".$org['derei_nombreOrg']."', '".$org['derei_nombreDireccion']."',
											'".$org['derei_nombreSecre']."', '".$org['derei_porOrdenFirma1']."', 
											'".$org['derei_cargoFirma1']."','".$org['derei_nombreAlcalde']."',
											'".$org['derei_porOrdenFirma2']."', '".$org['derei_cargoFirma2']."', 
											'".$org['derei_iniciales']."', '".$org['derei_rutaLogo']."',
											'".$stringDistrib."', '".$stringVistos."', '".$userID."')";
	
		$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    

    
	}
		// Sale PDF con todos los decrtetos en "modo descarga"
		$pdf->Output('D', 'Decreto_de_Reincorporacion_N_'.$folio.'.pdf');
		// Guarda copia del PDF en "directorio local"
		$pdf->Output('F', $raiz.$ruta.'Decreto_de_Reincorporacion_N_'.$folio.'.pdf');	
}
mysqli_close($conexion);
}
else
{
    exit("No existen datos para ser procesados...");
}
?>

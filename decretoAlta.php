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
$ruta = 'copias_pdf_generados/decretosAlta/';

if($opc2 == "")
{	
	$sql = "SELECT * FROM param_decreto_alta LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$org = mysqli_fetch_assoc($res);
	mysqli_free_result($res);

	$sql = "SELECT valt_id, valt_descripcion FROM param_vistos_alta ORDER BY valt_id ASC";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

	$vistos = array();

	while( $visto = mysqli_fetch_assoc($res) ){
		$vistos[] = $visto['valt_descripcion'];
	}

	mysqli_free_result($res);

	$sql = "SELECT dalt_id, dalt_descripcion FROM param_distri_alta ORDER BY dalt_id ASC";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

	$distribucion = array();

	while( $distri = mysqli_fetch_assoc($res) ){
		$distribucion[] = $distri['dalt_descripcion'];
	}

	mysqli_free_result($res);

}else if($opc2 == "ReGenerarInforme")
{
		
	$sql = "SELECT * FROM datos_decreto_alta WHERE dadeal_id= " . $ids[0] . " LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$datosDecretoBajaRealizado = mysqli_fetch_assoc($res);
	mysqli_free_result($res);
	
	$vistos = explode(".|", $datosDecretoBajaRealizado["dadeal_descripVistos"]);
	
	$distribucion = explode(".|", $datosDecretoBajaRealizado["dadeal_descripDistri"]);
	
	$org["deal_rutaLogo"] = $datosDecretoBajaRealizado["dadeal_rutaLogo"];
	$org["deal_nombreOrg"] = $datosDecretoBajaRealizado["dadeal_nombreOrg"]; 
	$org["deal_nombreSecre"] = $datosDecretoBajaRealizado["dadeal_nombreSecre"];
	$org["deal_nombreAlcalde"] = $datosDecretoBajaRealizado["dadeal_nombreAlcalde"];   
	$org["deal_cargoFirma1"] = $datosDecretoBajaRealizado["dadeal_cargoFirma1"];
	$org["deal_cargoFirma2"] = $datosDecretoBajaRealizado["dadeal_cargoFirma2"];
	$org["deal_porOrdenFirma1"] = $datosDecretoBajaRealizado["dadeal_porOrdenFirma1"];
	$org["deal_porOrdenFirma2"] = $datosDecretoBajaRealizado["dadeal_porOrdenFirma2"];
	$org["deal_iniciales"] = $datosDecretoBajaRealizado["dadeal_iniciales"];
	$org["deal_nombreDireccion"] = $datosDecretoBajaRealizado["dadeal_nombreDireccion"];
}

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        global $org, $opc, $area_bd_temuco;
        // Línea
        // $this->Line(20, 3, 200, 3);
        // Logo
        if( !empty($org['deal_rutaLogo']) ){
            $this->Image($org['deal_rutaLogo'],25,10,20,0);
        }
        // Arial bold 15
        $this->SetFont('Arial','BU',12);
        // Movernos a la derecha
        // $this->Cell(0);
        // Título
        $this->Cell(0,5,( $opc == 'preview' ) ? 'Borrador' : '',0,0,'C');
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

//require_once 'traeDatosActivo.php';

// llama funcion q crea nuevo folio centralizado
//$folio = nuevoFolioDecreto('alta');
//var_dump($folio);

// Creación del objeto de la clase heredada
$pdf = new PDF('P','mm','Legal');
//Define márgen izq, sup, y der.
$pdf->SetMargins(25, 10, 15);
//$pdf->SetLeftMargin(20);
$pdf->AliasNbPages();

if($opc2 == "")
{	
	$sql = "SELECT alt_folio FROM decretos_alta ORDER BY alt_folio DESC LIMIT 1";
	$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	$num = mysqli_fetch_assoc($res);
	$folio = ( empty($num['alt_folio']) ) ? 1 : ( (int) $num['alt_folio'] ) + 1;
	mysqli_free_result($res);

	$query = "SELECT AF.act_id, act_codigo, act_descripcionDetallada, aneg_codigo, aneg_descripcion, ccos_codigo, ubi_codigo, resp_nombre, 
         act_fechaIngreso, act_codBarras, AF.aneg_id, AF.ccos_id, ccos_descripcion, grup_descripcion, sgru_descripcion, cing_descripcion, 
         AF.ubi_id, ubi_descripcion, aux_rut, aux_razonSocial, act_porLote, act_cantidadLote, act_situacionDePartida, act_fechaAdquisicion, 
         act_vidaUtilTributaria, act_valorAdquisicion, act_numDocCompra, act_numOrdenCompra, act_fechaOC, tdoc_descripcion, ftec_descripcion, 
         ccont_codigo, ccont_descripcion, act_presupuesto, AC.anac_codigo, anac_descripcion, SUBSTR(TA.coda_codigo, 1, 1) AS titu_codigo, 
         TA.coda_descripcion AS titu_descripcion, SUBSTR(GA.coda_codigo, 2, 1) AS grua_codigo, GA.coda_descripcion AS grua_descripcion, 
         SUBSTR(SGA.coda_codigo, 3, 1) AS sgrua_codigo, SGA.coda_descripcion AS sgrua_descripcion, SUBSTR(CUA.coda_codigo, 4, 2) AS cuen_codigo, 
         CUA.coda_descripcion AS cuen_descripcion, SUBSTR(SCA.coda_codigo, 6) AS scuen_codigo, SCA.coda_descripcion AS scuen_descripcion
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
                               LEFT JOIN analiticos_cuentas AC
                                 ON AF.anac_id=AC.anac_id
                                   LEFT JOIN codificador_analiticos TA
                                     ON SUBSTR(AC.coda_codigo, 1, 1)=TA.coda_codigo
                                       LEFT JOIN codificador_analiticos GA
                                         ON SUBSTR(AC.coda_codigo, 1, 2)=GA.coda_codigo
                                           LEFT JOIN codificador_analiticos SGA
                                             ON SUBSTR(AC.coda_codigo, 1, 3)=SGA.coda_codigo
                                               LEFT JOIN codificador_analiticos CUA
                                                 ON SUBSTR(AC.coda_codigo, 1, 5)=CUA.coda_codigo
                                                   LEFT JOIN codificador_analiticos SCA
                                                     ON AC.coda_codigo=SCA.coda_codigo
                                                       LEFT JOIN grupos GRU
                                                         ON AF.grup_id=GRU.grup_id
                                                           LEFT JOIN sub_grupos SGR
                                                             ON AF.sgru_id=SGR.sgru_id
                                                               LEFT JOIN conceptos_ingreso CI
                                                                 ON AF.cing_id=CI.cing_id
                                                                   LEFT JOIN cuentas_contables CCO
                                                                     ON AF.ccont_id=CCO.ccont_id
                                                                       LEFT JOIN proveedores AUX
                                                                         ON AF.aux_id=AUX.aux_id
                                                                           LEFT JOIN tipo_documento TD
                                                                             ON AF.tdoc_id=TD.tdoc_id WHERE";

	foreach( $ids as $key => $actID ){
		if( $key == 0 ){
			$query.= " AF.act_id=".$actID;
		}else{
			$query.= " OR AF.act_id=".$actID;
		}
	}

	$query .= " ORDER BY AF.act_id ASC";

	$result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
	$numReg = mysqli_num_rows($result);

	$idLote = array();
}
elseif($opc2 == "ReGenerarInforme")
{
	$folio = $datosDecretoBajaRealizado["alt_folio"];
	
	$query = "SELECT AF.act_id, act_codigo, act_codEstado, act_descripcionDetallada, aneg_descripcion, ccos_descripcion, ubi_codigo, resp_nombre, 
         act_fechaIngreso, act_codBarras, act_venceUltimaGarantia, act_tipoControl, ccos_descripcion, cing_descripcion, ubi_descripcion, 
         aux_razonSocial, act_revalorizable, act_depreciable, act_tipoDepreciacion, act_valorResidual, act_porLote, act_cantidadLote, 
         act_situacionDePartida, act_fechaAdquisicion, act_vidaUtilTributaria, act_vidaUtilFinanciera, act_valorAdquisicion, act_numDocCompra, 
         act_numOrdenCompra, act_inicioRevalorizacion, act_bajoNormaPublica, act_presupuesto, act_bajoNormaIFRS, act_rutaImagen, act_glosaDeBaja, 
		 TA.coda_descripcion AS titu_descripcion, SUBSTR(TA.coda_codigo, 1, 1) AS titu_codigo, GA.coda_descripcion AS grua_descripcion, 
		 SUBSTR(GA.coda_codigo, 2, 1) AS grua_codigo, SGA.coda_descripcion AS sgrua_descripcion, SUBSTR(SGA.coda_codigo, 3, 1) AS sgrua_codigo,
		 CUA.coda_descripcion AS cuen_descripcion, SUBSTR(CUA.coda_codigo, 4, 2) AS cuen_codigo, SCA.coda_descripcion AS scuen_descripcion,
         SUBSTR(SCA.coda_codigo, 6) AS scuen_codigo, anac_descripcion, anac_codigo, aux_rut, 	
         ftec_descripcion, tdoc_descripcion, cond_descripcion, act_fechaOC, act_marca, act_modelo, act_serie, act_patenteVehiculo, act_siglaVehiculo
			
			FROM datos_decreto_alta AS DDB  
				JOIN decretos_alta DB
					ON DDB.alt_folio = DB.alt_folio
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
																		LEFT JOIN analiticos_cuentas AC
																			ON AF.anac_id=AC.anac_id
																				LEFT JOIN codificador_analiticos TA
																					ON SUBSTR(AC.coda_codigo, 1, 1)=TA.coda_codigo
																						LEFT JOIN codificador_analiticos GA
																							ON SUBSTR(AC.coda_codigo, 1, 2)=GA.coda_codigo
																								LEFT JOIN codificador_analiticos SGA
																									ON SUBSTR(AC.coda_codigo, 1, 3)=SGA.coda_codigo
																										LEFT JOIN codificador_analiticos CUA
																											ON SUBSTR(AC.coda_codigo, 1, 5)=CUA.coda_codigo
																												LEFT JOIN codificador_analiticos SCA
																													ON AC.coda_codigo=SCA.coda_codigo
																														LEFT JOIN conceptos_ingreso CI
																															ON AF.cing_id=CI.cing_id
																																LEFT JOIN condiciones_activos CA
																																	ON AF.cond_id=CA.cond_id
																																		LEFT JOIN tipo_documento TD
																																			ON AF.tdoc_id=TD.tdoc_id
																																				LEFT JOIN proveedores AUX 
																																					ON AF.aux_id=AUX.aux_id
																WHERE dadeal_id=" . $ids[0] . " GROUP BY act_porLote";
		
		
	$result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
	$numReg = mysqli_num_rows($result);
	//$resGeneral = $result;
	//$resDetalle = $result;
}
$totalPag = 0;

while( $data = mysqli_fetch_assoc($result) ){
    //1 Pág x Activo
    $pdf->AddPage();
    $totalPag++;
    //$pdf->Line(25, 36, 216, 36);
    // Encabezado
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(55);
    $encabezado = ( $_SESSION['emp'] != 'educacion' ) ? '' : "APRUEBA ALTA DE BIENES INVENTARIABLES\nDE LOS ESTABLECIMIENTOS EDUCACIONALES";
    $pdf->MultiCell(121, 5, $encabezado, 0, 'L');
    $pdf->Ln(10);
    
    $pdf->SetFont('Arial','B',10);
    
    if( $_SESSION['emp'] == 'educacion' ){
        $nombreDireccion = '';
    }elseif( $_SESSION['emp'] == 'municipal' ){
        $nombreDireccion = 'Dirección de Administración y Finanzas';
    }else{
        $nombreDireccion = $org['deal_nombreDireccion']; 
    }
     
    $pdf->Cell(55, 8, $nombreDireccion, 0);
    $align = ( $_SESSION['emp'] == 'municipal' ) ? 'R' : '';
    $wCellNum = ( $_SESSION['emp'] == 'municipal' ) ? 121 : 55;
    $pdf->Cell($wCellNum, 8, 'N°______________/', 0, 1, $align);
    
    if( $_SESSION['emp'] == 'salud' ){
        $txtAlta = 'Alta Interna';
    }elseif( $_SESSION['emp'] == 'educacion' ){
        $txtAlta = 'Alta Interna';
    }else{
        $txtAlta = 'Decreto Alta ';
    }
    
    $pdf->Cell(0, 8, $txtAlta.' N° '.$folio, 0, 1);
    
    //$fecha_hoy = date('d ').ucwords(strftime('%b')).date(' Y');
    $pdf->Cell(55);
    $align = ( $_SESSION['emp'] == 'municipal' ) ? 'C' : '';
    $pdf->Cell(55, 8, $org['deal_nombreOrg'].', ', 0, 1, $align);
    $pdf->Ln();
    
    // Enunciado
    if( $_SESSION['emp'] != 'municipal' ){
        $pdf->Cell(55);        
    }
    $pdf->Cell(0, 8, 'VISTOS:');
    $pdf->Ln();
    
    // Detalle enunciado
    $w=0; $h=5; $b=0; $t=55;    
    $pdf->SetFont('Arial','',10);
    
    foreach( $vistos as $item ){
        if( $_SESSION['emp'] != 'municipal' ){
            $pdf->Cell($t);        
        }else{
            $pdf->Cell(10);
        }
        $pdf->Write($h, $item);
        $pdf->Ln();
    }
    
    $pdf->SetFont('Arial','B',10);
    if( $_SESSION['emp'] != 'municipal' ){
        $pdf->Cell($t);        
    }
    $pdf->Cell(0, 8, 'DECRETO:');
    $pdf->Ln();
    
    if( $_SESSION['emp'] == 'salud' ){
        $depto = ', del Departamento de Salud';
    }elseif( $_SESSION['emp'] == 'educacion' ){
        $depto = ', del Departamento de Educación';
    }else{
        $depto = '';
    }
    
    $pdf->SetFont('Arial','',10);
    if( $_SESSION['emp'] != 'municipal' ){
        $pdf->Cell($t);        
    }
    $pdf->Write($h, '1.- Apruébese el Alta del Bien que se individualiza a continuación e incorpórese al registro de inventario Municipal'.$depto.'.', $b);
    //$pdf->Write($h, '1.- Apruébese el Alta del Bien que se individualiza a continuación e incorpórese al registro de inventario Municipal'.$depto.'.');
    $pdf->Ln();
    
    $pdf->Ln();
    // Títulos del Detalle
    $titulosActivo = array(
                            "1.- PRESUPUESTO",
                            "2.- AREA",
                            "3.- TITULO",
                            "4.- GRUPO",
                            "5.- SUB-GRUPO",
                            "6.- CUENTA",
                            "7.- SUB-CUENTA",
                            "8.- ANALITICO DE SUB-CUENTA",
                            "9.- CORRELATIVO",
                            "10.- VIDA UTIL ESTIMADA",
                            "11.- ESPECIFICACION DEL BIEN",
                            "12.- VALOR DE ADQUISICION",
                            "13.- ADQUIRIDO A",
                            "14.- MEDIANTE DOCUMENTO",
                            "15.- N° EXPEDIENTE",
                            "16.- DESTINO INICIAL",
                            "17.- UBICACION FISICA",
                            "18.- CODIGO",
                            "19.- INSCRIPCION DE DOMINIO"
                          );
    
    // guarda cód para usarlo idLote
    $idLote[] = $data['act_codigo'];
    
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
    $wT=55; $wD=111; $wC=10; $h=4; $b=0;
    
    $i=0;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_presupuesto'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $area_bd_temuco, $b);
    $pdf->Cell($wC, $h, $_SESSION['emp_codigo'], $b, 1);    
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['titu_descripcion'], $b);
    $pdf->Cell($wC, $h, $data['titu_codigo'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['grua_descripcion'], $b);
    $pdf->Cell($wC, $h, $data['grua_codigo'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['sgrua_descripcion'], $b);
    $pdf->Cell($wC, $h, $data['sgrua_codigo'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['cuen_descripcion'], $b);
    $pdf->Cell($wC, $h, $data['cuen_codigo'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['scuen_descripcion'], $b);
    $pdf->Cell($wC, $h, $data['scuen_codigo'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['anac_descripcion'], $b);
    $pdf->Cell($wC, $h, $data['anac_codigo'], $b, 1);
    $i++;    
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_codigo'], $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['act_vidaUtilTributaria'], $b, 1);
    $i++;
    $cantidadAF = $data['act_cantidadLote'];
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->MultiCell($wD, $h, ($cantidadAF == 0) ? '1  '.$data['act_descripcionDetallada'] : $cantidadAF.'  '.$data['act_descripcionDetallada'], $b, 'L');
    $i++;
    $valorAdquisicion = ($cantidadAF == 0) ? $data['act_valorAdquisicion'] : ($data['act_valorAdquisicion']*$cantidadAF);
    $valorAdquisicion = number_format($valorAdquisicion, 0, ',', '.');
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, '$ '.$valorAdquisicion, $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->MultiCell($wD, $h, $data['aux_rut'].'   '.substr($data['aux_razonSocial'], 0, 40), $b, 'L');    
    $i++;
    $fechaAdquisicion = $data['act_fechaAdquisicion'];
    $fechaAdquisicion = ( $fechaAdquisicion != '0000-00-00' ) ? date('d/m/Y', strtotime($fechaAdquisicion)) : '';
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $data['tdoc_descripcion'].'   '.$data['act_numDocCompra'].'   '.$fechaAdquisicion, $b, 1);    
    $i++;
    $act_fechaOC = $data['act_fechaOC'];
    $act_fechaOC = ( $act_fechaOC == null || $act_fechaOC == '0000-00-00' ) ? '' : date('d/m/Y', strtotime($act_fechaOC));
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, 'Orden Compra   '.$data['act_numOrdenCompra'].'   '.$act_fechaOC, $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $nv1['ubi_descripcion'], $b, 1); // ubi_nivel_1
    $i++;
    $ubicacionFisicaMuni = $data['aneg_descripcion'].', '.$nv3['ubi_descripcion']; // Unidad y ubi_nivel_3
    $ubicacionFisica = $nv2['ubi_descripcion'].', '.$nv3['ubi_descripcion']; // ubi_nivel_2 y ubi_nivel_3
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->MultiCell($wD, $h, ($_SESSION['emp'] == 'municipal') ? $ubicacionFisicaMuni : $ubicacionFisica, $b, 'L'); 
    $i++;
    $codificadorAnalitico = $data['titu_codigo'].'.'.$data['grua_codigo'].'.'.$data['sgrua_codigo'].'.'.$data['cuen_codigo'].'.'.$data['scuen_codigo'].'.'.$data['anac_codigo'];
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, $_SESSION['emp_codigo'].'.'.$codificadorAnalitico, $b, 1);
    $i++;
    $pdf->Cell($wT, $h, $titulosActivo[$i], $b);
    $pdf->Cell($wD, $h, '', $b, 1);        
    // Fin del Cuerpo (detalle del activo)    
    
    $pdf->Ln();
    
    // Sólo imprime pie en caso de 1 o últ pdf 
    if( $numReg == 1 || $totalPag == $numReg ){
        
        $pdf->SetFont('Arial','',10);
        if( $_SESSION['emp'] != 'municipal' ){
            $pdf->Cell($wT);        
        }        
        $pdf->Cell(0, 5, 'ANOTESE, COMUNIQUESE Y ARCHIVESE.');
        $pdf->Ln(25);
        
        // Firmas y Timbres
        $pdf->SetFont('Arial','B',10);
        $h=5;
        $pdf->Cell(83, $h, $org['deal_porOrdenFirma1'], 0, 0, 'C');
        $pdf->Cell(83, $h, $org['deal_porOrdenFirma2'], 0, 1, 'C');        
        $pdf->Cell(83, $h, $org['deal_nombreSecre'], 0, 0, 'C');
        $pdf->Cell(83, $h, $org['deal_nombreAlcalde'], 0, 1, 'C');    
        $pdf->Cell(83, $h, $org['deal_cargoFirma1'], 0, 0, 'C');
        $pdf->Cell(83, $h, $org['deal_cargoFirma2'], 0, 0, 'C');
        $pdf->Ln(15);
        
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(0, 5, $org['deal_iniciales']);
        $pdf->Ln(8);
        
        $w=0; $h=4; $b=0;
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell($w, $h, 'DISTRIBUCIÓN:', 0, 1);
        
        $pdf->SetFont('Arial','',9);
        
        foreach( $distribucion as $destino ){
            $pdf->Cell($w, $h, $destino, 0, 1);
        }
        
        if( $_SESSION['emp'] == 'municipal' ){
            $pdf->Cell($w, $h, strtoupper($nv1['ubi_descripcion'].', '.$nv2['ubi_descripcion'].', '.$nv3['ubi_descripcion']), 0, 1);
        }
    } // Fin restrinción impresión del pie
}

// Libera recursos de $query
mysqli_free_result($result);

$nombreCompletoDecreto = $_SESSION['emp'].'_Decreto_de_Alta_N_'.$folio.'.pdf';
// Genera PDF según $opc
if( $opc == 'preview' ){
    // Sale PDF con todos los decrtetos en "vista previa"
    $pdf->Output('I', $nombreCompletoDecreto);        
}else{

	if($opc2 == "")
	{	
		
		/** Ya no puedo usar el id act. interno dado que si es x lote debo actualizar tablas x otro campo identificatorio, en éste caso x el idLote
		*     foreach( $ids as $key => $actID ){
		*         // Registra trazabilidad de núm. dectreto => activo
		*         $sql = "INSERT INTO decretos_alta(alt_id, alt_folio, act_id, alt_creador) VALUES(NULL, '".$folio."', '".$actID."', '".$userID."')";
		*         $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
		*         
		*         $sql = "UPDATE activos_fijos SET 
		*         act_decretoAlta=1, act_modificador='".$userID."', act_modificacion='".$fecha_modificacion."' WHERE act_id=".$actID." LIMIT 1";
		*         $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));    
		*     }
		*/
		// Recorre los AF del Lote para actualizar
		foreach( $idLote as $key => $act_idLote ){
			$sqlAF = "SELECT act_id FROM activos_fijos WHERE act_idLote='".$act_idLote."'";
			$resAF = mysqli_query($conexion, $sqlAF) or die(mysqli_error($conexion));
        
			while( $af = mysqli_fetch_assoc($resAF) ){
				// Registra trazabilidad de núm. dectreto => activo
				$sql = "INSERT INTO decretos_alta(alt_id, alt_folio, act_id, alt_creador) VALUES(NULL, '".$folio."', '".$af['act_id']."', '".$userID."')";
				$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
            
				$sql = "UPDATE activos_fijos SET 
				act_decretoAlta=1, act_modificador='".$userID."', act_modificacion='".$fecha_modificacion."' WHERE act_id='".$af['act_id']."'";
				$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
				//var_dump($af);
			}    
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

		$sql = "INSERT INTO datos_decreto_alta (alt_folio, dadeal_nombreOrg, dadeal_nombreDireccion, 
										dadeal_nombreSecre, dadeal_porOrdenFirma1, dadeal_cargoFirma1,dadeal_nombreAlcalde,
										dadeal_porOrdenFirma2, dadeal_cargoFirma2, dadeal_iniciales, dadeal_rutaLogo,
										dadeal_descripDistri, dadeal_descripVistos, dadeal_creador)
									VALUES ('".$folio."', '".$org['deal_nombreOrg']."', '".$nombreDireccion."',
										'".$org['deal_nombreSecre']."', '".$org['deal_porOrdenFirma1']."', 
										'".$org['deal_cargoFirma1']."','".$org['deal_nombreAlcalde']."',
										'".$org['deal_porOrdenFirma2']."', '".$org['deal_cargoFirma2']."', 
										'".$org['deal_iniciales']."', '".$org['deal_rutaLogo']."',
										'".$stringDistrib."', '".$stringVistos."', '".$userID."')";
										
										
		$res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
	}

	// Sale PDF con todos los decrtetos en "modo descarga e impresión" 
	$pdf->Output('I', $nombreCompletoDecreto);
	// Guarda copia del PDF en "directorio local"
	$pdf->Output('F', $raiz.$ruta.$nombreCompletoDecreto);
	
	//var_dump($idLote);
    //exit;
    //require 'configBDCentral.php';
  
    // actualiza en BD centralizada el valor del últ. folio de decreto ingresado
    //$query = "UPDATE control_ultimos_num SET ultDecretoAlta='".$folio."', marca_modificacion='".$fecha_modificacion."' LIMIT 1";
    //mysqli_query($link, $query) or die(mysqli_error($link));
    //mysqli_close($link);
}

mysqli_close($conexion);
}
else
{
    exit("No existen datos para ser procesados...");
}
?>
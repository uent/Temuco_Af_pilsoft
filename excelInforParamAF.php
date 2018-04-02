<?php
require_once 'config.php';
require_once 'funciones.php';
require_once 'requires/gump.class.php';
require_once 'PHPExcel/Classes/PHPExcel.php';

ini_set("memory_limit", '1024M');
ini_set('max_execution_time', 400);

$userID = $_SESSION['userid'];

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

if (PHP_SAPI == 'cli'){
	die('This example should only be run from a Web Browser');
}

$gump = new GUMP();
$_POST = $gump->sanitize($_POST);

if( isset($_POST['informe_excel']) && $_POST['informe_excel'] == 'informe_param' ){
        
    $gump->validation_rules(array(
        'aneg_id' => 'numeric',
        'ubiNive31' => 'numeric',
		'ubiNivel2'=> 'alpha_dash',
		'ubiNivel1'=> 'alpha_dash',
        'act_codEstado' => 'alpha|exact_len,1',
        'fechaIngresoDesde' => 'required|datechilean',
        'fechaIngresoHasta' => 'required|datechilean'
    ));

    $validated_data = $gump->run($_POST);

    if( $validated_data === false ){
        
        $arreglos["invali"]=array();
        $errores=$gump->get_readable_errors();
        $long=count($errores);
        for($i=0;$i<$long;$i++){
            $arreglos["invali"][]=$errores[$i];
        }
        echo json_encode($arreglos);
        exit;
        
    }else{
        
        if( !empty($_POST['fechaIngresoDesde']) ){
            if( validarFecha($_POST['fechaIngresoDesde']) ){
                $fechaIngresoDesde = formatDateToMySql($_POST['fechaIngresoDesde']);
            }else{
                echo "La fecha de Ingreso DESDE es incorrecta.";
                exit;            
            }
        }

        if( !empty($_POST['fechaIngresoHasta']) ){
            if( validarFecha($_POST['fechaIngresoHasta']) ){
                $fechaIngresoHasta = formatDateToMySql($_POST['fechaIngresoHasta']);
            }else{
                echo "La fecha de Ingreso HASTA es incorrecta.";
                exit;            
            }
        }
        
        $aneg_id = $_POST['aneg_id'];
        
		$ubiNivel1  = $_POST['ubiNivel1'];
		$ubiNivel2  = $_POST['ubiNivel2'];
		$ubi_id  = $_POST['ubiNivel3'];

        $codEstado = $_POST['act_codEstado'];        
        
    }

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Pilsoft SpA")
							 ->setLastModifiedBy("Pilsoft SpA")
							 ->setTitle("Informe Parametrico AF")
							 ->setSubject("Informe Parametrico AF")
							 ->setDescription("Informe Parametrico AF - Sist. Control y Gestion Activo Fijo")
							 ->setKeywords("Informes Reportes")
							 ->setCategory("Informes Reportes");

// Cabeceras
$titulos = array(
                    "Código del Activo",
                    "Estado",
                    "Descripción",
                    "Detalles",
					"Situación de Partida",
					"Situación Contable Tributaria",
					"Valor Libro Tributario",
					"Revalorización Acumulada Tributaria",
					"Depreciación Acumulada Tributaria",
					"Vida Útil Remanente Tributaria",
					"Situación Contable Financiera",
					"Valor Libro Financiero",
					"Inicio de Revalorización Financiera",
					"Revalorización Acumulada Financiera",
					"Inicio de Depreciación Financiera",
					"Depreciación Acumulada Financiera",
					"Vida Útil Financiera",
					"Vida Útil Remanente Financiera",
					
					"Unidad de Medida para Vida Útil",
					"Por Lote",
					"Cantidad del Lote",
					"Fecha Orden de Compra",
					"Revalorizable",
					"Inicio Revalorización",
					"Depreciable",
					"Inicio Depreciación",
					"Bajo Norma Publica",
					"Bajo Norma Tributaria",
					"Bajo Norma IFRS",
					"Leasing",
					
					"Decreto de Alta",
					"Nro. Decreto de Alta",
					"Fecha Decreto de Alta",
					"Decreto de Baja",
					"Nro. Decreto de Baja",
					"Fecha Decreto de Baja",
					"Sigla Vehículo",
					"Chasis Vehículo",
					"Numero Motor del Vehículo",
					"Serie",
					"Marca",
					"Modelo",
					"Patente Vehículo",
					"Numero Inmueble",
					"Fecha Escritura",
					"Tipo Inmueble",
					"Nombre Inmueble",
					"Detalle Inmueble",
					"Año Inmueble",
					"Sector Inmueble",
					"Rol Inmueble",
					"act_DAInmueble",
					"act_fojaInmueble",
					"Medida Terreno del Inmueble",

					"Dirección Inmueble",
					"Obs. Inmueble",
					"Fecha Ingreso Inmueble",
					"Propietario Anterior del Inmueble",
                    "Ingreso",
                    "Desc. Concepto",
                    "Desc. Familia",
                    "Desc. Subfamilia",
                    "Desc. A. Negocio",
                    "Desc. C. Costo",
					"Desc. Dirección",
					"Desc. Depto.",
                    "Desc. Oficina",
                    "Garantía",
                    "Tipo Control",
                    "Adquisición",
                    "Condición",
                    "Vida Útil",
                    "Valor (C)",
                    "V. Residual",                    
                    "Tipo Doc.",
					"Nro. Orden de Compra",
                    "Presupuesto",
                    "R. Social Proveedor",
                    "Descripción Proveedor",
                    "Cód. de Baja",
                    "Desc. de Baja",
                    "Fecha de Baja",
                    "Detalle de la Baja"
                );

                
$objPHPExcel->setActiveSheetIndex(0);

$ultColum = 'A';
$count = count($titulos);
// Calcula la última columna
for($i=0; $i < $count; $i++){
    $ultColum++;
}

//echo 'MI ULT COL ES: '.$ultColum; echo('<br>');
//echo $i; echo('<br>');

// Cabeceras
for($i=0, $columna='A'; $columna != $ultColum; $i++, $columna++ ){
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columna.'1', utf8_encode($titulos[$i]));
    $objPHPExcel->getActiveSheet()->getColumnDimension($columna)->setAutoSize(true);
    //echo($columna.'1'.' - '.$titulos[$i]); echo('<br>');
}

//vEstilos Cabecera
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$columna.'1')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('A1:'.$columna.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//$objPHPExcel->getActiveSheet()->getStyle('A1:'.$columna.'1')->getFill()->getStartColor()->setARGB('FF00FFFF');
//echo($columna.'1'); echo('<br>');
//exit;
// campos extras futuros posibles
// resp_codigo, resp_nombre, act_situacionDePartida, act_inicioRevalorizacion, act_bajoNormaPublica, act_bajoNormaTributaria, 
// act_bajoNormaIFRS, ftec_descripcion 

			
// Confección del informe paramétrico (cantidad de filas según coinsidencias con filtros del form ingresados por el usuario)
$query = "SELECT act_codigo, act_codEstado, act_descripcion, act_descripcionDetallada, act_situacionDePartida,
		 act_situacionContabTribu, act_valorLibroTributario, act_revalorizacionAcumuTribu, act_depreciacionAcumuTribu, act_vidaUtilRemanenteTribu,
		 act_situacionContabFinanc, act_valorLibroFinanciero, act_inicioRevalorizacionFinanc, act_revalorizacionAcumuFinanc, act_inicioDepreciacionFinanc, act_depreciacionAcumuFinanc, act_vidaUtilFinanciera, act_vidaUtilRemanenteFinanc, 
		 act_unidadMedidaVidaUtil, act_porLote, act_cantidadLote, act_fechaOC,
		 act_revalorizable,act_inicioRevalorizacion, act_depreciable, act_inicioDepreciacion, 
		 act_bajoNormaPublica, 
		 act_bajoNormaTributaria, act_bajoNormaIFRS, act_leasing, act_decretoAlta, alt_folio, alt_creacion, act_decretoBaja, baj_folio, baj_creacion,act_siglaVehiculo,
		 act_chasisVehiculo, act_numMotorVehiculo, act_serie, act_marca,act_modelo, 
		 act_patenteVehiculo, act_numInmueble, act_fechaEscritura, 
		 act_tipoInmueble, act_nombreInmueble, act_detalleInmueble, act_anioInmueble, act_sectorInmueble, act_rolInmueble, act_DAInmueble, act_fojaInmueble, act_fechaIngresoInmueble,
		 act_medidaTerrenoInmueble, act_propAnteriorInmueble, act_direccionInmueble, act_obsInmueble,
		 act_fechaIngreso, 
         cing_descripcion, grup_descripcion, sgru_descripcion, aneg_descripcion, 
         ccos_descripcion, uv1.ubi_descripcion AS direccion, uv2.ubi_descripcion AS depto, uv3.ubi_descripcion, act_venceUltimaGarantia, act_tipoControl, act_fechaAdquisicion, 
         cond_descripcion, act_vidaUtilTributaria, act_valorAdquisicion, act_valorResidual, tdoc_descripcion, 
         act_numOrdenCompra, act_presupuesto, aux_razonSocial, aux_descripcion, baja_codigo, 
         baja_descripcion, act_fechaDeBaja, act_glosaDeBaja 
         FROM
		 activos_fijos AF
            JOIN areas_negocios AN
                ON AF.aneg_id=AN.aneg_id
                    JOIN centros_costo CC
                        ON AF.ccos_id=CC.ccos_id
                            JOIN ubicaciones UB
                                ON AF.ubi_id=UB.ubi_id
                                    JOIN responsables RE
                                        ON UB.resp_id=RE.resp_id
                                            LEFT JOIN grupos GR
                                                ON AF.grup_id=GR.grup_id
                                                    LEFT JOIN sub_grupos SG
                                                        ON AF.sgru_id=SG.sgru_id
                                                            LEFT JOIN fichas_tecnicas FT
                                                                ON AF.act_id=FT.act_id
                                                                    LEFT JOIN conceptos_ingreso CI
                                                                        ON AF.cing_id=CI.cing_id
                                                                            LEFT JOIN conceptos_baja CB
                                                                                ON AF.baja_id=CB.baja_id
                                                                                    LEFT JOIN condiciones_activos CA
                                                                                        ON AF.cond_id=CA.cond_id
                                                                                            LEFT JOIN cuentas_contables CCO
                                                                                                ON AF.ccont_id=CCO.ccont_id
                                                                                                    LEFT JOIN tipo_documento TD
                                                                                                        ON AF.tdoc_id=TD.tdoc_id
                                                                                                            LEFT JOIN proveedores AUX
                                                                                                                ON AF.aux_id=AUX.aux_id 
																													LEFT JOIN decretos_alta DALT
																														ON AF.act_id=DALT.act_id
																															LEFT JOIN decretos_baja DBAJ
																																ON AF.act_id=DBAJ.act_id 
																						JOIN ubicaciones AS uv3 
																						LEFT JOIN (ubicaciones AS uv2)
																						ON uv2.ubi_codigo=CONCAT(SUBSTR(uv3.ubi_codigo, 1, 5), '-00')
																						LEFT JOIN ubicaciones AS uv1
																						ON uv1.ubi_codigo=CONCAT(SUBSTR(uv2.ubi_codigo, 1, 2), '-00-00') ";
																													

$query.= "WHERE (act_fechaIngreso >= '".$fechaIngresoDesde."' 
			AND act_fechaIngreso <='".$fechaIngresoHasta."') AND uv3.ubi_id = AF.ubi_id ";
        
// Filtros
if( !empty($aneg_id) ){
    $query.= " AND AF.aneg_id=".$aneg_id." ";
}

if( !empty($ubi_id) ){
    $query.= " AND AF.ubi_id=".$ubi_id." ";
}

if( !empty($codEstado) ){
    $query.= " AND AF.act_codEstado='".$codEstado."' ";
}

if( !empty($ubiNivel1) ){
	$query.= " AND uv1.ubi_codigo = '". $ubiNivel1 . "' ";
}
	
if( !empty($ubiNivel2) ){
	$query.= " AND uv2.ubi_codigo = '". $ubiNivel2 . "' ";
}

if( !empty($ubiNivel3) ){
	$query.= " AND uv3.ubi_codigo = '". $ubiNivel3 . "' ";
}
           
$query.= " ORDER BY act_codigo ASC, act_descripcion ASC ";                                                                                              

$result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));

$fila = 2;

while( $data = mysqli_fetch_assoc($result) ){
    $data['act_codEstado'] = ( $data['act_codEstado'] == 'V' ) ? 'VIGENTE' : 'BAJA';
    $data['act_fechaIngreso'] = formatDateToMySql($data['act_fechaIngreso']);
    $data['act_venceUltimaGarantia'] = formatDateToMySql($data['act_venceUltimaGarantia']);
    $data['act_fechaAdquisicion'] = formatDateToMySql($data['act_fechaAdquisicion']);
    $data['act_inicioDepreciacion'] = formatDateToMySql($data['act_inicioDepreciacion']);
	$data['act_decretoAlta'] = ( $data['act_decretoAlta'] == '1' ) ? 'SI' : 'NO';
	$data['act_decretoBaja'] = ( $data['act_decretoBaja'] == '1' ) ? 'SI' : 'NO';
	
	if($data['alt_creacion'] != '') $data['alt_creacion'] = date('d-m-Y', strtotime($data['alt_creacion']));
	if($data['baj_creacion'] != '') $data['baj_creacion'] = date('d-m-Y', strtotime($data['baj_creacion']));
	
	$data['act_fechaEscritura'] = formatDateToMySql($data['act_fechaEscritura']);
	
	
	//$data['baj_creacion'] = formatDateToMySql($data['baj_creacion']);

    //$data['act_fechaDeBaja'] = ( isset($data['act_fechaDeBaja']) ) ? formatDateToMySql($data['act_fechaDeBaja']) : '';
    
    $col = 0;
    foreach( $data as $clave => $valor ){
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $fila, utf8_encode($valor));
        $col++;
    }
    $fila++;
    
//    $objPHPExcel->setActiveSheetIndex()->setCellValue($columna.$fila, $data['act_codigo']);
//    $objPHPExcel->setActiveSheetIndex()->setCellValue($columna.$fila, $data['act_codEstado']);
//    $objPHPExcel->setActiveSheetIndex()->setCellValue($columna.$fila, $data['act_descripcion']);  
}

mysqli_free_result($result);
mysqli_close($conexion);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Informe Parametrico AF');

// Set ActiveSheet index to the first sheet, 
// so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);                             

$fecha_now  = date('d-m-Y H:i');

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="InformeParametricoAF_'.$fecha_now.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
//header ('Content-type: text/plain; charset=iso-8859-1');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;    
}
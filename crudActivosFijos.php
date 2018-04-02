<?php
require_once 'config.php';
require_once 'funciones.php';
require_once 'requires/gump.class.php';

$userID = $_SESSION['userid'];
$baseDatos = $_SESSION['emp'];

$fecha_hoy  = date('Y-m-d');
$mes_actual = date('Y-m').'-01'; //x default

define('FECHA_HOY', $fecha_hoy);
define('ANIO_MES', $mes_actual);

$gump = new GUMP();
$_POST = $gump->sanitize($_POST);

if( isset($_GET['opcion']) ){
    // En caso q vaya a leer.
    $opcion = $_GET['opcion'];
}else{
    $opcion = $_POST['opcion'];
}

function nuevoCodigoBarras(){
    require 'configBDCentral.php';
    
    // Obtiene últ barcode de la BD central para generar uno nuevo
    $query  = "SELECT ultCodigoBarra FROM control_ultimos_num LIMIT 1";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $cifra  = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    
    $barcode = ( empty($cifra['ultCodigoBarra']) ) ? 1 : ( (int) $cifra['ultCodigoBarra'] ) + 1;    
    
    $cantCeros = ( strlen($barcode) < 13 ) ? ( 13 - strlen($barcode) ) : 0;
    //echo $cantCeros; exit;
    $stringCeros = '';
    
    for($i=0; $i<$cantCeros; $i++){
        $stringCeros.= '0';
    }

    $barcode = $stringCeros.$barcode;
    
    $sql = "SELECT codigo_activo_fijo FROM control_codigos_af WHERE codigo_activo_fijo='".$barcode."' LIMIT 1"; 
    $res = mysqli_query($link, $sql) or die(mysqli_error($link));
    $rows = mysqli_fetch_row($res);
    mysqli_free_result($res);
    
    $sql = "SELECT MAX(codigo_activo_fijo) AS maxBarcode FROM control_codigos_af"; 
    $res = mysqli_query($link, $sql) or die(mysqli_error($link));
    $max = mysqli_fetch_assoc($res);
    mysqli_free_result($res);
    
    if( $rows == 0 ){
        return $barcode;
    }else{
        $maxBarcode = ( (int) $max['maxBarcode'] ) + 1;
        
        $cantCeros = ( strlen($maxBarcode) < 13 ) ? ( 13 - strlen($maxBarcode) ) : 0;
        
        $stringCeros = '';
        
        for($i=0; $i<$cantCeros; $i++){
            $stringCeros.= '0';
        }
    
        $maxBarcode = $stringCeros.$maxBarcode;        
        
        return $maxBarcode;
    }
    
    mysqli_close($link);    
}

$gump->filter_rules(array(
    'act_descripcion' => 'sanitize_string|ms_word_characters|trim',
    'act_descripcionDetallada' => 'sanitize_string|ms_word_characters|trim',
    'act_marca' => 'sanitize_string|ms_word_characters|trim',
    'act_modelo' => 'sanitize_string|ms_word_characters|trim',
    'act_serie' => 'sanitize_string|ms_word_characters|trim',
    'act_patenteVehiculo' => 'sanitize_string|ms_word_characters|trim',
    'act_siglaVehiculo' => 'sanitize_string|ms_word_characters|trim',
    'act_chasisVehiculo' => 'sanitize_string|ms_word_characters|trim',
    'act_numMotorVehiculo' => 'sanitize_string|ms_word_characters|trim',
    'act_numCarpetaInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_tipoInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_anioInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_sectorInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_rolInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_fojaInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_medidaTerrenoInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_propAnteriorInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_nombreInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_detalleInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_direccionInmueble' => 'sanitize_string|ms_word_characters|trim',
    'act_obsInmueble' => 'sanitize_string|ms_word_characters|trim'
));

if( $opcion != 'leer' ){
    
    if (isset($_POST['act_inicioDepreciacion'])){
        $act_inicioDepreciacion = '01-'.$_POST['act_inicioDepreciacion'];  
    }else{
        $act_inicioDepreciacion = date('d-m-Y');
    }
    
    if (isset($_POST['act_inicioRevalorizacion'])){
        $act_inicioRevalorizacion = '01-'.$_POST['act_inicioRevalorizacion'];
    }else{
        $act_inicioRevalorizacion = date('d-m-Y');
    }
    
    if( $opcion == 'editar' ){
        $gump->validation_rules(array(
            'act_id' => 'required|numeric',
            'act_codigo' => 'required|alpha_dash|max_len,13|min_len,13',
            'act_codBarras' => 'required|numeric|max_len,13|min_len,13',
            'act_descripcion' => 'max_len,60|min_len,3',
            'anac_id' => 'required|numeric',
            'act_descripcionDetallada' => 'max_len,300|min_len,1',
            'cing_id' => 'required|numeric',
            'grup_id' => 'required|numeric',
            'aneg_id' => 'required|numeric',
            'ccos_id' => 'required|numeric',
            'ubi_id' => 'required|numeric',
            'cond_id' => 'numeric',           
            'ccont_id' => 'numeric',
            'tdoc_id' => 'numeric',
            $act_inicioDepreciacion  => 'datechilean',
            $act_inicioRevalorizacion => 'datechilean',
            'act_fechaIngreso' => 'required|datechilean',
            'act_venceUltimaGarantia' => 'datechilean',
            'act_fechaAdquisicion' => 'required|datechilean',
            'act_vidaUtilTributaria' => 'numeric|max_len,1000|min_len,0',            
            'act_valorAdquisicion' => 'required|numeric',
            'act_valorResidual' => 'required|numeric',
            'act_presupuesto' => 'max_len,30|min_len,0',
            'act_numDocCompra' => 'numeric|max_len,8|min_len,0',
            'act_numOrdenCompra' => 'numeric|max_len,20|min_len,0',
            'act_fechaOC' => 'datechilean',
            'act_docAdquisicion' => 'extension,png;jpg;pdf',
            'act_marca' => 'max_len,60',
            'act_modelo' => 'max_len,60',
            'act_serie' => 'max_len,255',
            'act_patenteVehiculo' => 'max_len,20',
            'act_siglaVehiculo' => 'max_len,15',
            'act_chasisVehiculo' => 'max_len,60',
            'act_numMotorVehiculo' => 'max_len,60',
            'act_numCarpetaInmueble' => 'max_len,255',
            'act_numInmueble' => 'numeric|max_len,20',
            'act_fechaEscritura' => 'datechilean',
            'act_tipoInmueble' => 'max_len,30',
            'act_anioInmueble' => 'max_len,4|min_len,4',
            'act_sectorInmueble' => 'max_len,255',
            'act_rolInmueble' => 'max_len,255',
            'act_DAInmueble' => 'numeric|max_len,20',
            'act_fojaInmueble' => 'max_len,30',
            'act_fechaIngresoInmueble' => 'datechilean',
            'act_medidaTerrenoInmueble' => 'max_len,30',
            'act_propAnteriorInmueble' => 'max_len,255',
            'act_nombreInmueble' => 'max_len,255',
            'act_detalleInmueble' => 'max_len,600',
            'act_direccionInmueble' => 'max_len,600',
            'act_obsInmueble' => 'max_len,600',
            'imagen' => 'extension,png;jpg;jpeg'
        )); 
    }
     
    if( $opcion == 'insertar' ){
        $gump->validation_rules(array(
            //'act_codigo' => 'required|alpha_dash|max_len,13|min_len,13',
            //'act_codBarras' => 'required|numeric|max_len,13|min_len,13',
            'act_descripcion' => 'max_len,60|min_len,3',
            'anac_id' => 'required|numeric',
            'act_descripcionDetallada' => 'max_len,300|min_len,1',
            'cing_id' => 'required|numeric',
            'grup_id' => 'required|numeric',
            'aneg_id' => 'required|numeric',
            'ccos_id' => 'required|numeric',
            'ubi_id' => 'required|numeric',
            'cond_id' => 'numeric',
            'ccont_id' => 'numeric',
            'tdoc_id' => 'numeric',
            $act_inicioDepreciacion => 'datechilean',
            $act_inicioRevalorizacion => 'datechilean',
            'act_fechaIngreso' => 'required|datechilean',
            'act_venceUltimaGarantia' => 'datechilean',
            'act_fechaAdquisicion' => 'required|datechilean',
            'act_vidaUtilTributaria' => 'numeric|max_len,1000|min_len,0',
            'act_valorAdquisicion' => 'required|numeric',
            'act_valorResidual' => 'required|numeric',
            'act_presupuesto' => 'max_len,30|min_len,0',
            'act_numDocCompra' => 'numeric|max_len,8|min_len,0',
            'act_numOrdenCompra' => 'numeric|max_len,20|min_len,0',
            'act_fechaOC' => 'datechilean',            
            'act_docAdquisicion' => 'extension,png;jpg;pdf',
            'act_marca' => 'max_len,60',
            'act_modelo' => 'max_len,60',
            'act_serie' => 'max_len,255',
            'act_patenteVehiculo' => 'max_len,20',
            'act_siglaVehiculo' => 'max_len,15',
            'act_chasisVehiculo' => 'max_len,60',
            'act_numMotorVehiculo' => 'max_len,60',
            'act_numCarpetaInmueble' => 'max_len,255',
            'act_numInmueble' => 'numeric|max_len,20',
            'act_fechaEscritura' => 'datechilean',
            'act_tipoInmueble' => 'max_len,30',
            'act_anioInmueble' => 'max_len,4|min_len,4',
            'act_sectorInmueble' => 'max_len,255',
            'act_rolInmueble' => 'max_len,255',
            'act_DAInmueble' => 'numeric|max_len,20',
            'act_fojaInmueble' => 'max_len,30',
            'act_fechaIngresoInmueble' => 'datechilean',
            'act_medidaTerrenoInmueble' => 'max_len,30',
            'act_propAnteriorInmueble' => 'max_len,255',
            'act_nombreInmueble' => 'max_len,255',
            'act_detalleInmueble' => 'max_len,600',
            'act_direccionInmueble' => 'max_len,600',
            'act_obsInmueble' => 'max_len,600',            
            'imagen' => 'extension,png;jpg;jpeg'
        ));
    }
    
    if( $opcion == 'eliminar' ){
        $gump->validation_rules(array(
            'act_id' => 'required|numeric'
        ));
    }
}

$validated_data = $gump->run($_POST);

if( $validated_data === false ){
    $arreglos["invali"] = array();
    $errores = $gump->get_readable_errors();
    $long = count($errores);
    for($i=0;$i<$long;$i++){
        $arreglos["invali"][]=$errores[$i];
    }
    echo json_encode($arreglos);
    exit();
}else{
    if( $opcion != 'leer' && $opcion != 'eliminar' ){
        if( !empty($_POST['act_id']) ){
            $act_id = $_POST['act_id'];
        }
        
        if( !empty($_POST['act_codigo']) ){
            $act_codigo = utf8_decode(trim($_POST['act_codigo']));
        }else{
            $act_codigo = '';
        }
        
        if( !empty($_POST['anac_id']) ){
            $anac_id = $_POST['anac_id'];
        }else{
            $anac_id = null;
        }
        
        if( !empty($_POST['act_descripcion']) ){
            $act_descripcion = utf8_decode(trim($_POST['act_descripcion']));      
        }else{
            $act_descripcion = null;
        }
        
        $act_codBarras = $_POST['act_codBarras'];
        $act_descripcionDetallada = utf8_decode(trim($_POST['act_descripcionDetallada']));
        
        if( !empty($_POST['act_fechaIngreso']) ){
            if( validarFecha($_POST['act_fechaIngreso']) ){
                $act_fechaIngreso = formatDateToMySql($_POST['act_fechaIngreso']);
                // var utilizada para formar parte del nombre e identificar la img y doc del AF
                $fechaRegistroAF = str_replace('-', '', $_POST['act_fechaIngreso']).date('His');
            }else{
                echo json_encode("La fecha del Ingreso es incorrecta.");
                exit;            
            }
        }else{
            $act_fechaIngreso = FECHA_HOY;
            // var utilizada para formar parte del nombre e identificar la img y doc del AF
            $fechaRegistroAF = date('dmYHis');
        } // valida la fecha de ingreso       
        
        $dir_imagenes = 'imagenes_activos/';
        $dir_docsAdquisicion = 'docs_adquisicion/';
        
        if( !is_dir($dir_imagenes) ){
            mkdir($dir_imagenes, 0755);
        }
        
        if( !is_writable($dir_imagenes) ){
            echo json_encode("Directorio de imagenes sin permisos de escritura.");
            exit;
        }

        if( !empty($_FILES['imagen']['tmp_name']) ){
            
            if( is_uploaded_file($_FILES['imagen']['tmp_name']) ){
                
                if( $_FILES['imagen']['error'] > 0 ){
                    echo json_encode("Error al intentar subir el archivo de imagen.");
                    exit;
                }
        
                if( $_FILES['imagen']['size'] > 2097152 ){
                    echo json_encode("El tamaño del archivo de imagen es mayor a los 2MB.");
                    exit;            
                }
        
                $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                            
                if( $ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif' ){
                    echo json_encode("El tipo de archivo que intenta subir no está permitido.");
                    exit;
                }
        
                $tipoMIME = $_FILES['imagen']['type'];
                
                if( $tipoMIME !== 'image/jpeg' && $tipoMIME !== 'image/png' && $tipoMIME !== 'image/gif'  ){
                    echo json_encode("Tipo de formato MIME que intenta subir no está permitido.");
                    exit;            
                }
                
                $nombre_archivo = $fechaRegistroAF.'_'.$baseDatos.'.'.$ext;
                $ruta_destino = $dir_imagenes.$nombre_archivo;
                
                if( !move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino) ){
                    echo json_encode("Error al intentar mover el archivo de imagen.");
                    exit;
                }
            }else{
                $ruta_destino = '';
                echo json_encode("Posible ataque del archivo subido: <br />Nombre del archivo: '".$_FILES['imagen']['tmp_name']."'.");
            }
        }else{
            $ruta_destino = null;
        }// imgs
        
        /* validaciones doc de adquisición */
        if( !is_dir($dir_docsAdquisicion) ){
            mkdir($dir_docsAdquisicion, 0755);
        }
        
        if( !is_writable($dir_docsAdquisicion) ){
            echo json_encode("Directorio [docs adquisición] sin permisos de escritura.");
            exit;
        }
        
        if( !empty($_FILES['act_docAdquisicion']['tmp_name']) ){
            
            if( is_uploaded_file($_FILES['act_docAdquisicion']['tmp_name']) ){
                
                if( $_FILES['act_docAdquisicion']['error'] > 0 ){
                    echo json_encode("Error al intentar subir el archivo de tipo documento.");
                    exit;
                }
        
                if( $_FILES['act_docAdquisicion']['size'] > 2097152 ){
                    echo json_encode("El tamaño del archivo tipo documento es mayor a los 2MB.");
                    exit;            
                }
        
                $ext = pathinfo($_FILES['act_docAdquisicion']['name'], PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                            
                if( $ext != 'jpg' && $ext != 'png' && $ext != 'pdf' ){
                    echo json_encode("El tipo de archivo para documento de compra, no está permitido.");
                    exit;
                }
        
                $tipoMIME = $_FILES['act_docAdquisicion']['type'];
                
                if( $tipoMIME !== 'image/jpeg' && $tipoMIME !== 'image/png' && $tipoMIME !== 'application/pdf'  ){
                    echo json_encode("Tipo de formato MIME que intenta subir no está permitido.");
                    exit;            
                }
                
                $nombre_doc_adquisicion = $fechaRegistroAF.'_'.$baseDatos.'.'.$ext;
                $ruta_destino_doc_adq = $dir_docsAdquisicion.$nombre_doc_adquisicion;
                
                if( !move_uploaded_file($_FILES['act_docAdquisicion']['tmp_name'], $ruta_destino_doc_adq) ){
                    echo json_encode("Error al intentar mover el archivo del tipo documento.");
                    exit;
                }
            }else{
                $ruta_destino_doc_adq = '';
                echo json_encode("Posible ataque del archivo subido: <br />Nombre del archivo: '".$_FILES['act_docAdquisicion']['tmp_name']."'.");
            }
        }else{
            $ruta_destino_doc_adq = null;
        } // docs adquisición        

        $cing_id = $_POST['cing_id'];
        $grup_id = $_POST['grup_id'];
        $sgru_id = $_POST['sgru_id'];
        $aneg_id = $_POST['aneg_id'];
        $ccos_id = $_POST['ccos_id'];
        $ubi_id  = $_POST['ubi_id'];
        $ccont_id = $_POST['ccont_id'];
        
        if( !empty($_POST['act_venceUltimaGarantia']) ){
            if( validarFecha($_POST['act_venceUltimaGarantia']) ){
                $act_venceUltimaGarantia = formatDateToMySql($_POST['act_venceUltimaGarantia']);
            }else{
                echo json_encode("La fecha de la Garantía es incorrecta.");
                exit;            
            }
        }else{
            $act_venceUltimaGarantia = FECHA_HOY;
        } // ultima garantia
        
        $act_tipoControl = $_POST['act_tipoControl'];
        $act_revalorizable = $_POST['act_revalorizable'];
        $act_depreciable = $_POST['act_depreciable'];
        $act_tipoDepreciacion = $_POST['act_tipoDepreciacion'];
        $act_unidadMedidaVidaUtil = $_POST['act_unidadMedidaVidaUtil'];
        $act_umed_id = ($act_unidadMedidaVidaUtil == 'M') ? 0 : $_POST['act_umed_id'];
        
        $act_porLote = $_POST['act_porLote'];
        $act_cantidadLote = ($act_porLote == 'NO') ? 0 : $_POST['act_cantidadLote'];
        
        if( $opcion == 'insertar' ){
            if( $act_porLote == 'NO' ){
                $act_valorAdquisicion = $_POST['act_valorAdquisicion'];
            }else{
                $act_valorAdquisicion = ($_POST['act_valorAdquisicion'] / $act_cantidadLote );
            }
        }else{
            $act_valorAdquisicion = $_POST['act_valorAdquisicion'];
        }
        
        $act_bajoNormaPublica = $_POST['act_bajoNormaPublica'];
        $act_situacionDePartida = $_POST['act_situacionDePartida'];
        
        if( !empty($_POST['act_fechaAdquisicion']) ){
            if( validarFecha($_POST['act_fechaAdquisicion']) ){
                $act_fechaAdquisicion = formatDateToMySql($_POST['act_fechaAdquisicion']);
            }else{
                echo json_encode("La fecha del Ingreso es incorrecta.");
                exit;            
            }
        }else{
            $act_fechaAdquisicion = FECHA_HOY;
        } // fecha  
        
        $cond_id  = $_POST['cond_id']; // condicion del activo
        $act_vidaUtilTributaria = $_POST['act_vidaUtilTributaria'];
        $act_vidaUtilFinanciera = $_POST['act_vidaUtilFinanciera'];        
        $act_numDocCompra = $_POST['act_numDocCompra'];
        $tdoc_id = $_POST['tdoc_id'];
        $act_numOrdenCompra = $_POST['act_numOrdenCompra'];
        $act_valorResidual = $_POST['act_valorResidual'];
        $act_presupuesto = $_POST['act_presupuesto'];
        
        if( !empty($_POST['aux_id']) ){
        $txtProveedor = $_POST['aux_id'];
        $dataProveedor = explode('/', $txtProveedor);
        $rutProveedor = trim($dataProveedor[0]);

        $rutAux = mysqli_real_escape_string($conexion, $rutProveedor);
        
        $sql = "SELECT aux_id FROM proveedores WHERE aux_rut='".$rutAux."' LIMIT 1";
        $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
        $aux = mysqli_fetch_assoc($res);

        $aux_id = ( !empty($aux['aux_id']) ) ? $aux['aux_id'] : 0; // id proveedor
        
        }else{
            $aux_id = 0; // id proveedor no ingresado
        }

        if( !empty($_POST['act_fechaOC']) ){
            if( validarFecha($_POST['act_fechaOC']) ){
                $act_fechaOC = formatDateToMySql($_POST['act_fechaOC']);
            }else{
                echo json_encode("La fecha de Orden de Compra es incorrecta.");
                exit;
            }
        }else{
            $act_fechaOC = null;
        } // valida fecha de la OC
                
        if( !empty($_POST['act_inicioRevalorizacion']) ){
            if( validarMesAnio('01-'.$_POST['act_inicioRevalorizacion']) ){
                $iniRevalor = explode('-', $_POST['act_inicioRevalorizacion']); // mes-anio
                $act_inicioRevalorizacion = $iniRevalor[1].'-'.$iniRevalor[0].'-01';
            }else{
                echo json_encode("Formato inválido en Inicio de Revalorización.");
                exit;
            }
        }else{
            $act_inicioRevalorizacion = FECHA_HOY;
        } // fin if act_inicio
    
        if( !empty($_POST['act_inicioDepreciacion']) ){
            if( validarMesAnio('01-'.$_POST['act_inicioDepreciacion']) ){
                $iniDrepre = explode('-', $_POST['act_inicioDepreciacion']); // mes-anio
                $act_inicioDepreciacion = $iniDrepre[1].'-'.$iniDrepre[0].'-01';
            }else{
                echo json_encode("Formato inválido en Inicio de Depreciación.");
                exit;
            }
        }else{
            $act_inicioDepreciacion = FECHA_HOY;
        } // fecha
        
        $ftec_descripcion = utf8_decode(trim($_POST['ftec_descripcion'])); // ficha téc.
        
        $act_numInmueble = $_POST['act_numInmueble'];
        $act_DAInmueble = $_POST['act_DAInmueble'];
        
        $act_marca = utf8_decode(trim($_POST['act_marca']));
        $act_modelo = utf8_decode(trim($_POST['act_modelo']));
        $act_serie = utf8_decode(trim($_POST['act_serie']));
        $act_patenteVehiculo = utf8_decode(trim($_POST['act_patenteVehiculo']));
        $act_siglaVehiculo = utf8_decode(trim($_POST['act_siglaVehiculo']));
        $act_chasisVehiculo = utf8_decode(trim($_POST['act_chasisVehiculo']));
        $act_numMotorVehiculo = utf8_decode(trim($_POST['act_numMotorVehiculo']));
        $act_numCarpetaInmueble = utf8_decode(trim($_POST['act_numCarpetaInmueble']));
        $act_tipoInmueble = utf8_decode(trim($_POST['act_tipoInmueble']));
        $act_anioInmueble = utf8_decode(trim($_POST['act_anioInmueble']));
        $act_sectorInmueble = utf8_decode(trim($_POST['act_sectorInmueble']));
        $act_rolInmueble = utf8_decode(trim($_POST['act_rolInmueble']));
        $act_fojaInmueble = utf8_decode(trim($_POST['act_fojaInmueble']));
        $act_medidaTerrenoInmueble = utf8_decode(trim($_POST['act_medidaTerrenoInmueble']));
        $act_propAnteriorInmueble = utf8_decode(trim($_POST['act_propAnteriorInmueble']));
        $act_nombreInmueble = utf8_decode(trim($_POST['act_nombreInmueble']));
        $act_detalleInmueble = utf8_decode(trim($_POST['act_detalleInmueble']));
        $act_direccionInmueble = utf8_decode(trim($_POST['act_direccionInmueble']));
        $act_obsInmueble = utf8_decode(trim($_POST['act_obsInmueble']));
        
        if( !empty($_POST['act_fechaEscritura']) ){
            if( validarFecha($_POST['act_fechaEscritura']) ){
                $act_fechaEscritura = formatDateToMySql($_POST['act_fechaEscritura']);
            }else{
                echo json_encode("La fecha de la Escritura es incorrecta.");
                exit;
            }
        }else{
            $act_fechaEscritura = '1970-01-01';
        } // valida la fecha de Escritura

        if( !empty($_POST['act_fechaIngresoInmueble']) ){
            if( validarFecha($_POST['act_fechaIngresoInmueble']) ){
                $act_fechaIngresoInmueble = formatDateToMySql($_POST['act_fechaIngresoInmueble']);
            }else{
                echo json_encode("La fecha de Ingreso Inmueble es incorrecta.");
                exit;            
            }
        }else{
            $act_fechaIngresoInmueble = '1970-01-01';
        } // valida la fecha de Ingreso Inmueble
        
        // Para el caso q la opcion sea 'editar'        
        $fecha_modificacion = date("Y-m-d H:i:s");
    } // Fin Insertar/Editar
    
    elseif( $opcion == 'eliminar' )
    {
        $act_id = $_POST['act_id'];
    }
}

switch ( $opcion ){
	case "insertar":
        insertarData(
            $anac_id, $act_descripcion, $act_descripcionDetallada, $ruta_destino, $act_fechaIngreso, $cing_id,
            $grup_id, $sgru_id, $aneg_id, $ccos_id, $ubi_id, $ccont_id, $act_venceUltimaGarantia, $act_tipoControl, $act_revalorizable,
            $act_depreciable, $act_tipoDepreciacion, $act_unidadMedidaVidaUtil, $act_umed_id, $act_porLote, $act_cantidadLote, $act_bajoNormaPublica,
            $act_situacionDePartida, $act_fechaAdquisicion, $cond_id, $act_vidaUtilTributaria, $act_vidaUtilFinanciera, $act_valorAdquisicion,
            $act_numDocCompra, $tdoc_id, $ruta_destino_doc_adq, $act_numOrdenCompra, $act_fechaOC, $act_valorResidual, $act_presupuesto, $aux_id, 
            $act_inicioRevalorizacion, $act_inicioDepreciacion, $ftec_descripcion, $act_marca, $act_modelo, $act_serie, $act_patenteVehiculo, 
            $act_siglaVehiculo, $act_chasisVehiculo, $act_numMotorVehiculo, $act_numCarpetaInmueble, $act_numInmueble, $act_fechaEscritura, 
            $act_tipoInmueble, $act_anioInmueble, $act_sectorInmueble, $act_rolInmueble, $act_DAInmueble, $act_fojaInmueble, $act_fechaIngresoInmueble, 
            $act_medidaTerrenoInmueble, $act_propAnteriorInmueble, $act_nombreInmueble, $act_detalleInmueble, $act_direccionInmueble, $act_obsInmueble, 
            $userID, $conexion, $baseDatos
            );
	break;

	case "leer":
        leerData($servidor, $usuario, $clave, $BD, $conexion);
	break;

	case "editar":
        editarData(
            $act_id, $anac_id, $act_descripcion, $act_codBarras, $act_descripcionDetallada, $ruta_destino, $act_fechaIngreso, $cing_id, $grup_id,
            $sgru_id, $aneg_id, $ccos_id, $ubi_id, $ccont_id, $act_venceUltimaGarantia, $act_tipoControl, $act_revalorizable, $act_depreciable,
            $act_tipoDepreciacion, $act_unidadMedidaVidaUtil, $act_umed_id, $act_porLote, $act_cantidadLote, $act_bajoNormaPublica,
            $act_situacionDePartida, $act_fechaAdquisicion, $cond_id, $act_vidaUtilTributaria, $act_vidaUtilFinanciera, $act_valorAdquisicion,
            $act_numDocCompra, $tdoc_id, $ruta_destino_doc_adq, $act_numOrdenCompra, $act_fechaOC, $act_valorResidual, $act_presupuesto, $aux_id, 
            $act_inicioRevalorizacion, $act_inicioDepreciacion, $ftec_descripcion, $act_marca, $act_modelo, $act_serie, $act_patenteVehiculo, 
            $act_siglaVehiculo, $act_chasisVehiculo, $act_numMotorVehiculo, $act_numCarpetaInmueble, $act_numInmueble, $act_fechaEscritura, 
            $act_tipoInmueble, $act_anioInmueble, $act_sectorInmueble, $act_rolInmueble, $act_DAInmueble, $act_fojaInmueble, $act_fechaIngresoInmueble, 
            $act_medidaTerrenoInmueble, $act_propAnteriorInmueble, $act_nombreInmueble, $act_detalleInmueble, $act_direccionInmueble, $act_obsInmueble, 
            $userID, $fecha_modificacion, $conexion
            );
	break;

	case "eliminar":
        eliminarData($act_id, $conexion);
	break;
            
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
}

function leerData($servidor, $usuario, $clave, $BD, $conexion)
{
    // DB table to use
    $table = 'activos_fijos';
    
    // Table's primary key
    $primaryKey = 'act_id';
             
    // db = campo de la bd, dt = columna del datatable
    $columns = array(
        array( 'db' => 'AF.act_id', 'dt' => 'act_id', 'field' => 'act_id' ),
    	array( 'db' => 'act_codigo', 'dt' => 'act_codigo', 'field' => 'act_codigo' ),
    	array( 'db' => 'act_codEstado', 'dt' => 'act_codEstado', 'field' => 'act_codEstado' ),
        array( 'db' => 'AF.anac_id', 'dt' => 'anac_id', 'field' => 'anac_id' ),
        array( 'db' => "CONCAT_WS('<br />',AC.anac_descripcion, AC.coda_codigo)", 'dt'=>'anac_descripcion', 'field'=>'DescCodAC', 'as'=>'DescCodAC' ),        
    	array( 'db' => 'act_descripcion', 'dt' => 'act_descripcion', 'field' => 'act_descripcion' ),
        array( 'db' => 'aneg_codigo', 'dt' => 'aneg_codigo', 'field' => 'aneg_codigo' ),
    	array( 'db' => 'aneg_descripcion', 'dt' => 'aneg_descripcion', 'field' => 'aneg_descripcion' ),
    	array( 'db' => "CONCAT_WS('<br />', ccos_codigo, ccos_descripcion)", 'dt' => 'ccos_codigo', 'field' => 'ccos_codDesc', 'as' => 'ccos_codDesc' ),
    	array( 'db' => "CONCAT_WS('<br />', ubi_codigo, ubi_descripcion)", 'dt' => 'ubi_codigo', 'field' => 'ubi_codDesc', 'as' => 'ubi_codDesc' ),
        array( 'db' => 'resp_nombre', 'dt' => 'resp_nombre', 'field' => 'resp_nombre' ),
       	array( 
               'db' => 'act_fechaIngreso', 
               'dt' => 'act_fechaIngreso',
               'field' => 'act_fechaIngreso',
               'formatter' => function( $d, $row ){
                    return date( 'd-m-Y', strtotime($d) );
               }
             ),
        array( 'db' => 'act_codBarras', 'dt' => 'act_codBarras', 'field' => 'act_codBarras' ),
        array( 'db' => 'act_descripcionDetallada', 'dt' => 'act_descripcionDetallada', 'field' => 'act_descripcionDetallada' ),
       	array( 
               'db' => 'act_venceUltimaGarantia', 
               'dt' => 'act_venceUltimaGarantia',
               'field' => 'act_venceUltimaGarantia',
               'formatter' => function( $d, $row ){
                    return date( 'd-m-Y', strtotime($d) );
               }
             ),
        array( 'db' => 'act_tipoControl', 'dt' => 'act_tipoControl', 'field' => 'act_tipoControl' ),
        array( 'db' => 'AF.aneg_id', 'dt' => 'aneg_id', 'field' => 'aneg_id' ),
        array( 'db' => 'AF.cing_id', 'dt' => 'cing_id', 'field' => 'cing_id' ),
        array( 'db' => 'AF.ccos_id', 'dt' => 'ccos_id', 'field' => 'ccos_id' ),
        array( 'db' => 'AF.grup_id', 'dt' => 'grup_id', 'field' => 'grup_id' ),
        array( 'db' => 'AF.sgru_id', 'dt' => 'sgru_id', 'field' => 'sgru_id' ),
        array( 'db' => 'AF.ubi_id', 'dt' => 'ubi_id', 'field' => 'ubi_id' ),
        array( 'db' => 'AF.ccont_id', 'dt' => 'ccont_id', 'field' => 'ccont_id' ),
        //array( 'db' => 'AF.aux_id', 'dt' => 'aux_id', 'field' => 'aux_id' ),
        array( 'db' => "CONCAT_WS(' / ', aux_rut, aux_razonSocial)", 'dt' => 'aux_id', 'field' => 'aux_rutRazon', 'as' => 'aux_rutRazon' ),
        array( 'db' => 'ubi_descripcion', 'dt' => 'ubi_descripcion', 'field' => 'ubi_descripcion' ),
        array( 'db' => 'act_revalorizable', 'dt' => 'act_revalorizable', 'field' => 'act_revalorizable' ),
        array( 'db' => 'act_depreciable', 'dt' => 'act_depreciable', 'field' => 'act_depreciable' ),
        array( 'db' => 'act_tipoDepreciacion', 'dt' => 'act_tipoDepreciacion', 'field' => 'act_tipoDepreciacion' ),
        array( 'db' => 'act_unidadMedidaVidaUtil', 'dt' => 'act_unidadMedidaVidaUtil', 'field' => 'act_unidadMedidaVidaUtil' ),
        array( 'db' => 'act_umed_id', 'dt' => 'act_umed_id', 'field' => 'act_umed_id' ),
        array( 'db' => 'act_porLote', 'dt' => 'act_porLote', 'field' => 'act_porLote' ),
        array( 'db' => 'act_cantidadLote', 'dt' => 'act_cantidadLote', 'field' => 'act_cantidadLote' ),
        array( 'db' => 'act_situacionDePartida', 'dt' => 'act_situacionDePartida', 'field' => 'act_situacionDePartida' ),
       	array( 
               'db' => 'act_fechaAdquisicion',  
               'dt' => 'act_fechaAdquisicion',
               'field' => 'act_fechaAdquisicion',
               'formatter' => function( $d, $row ){
                    return date( 'd-m-Y', strtotime($d) );
               }
             ),        
        array( 'db' => 'act_vidaUtilTributaria', 'dt' => 'act_vidaUtilTributaria', 'field' => 'act_vidaUtilTributaria' ),
        array( 'db' => 'act_presupuesto', 'dt' => 'act_presupuesto', 'field' => 'act_presupuesto' ),
        array( 'db' => 'act_vidaUtilFinanciera', 'dt' => 'act_vidaUtilFinanciera', 'field' => 'act_vidaUtilFinanciera' ),
        array( 'db' => 'act_valorAdquisicion', 'dt' => 'act_valorAdquisicion', 'field' => 'act_valorAdquisicion' ),
        array( 'db' => 'act_numDocCompra', 'dt' => 'act_numDocCompra', 'field' => 'act_numDocCompra' ),
        array( 'db' => 'AF.tdoc_id', 'dt' => 'tdoc_id', 'field' => 'tdoc_id' ),
        array( 'db' => 'act_numOrdenCompra', 'dt' => 'act_numOrdenCompra', 'field' => 'act_numOrdenCompra' ),
       	array( 
               'db' => 'act_fechaOC',  
               'dt' => 'act_fechaOC',
               'field' => 'act_fechaOC',
               'formatter' => function( $d, $row ){
                    if( $d != null ){
                        return date( 'd-m-Y', strtotime($d) );
                    }else{
                        return '';
                    }
               }
             ),        
        array( 'db' => 'act_valorResidual', 'dt' => 'act_valorResidual', 'field' => 'act_valorResidual' ),
        array( 'db' => 'act_inicioRevalorizacion', 'dt' => 'act_inicioRevalorizacion', 'field' => 'act_inicioRevalorizacion' ),
        array( 'db' => 'act_inicioDepreciacion', 'dt' => 'act_inicioDepreciacion', 'field' => 'act_inicioDepreciacion' ),
        array( 'db' => 'act_bajoNormaPublica', 'dt' => 'act_bajoNormaPublica', 'field' => 'act_bajoNormaPublica' ),
        array( 'db' => 'act_bajoNormaTributaria', 'dt' => 'act_bajoNormaTributaria', 'field' => 'act_bajoNormaTributaria' ),
        array( 'db' => 'act_bajoNormaIFRS', 'dt' => 'act_bajoNormaIFRS', 'field' => 'act_bajoNormaIFRS' ),
        array( 'db' => 'act_rutaImagen', 'dt' => 'act_rutaImagen', 'field' => 'act_rutaImagen' ),
        array( 'db' => 'act_rutaDocAdquisicion', 'dt' => 'act_rutaDocAdquisicion', 'field' => 'act_rutaDocAdquisicion' ),
        array( 'db' => 'ftec_descripcion', 'dt' => 'ftec_descripcion', 'field' => 'ftec_descripcion' ),
        array( 'db' => 'AF.cond_id', 'dt' => 'cond_id', 'field' => 'cond_id' ),
        array( 'db' => 'act_fechaDeBaja', 'dt' => 'act_fechaDeBaja', 'field' => 'act_fechaDeBaja' ),
        array( 'db' => 'AF.baja_id', 'dt' => 'baja_id', 'field' => 'baja_id' ),
        array( 'db' => 'act_glosaDeBaja', 'dt' => 'act_glosaDeBaja', 'field' => 'act_glosaDeBaja' ),
        array( 'db' => 'act_marca', 'dt' => 'act_marca', 'field' => 'act_marca' ),
        array( 'db' => 'act_modelo', 'dt' => 'act_modelo', 'field' => 'act_modelo' ),
        array( 'db' => 'act_serie', 'dt' => 'act_serie', 'field' => 'act_serie' ),
        array( 'db' => 'act_patenteVehiculo', 'dt' => 'act_patenteVehiculo', 'field' => 'act_patenteVehiculo' ),
        array( 'db' => 'act_siglaVehiculo', 'dt' => 'act_siglaVehiculo', 'field' => 'act_siglaVehiculo' ),
        array( 'db' => 'act_chasisVehiculo', 'dt' => 'act_chasisVehiculo', 'field' => 'act_chasisVehiculo' ),
        array( 'db' => 'act_numMotorVehiculo', 'dt' => 'act_numMotorVehiculo', 'field' => 'act_numMotorVehiculo' ),
        array( 'db' => 'act_numCarpetaInmueble', 'dt' => 'act_numCarpetaInmueble', 'field' => 'act_numCarpetaInmueble' ),
        array( 'db' => 'act_numInmueble', 'dt' => 'act_numInmueble', 'field' => 'act_numInmueble' ),
        array( 'db' => 'act_fechaEscritura', 'dt' => 'act_fechaEscritura', 'field' => 'act_fechaEscritura' ),
        array( 'db' => 'act_tipoInmueble', 'dt' => 'act_tipoInmueble', 'field' => 'act_tipoInmueble' ),
        array( 'db' => 'act_anioInmueble', 'dt' => 'act_anioInmueble', 'field' => 'act_anioInmueble' ),
        array( 'db' => 'act_sectorInmueble', 'dt' => 'act_sectorInmueble', 'field' => 'act_sectorInmueble' ),
        array( 'db' => 'act_rolInmueble', 'dt' => 'act_rolInmueble', 'field' => 'act_rolInmueble' ),
        array( 'db' => 'act_DAInmueble', 'dt' => 'act_DAInmueble', 'field' => 'act_DAInmueble' ),
        array( 'db' => 'act_fojaInmueble', 'dt' => 'act_fojaInmueble', 'field' => 'act_fojaInmueble' ),
        array( 'db' => 'act_fechaIngresoInmueble', 'dt' => 'act_fechaIngresoInmueble', 'field' => 'act_fechaIngresoInmueble' ),
        array( 'db' => 'act_medidaTerrenoInmueble', 'dt' => 'act_medidaTerrenoInmueble', 'field' => 'act_medidaTerrenoInmueble' ),
        array( 'db' => 'act_propAnteriorInmueble', 'dt' => 'act_propAnteriorInmueble', 'field' => 'act_propAnteriorInmueble' ),
        array( 'db' => 'act_nombreInmueble', 'dt' => 'act_nombreInmueble', 'field' => 'act_nombreInmueble' ),
        array( 'db' => 'act_detalleInmueble', 'dt' => 'act_detalleInmueble', 'field' => 'act_detalleInmueble' ),
        array( 'db' => 'act_direccionInmueble', 'dt' => 'act_direccionInmueble', 'field' => 'act_direccionInmueble' ),        
        array( 'db' => 'act_obsInmueble', 'dt' => 'act_obsInmueble', 'field' => 'act_obsInmueble' )
        );

    // SQL server conex info
    $sql_details = array(
    	'user' => $usuario,
    	'pass' => $clave,
    	'db'   => $BD,
    	'host' => $servidor
    );

    require_once 'requires/ssp.customized.class.php';
    
    $joinQuery = "FROM activos_fijos AF
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
                                                             LEFT JOIN conceptos_baja CB
                                                                 ON AF.baja_id=CB.baja_id
                                                                     LEFT JOIN cuentas_contables CCO
                                                                        ON AF.ccont_id=CCO.ccont_id
                                                                            LEFT JOIN analiticos_cuentas AC
                                                                                ON AF.anac_id=AC.anac_id
                                                                                    LEFT JOIN proveedores AUX
                                                                                        ON AF.aux_id=AUX.aux_id";

    $extraWhere = '';
    $groupBy = '';
    $having = '';
    
    echo json_encode(
    	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
    );  
}

function insertarData(
            $anac_id, $act_descripcion, $act_descripcionDetallada, $ruta_destino, $act_fechaIngreso, $cing_id,
            $grup_id, $sgru_id, $aneg_id, $ccos_id, $ubi_id, $ccont_id, $act_venceUltimaGarantia, $act_tipoControl, $act_revalorizable,
            $act_depreciable, $act_tipoDepreciacion, $act_unidadMedidaVidaUtil, $act_umed_id, $act_porLote, $act_cantidadLote, $act_bajoNormaPublica,
            $act_situacionDePartida, $act_fechaAdquisicion, $cond_id, $act_vidaUtilTributaria, $act_vidaUtilFinanciera, $act_valorAdquisicion,
            $act_numDocCompra, $tdoc_id, $ruta_destino_doc_adq, $act_numOrdenCompra, $act_fechaOC, $act_valorResidual, $act_presupuesto, $aux_id, 
            $act_inicioRevalorizacion, $act_inicioDepreciacion, $ftec_descripcion, $act_marca, $act_modelo, $act_serie, $act_patenteVehiculo, 
            $act_siglaVehiculo, $act_chasisVehiculo, $act_numMotorVehiculo, $act_numCarpetaInmueble, $act_numInmueble, $act_fechaEscritura, 
            $act_tipoInmueble, $act_anioInmueble, $act_sectorInmueble, $act_rolInmueble, $act_DAInmueble, $act_fojaInmueble, $act_fechaIngresoInmueble, 
            $act_medidaTerrenoInmueble, $act_propAnteriorInmueble, $act_nombreInmueble, $act_detalleInmueble, $act_direccionInmueble, $act_obsInmueble, 
            $userID, $conexion, $baseDatos
        )
{
    $cantAF = (int) $act_cantidadLote;
    $cantAF = ( $cantAF == 0 ) ? 1 : $cantAF;
     
    for( $i=0; $i<$cantAF; $i++ ){
    
    $act_codigo = nuevoCodigoBarras(); // nuevo codigo x AF
    $act_codBarras = $act_codigo; // codAF es = al codBarra 
    
    if( $i == 0 ){ $idLote = $act_codigo; } // asigna el primer cod como num. del lote
    
    $cantidad = verificaExistencia($act_codigo, $conexion);
    
      if( $cantidad > 0 ){
        echo "existe";
        mysqli_close($conexion);
      }else{
        //vars no existen en form, asigna ANIO_MES        
        $act_situacionContabTribu       = ANIO_MES;
        $act_situacionContabFinanc      = ANIO_MES;
        $act_inicioRevalorizacionFinanc = ANIO_MES;
        $act_inicioDepreciacionFinanc   = ANIO_MES;
        
        $query = "INSERT INTO activos_fijos(
        act_id, act_codigo, act_codBarras, act_fechaIngreso, anac_id, act_descripcion, act_descripcionDetallada, act_situacionDePartida, 
        act_situacionContabTribu, act_vidaUtilTributaria, act_vidaUtilFinanciera, act_unidadMedidaVidaUtil, 
        act_umed_id, act_porLote, act_cantidadLote, act_idLote, act_valorAdquisicion, act_fechaAdquisicion, cond_id, act_numDocCompra, tdoc_id, 
        act_numOrdenCompra, act_fechaOC, act_presupuesto, aux_id, act_venceUltimaGarantia, act_tipoControl, cing_id, grup_id, sgru_id, ubi_id, aneg_id, 
        ccos_id, ccont_id, act_revalorizable, act_inicioRevalorizacion, act_depreciable, act_inicioDepreciacion, act_tipoDepreciacion, 
        act_situacionContabFinanc, act_inicioRevalorizacionFinanc, act_inicioDepreciacionFinanc, act_valorResidual, act_bajoNormaPublica, 
        act_rutaImagen, act_rutaDocAdquisicion, act_marca, act_modelo, act_serie, act_patenteVehiculo, act_siglaVehiculo, act_chasisVehiculo, 
        act_numMotorVehiculo, act_numCarpetaInmueble, act_numInmueble, act_fechaEscritura, act_tipoInmueble, act_anioInmueble, act_sectorInmueble, 
        act_rolInmueble, act_DAInmueble, act_fojaInmueble, act_fechaIngresoInmueble, act_medidaTerrenoInmueble, act_propAnteriorInmueble, 
        act_nombreInmueble, act_detalleInmueble, act_direccionInmueble, act_obsInmueble, act_creador 
        )
        VALUES
        (NULL,'".$act_codigo."', '".$act_codBarras."', '".$act_fechaIngreso."', '".$anac_id."', '".$act_descripcion."', '".$act_descripcionDetallada."', 
        '".$act_situacionDePartida."', '".$act_situacionContabTribu."', '".$act_vidaUtilTributaria."', '".$act_vidaUtilFinanciera."', 
        '".$act_unidadMedidaVidaUtil."', '".$act_umed_id."', '".$act_porLote."', '".$act_cantidadLote."', '".$idLote."', '".$act_valorAdquisicion."', 
        '".$act_fechaAdquisicion."', '".$cond_id."', '".$act_numDocCompra."', '".$tdoc_id."', '".$act_numOrdenCompra."', '".$act_fechaOC."', 
        '".$act_presupuesto."', '".$aux_id."', '".$act_venceUltimaGarantia."', '".$act_tipoControl."', '".$cing_id."', '".$grup_id."', '".$sgru_id."', 
        '".$ubi_id."', '".$aneg_id."', '".$ccos_id."', '".$ccont_id."', '".$act_revalorizable."', '".$act_inicioRevalorizacion."', '".$act_depreciable."', 
        '".$act_inicioDepreciacion."', '".$act_tipoDepreciacion."', '".$act_situacionContabFinanc."', '".$act_inicioRevalorizacionFinanc."', 
        '".$act_inicioDepreciacionFinanc."', '".$act_valorResidual."', '".$act_bajoNormaPublica."', '".$ruta_destino."', '".$ruta_destino_doc_adq."', 
        '".$act_marca."', '".$act_modelo."', '".$act_serie."', '".$act_patenteVehiculo."', '".$act_siglaVehiculo."', '".$act_chasisVehiculo."', 
        '".$act_numMotorVehiculo."', '".$act_numCarpetaInmueble."', '".$act_numInmueble."', '".$act_fechaEscritura."', '".$act_tipoInmueble."', 
        '".$act_anioInmueble."', '".$act_sectorInmueble."', '".$act_rolInmueble."', '".$act_DAInmueble."', '".$act_fojaInmueble."', 
        '".$act_fechaIngresoInmueble."', '".$act_medidaTerrenoInmueble."', '".$act_propAnteriorInmueble."', '".$act_nombreInmueble."', 
        '".$act_detalleInmueble."', '".$act_direccionInmueble."',  '".$act_obsInmueble."', '".$userID."')";                 

        $resSql = mysqli_query($conexion, $query);
        $last_auto_id = mysqli_insert_id($conexion);                
        
        if( $resSql ){
            // registra tmb el ingreso en reubicaciones, como la primera ubicación, si no asigna valores 0 hasta q sea actualizado...
            $query = "INSERT INTO reubicaciones_activos(reub_id, act_id, reub_fecha, reub_hora, ubi_id, aneg_id, ccos_id, reub_creador)
                     VALUES(NULL, '".$last_auto_id."', '".$act_fechaIngreso."', '00:00:00', '".$ubi_id."', '".$aneg_id."', '".$ccos_id."', '".$userID."')";
            $result = mysqli_query($conexion, $query);
            
            // registra los datos de la ficha técnica en la tabla asociada a ella venga o no desc.
            $query = "INSERT INTO fichas_tecnicas(ftec_id, ftec_descripcion, act_id, ftec_creador)
                     VALUES(NULL, '".$ftec_descripcion."', '".$last_auto_id."', '".$userID."')";
            $result = mysqli_query($conexion, $query);
            
            require 'configBDCentral.php';
            
            // registra los datos en BD centralizada para actualizar lista de barcode
            $query = "INSERT INTO control_codigos_af(codigo_activo_fijo, base_datos)
                     VALUES('".$act_codBarras."', '".$baseDatos."')";
            mysqli_query($link, $query);
            
            // actualiza en BD centralizada el valor del últ. cód. barras ingresado
            $query = "UPDATE control_ultimos_num SET ultCodigoBarra='".$act_codBarras."' LIMIT 1";
            mysqli_query($link, $query);
            
            mysqli_close($link);
        }
      }
    }
    resultadoQuery($resSql);
    mysqli_close($conexion);
}

function editarData(
            $act_id, $anac_id, $act_descripcion, $act_codBarras, $act_descripcionDetallada, $ruta_destino, $act_fechaIngreso, $cing_id, $grup_id,
            $sgru_id, $aneg_id, $ccos_id, $ubi_id, $ccont_id, $act_venceUltimaGarantia, $act_tipoControl, $act_revalorizable, $act_depreciable,
            $act_tipoDepreciacion, $act_unidadMedidaVidaUtil, $act_umed_id, $act_porLote, $act_cantidadLote, $act_bajoNormaPublica,
            $act_situacionDePartida, $act_fechaAdquisicion, $cond_id, $act_vidaUtilTributaria, $act_vidaUtilFinanciera, $act_valorAdquisicion,
            $act_numDocCompra, $tdoc_id, $ruta_destino_doc_adq, $act_numOrdenCompra, $act_fechaOC, $act_valorResidual, $act_presupuesto, $aux_id, 
            $act_inicioRevalorizacion, $act_inicioDepreciacion, $ftec_descripcion, $act_marca, $act_modelo, $act_serie, $act_patenteVehiculo, 
            $act_siglaVehiculo, $act_chasisVehiculo, $act_numMotorVehiculo, $act_numCarpetaInmueble, $act_numInmueble, $act_fechaEscritura, 
            $act_tipoInmueble, $act_anioInmueble, $act_sectorInmueble, $act_rolInmueble, $act_DAInmueble, $act_fojaInmueble, $act_fechaIngresoInmueble, 
            $act_medidaTerrenoInmueble, $act_propAnteriorInmueble, $act_nombreInmueble, $act_detalleInmueble, $act_direccionInmueble, $act_obsInmueble, 
            $userID, $fecha_modificacion, $conexion
            )
{
    //vars no existen en form, asigna ANIO_MES        
    $act_situacionContabTribu       = ANIO_MES;
    $act_situacionContabFinanc      = ANIO_MES;
    $act_inicioRevalorizacionFinanc = ANIO_MES;
    $act_inicioDepreciacionFinanc   = ANIO_MES;
        
    $query = "UPDATE activos_fijos SET               
             act_codBarras='".$act_codBarras."', act_fechaIngreso='".$act_fechaIngreso."', act_descripcion='".$act_descripcion."', 
             act_descripcionDetallada='".$act_descripcionDetallada."', act_situacionDePartida='".$act_situacionDePartida."', 
             act_situacionContabTribu='".$act_situacionContabTribu."', act_vidaUtilTributaria='".$act_vidaUtilTributaria."', 
             act_vidaUtilFinanciera='".$act_vidaUtilFinanciera."', act_unidadMedidaVidaUtil='".$act_unidadMedidaVidaUtil."', 
             act_umed_id='".$act_umed_id."', act_porLote='".$act_porLote."', act_cantidadLote='".$act_cantidadLote."', 
             act_valorAdquisicion='".$act_valorAdquisicion."', act_fechaAdquisicion='".$act_fechaAdquisicion."', act_numDocCompra='".$act_numDocCompra."', 
             tdoc_id='".$tdoc_id."', act_numOrdenCompra='".$act_numOrdenCompra."', act_fechaOC='".$act_fechaOC."',act_valorResidual='".$act_valorResidual."', 
             act_presupuesto='".$act_presupuesto."', aux_id='".$aux_id."', cond_id= '".$cond_id."', act_venceUltimaGarantia='".$act_venceUltimaGarantia."', 
             act_tipoControl='".$act_tipoControl."', cing_id='".$cing_id."', grup_id='".$grup_id."', sgru_id='".$sgru_id."', ubi_id='".$ubi_id."', 
             aneg_id='".$aneg_id."', ccos_id='".$ccos_id."', ccont_id='".$ccont_id."', act_revalorizable='".$act_revalorizable."', 
             act_inicioRevalorizacion='".$act_inicioRevalorizacion."', act_depreciable='".$act_depreciable."',
             act_inicioDepreciacion='".$act_inicioDepreciacion."', act_tipoDepreciacion='".$act_tipoDepreciacion."', 
             act_situacionContabFinanc='".$act_situacionContabFinanc."', act_inicioRevalorizacionFinanc='".$act_inicioRevalorizacionFinanc."', 
             act_inicioDepreciacionFinanc='".$act_inicioDepreciacionFinanc."', act_bajoNormaPublica='".$act_bajoNormaPublica."', ";
             
             if( !empty($ruta_destino) ){ $query.= "act_rutaImagen='".$ruta_destino."', "; }
             if( !empty($ruta_destino_doc_adq) ){ $query.= "act_rutaDocAdquisicion='".$ruta_destino_doc_adq."', "; }
             
    $query.= "act_marca='".$act_marca."', act_modelo='".$act_modelo."', act_serie='".$act_serie."', act_patenteVehiculo='".$act_patenteVehiculo."', 
             act_siglaVehiculo='".$act_siglaVehiculo."', act_chasisVehiculo='".$act_chasisVehiculo."', act_numMotorVehiculo='".$act_numMotorVehiculo."', 
             act_numCarpetaInmueble='".$act_numCarpetaInmueble."', act_numInmueble='".$act_numInmueble."', act_fechaEscritura='".$act_fechaEscritura."', 
             act_tipoInmueble='".$act_tipoInmueble."', act_anioInmueble='".$act_anioInmueble."', act_sectorInmueble='".$act_sectorInmueble."', 
             act_rolInmueble='".$act_rolInmueble."', act_DAInmueble='".$act_DAInmueble."', act_fojaInmueble='".$act_fojaInmueble."', 
             act_fechaIngresoInmueble='".$act_fechaIngresoInmueble."', act_medidaTerrenoInmueble='".$act_medidaTerrenoInmueble."', 
             act_propAnteriorInmueble='".$act_propAnteriorInmueble."', act_nombreInmueble='".$act_nombreInmueble."', 
             act_detalleInmueble='".$act_detalleInmueble."', act_direccionInmueble='".$act_direccionInmueble."', act_obsInmueble='".$act_obsInmueble."', 
             anac_id='".$anac_id."', act_modificador='".$userID."', act_modificacion='".$fecha_modificacion."' WHERE act_id=".$act_id." LIMIT 1";
    
    $result = mysqli_query($conexion, $query);
    
    resultadoQuery( $result );

    if( $result ){
        
        $query = "SELECT act_id FROM reubicaciones_activos WHERE act_id=".$act_id." ORDER BY reub_id ASC";
        $result = mysqli_query($conexion, $query);
        $count  = mysqli_num_rows($result);
        // si sólo es existe una reubicación, entonces se considera como actualización de campos del form.
        if( $count == 1 ){ 
            $query = "UPDATE reubicaciones_activos SET reub_fecha='".$act_fechaIngreso."', ubi_id='".$ubi_id."', aneg_id='".$aneg_id."', 
                      ccos_id='".$ccos_id."', reub_modificador='".$userID."', reub_modificacion='".$fecha_modificacion."' 
                      WHERE act_id=".$act_id." ORDER BY reub_id DESC LIMIT 1";
            $result = mysqli_query($conexion, $query);
        }
                
        $query = "UPDATE fichas_tecnicas SET ftec_descripcion='".$ftec_descripcion."',
        ftec_modificador='".$userID."', ftec_modificacion='".$fecha_modificacion."' WHERE act_id=".$act_id." LIMIT 1";                 
        $result = mysqli_query($conexion, $query);
        
    }
    
    mysqli_close($conexion);
}

function eliminarData($act_id, $conexion)
{
    $reubi = verificaNumUbicaciones($act_id, $conexion);
    $dAlta = verificaDecretoAlta($act_id, $conexion);
    $dBaja = verificaDecretoBaja($act_id, $conexion);
    $dTras = verificaDecretoTraslado($act_id, $conexion);
    
    if( $reubi > 1 || $dAlta > 0 || $dBaja > 0 || $dTras > 0 ){
        echo "no_eliminar";
        mysqli_close($conexion);
    }else{
        $query = "DELETE FROM activos_fijos WHERE act_id='".$act_id."' LIMIT 1";
        $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));

        resultadoQuery( $result );

        if( $result ){
            $query = "DELETE FROM reubicaciones_activos WHERE act_id='".$act_id."'";
            $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
            
            $query = "DELETE FROM fichas_tecnicas WHERE act_id='".$act_id."'";
            $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));            
        }
        
        mysqli_close($conexion);                
    }
}

function verificaExistencia($campoClave, $conexion)
{
    $query = "SELECT act_id FROM activos_fijos WHERE act_codigo='".$campoClave."' LIMIT 1";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;
    
    mysqli_free_result($result);
}

function verificaNumUbicaciones($act_id, $conexion)
{
    $query = "SELECT act_id FROM reubicaciones_activos WHERE act_id='".$act_id."'";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;
    
    mysqli_free_result($result);
}

function verificaDecretoAlta($act_id, $conexion)
{
    $query = "SELECT act_id FROM decretos_alta WHERE act_id='".$act_id."' LIMIT 1";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;
    
    mysqli_free_result($result);
}

function verificaDecretoBaja($act_id, $conexion)
{
    $query = "SELECT act_id FROM decretos_baja WHERE act_id='".$act_id."' LIMIT 1";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;
    
    mysqli_free_result($result);
}

function verificaDecretoTraslado($act_id, $conexion)
{
    $query = "SELECT act_id FROM decretos_traslado WHERE act_id='".$act_id."' LIMIT 1";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;
    
    mysqli_free_result($result);
}

function resultadoQuery($resultado)
{
    if( $resultado ){
        $respuesta = 'ok';
    }else{
        $respuesta = 'error';
        // en caso de querer hacer debug...
        //$respuesta = mysqli_error($conexion);
    }
    
    echo $respuesta;
}
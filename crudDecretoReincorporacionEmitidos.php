<?php

require_once 'requires/gump.class.php';



$opcion = '';
$gump = new GUMP();
$_POST = $gump->sanitize($_POST);

if( isset($_GET['opcion']) ){
    // en caso q se vaya a leer act_codigo
    $opcion = $_GET['opcion'];
}else{
    $opcion = $_POST['opcionE'];
}

$gump->filter_rules(array(
    'dadere_nombreOrg' => 'sanitize_string|ms_word_characters',
    'dadere_nombreDireccion' => 'sanitize_string|ms_word_characters',
    'dadere_nombreSecre' => 'sanitize_string|ms_word_characters',
    'dadere_porOrdenFirma1' => 'sanitize_string|ms_word_characters',
    'dadere_cargoFirma1' => 'sanitize_string|ms_word_characters',
    'dadere_nombreAlcalde' => 'sanitize_string|ms_word_characters',
    'dadere_porOrdenFirma2' => 'sanitize_string|ms_word_characters',
    'dadere_cargoFirma2' => 'sanitize_string|ms_word_characters',
    'dadere_iniciales' => 'sanitize_string|ms_word_characters',
	'dadere_rutaLogo' => 'sanitize_string|ms_word_characters',
	'dadere_descripVistos' => 'sanitize_string|ms_word_characters',
	'dadere_descripDistri' => 'sanitize_string|ms_word_characters'
	
	
));

$errores = array();

if( $opcion != 'leer' ){
    if ($opcion=='editar'){
        
		
		
        $gump->validation_rules(array(
		
            'dadere_id'=>'required|numeric',
            'dadere_nombreOrg' => 'required|alpha_space|max_len,60|min_len,5',#
            'dadere_nombreDireccion' => 'alpha_space|max_len,100|min_len,5',#
            'dadere_nombreSecre' => 'required|alpha_space|max_len,60|min_len,5',#
			'dadere_porOrdenFirma1' => 'alpha_space|max_len,60|min_len,3',#
            'dadere_cargoFirma1' => 'alpha_space|max_len,60|min_len,3',#
            'dadere_nombreAlcalde' => 'required|alpha_space|max_len,60|min_len,5',#
            'dadere_porOrdenFirma2' => 'alpha_space|max_len,60|min_len,3',#
            'dadere_cargoFirma2' => 'alpha_space|max_len,60|min_len,3',#
            'dadere_iniciales' => 'max_len,20|min_len,3',#
            'dadere_rutaLogo' => 'extension,png;jpg',#
            'dadere_descripDistri' => 'min_len,3',
            'dadere_descripVistos' => 'min_len,3'
       
        )); 
        
		
		
		
		
    }elseif($opcion=='eliminar'){
        
        $gump->validation_rules(array(
        'dadere_id' => 'required|numeric'
        ));    
        
    }
    
    $validated_data = $gump->run($_POST);
    
    if($validated_data===false) {
        $arreglos["invali"]=array();
        $errores=$gump->get_readable_errors();
        $long=count($errores);
        for($i=0;$i<$long;$i++){
        $arreglos["invali"][]=$errores[$i];
        }
        echo json_encode($arreglos);
        exit();
    }else{
        
        if($opcion=='eliminar'){
           $dadere_id = $_POST['dadere_id'];
        }
        
        if($opcion=='editar'){
            $dadere_id = $_POST['dadere_id'];
            $dadere_nombreOrg = utf8_decode($_POST['dadere_nombreOrg']);
            $dadere_nombreDireccion = utf8_decode($_POST['dadere_nombreDireccion']);  
            $dadere_nombreSecre = utf8_decode($_POST['dadere_nombreSecre']);
            $dadere_porOrdenFirma1 = utf8_decode($_POST['dadere_porOrdenFirma1']);
            $dadere_cargoFirma1 = utf8_decode($_POST['dadere_cargoFirma1']);
            $dadere_nombreAlcalde = utf8_decode($_POST['dadere_nombreAlcalde']);
            $dadere_porOrdenFirma2 = utf8_decode($_POST['dadere_porOrdenFirma2']);
            $dadere_cargoFirma2 = utf8_decode($_POST['dadere_cargoFirma2']);
            $dadere_iniciales = utf8_decode($_POST['dadere_iniciales']);
			$dadere_descripVistos = utf8_decode($_POST['dadere_descripVistos']);
            $dadere_descripDistri = utf8_decode($_POST['dadere_descripDistri']);

			
			$dadere_modificacion = date("Y-m-d H:i:s");
            $dadere_rutaLogo = 'dadere_rutaLogo/';
                
            if( !is_dir($dadere_rutaLogo) ){
                mkdir($dadere_rutaLogo, 0755);
            }
                
            if( !is_writable($dadere_rutaLogo) ){
                echo "Directorio de imagenes sin permisos de escritura.";
                exit;
            }    
                
            if( !empty($_FILES['imagen']['tmp_name']) ){
                
                if( is_uploaded_file($_FILES['imagen']['tmp_name']) ){
                    
                    if( $_FILES['imagen']['error'] > 0 ){
                        echo "Error al intentar subir el archivo de imagen.";
                        exit;
                    }
            
                    if( $_FILES['imagen']['size'] > 2097152 ){
                        echo "El tamano del archivo de imagen es mayor a los 2MB.";
                        exit;            
                    }
            
                    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $ext = strtolower($ext);
                                
                    if( $ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif' ){
                        echo "El tipo de archivo que intenta subir no esti permitido.";
                        exit;
                    }
            
                    $tipoMIME = $_FILES['imagen']['type'];
                    
                    if( $tipoMIME !== 'image/jpeg' && $tipoMIME !== 'image/png' && $tipoMIME !== 'image/gif'  ){
                        echo "Tipo de formato MIME que intenta subir no esti permitido.";
                        exit;            
                    }
                    
                    $nombre_archivo = $dadere_nombreOrg.'.'.$ext;
                    $ruta_destino = $dadere_rutaLogo.$nombre_archivo;
                    
                    if( !move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino) ){
                        echo "Error al intentar mover el archivo de imagen.";
                        exit;
                    }
                }else{
                    $ruta_destino = '';
                    echo "Posible ataque del archivo subido: ";
                    echo "nombre del archivo '".$_FILES['imagen']['tmp_name']."'.";
                }
            }else{
                $ruta_destino = null;
            }//IMAGENES  
        }
        

    }
  // if validate
}




 switch ( $opcion ){

	case "leer":
        leerData();
	break;

	case "editar":
        editarData(
        $dadere_id, $dadere_nombreOrg, $dadere_nombreDireccion, $dadere_nombreSecre, $dadere_porOrdenFirma1, $dadere_cargoFirma1, 
        $dadere_nombreAlcalde, $dadere_porOrdenFirma2, $dadere_cargoFirma2, $dadere_iniciales, $ruta_destino, $dadere_descripVistos, $dadere_descripDistri, $dadere_modificacion 
        );
	break;
        
	case "eliminar":
        eliminarData($dadere_id);
	break;
        
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
 }




function editarData(
            $dadere_id, $dadere_nombreOrg, $dadere_nombreDireccion, $dadere_nombreSecre, $dadere_porOrdenFirma1, $dadere_cargoFirma1, 
            $dadere_nombreAlcalde, $dadere_porOrdenFirma2, $dadere_cargoFirma2, $dadere_iniciales, $dadere_rutaLogo, $dadere_descripVistos, $dadere_descripDistri, $dadere_modificacion
        )
	{  	
	require 'config.php';
	
	
	$userID = $_SESSION['userid'];

    $query = "UPDATE datos_decreto_Reincorporacion SET dadere_nombreOrg='".$dadere_nombreOrg."', dadere_nombreDireccion='".$dadere_nombreDireccion."', 
             dadere_nombreSecre='".$dadere_nombreSecre."', dadere_porOrdenFirma1='".$dadere_porOrdenFirma1."', dadere_cargoFirma1='".$dadere_cargoFirma1."', 
             dadere_nombreAlcalde='".$dadere_nombreAlcalde."', dadere_porOrdenFirma2='".$dadere_porOrdenFirma2."', dadere_cargoFirma2='".$dadere_cargoFirma2."', ";
    $query.= ( !empty($ruta_destino) ) ? "dadere_rutaLogo='".$dadere_rutaLogo."', " : '';
    $query.= "dadere_descripVistos='" . $dadere_descripVistos . "', dadere_descripDistri='" . $dadere_descripDistri . "', dadere_iniciales='".$dadere_iniciales."', dadere_modificador='".$userID."',dadere_modificacion='". date("Y-m-d H:i:s") ."' WHERE dadere_id='".$dadere_id."'";
    
    $result = mysqli_query($conexion, $query);
    echo mysqli_error($conexion);
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function eliminarData($idRegistro)
{
	require 'config.php';
		

    $userID = $_SESSION['userid'];
		
		$query = " UPDATE decretos_reincorporacion, datos_decreto_reincorporacion
					SET decretos_reincorporacion.derei_estado = 0, 
						decretos_reincorporacion.derei_modificacion = '" . date("Y-m-d H:i:s") . "', 
							decretos_reincorporacion.derei_modificador = '". $userID ."'
							
							WHERE decretos_reincorporacion.derei_folio = datos_decreto_reincorporacion.derei_folio 
								AND datos_decreto_reincorporacion.dadere_id= " .$idRegistro. "; ";
	
	
		$query .= "UPDATE activos_fijos, decretos_reincorporacion, datos_decreto_reincorporacion 
				SET activos_fijos.act_decretoBaja = 1 , activos_fijos.act_codEstado='B', act_modificacion = '" . date("Y-m-d H:i:s") . "' , act_modificador = '". $userID . "' 
							WHERE activos_fijos.act_id = decretos_reincorporacion.act_id 
								AND decretos_reincorporacion.derei_folio = datos_decreto_reincorporacion.derei_folio 
								AND datos_decreto_reincorporacion.dadere_id = " .$idRegistro. "; ";  
			
										
						
	//echo $query;									
    $result = mysqli_multi_query($conexion, $query); 
    //echo mysqli_error($conexion);
    resultadoQuery( $result );
    
    mysqli_close($conexion);
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
	
	
	
	function leerData()
{
    // DB table to use
    $table = 'datos_decreto_Reincorporacion';

    // Table's primary key
    $primaryKey = 'dadere_id';

    // db = campo de la bd, dt = columna del datatable
	
	
    $columns = array(
        array( 'db' => 'dadere_id', 'dt' => null, 'field' => 'dadere_id' ),
    	array( 'db' => 'dadere_id', 'dt' => 'dadere_id', 'field' => 'dadere_id' ),
    	array( 'db' => 'datos_decreto_Reincorporacion.derei_folio', 'dt' => 'derei_folio', 'field' => 'derei_folio' ),
    	array( 'db' => 'dadere_nombreOrg', 'dt' => 'dadere_nombreOrg', 'field' => 'dadere_nombreOrg' ),
    	array( 'db' => 'dadere_nombreDireccion', 'dt' => 'dadere_nombreDireccion', 'field' => 'dadere_nombreDireccion' ),
    	array( 'db' => 'dadere_nombreSecre', 'dt' => 'dadere_nombreSecre', 'field' => 'dadere_nombreSecre' ),
		array( 'db' => 'dadere_porOrdenFirma1', 'dt' => 'dadere_porOrdenFirma1', 'field' => 'dadere_porOrdenFirma1' ),
		array( 'db' => 'dadere_cargoFirma1', 'dt' => 'dadere_cargoFirma1', 'field' => 'dadere_cargoFirma1' ),
		array( 'db' => 'dadere_nombreAlcalde', 'dt' => 'dadere_nombreAlcalde', 'field' => 'dadere_nombreAlcalde' ),
		array( 'db' => 'dadere_porOrdenFirma2', 'dt' => 'dadere_porOrdenFirma2', 'field' => 'dadere_porOrdenFirma2' ),
		array( 'db' => 'dadere_cargoFirma2', 'dt' => 'dadere_cargoFirma2', 'field' => 'dadere_cargoFirma2' ),
		array( 'db' => 'dadere_iniciales', 'dt' => 'dadere_iniciales', 'field' => 'dadere_iniciales' ),
		array( 'db' => 'dadere_rutaLogo', 'dt' => 'dadere_rutaLogo', 'field' => 'dadere_rutaLogo' ),
		array( 'db' => 'dadere_descripDistri', 'dt' => 'dadere_descripDistri', 'field' => 'dadere_descripDistri' ),
		array( 'db' => 'dadere_descripVistos', 'dt' => 'dadere_descripVistos', 'field' => 'dadere_descripVistos' ),
    	array(
               'db' => 'dadere_creacion',
               'dt' => 'dadere_creacion',
               'field' => 'dadere_creacion',
               'formatter' => function( $d, $row ){
                    return date( 'd-m-Y', strtotime($d) );
               }
             ),
		array(
               'db' => 'dadere_modificacion',
               'dt' => 'dadere_modificacion',
               'field' => 'dadere_modificacion',
               'formatter' => function( $d, $row ){
					if($d != "0000-00-00 00:00:00")
					{
						return date( 'd-m-Y', strtotime($d) );
					}
					else
					{
						return "No Modificado";
					}			
               }
             )	 
        );
		

	
    // SQL server conex info
    require 'config.php';
    $sql_details = array(
    	'user' => $usuario,
    	'pass' => $clave,
    	'db'   => $BD,
    	'host' => $servidor
    );

    // require( 'ssp.class.php' );
    require_once 'requires/ssp.customized.class.php';	
	
    $joinQuery = "FROM datos_decreto_Reincorporacion JOIN decretos_reincorporacion ON datos_decreto_Reincorporacion.derei_folio = decretos_reincorporacion.derei_folio";

    $extraWhere = 'decretos_reincorporacion.derei_estado = 1';
    $groupBy = 'datos_decreto_Reincorporacion.derei_folio'; 
    $having = ''; 

    echo json_encode(
    	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
    );
}

?>

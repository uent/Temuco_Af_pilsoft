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
    'dadetr_nombreOrg' => 'sanitize_string|ms_word_characters',
    'dadetr_nombreDireccion' => 'sanitize_string|ms_word_characters',
    'dadetr_nombreSecre' => 'sanitize_string|ms_word_characters',
    'dadetr_porOrdenFirma1' => 'sanitize_string|ms_word_characters',
    'dadetr_cargoFirma1' => 'sanitize_string|ms_word_characters',
    'dadetr_nombreAlcalde' => 'sanitize_string|ms_word_characters',
    'dadetr_porOrdenFirma2' => 'sanitize_string|ms_word_characters',
    'dadetr_cargoFirma2' => 'sanitize_string|ms_word_characters',
    'dadetr_iniciales' => 'sanitize_string|ms_word_characters',
	'dadetr_rutaLogo' => 'sanitize_string|ms_word_characters',
	'dadetr_descripVistos' => 'sanitize_string|ms_word_characters',
	'dadetr_descripDistri' => 'sanitize_string|ms_word_characters'
	
	
));

$errores = array();

if( $opcion != 'leer' ){
    if ($opcion=='editar'){
        
		
		
        $gump->validation_rules(array(
		
            'dadetr_id'=>'required|numeric',
            'dadetr_nombreOrg' => 'required|alpha_space|max_len,60|min_len,5',#
            'dadetr_nombreDireccion' => 'alpha_space|max_len,100|min_len,5',#
            'dadetr_nombreSecre' => 'required|alpha_space|max_len,60|min_len,5',#
			'dadetr_porOrdenFirma1' => 'alpha_space|max_len,60|min_len,3',#
            'dadetr_cargoFirma1' => 'alpha_space|max_len,60|min_len,3',#
            'dadetr_nombreAlcalde' => 'required|alpha_space|max_len,60|min_len,5',#
            'dadetr_porOrdenFirma2' => 'alpha_space|max_len,60|min_len,3',#
            'dadetr_cargoFirma2' => 'alpha_space|max_len,60|min_len,3',#
            'dadetr_iniciales' => 'max_len,20|min_len,3',#
            'dadetr_rutaLogo' => 'extension,png;jpg',#
            'dadetr_descripDistri' => 'min_len,3',
            'dadetr_descripVistos' => 'min_len,3'
       
        )); 
        
		
		
		
		
    }elseif($opcion=='eliminar'){
        
        $gump->validation_rules(array(
        'dadetr_id' => 'required|numeric'
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
           $dadetr_id = $_POST['dadetr_id'];
        }
        
        if($opcion=='editar'){
            $dadetr_id = $_POST['dadetr_id'];
            $dadetr_nombreOrg = utf8_decode($_POST['dadetr_nombreOrg']);
            $dadetr_nombreDireccion = utf8_decode($_POST['dadetr_nombreDireccion']);  
            $dadetr_nombreSecre = utf8_decode($_POST['dadetr_nombreSecre']);
            $dadetr_porOrdenFirma1 = utf8_decode($_POST['dadetr_porOrdenFirma1']);
            $dadetr_cargoFirma1 = utf8_decode($_POST['dadetr_cargoFirma1']);
            $dadetr_nombreAlcalde = utf8_decode($_POST['dadetr_nombreAlcalde']);
            $dadetr_porOrdenFirma2 = utf8_decode($_POST['dadetr_porOrdenFirma2']);
            $dadetr_cargoFirma2 = utf8_decode($_POST['dadetr_cargoFirma2']);
            $dadetr_iniciales = utf8_decode($_POST['dadetr_iniciales']);
			$dadetr_descripVistos = utf8_decode($_POST['dadetr_descripVistos']);
            $dadetr_descripDistri = utf8_decode($_POST['dadetr_descripDistri']);

			
			$dadetr_modificacion = date("Y-m-d H:i:s");
            $dadetr_rutaLogo = 'dadetr_rutaLogo/';
                
            if( !is_dir($dadetr_rutaLogo) ){
                mkdir($dadetr_rutaLogo, 0755);
            }
                
            if( !is_writable($dadetr_rutaLogo) ){
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
                    
                    $nombre_archivo = $dadetr_nombreOrg.'.'.$ext;
                    $ruta_destino = $dadetr_rutaLogo.$nombre_archivo;
                    
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
        $dadetr_id, $dadetr_nombreOrg, $dadetr_nombreDireccion, $dadetr_nombreSecre, $dadetr_porOrdenFirma1, $dadetr_cargoFirma1, 
        $dadetr_nombreAlcalde, $dadetr_porOrdenFirma2, $dadetr_cargoFirma2, $dadetr_iniciales, $ruta_destino, $dadetr_descripVistos, $dadetr_descripDistri, $dadetr_modificacion 
        );
	break;
        
	case "eliminar":
        eliminarData($dadetr_id);
	break;
        
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
 }




function editarData(
            $dadetr_id, $dadetr_nombreOrg, $dadetr_nombreDireccion, $dadetr_nombreSecre, $dadetr_porOrdenFirma1, $dadetr_cargoFirma1, 
            $dadetr_nombreAlcalde, $dadetr_porOrdenFirma2, $dadetr_cargoFirma2, $dadetr_iniciales, $dadetr_rutaLogo, $dadetr_descripVistos, $dadetr_descripDistri, $dadetr_modificacion
        )
	{  	
	require 'config.php';
	
	
	$userID = $_SESSION['userid'];

    $query = "UPDATE datos_decreto_Traslado SET dadetr_nombreOrg='".$dadetr_nombreOrg."', dadetr_nombreDireccion='".$dadetr_nombreDireccion."', 
             dadetr_nombreSecre='".$dadetr_nombreSecre."', dadetr_porOrdenFirma1='".$dadetr_porOrdenFirma1."', dadetr_cargoFirma1='".$dadetr_cargoFirma1."', 
             dadetr_nombreAlcalde='".$dadetr_nombreAlcalde."', dadetr_porOrdenFirma2='".$dadetr_porOrdenFirma2."', dadetr_cargoFirma2='".$dadetr_cargoFirma2."', ";
    $query.= ( !empty($ruta_destino) ) ? "dadetr_rutaLogo='".$dadetr_rutaLogo."', " : '';
    $query.= "dadetr_descripVistos='" . $dadetr_descripVistos . "', dadetr_descripDistri='" . $dadetr_descripDistri . "', dadetr_iniciales='".$dadetr_iniciales."', dadetr_modificador='".$userID."',dadetr_modificacion='". date("Y-m-d H:i:s") ."' WHERE dadetr_id='".$dadetr_id."'";
    
    $result = mysqli_query($conexion, $query);
    echo mysqli_error($conexion);
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function eliminarData($idRegistro)
{
	require 'config.php';
		

    $userID = $_SESSION['userid'];
		
		$query = " UPDATE decretos_traslado, datos_decreto_Traslado
					SET decretos_traslado.tras_estado = 0, 
						decretos_traslado.tras_modificacion = '" . date("Y-m-d H:i:s") . "', 
							decretos_traslado.tras_modificador = '". $userID ."'
							
							WHERE decretos_traslado.tras_folio = datos_decreto_Traslado.tras_folio 
								AND datos_decreto_Traslado.dadetr_id= " .$idRegistro. "; ";

			
		/*$query .= "UPDATE reubicaciones_activos, datos_decreto_Traslado, decretos_traslado
				SET reubicaciones_activos.reub_decretado = 0,  reub_modificacion = '" . date("Y-m-d H:i:s") . "' , reub_modificador = '". $userID . "' 
							WHERE 
								decretos_traslado.tras_folio = datos_decreto_Traslado.tras_folio
								AND decretos_traslado.reub_id = reubicaciones_activos.reub_id
								AND datos_decreto_Traslado.dadetr_id = " .$idRegistro. "; ";  */
				


		
						
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
    $table = 'datos_decreto_Traslado';

    // Table's primary key
    $primaryKey = 'dadetr_id';

    // db = campo de la bd, dt = columna del datatable
	
	
    $columns = array(
        array( 'db' => 'dadetr_id', 'dt' => null, 'field' => 'dadetr_id' ),
    	array( 'db' => 'dadetr_id', 'dt' => 'dadetr_id', 'field' => 'dadetr_id' ),
    	array( 'db' => 'datos_decreto_Traslado.tras_folio', 'dt' => 'tras_folio', 'field' => 'tras_folio' ),
    	array( 'db' => 'dadetr_nombreOrg', 'dt' => 'dadetr_nombreOrg', 'field' => 'dadetr_nombreOrg' ),
    	array( 'db' => 'dadetr_nombreDireccion', 'dt' => 'dadetr_nombreDireccion', 'field' => 'dadetr_nombreDireccion' ),
    	array( 'db' => 'dadetr_nombreSecre', 'dt' => 'dadetr_nombreSecre', 'field' => 'dadetr_nombreSecre' ),
		array( 'db' => 'dadetr_porOrdenFirma1', 'dt' => 'dadetr_porOrdenFirma1', 'field' => 'dadetr_porOrdenFirma1' ),
		array( 'db' => 'dadetr_cargoFirma1', 'dt' => 'dadetr_cargoFirma1', 'field' => 'dadetr_cargoFirma1' ),
		array( 'db' => 'dadetr_nombreAlcalde', 'dt' => 'dadetr_nombreAlcalde', 'field' => 'dadetr_nombreAlcalde' ),
		array( 'db' => 'dadetr_porOrdenFirma2', 'dt' => 'dadetr_porOrdenFirma2', 'field' => 'dadetr_porOrdenFirma2' ),
		array( 'db' => 'dadetr_cargoFirma2', 'dt' => 'dadetr_cargoFirma2', 'field' => 'dadetr_cargoFirma2' ),
		array( 'db' => 'dadetr_iniciales', 'dt' => 'dadetr_iniciales', 'field' => 'dadetr_iniciales' ),
		array( 'db' => 'dadetr_rutaLogo', 'dt' => 'dadetr_rutaLogo', 'field' => 'dadetr_rutaLogo' ),
		array( 'db' => 'dadetr_descripDistri', 'dt' => 'dadetr_descripDistri', 'field' => 'dadetr_descripDistri' ),
		array( 'db' => 'dadetr_descripVistos', 'dt' => 'dadetr_descripVistos', 'field' => 'dadetr_descripVistos' ),
    	array(
               'db' => 'dadetr_creacion',
               'dt' => 'dadetr_creacion',
               'field' => 'dadetr_creacion',
               'formatter' => function( $d, $row ){
                    return date( 'd-m-Y', strtotime($d) );
               }
             ),
		array(
               'db' => 'dadetr_modificacion',
               'dt' => 'dadetr_modificacion',
               'field' => 'dadetr_modificacion',
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
	
    $joinQuery = "FROM datos_decreto_Traslado JOIN decretos_traslado ON datos_decreto_Traslado.tras_folio = decretos_traslado.tras_folio";

    $extraWhere = 'decretos_traslado.tras_estado = 1';
    $groupBy = 'datos_decreto_Traslado.tras_folio'; 
    $having = ''; 

    echo json_encode(
    	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
    );
}

?>

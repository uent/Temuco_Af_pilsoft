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
    'dadeal_nombreOrg' => 'sanitize_string|ms_word_characters',
    'dadeal_nombreDireccion' => 'sanitize_string|ms_word_characters',
    'dadeal_nombreSecre' => 'sanitize_string|ms_word_characters',
    'dadeal_porOrdenFirma1' => 'sanitize_string|ms_word_characters',
    'dadeal_cargoFirma1' => 'sanitize_string|ms_word_characters',
    'dadeal_nombreAlcalde' => 'sanitize_string|ms_word_characters',
    'dadeal_porOrdenFirma2' => 'sanitize_string|ms_word_characters',
    'dadeal_cargoFirma2' => 'sanitize_string|ms_word_characters',
    'dadeal_iniciales' => 'sanitize_string|ms_word_characters',
	'dadeal_rutaLogo' => 'sanitize_string|ms_word_characters',
	'dadeal_descripVistos' => 'sanitize_string|ms_word_characters',
	'dadeal_descripDistri' => 'sanitize_string|ms_word_characters'
	
	
));

$errores = array();

if( $opcion != 'leer' ){
    if ($opcion=='editar'){
        
		
		
        $gump->validation_rules(array(
		
            'dadeal_id'=>'required|numeric',
            'dadeal_nombreOrg' => 'required|alpha_space|max_len,60|min_len,5',#
            'dadeal_nombreDireccion' => 'alpha_space|max_len,100|min_len,5',#
            'dadeal_nombreSecre' => 'required|alpha_space|max_len,60|min_len,5',#
			'dadeal_porOrdenFirma1' => 'alpha_space|max_len,60|min_len,3',#
            'dadeal_cargoFirma1' => 'alpha_space|max_len,60|min_len,3',#
            'dadeal_nombreAlcalde' => 'required|alpha_space|max_len,60|min_len,5',#
            'dadeal_porOrdenFirma2' => 'alpha_space|max_len,60|min_len,3',#
            'dadeal_cargoFirma2' => 'alpha_space|max_len,60|min_len,3',#
            'dadeal_iniciales' => 'max_len,20|min_len,3',#
            'dadeal_rutaLogo' => 'extension,png;jpg',#
            'dadeal_descripDistri' => 'min_len,3',
            'dadeal_descripVistos' => 'min_len,3'
       
        )); 
        
		
		
		
		
    }elseif($opcion=='eliminar'){
        
        $gump->validation_rules(array(
        'dadeal_id' => 'required|numeric'
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
           $dadeal_id = $_POST['dadeal_id'];
        }
        
        if($opcion=='editar'){
            $dadeal_id = $_POST['dadeal_id'];
            $dadeal_nombreOrg = utf8_decode($_POST['dadeal_nombreOrg']);
            $dadeal_nombreDireccion = utf8_decode($_POST['dadeal_nombreDireccion']);  
            $dadeal_nombreSecre = utf8_decode($_POST['dadeal_nombreSecre']);
            $dadeal_porOrdenFirma1 = utf8_decode($_POST['dadeal_porOrdenFirma1']);
            $dadeal_cargoFirma1 = utf8_decode($_POST['dadeal_cargoFirma1']);
            $dadeal_nombreAlcalde = utf8_decode($_POST['dadeal_nombreAlcalde']);
            $dadeal_porOrdenFirma2 = utf8_decode($_POST['dadeal_porOrdenFirma2']);
            $dadeal_cargoFirma2 = utf8_decode($_POST['dadeal_cargoFirma2']);
            $dadeal_iniciales = utf8_decode($_POST['dadeal_iniciales']);
			$dadeal_descripVistos = utf8_decode($_POST['dadeal_descripVistos']);
            $dadeal_descripDistri = utf8_decode($_POST['dadeal_descripDistri']);

			
			//$dadeal_modificacion = date("Y-m-d H:i:s");
            $dadeal_rutaLogo = 'dadeal_rutaLogo/';
                
            if( !is_dir($dadeal_rutaLogo) ){
                mkdir($dadeal_rutaLogo, 0755);
            }
                
            if( !is_writable($dadeal_rutaLogo) ){
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
                    
                    $nombre_archivo = $dadeal_nombreOrg.'.'.$ext;
                    $ruta_destino = $dadeal_rutaLogo.$nombre_archivo;
                    
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
        $dadeal_id, $dadeal_nombreOrg, $dadeal_nombreDireccion, $dadeal_nombreSecre, $dadeal_porOrdenFirma1, $dadeal_cargoFirma1, 
        $dadeal_nombreAlcalde, $dadeal_porOrdenFirma2, $dadeal_cargoFirma2, $dadeal_iniciales, $ruta_destino, $dadeal_descripVistos, $dadeal_descripDistri 
        );
	break;
        
	case "eliminar":
        eliminarData($dadeal_id);
	break;
        
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
 }




function editarData(
            $dadeal_id, $dadeal_nombreOrg, $dadeal_nombreDireccion, $dadeal_nombreSecre, $dadeal_porOrdenFirma1, $dadeal_cargoFirma1, 
            $dadeal_nombreAlcalde, $dadeal_porOrdenFirma2, $dadeal_cargoFirma2, $dadeal_iniciales, $dadeal_rutaLogo, $dadeal_descripVistos, $dadeal_descripDistri
        )
	{  	
	require_once 'config.php';

	$userID = $_SESSION['userid'];

    $query = "UPDATE datos_decreto_alta SET dadeal_nombreOrg='".$dadeal_nombreOrg."', dadeal_nombreDireccion='".$dadeal_nombreDireccion."', 
             dadeal_nombreSecre='".$dadeal_nombreSecre."', dadeal_porOrdenFirma1='".$dadeal_porOrdenFirma1."', dadeal_cargoFirma1='".$dadeal_cargoFirma1."', 
             dadeal_nombreAlcalde='".$dadeal_nombreAlcalde."', dadeal_porOrdenFirma2='".$dadeal_porOrdenFirma2."', dadeal_cargoFirma2='".$dadeal_cargoFirma2."', ";
    $query.= ( !empty($ruta_destino) ) ? "dadeal_rutaLogo='".$dadeal_rutaLogo."', " : '';
    $query.= "dadeal_descripVistos='" . $dadeal_descripVistos . "', dadeal_descripDistri='" . $dadeal_descripDistri . "', dadeal_iniciales='".$dadeal_iniciales."', dadeal_modificador='".$userID."',dadeal_modificacion= NOW() 
			WHERE dadeal_id='".$dadeal_id."'";
    
    $result = mysqli_query($conexion, $query);
    echo mysqli_error($conexion);
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function eliminarData($idRegistro)
{
	require_once 'config.php';
	
	$userID = $_SESSION['userid'];	
	
		$query = " UPDATE decretos_alta, datos_decreto_alta
					SET decretos_alta.alt_estado = 0, decretos_alta.alt_modificacion = '" . date("Y-m-d H:i:s") . "', decretos_alta.alt_modificador = '". $userID ."'
							WHERE decretos_alta.alt_folio = datos_decreto_alta.alt_folio 
								AND datos_decreto_alta.dadeal_id= " .$idRegistro. "; ";
	
	
		$query .= "UPDATE activos_fijos, decretos_alta, datos_decreto_alta 
				SET activos_fijos.act_decretoAlta = 0 , activos_fijos.act_modificacion = '" . date("Y-m-d H:i:s") . "' , activos_fijos.act_modificador = '". $userID . "' 
							WHERE activos_fijos.act_id = decretos_alta.act_id 
								AND decretos_alta.alt_folio = datos_decreto_alta.alt_folio 
								AND datos_decreto_alta.dadeal_id = " .$idRegistro. "; ";  
								
						
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
    $table = 'datos_decreto_alta';

    // Table's primary key
    $primaryKey = 'dadeal_id';

    // db = campo de la bd, dt = columna del datatable
	
	
    $columns = array(
        array( 'db' => 'dadeal_id', 'dt' => null, 'field' => 'dadeal_id' ),
    	array( 'db' => 'dadeal_id', 'dt' => 'dadeal_id', 'field' => 'dadeal_id' ),
    	array( 'db' => 'datos_decreto_alta.alt_folio', 'dt' => 'alt_folio', 'field' => 'alt_folio' ),
    	array( 'db' => 'dadeal_nombreOrg', 'dt' => 'dadeal_nombreOrg', 'field' => 'dadeal_nombreOrg' ),
    	array( 'db' => 'dadeal_nombreDireccion', 'dt' => 'dadeal_nombreDireccion', 'field' => 'dadeal_nombreDireccion' ),
    	array( 'db' => 'dadeal_nombreSecre', 'dt' => 'dadeal_nombreSecre', 'field' => 'dadeal_nombreSecre' ),
		array( 'db' => 'dadeal_porOrdenFirma1', 'dt' => 'dadeal_porOrdenFirma1', 'field' => 'dadeal_porOrdenFirma1' ),
		array( 'db' => 'dadeal_cargoFirma1', 'dt' => 'dadeal_cargoFirma1', 'field' => 'dadeal_cargoFirma1' ),
		array( 'db' => 'dadeal_nombreAlcalde', 'dt' => 'dadeal_nombreAlcalde', 'field' => 'dadeal_nombreAlcalde' ),
		array( 'db' => 'dadeal_porOrdenFirma2', 'dt' => 'dadeal_porOrdenFirma2', 'field' => 'dadeal_porOrdenFirma2' ),
		array( 'db' => 'dadeal_cargoFirma2', 'dt' => 'dadeal_cargoFirma2', 'field' => 'dadeal_cargoFirma2' ),
		array( 'db' => 'dadeal_iniciales', 'dt' => 'dadeal_iniciales', 'field' => 'dadeal_iniciales' ),
		array( 'db' => 'dadeal_rutaLogo', 'dt' => 'dadeal_rutaLogo', 'field' => 'dadeal_rutaLogo' ),
		array( 'db' => 'dadeal_descripDistri', 'dt' => 'dadeal_descripDistri', 'field' => 'dadeal_descripDistri' ),
		array( 'db' => 'dadeal_descripVistos', 'dt' => 'dadeal_descripVistos', 'field' => 'dadeal_descripVistos' ),
    	array(
               'db' => 'dadeal_creacion',
               'dt' => 'dadeal_creacion',
               'field' => 'dadeal_creacion',
               'formatter' => function( $d, $row ){
                    return date( 'd-m-Y', strtotime($d) );
               }
             ),
		array(
               'db' => 'dadeal_modificacion',
               'dt' => 'dadeal_modificacion',
               'field' => 'dadeal_modificacion',
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
    require_once 'config.php';
    $sql_details = array(
    	'user' => $usuario,
    	'pass' => $clave,
    	'db'   => $BD,
    	'host' => $servidor
    );

    // require( 'ssp.class.php' );
    require_once 'requires/ssp.customized.class.php';	
	
    $joinQuery = "FROM datos_decreto_alta JOIN decretos_alta ON datos_decreto_alta.alt_folio = decretos_alta.alt_folio";

    $extraWhere = 'decretos_alta.alt_estado = 1';
    $groupBy = 'datos_decreto_alta.alt_folio'; 
    $having = ''; 

    echo json_encode(
    	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
    );
}

?>

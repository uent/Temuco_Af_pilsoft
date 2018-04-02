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
    'dadeba_nombreOrg' => 'sanitize_string|ms_word_characters',
    'dadeba_nombreDireccion' => 'sanitize_string|ms_word_characters',
    'dadeba_nombreSecre' => 'sanitize_string|ms_word_characters',
    'dadeba_porOrdenFirma1' => 'sanitize_string|ms_word_characters',
    'dadeba_cargoFirma1' => 'sanitize_string|ms_word_characters',
    'dadeba_nombreAlcalde' => 'sanitize_string|ms_word_characters',
    'dadeba_porOrdenFirma2' => 'sanitize_string|ms_word_characters',
    'dadeba_cargoFirma2' => 'sanitize_string|ms_word_characters',
    'dadeba_iniciales' => 'sanitize_string|ms_word_characters',
	'dadeba_rutaLogo' => 'sanitize_string|ms_word_characters',
	'dadeba_descripVistos' => 'sanitize_string|ms_word_characters',
	'dadeba_descripDistri' => 'sanitize_string|ms_word_characters'
	
	
));

$errores = array();

if( $opcion != 'leer' ){
    if ($opcion=='editar'){
        
		
		
        $gump->validation_rules(array(
		
            'dadeba_id'=>'required|numeric',
            'dadeba_nombreOrg' => 'required|alpha_space|max_len,60|min_len,5',#
            'dadeba_nombreDireccion' => 'alpha_space|max_len,100|min_len,5',#
            'dadeba_nombreSecre' => 'required|alpha_space|max_len,60|min_len,5',#
			'dadeba_porOrdenFirma1' => 'alpha_space|max_len,60|min_len,3',#
            'dadeba_cargoFirma1' => 'alpha_space|max_len,60|min_len,3',#
            'dadeba_nombreAlcalde' => 'required|alpha_space|max_len,60|min_len,5',#
            'dadeba_porOrdenFirma2' => 'alpha_space|max_len,60|min_len,3',#
            'dadeba_cargoFirma2' => 'alpha_space|max_len,60|min_len,3',#
            'dadeba_iniciales' => 'max_len,20|min_len,3',#
            'dadeba_rutaLogo' => 'extension,png;jpg',#
            'dadeba_descripDistri' => 'min_len,3',
            'dadeba_descripVistos' => 'min_len,3'
       
        )); 
        
		
		
		
		
    }elseif($opcion=='eliminar'){
        
        $gump->validation_rules(array(
        'dadeba_id' => 'required|numeric'
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
           $dadeba_id = $_POST['dadeba_id'];
        }
        
        if($opcion=='editar'){
            $dadeba_id = $_POST['dadeba_id'];
            $dadeba_nombreOrg = utf8_decode($_POST['dadeba_nombreOrg']);
            $dadeba_nombreDireccion = utf8_decode($_POST['dadeba_nombreDireccion']);  
            $dadeba_nombreSecre = utf8_decode($_POST['dadeba_nombreSecre']);
            $dadeba_porOrdenFirma1 = utf8_decode($_POST['dadeba_porOrdenFirma1']);
            $dadeba_cargoFirma1 = utf8_decode($_POST['dadeba_cargoFirma1']);
            $dadeba_nombreAlcalde = utf8_decode($_POST['dadeba_nombreAlcalde']);
            $dadeba_porOrdenFirma2 = utf8_decode($_POST['dadeba_porOrdenFirma2']);
            $dadeba_cargoFirma2 = utf8_decode($_POST['dadeba_cargoFirma2']);
            $dadeba_iniciales = utf8_decode($_POST['dadeba_iniciales']);
			$dadeba_descripVistos = utf8_decode($_POST['dadeba_descripVistos']);
            $dadeba_descripDistri = utf8_decode($_POST['dadeba_descripDistri']);

			
			$dadeba_modificacion = date("Y-m-d H:i:s");
            $dadeba_rutaLogo = 'dadeba_rutaLogo/';
                
            if( !is_dir($dadeba_rutaLogo) ){
                mkdir($dadeba_rutaLogo, 0755);
            }
                
            if( !is_writable($dadeba_rutaLogo) ){
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
                    
                    $nombre_archivo = $dadeba_nombreOrg.'.'.$ext;
                    $ruta_destino = $dadeba_rutaLogo.$nombre_archivo;
                    
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
        $dadeba_id, $dadeba_nombreOrg, $dadeba_nombreDireccion, $dadeba_nombreSecre, $dadeba_porOrdenFirma1, $dadeba_cargoFirma1, 
        $dadeba_nombreAlcalde, $dadeba_porOrdenFirma2, $dadeba_cargoFirma2, $dadeba_iniciales, $ruta_destino, $dadeba_descripVistos, $dadeba_descripDistri, $dadeba_modificacion 
        );
	break;
        
	case "eliminar":
        eliminarData($dadeba_id);
	break;
        
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
 }




function editarData(
            $dadeba_id, $dadeba_nombreOrg, $dadeba_nombreDireccion, $dadeba_nombreSecre, $dadeba_porOrdenFirma1, $dadeba_cargoFirma1, 
            $dadeba_nombreAlcalde, $dadeba_porOrdenFirma2, $dadeba_cargoFirma2, $dadeba_iniciales, $dadeba_rutaLogo, $dadeba_descripVistos, $dadeba_descripDistri, $dadeba_modificacion
        )
	{  	
	require 'config.php';
	
	
	$userID = $_SESSION['userid'];

    $query = "UPDATE datos_decreto_baja SET dadeba_nombreOrg='".$dadeba_nombreOrg."', dadeba_nombreDireccion='".$dadeba_nombreDireccion."', 
             dadeba_nombreSecre='".$dadeba_nombreSecre."', dadeba_porOrdenFirma1='".$dadeba_porOrdenFirma1."', dadeba_cargoFirma1='".$dadeba_cargoFirma1."', 
             dadeba_nombreAlcalde='".$dadeba_nombreAlcalde."', dadeba_porOrdenFirma2='".$dadeba_porOrdenFirma2."', dadeba_cargoFirma2='".$dadeba_cargoFirma2."', ";
    $query.= ( !empty($ruta_destino) ) ? "dadeba_rutaLogo='".$dadeba_rutaLogo."', " : '';
    $query.= "dadeba_descripVistos='" . $dadeba_descripVistos . "', dadeba_descripDistri='" . $dadeba_descripDistri . "', dadeba_iniciales='".$dadeba_iniciales."', dadeba_modificador='".$userID."',dadeba_modificacion='". date("Y-m-d H:i:s") ."'
			WHERE dadeba_id='".$dadeba_id."'";
    
    $result = mysqli_query($conexion, $query);
    echo mysqli_error($conexion);
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function eliminarData($idRegistro)
{
	require 'config.php';

	$userID = $_SESSION['userid'];	
	
		$query = " UPDATE decretos_baja, datos_decreto_baja
					SET decretos_baja.baj_estado = 0, baj_modificacion = '" . date("Y-m-d H:i:s") . "', baj_modificador = '". $userID ."'
							WHERE decretos_baja.baj_folio = datos_decreto_baja.baj_folio 
								AND datos_decreto_baja.dadeba_id= " .$idRegistro. "; ";
	
	
		$query .= "UPDATE activos_fijos, decretos_baja, datos_decreto_baja 
				SET activos_fijos.act_decretoBaja = 0 , act_modificacion = '" . date("Y-m-d H:i:s") . "' , act_modificador = '". $userID . "' 
							WHERE activos_fijos.act_id = decretos_baja.act_id 
								AND decretos_baja.baj_folio = datos_decreto_baja.baj_folio 
								AND datos_decreto_baja.dadeba_id = " .$idRegistro. "; ";  
			

			
				
						
	//echo $query;									
    //echo $idRegistro;
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
    $table = 'datos_decreto_baja';

    // Table's primary key
    $primaryKey = 'dadeba_id';

    // db = campo de la bd, dt = columna del datatable
	
	
    $columns = array(
        array( 'db' => 'dadeba_id', 'dt' => null, 'field' => 'dadeba_id' ),
    	array( 'db' => 'dadeba_id', 'dt' => 'dadeba_id', 'field' => 'dadeba_id' ),
    	array( 'db' => 'datos_decreto_baja.baj_folio', 'dt' => 'baj_folio', 'field' => 'baj_folio' ),
    	array( 'db' => 'dadeba_nombreOrg', 'dt' => 'dadeba_nombreOrg', 'field' => 'dadeba_nombreOrg' ),
    	array( 'db' => 'dadeba_nombreDireccion', 'dt' => 'dadeba_nombreDireccion', 'field' => 'dadeba_nombreDireccion' ),
    	array( 'db' => 'dadeba_nombreSecre', 'dt' => 'dadeba_nombreSecre', 'field' => 'dadeba_nombreSecre' ),
		array( 'db' => 'dadeba_porOrdenFirma1', 'dt' => 'dadeba_porOrdenFirma1', 'field' => 'dadeba_porOrdenFirma1' ),
		array( 'db' => 'dadeba_cargoFirma1', 'dt' => 'dadeba_cargoFirma1', 'field' => 'dadeba_cargoFirma1' ),
		array( 'db' => 'dadeba_nombreAlcalde', 'dt' => 'dadeba_nombreAlcalde', 'field' => 'dadeba_nombreAlcalde' ),
		array( 'db' => 'dadeba_porOrdenFirma2', 'dt' => 'dadeba_porOrdenFirma2', 'field' => 'dadeba_porOrdenFirma2' ),
		array( 'db' => 'dadeba_cargoFirma2', 'dt' => 'dadeba_cargoFirma2', 'field' => 'dadeba_cargoFirma2' ),
		array( 'db' => 'dadeba_iniciales', 'dt' => 'dadeba_iniciales', 'field' => 'dadeba_iniciales' ),
		array( 'db' => 'dadeba_rutaLogo', 'dt' => 'dadeba_rutaLogo', 'field' => 'dadeba_rutaLogo' ),
		array( 'db' => 'dadeba_descripDistri', 'dt' => 'dadeba_descripDistri', 'field' => 'dadeba_descripDistri' ),
		array( 'db' => 'dadeba_descripVistos', 'dt' => 'dadeba_descripVistos', 'field' => 'dadeba_descripVistos' ),
    	array(
               'db' => 'dadeba_creacion',
               'dt' => 'dadeba_creacion',
               'field' => 'dadeba_creacion',
               'formatter' => function( $d, $row ){
                    return date( 'd-m-Y', strtotime($d) );
               }
             ),
		array(
               'db' => 'dadeba_modificacion',
               'dt' => 'dadeba_modificacion',
               'field' => 'dadeba_modificacion',
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
	
    $joinQuery = "FROM datos_decreto_baja JOIN decretos_baja ON datos_decreto_baja.baj_folio = decretos_baja.baj_folio";

    $extraWhere = 'decretos_baja.baj_estado = 1';
    $groupBy = 'datos_decreto_baja.baj_folio'; 
    $having = ''; 

    echo json_encode(
    	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
    );
}

?>

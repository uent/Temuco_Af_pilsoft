<?php
require_once 'config.php';
require_once 'requires/gump.class.php';

$userID = $_SESSION['userid'];

$opcion = '';
$gump = new GUMP();
$_POST = $gump->sanitize($_POST);

if( isset($_GET['opcion']) ){
    // en caso q se vaya a leer
    $opcion = $_GET['opcion'];
}else{
    $opcion = $_POST['opcion'];
}

$errores = array();

$gump->filter_rules(array(
    'dalt_descripcion' => 'sanitize_string|ms_word_characters'
));

if( $opcion != 'leer' ){
    if ($opcion=='editar'){
         $gump->validation_rules(array(
        'dalt_id'=>'required|numeric',
        'dalt_descripcion' => 'required|alpha_space|max_len,255|min_len,10'
    ));
    }elseif($opcion=='insertar'){
        $gump->validation_rules(array(  
        'dalt_descripcion' => 'required|alpha_space|max_len,255|min_len,10'
    ));    
    }elseif($opcion=='eliminar'){    
         $gump->validation_rules(array(
        'dalt_id'=>'required|numeric'        
    ));
    }
        
    $validated_data = $gump->run($_POST);

    if($validated_data===false){
     
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
           $dalt_id = $_POST['dalt_id'];
        }
        if($opcion=='editar'){
            $dalt_id = $_POST['dalt_id'];
            $dalt_descripcion = utf8_decode($_POST['dalt_descripcion']);
            $dalt_modificacion = date("Y-m-d H:i:s");
        }
        if($opcion=='insertar'){
            $dalt_descripcion = utf8_decode($_POST['dalt_descripcion']);  
        }       
    }
}

switch ( $opcion ){
	case "insertar":
        $cantidad = verificaExistencia($dalt_descripcion, $conexion);
        
        if( $cantidad > 0 ){
            exit("existe");
        }else{
            insertarData($dalt_descripcion, $userID, $conexion);
        }
	break;

	case "leer":
        leerData($conexion);
	break;

	case "editar":
        editarData($dalt_id, $dalt_descripcion, $userID, $dalt_modificacion, $conexion);
	break;
        
	case "eliminar":
        eliminarData($dalt_id, $conexion);
	break;
        
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
}

function leerData($conexion)
{
    $query = "SELECT dalt_id, dalt_descripcion, dalt_creacion 
              FROM param_distri_alta ORDER BY dalt_id ASC";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
       
    $arreglo["data"] = array(); // asigna un array vacío si no hay registros en bd.
    while( $data = mysqli_fetch_assoc($result) ){
        $arreglo["data"][] = array_map("utf8_encode", $data);
    }
    
    // encode para el dataTableval_descripcion
    echo json_encode($arreglo);
    
    mysqli_free_result($result);
    mysqli_close($conexion);    
}

function insertarData($dalt_descripcion, $userID, $conexion)
{
    $query = "INSERT INTO param_distri_alta(dalt_id, dalt_descripcion, dalt_creador) 
              VALUES(NULL, '".$dalt_descripcion."', '".$userID."')";
    $result = mysqli_query($conexion, $query);
    
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function editarData($dalt_id, $dalt_descripcion, $userID, $dalt_modificacion, $conexion)
{  
    $query = "UPDATE param_distri_alta SET dalt_descripcion='".$dalt_descripcion."',
              dalt_creador='".$userID."', dalt_modificacion='".$dalt_modificacion."' WHERE dalt_id='".$dalt_id."' LIMIT 1";
    $result = mysqli_query($conexion, $query);
    
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function eliminarData($dalt_id, $conexion)
{	
	$query = "DELETE FROM param_distri_alta WHERE dalt_id='".$dalt_id."' LIMIT 1";
	$result = mysqli_query($conexion, $query);
	
	resultadoQuery( $result );
	
	mysqli_close($conexion);
}

function verificaExistencia($campoClave, $conexion)
{
    $query = "SELECT dalt_id FROM param_distri_alta WHERE dalt_descripcion='".$campoClave."' LIMIT 1";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;
    
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
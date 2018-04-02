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

$errores=array();
$gump->filter_rules(array(
    'dbaj_descripcion' => 'sanitize_string|ms_word_characters'
));
if( $opcion != 'leer' ){

if ($opcion=='editar'){
     $gump->validation_rules(array(
    'dbaj_id'=>'required|numeric',
    'dbaj_descripcion' => 'required|alpha_space|max_len,255|min_len,10'
)); 

}else if($opcion=='insertar'){
    $gump->validation_rules(array(  
    'dbaj_descripcion' => 'required|alpha_space|max_len,255|min_len,10'
)); 

}else if($opcion=='eliminar'){

     $gump->validation_rules(array(
    'dbaj_id'=>'required|numeric'
    
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
   $dbaj_id = $_POST['dbaj_id'];
}
if($opcion=='editar'){
    $dbaj_id = $_POST['dbaj_id'];
    $dbaj_descripcion = utf8_decode($_POST['dbaj_descripcion']);
    $dbaj_modificacion = date("Y-m-d H:i:s");
}
if($opcion=='insertar'){
    $dbaj_descripcion = utf8_decode($_POST['dbaj_descripcion']);  
}
   
}
}

switch ( $opcion ){
	case "insertar":
            // validar los datos de entrada de forma previa dbaj_descripcion
             $cantidad = verificaExistencia($dbaj_descripcion, $conexion);
            
            if( $cantidad > 0 ){
                exit("existe");
            }else{
                insertarData($dbaj_descripcion, $userID, $conexion);
            }
	break;

	case "leer":
        leerData($conexion);
	break;

	case "editar":
    
        editarData($dbaj_id, $dbaj_descripcion, $userID,$dbaj_modificacion, $conexion);
	break;
        
	case "eliminar":
        eliminarData($dbaj_id, $conexion);
	break;
        
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
}

function leerData($conexion)
{
    $query = "SELECT dbaj_id,dbaj_descripcion,dbaj_creacion 
              FROM param_distri_baja ORDER BY dbaj_id ASC";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
       
    $arreglo["data"] = ''; // asigna un array vacío si no hay registros en bd.
    while( $data = mysqli_fetch_assoc($result) ){
        $arreglo["data"][] = array_map("utf8_encode", $data);
    }
    
    // encode para el dataTableval_descripcion
    echo json_encode($arreglo);
    
    mysqli_free_result($result);
    mysqli_close($conexion);    
}

function insertarData( $dbaj_descripcion, $userID, $conexion)
{
    $query = "INSERT INTO param_distri_baja(dbaj_id, dbaj_descripcion,dbaj_creador) 
              VALUES(NULL,'".$dbaj_descripcion."','".$userID."')";
    $result = mysqli_query($conexion, $query);
    
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function editarData($dbaj_id,$dbaj_descripcion, $userID, $dbaj_modificacion, $conexion)
{  
    $query = "UPDATE param_distri_baja SET  dbaj_descripcion='".$dbaj_descripcion."',
              dbaj_creador='".$userID."', dbaj_modificacion='".$dbaj_modificacion."' WHERE dbaj_id='".$dbaj_id."' LIMIT 1";
    $result = mysqli_query($conexion, $query);
    
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function eliminarData($dbaj_id, $conexion)
{	
	// $cantidad = buscaActivo_fijos($cond_id, $conexion);
    
    $cantidad=0;
	if( $cantidad > 0 ){
        $msj = "Lo sentimos, actualmente existen <b>" .$cantidad. "</b> asociada activo fijo que intenta eliminar.
                Favor, cambie los condicion estado y luego intente nuevamente.";
        echo utf8_encode($msj);
        mysqli_close($conexion);
    
    }else{
		$query = "DELETE FROM param_distri_baja WHERE dbaj_id='".$dbaj_id."' LIMIT 1";
		//$query = "UPDATE usuarios SET estado=0 WHERE idusuario=".$idusuario." LIMIT 1"; vbaj_id
		$result = mysqli_query($conexion, $query);
		
		resultadoQuery( $result );
		
		mysqli_close($conexion);
	}
}

function verificaExistencia($campoClave, $conexion)
{
    $query = "SELECT dbaj_id FROM param_distri_baja WHERE dbaj_descripcion='".$campoClave."' LIMIT 1";
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
function buscaActivo_fijos($idRegistro, $conexion)
{
    $query = "SELECT cond_id FROM activos_fijos WHERE cond_id ='".$idRegistro."'";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;        
}
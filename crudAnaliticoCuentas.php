<?php
require_once 'config.php';
require_once 'requires/gump.class.php';
$userID = $_SESSION['userid'];

$opcion = '';

$gump = new GUMP();
$_POST = $gump->sanitize($_POST);

if( isset($_GET['opcion']) ){
    // En caso q vaya a leer.
    $opcion = $_GET['opcion'];
}else{
    $opcion = $_POST['opcion'];
}
$errores=array();

$gump->filter_rules(array(
    'anac_descripcion' => 'sanitize_string|ms_word_characters'
));

if( $opcion != 'leer' ){

if ($opcion=='editar'){

$gump->validation_rules(array(
    'anac_id'=>'required|numeric',
    'anac_descripcion' => 'required|alpha_space|max_len,60|min_len,3'
  ));


}else if($opcion=='insertar'){

$gump->validation_rules(array( 
    'coda_codigo' => 'required|alpha_dash|max_len,7|min_len,6',
    'anac_descripcion' => 'required|alpha_space|max_len,60|min_len,3'
));

}else if($opcion=='eliminar'){

     $gump->validation_rules(array(
    'anac_id'=>'required|numeric'  
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
   $anac_id = $_POST['anac_id'];
}
if($opcion=='editar'){
   $anac_id = $_POST['anac_id'];
   
   $anac_descripcion = utf8_decode($_POST['anac_descripcion']);
    // Para el caso q la opcion sea 'editar'        
   $anac_modificacion = date("Y-m-d H:i:s");
}
if($opcion=='insertar'){
        $coda_nivel_5 = $_POST['coda_nivel_5'];
		$coda_codigo = $_POST['coda_codigo'];
        $anac_descripcion = utf8_decode($_POST['anac_descripcion']);
    }

}

}

switch ( $opcion ){
	case "insertar":
        insertarData($coda_codigo, $anac_descripcion, $coda_nivel_5, $conexion);
	break;

	case "leer":
        leerData($conexion);
	break;

	case "editar":
        editarData($anac_id, $anac_descripcion, $anac_modificacion,$userID , $conexion);
	break;
        
	case "eliminar":
        eliminarData($anac_id, $conexion);
	break;
        
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
}

function leerData($conexion)
{
    $query = "SELECT anac_id, coda_codigo, anac_descripcion, anac_creacion 
              FROM analiticos_cuentas ORDER BY coda_codigo ASC";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
       
    //$arreglo["data"] = ''; // asigna un array vacío si no hay registros en bd.
    while( $data = mysqli_fetch_assoc($result) ){
        $arreglo["data"][] = array_map("utf8_encode", $data);
    }
    
    // json para el dataTable
    echo json_encode($arreglo);
    mysqli_free_result($result);
    mysqli_close($conexion);    
}

function editarData($anac_id, $anac_descripcion, $anac_modificacion,$userID, $conexion)
{  
    $query = "UPDATE analiticos_cuentas SET anac_descripcion='".$anac_descripcion."',
              anac_modificador='".$userID."', anac_modificacion='".$anac_modificacion."' WHERE anac_id=".$anac_id." LIMIT 1";
    $result = mysqli_query($conexion, $query);
    
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function eliminarData($idRegistro, $conexion)
{
	$query = "SELECT act_codigo FROM activos_fijos WHERE anac_id = " . $idRegistro . " LIMIT 1";

	$result = mysqli_query($conexion, $query);
	
	$aux = mysqli_fetch_assoc($result);
	
	if($aux == null) 
    {
        $query = "DELETE FROM analiticos_cuentas WHERE anac_id=".$idRegistro." LIMIT 1";
        $result = mysqli_query($conexion, $query);
        
        resultadoQuery( $result );
        
        mysqli_close($conexion);
		
		//echo "Eliminacion Exitosa";
    }
	else 
	{
		//resultadoQuery("");
		echo "No se puede eliminar este anal&iacute;tico, aun est&aacute; asociado al activo fijo con c&oacute;digo: " . $aux["act_codigo"];
	}
	
}



function insertarData($coda_codigo,$anac_descripcion,$coda_nivel_5,$conexion)
{
	$userID = $_SESSION['userid'];
	
	$query = "select anac_id from analiticos_cuentas where coda_codigo = '".$coda_codigo."' AND anac_descripcion = '".$anac_descripcion."' ";
	
	$result = mysqli_query($conexion, $query);

	if( mysqli_num_rows($result) == 0)
	{	
	
		$query = "select max(CAST(anac_codigo AS SIGNED)) AS idMax from analiticos_cuentas";
		
		$result = mysqli_query($conexion, $query);
	
		$aux = mysqli_fetch_assoc($result);
	
		$idMax = $aux["idMax"]; 
	
		$query = "INSERT INTO analiticos_cuentas (anac_codigo, anac_descripcion, coda_codigo, anac_creador , anac_creacion)
					VALUES ('". ($idMax + 1) ."','". $anac_descripcion ."','". $coda_nivel_5 ."','". $userID ."','". date("Y-m-d H:i:s") ."' ); ";
										
		$result = mysqli_query($conexion, $query);
		
		resultadoQuery( $result );
        
		mysqli_close($conexion);		
	}else
	{
		resultadoQuery( "existe" );
	}			
}

function resultadoQuery($resultado)
{
    if( strcmp($resultado,'existe') == 0 ){
        $respuesta = 'existe';
    }else if( $resultado ){
        $respuesta = 'ok';
    }else{
        $respuesta = 'error';
        // en caso de querer hacer debug...
        //$respuesta = mysqli_error($conexion);
    }
    
    echo $respuesta;
}
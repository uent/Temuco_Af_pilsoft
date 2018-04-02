<?php
require_once 'config.php';
require_once 'requires/gump.class.php';
$userID = $_SESSION['userid'];
$fechaActual = date("Y-m-d H:i:s");

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
    'ubi_descripcion' => 'sanitize_string|ms_word_characters'
));
if( $opcion != 'leer' ){

if ($opcion=='editar'){

     $gump->validation_rules(array(
    'ubi_id'=>'required|numeric',
    'ubi_codigo' => 'required|alpha_dash|max_len,8|min_len,8',
    'ubi_descripcion' => 'required|alpha_space|max_len,60|min_len,3'
)); 

}else if($opcion=='insertar'){

    $gump->validation_rules(array(  
    'ubi_codigo' => 'required|alpha_dash|max_len,8|min_len,8',
    'ubi_descripcion' => 'required|alpha_space|max_len,60|min_len,3'
)); 

}else if($opcion=='eliminar'){

    $gump->validation_rules(array(
    'ubi_id'=>'required|numeric'
    
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
   $ubi_id = $_POST['ubi_id'];
}
if($opcion=='editar'){
   if( isset($_POST['ubi_codigo']) ){
        $ubi_codigo = $_POST['ubi_codigo'];
        
		$ubi_id = $_POST['ubi_id'];
		
        //Crea formato de niveles para centro de costo
        $nivel1 = substr($ubi_codigo, 0, 2).'-00-00';
        $nivel2 = substr($ubi_codigo, 0, 5).'-00';

        // Según formato, asigna nivel
        if( $ubi_codigo == $nivel1 ){
            $ubi_nivel = 1;
        }elseif( $ubi_codigo == $nivel2 ){
            $ubi_nivel = 2;
        }else{
            $ubi_nivel = 3;
        }        
    }
    
    if( isset($_POST['ubi_descripcion']) ){
        $ubi_descripcion = utf8_decode($_POST['ubi_descripcion']);
    }

    if( isset($_POST['resp_id']) ){
        $resp_id = $_POST['resp_id'];
    }else{
        $resp_id = 0;
    }
    
    // Para el caso q la opcion sea 'editar'        
    $ubi_modificacion = date("Y-m-d H:i:s");
}
if($opcion=='insertar'){
       if( isset($_POST['ubi_codigo']) ){
        $ubi_codigo = $_POST['ubi_codigo'];
        
        //Crea formato de niveles para centro de costo
        $nivel1 = substr($ubi_codigo, 0, 2).'-00-00';
        $nivel2 = substr($ubi_codigo, 0, 5).'-00';

        // Según formato, asigna nivel
        if( $ubi_codigo == $nivel1 ){
            $ubi_nivel = 1;
        }elseif( $ubi_codigo == $nivel2 ){
            $ubi_nivel = 2;
        }else{
            $ubi_nivel = 3;
        }        
    }
    
    if( isset($_POST['ubi_descripcion']) ){
        $ubi_descripcion = utf8_decode($_POST['ubi_descripcion']);
    }
	if( isset($_POST['aneg_nivel_0']) ){
        $aneg_id = utf8_decode($_POST['aneg_nivel_0']);
    }
    if( isset($_POST['resp_id']) ){
        $resp_id = $_POST['resp_id'];
    }else{
        $resp_id = 0;
    }
    
    // Para el caso q la opcion sea 'editar'        
   // $ubi_modificacion = date("Y-m-d H:i:s");
    }

   
}

}



switch ( $opcion ){
    case "leer":
        leerData($conexion);
    break;
	case "insertar":
        insertarData($ubi_codigo, $ubi_descripcion, $ubi_nivel, $resp_id, $aneg_id, $userID, $fechaActual, $conexion);
	break;

	case "editar":
        editarData($ubi_id, $ubi_codigo, $ubi_descripcion, $ubi_nivel, $resp_id, $userID, $ubi_modificacion, $conexion);
	break;
        
	case "eliminar":
        eliminarData($ubi_id, $conexion);
	break;
        
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
}

function leerData($conexion)
{
    $query = "SELECT * FROM ubicaciones ORDER BY ubi_codigo, ubi_nivel ASC";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
       
    $arreglo["data"] = array(); // asigna un array vacío si no hay registros en bd.
    while( $data = mysqli_fetch_assoc($result) ){
        $data['ubi_nivel'] = 'nivel:'.$data['ubi_nivel'];
        $arreglo["data"][] = array_map("utf8_encode", $data);
    }
    
    // json para el dataTable
    echo json_encode($arreglo);
    
    mysqli_free_result($result);
    mysqli_close($conexion);    
}

function insertarData($ubi_codigo, $ubi_descripcion, $ubi_nivel, $resp_id, $aneg_id, $userID, $fechaActual, $conexion)
{
    $cantidad = verificaExistencia($ubi_codigo,$aneg_id, $conexion);
    
    if( $ubi_nivel == 3 ){
        $padreN2 = buscaRaizPadreN2CC($ubi_codigo, $conexion);
        if( $padreN2 == 'N/A' ){
            echo "No puede asignar una ubicación a la raíz de Nivel 2 <b>xx-00-xx</b>, debe crear un nivel 2.";
            mysqli_close($conexion);
            exit;
        }elseif( $padreN2 == 0 ){
            echo "Aún no existe la ubicación de Nivel 2 señalado para: <b>".$ubi_codigo."</b>, debe crearlo.";            
            mysqli_close($conexion);
            exit;            
        }
    }
    
    if( $cantidad > 0 ){
        echo "existe";
        mysqli_close($conexion);
    }else{
		
        $query = "INSERT INTO ubicaciones(ubi_id, ubi_codigo, ubi_descripcion, ubi_nivel, resp_id, ubi_creador) 
                  VALUES(NULL, '".$ubi_codigo."', '".$ubi_descripcion."', '".$ubi_nivel."', '".$resp_id."', '".$userID."')";
        $result = mysqli_query($conexion, $query);
        
		if($ubi_nivel == 3)
		{
			$ubi_id = mysqli_insert_id($conexion);
		
			$query = "SELECT NV3.ubi_id AS ubi_id_3, NV2.ubi_id AS ubi_id_2, NV1.ubi_id AS ubi_id_1
					FROM ubicaciones NV3
						JOIN ubicaciones NV2 
							ON CONCAT(SUBSTR(NV3.ubi_codigo, 1, 5), '-00') = NV2.ubi_codigo
								JOIN
									ubicaciones NV1 ON CONCAT(SUBSTR(NV2.ubi_codigo, 1, 2), '-00-00') = NV1.ubi_codigo
										WHERE
											NV3.ubi_codigo = '".$ubi_codigo."'";
		
			$result = mysqli_query($conexion, $query);
			$datos = mysqli_fetch_assoc($result);
		
			$query = "INSERT INTO anegunidades_ubiniveles(aneg_id, ubi_id_nv1, ubi_id_nv2, ubi_id_nv3, ud_creador, ud_creacion) 
                  VALUES('".$aneg_id."', '".$datos["ubi_id_1"]."', '".$datos["ubi_id_2"]."', '".$datos["ubi_id_3"]."', '".$userID."', '".$fechaActual."')";
			$result = mysqli_query($conexion, $query);
		
				
		}
		
		resultadoQuery( $result );
        
        mysqli_close($conexion);
    }
}

function editarData($idRegistro, $ubi_codigo, $ubi_descripcion, $ubi_nivel, $resp_id, $userID, $ubi_modificacion, $conexion)
{  
    $query = "UPDATE ubicaciones SET ubi_codigo='".$ubi_codigo."', ubi_descripcion='".$ubi_descripcion."', ubi_nivel='".$ubi_nivel."', resp_id='".$resp_id."', ubi_modificador='".$userID."', ubi_modificacion='".$ubi_modificacion."' WHERE ubi_id=".$idRegistro." LIMIT 1";
    $result = mysqli_query($conexion, $query);
    
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function eliminarData($idRegistro, $conexion)
{
    $cantidad = buscaUbicacionesEnActivos($idRegistro, $conexion);
    
    $hijosUbicaciones = buscaHijosUbicaciones($idRegistro, $conexion);
    
    if( $cantidad > 0 )
    {
        $msj = "Lo sentimos, actualmente existen <b>" .$cantidad. "</b> activos 
                asociados a la ubicación que intenta eliminar.
                Favor, cambie los activos y luego intente nuevamente.";
        echo utf8_encode($msj);
        mysqli_close($conexion);
    
    }
    elseif( $hijosUbicaciones > 1 )
    {
        // Como la query de la función tmb cuenta al CC padre, se resta !
        $msj = "Lo sentimos, actualmente existen <b>" .($hijosUbicaciones-1). "</b>  
                Ubicaciones derivadas del que intenta eliminar.
                Favor, elimine las ubicaciones hijas y luego intente nuevamente.";
        echo utf8_encode($msj);
        mysqli_close($conexion);        
    }
    else
    {
		$query = "SELECT ubi_nivel FROM ubicaciones WHERE ubi_id = '" .$idRegistro. "'";
		$result = mysqli_query($conexion, $query);
		$nivel = mysqli_fetch_assoc($result);
	
		if( $nivel["ubi_nivel"] == 3)
		{	
			$query = "DELETE FROM anegunidades_ubiniveles WHERE ubi_id_nv3= '".$idRegistro."' ";
			$result = mysqli_query($conexion, $query);
        }
		
		$query = "DELETE FROM ubicaciones WHERE ubi_id=".$idRegistro." LIMIT 1";
		$result = mysqli_query($conexion, $query);
        
		resultadoQuery( $result );
        
		mysqli_close($conexion);
    }
}

function verificaExistencia($campoClave,$aneg_id, $conexion)
{
    $query = "SELECT ubi_id FROM ubicaciones WHERE ubi_codigo='".$campoClave."' LIMIT 1";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
	//echo $query;
	$query = "SELECT ubi_codigo AS nivel_3 FROM ubicaciones INNER JOIN anegunidades_ubiniveles 
				ON anegunidades_ubiniveles.ubi_id_nv3 = ubicaciones.ubi_codigo
					WHERE ubi_codigo='".$campoClave."' AND anegunidades_ubiniveles.aneg_id = '" .$aneg_id. "' LIMIT 1";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows += mysqli_num_rows($result);
		
    return $rows;            
}

function buscaRaizPadreN2CC($ubi_codigo, $conexion)
{
    // Busca si existe el nivel2 antes del 3
    $codN1 = substr($ubi_codigo, 0, 2);
    $codN2 = substr($ubi_codigo, 3, 2);
    // Antes de un N3 xx-yy-ZZ, debe existir
    // xx-YY-00 que sería el nivel raíz padre
    $raizPadreN2 = $codN1.'-' .$codN2. '-00';
    
    if( $codN2 == '00' ){
        return 'N/A'; // No se puede crear o asignar un nivel 3 a una raiz de nivel 2 con código del tipo "xx-00-xx".
    }else{
        $query = "SELECT ubi_id, ubi_codigo FROM ubicaciones WHERE ubi_codigo='".$raizPadreN2."' AND ubi_nivel=2";
        $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
        $count = mysqli_num_rows($result);
        
        return $count;
    }
}

function buscaUbicacionesEnActivos($idRegistro, $conexion)
{
    $query = "SELECT ubi_id FROM activos_fijos WHERE ubi_id='".$idRegistro."'";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;        
}

function buscaHijosUbicaciones($idRegistro, $conexion)
{
    $query = "SELECT ubi_id, ubi_codigo, ubi_nivel FROM ubicaciones WHERE ubi_id='".$idRegistro."' LIMIT 1";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_fetch_assoc($result);
    $codigo = $rows['ubi_codigo'];
    $nivelCC = $rows['ubi_nivel'];

    // Extrae parte del nivel a buscar
    $codNivel1 = substr($codigo, 0, 2);
    $codNivel2 = substr($codigo, 3, 2);
    
    // Hijos del nivel 1
    if( $nivelCC == 1 ){
        $query = "SELECT ubi_id, ubi_codigo FROM ubicaciones WHERE ubi_codigo REGEXP '^".$codNivel1."\\-..\\-..$'";
        $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
        $rows = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        
        return $count;
    }
    
    // Hijos del nivel 2
    if( $nivelCC == 2 ){
        $query = "SELECT ubi_id, ubi_codigo FROM ubicaciones WHERE ubi_codigo REGEXP '^".$codNivel1."\\-".$codNivel2."\\-..$'";
        $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
        $rows = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        
        return $count;
    }
    
    return 0;
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
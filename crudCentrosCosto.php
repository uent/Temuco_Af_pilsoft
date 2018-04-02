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
    'ccos_descripcion' => 'sanitize_string|ms_word_characters'
));

if( $opcion != 'leer' ){

if ($opcion=='editar'){

$gump->validation_rules(array(
    'ccos_id'=>'required|numeric',
    'ccos_codigo' => 'required|alpha_dash|max_len,8|min_len,8',
    'ccos_descripcion' => 'required|alpha_space|max_len,60|min_len,3'
  ));


}else if($opcion=='insertar'){

$gump->validation_rules(array( 
    'ccos_codigo' => 'required|alpha_dash|max_len,8|min_len,8',
    'ccos_descripcion' => 'required|alpha_space|max_len,60|min_len,3'
)); 

}else if($opcion=='eliminar'){

     $gump->validation_rules(array(
    'ccos_id'=>'required|numeric'  
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
   $ccos_id = $_POST['ccos_id'];
}
if($opcion=='editar'){
   $ccos_id = $_POST['ccos_id'];

        $ccos_codigo = $_POST['ccos_codigo'];
        //Crea formato de niveles para centro de costo
        $nivel1 = substr($ccos_codigo, 0, 2).'-00-00';
        $nivel2 = substr($ccos_codigo, 0, 5).'-00';
        // Según formato, asigna nivel
        if( $ccos_codigo == $nivel1 ){
            $ccos_nivel = 1;
        }elseif( $ccos_codigo == $nivel2 ){
            $ccos_nivel = 2;
        }else{
            $ccos_nivel = 3;
        }        
    $ccos_descripcion = utf8_decode($_POST['ccos_descripcion']);
    // Para el caso q la opcion sea 'editar'        
    $ccos_modificacion = date("Y-m-d H:i:s");
}
if($opcion=='insertar'){
        $ccos_codigo = $_POST['ccos_codigo'];
        
        //Crea formato de niveles para centro de costo
        $nivel1 = substr($ccos_codigo, 0, 2).'-00-00';
        $nivel2 = substr($ccos_codigo, 0, 5).'-00';

        // Según formato, asigna nivel
        if( $ccos_codigo == $nivel1 ){
            $ccos_nivel = 1;
        }elseif( $ccos_codigo == $nivel2 ){
            $ccos_nivel = 2;
        }else{
            $ccos_nivel = 3;
        }    
        $ccos_descripcion = utf8_decode($_POST['ccos_descripcion']);    
    }

}

}

switch ( $opcion ){
	case "insertar":
        insertarData($ccos_codigo, $ccos_descripcion, $ccos_nivel, $userID, $conexion);
	break;

	case "leer":
        leerData($conexion);
	break;

	case "editar":
        editarData($ccos_id, $ccos_codigo, $ccos_descripcion, $ccos_nivel, $userID, $ccos_modificacion, $conexion);
	break;
        
	case "eliminar":
        eliminarData($ccos_id, $conexion);
	break;
        
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
}

function leerData($conexion)
{
    $query = "SELECT ccos_id, ccos_codigo, ccos_descripcion, ccos_nivel, ccos_creacion 
              FROM centros_costo ORDER BY ccos_codigo, ccos_nivel ASC";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
       
    //$arreglo["data"] = ''; // asigna un array vacío si no hay registros en bd.
    while( $data = mysqli_fetch_assoc($result) ){
        $data['ccos_nivel'] = 'nivel:'.$data['ccos_nivel'];
        $arreglo["data"][] = 
		array_map("utf8_encode", $data);
    }
    
    // json para el dataTable
    echo json_encode($arreglo);
    mysqli_free_result($result);
    mysqli_close($conexion);    
}

function insertarData($ccos_codigo, $ccos_descripcion, $ccos_nivel, $userID, $conexion)
{
    $cantidad = verificaExistencia($ccos_codigo, $conexion);
    
    if( $ccos_nivel == 3 ){
        $padreN2 = buscaRaizPadreN2CC($ccos_codigo, $conexion);
        if( $padreN2 == 'N/A' ){
            echo "No puede asignar un C.C a la raíz de Nivel 2 <b>xx-00-xx</b>, debe crear un nivel 2.";
            mysqli_close($conexion);
            exit;
        }elseif( $padreN2 == 0 ){
            echo "Aún no existe el centro de Nivel 2 señalado para: <b>".$ccos_codigo."</b>, debe crearlo.";            
            mysqli_close($conexion);
            exit;            
        }
    }
    
    if( $cantidad > 0 ){
        echo "existe";
        mysqli_close($conexion);
    }else{
        $query = "INSERT INTO centros_costo(ccos_id, ccos_codigo, ccos_descripcion, ccos_nivel, ccos_creador) 
                  VALUES(NULL, '".$ccos_codigo."', '".$ccos_descripcion."', '".$ccos_nivel."', '".$userID."')";
        $result = mysqli_query($conexion, $query);
        
        resultadoQuery( $result );
        
        mysqli_close($conexion);
    }
}

function editarData($idRegistro, $ccos_codigo, $ccos_descripcion, $ccos_nivel, $userID, $ccos_modificacion, $conexion)
{  
    $query = "UPDATE centros_costo SET ccos_codigo='".$ccos_codigo."', ccos_descripcion='".$ccos_descripcion."', ccos_nivel='".$ccos_nivel."',
              ccos_modificador='".$userID."', ccos_modificacion='".$ccos_modificacion."' WHERE ccos_id=".$idRegistro." LIMIT 1";
    $result = mysqli_query($conexion, $query);
    
    resultadoQuery( $result );
    
    mysqli_close($conexion);
}

function eliminarData($idRegistro, $conexion)
{
    $cantidad = buscaCCenActivos($idRegistro, $conexion);
    
    $hijosCC = buscaHijosCC($idRegistro, $conexion);
    
    if( $cantidad > 0 )
    {
        $msj = "Lo sentimos, actualmente existen <b>" .$cantidad. "</b> activos 
                asociados al centro de costo que intenta eliminar.
                Favor, cambie los activos y luego intente nuevamente.";
        //echo utf8_encode($msj);
        echo($msj);
        mysqli_close($conexion);
    
    }
    elseif( $hijosCC > 1 )
    {
        // Como la query de la función tmb cuenta al CC padre, se resta !
        $msj = "Lo sentimos, actualmente existen <b>" .($hijosCC-1). "</b>  
                Centros de Costos derivados del que intenta eliminar.
                Favor, elimine los C.C hijos y luego intente nuevamente.";
        echo $msj;
        mysqli_close($conexion);        
    }
    else
    {
        $query = "DELETE FROM centros_costo WHERE ccos_id=".$idRegistro." LIMIT 1";
        $result = mysqli_query($conexion, $query);
        
        resultadoQuery( $result );
        
        mysqli_close($conexion);
    }
}

function verificaExistencia($campoClave, $conexion)
{
    $query = "SELECT ccos_id FROM centros_costo WHERE ccos_codigo='".$campoClave."' LIMIT 1";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;            
}

function buscaRaizPadreN2CC($ccos_codigo, $conexion)
{
    // Busca si existe el nivel2 antes del 3
    $codN1 = substr($ccos_codigo, 0, 2);
    $codN2 = substr($ccos_codigo, 3, 2);
    // Antes de un N3 xx-yy-ZZ, debe existir
    // xx-YY-00 que sería el nivel raíz padre
    $raizPadreN2 = $codN1.'-' .$codN2. '-00';
    
    if( $codN2 == '00' ){
        return 'N/A'; // No se puede crear o asignar un nivel 3 a una raiz de nivel 2 con código del tipo "xx-00-xx".
    }else{
        $query = "SELECT ccos_id, ccos_codigo FROM centros_costo WHERE ccos_codigo='".$raizPadreN2."' AND ccos_nivel=2";
        $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
        $count = mysqli_num_rows($result);
        
        return $count;
    }
}

function buscaCCenActivos($idRegistro, $conexion)
{
    $query = "SELECT ccos_id FROM activos_fijos WHERE ccos_id='".$idRegistro."'";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;        
}

function buscaHijosCC($idRegistro, $conexion)
{
    $query = "SELECT ccos_id, ccos_codigo, ccos_nivel FROM centros_costo WHERE ccos_id='".$idRegistro."' LIMIT 1";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_fetch_assoc($result);
    $codigo = $rows['ccos_codigo'];
    $nivelCC = $rows['ccos_nivel'];

    // Extrae parte del nivel a buscar
    $codNivel1 = substr($codigo, 0, 2);
    $codNivel2 = substr($codigo, 3, 2);
    
    // Hijos del nivel 1
    if( $nivelCC == 1 ){
        $query = "SELECT ccos_id, ccos_codigo FROM centros_costo WHERE ccos_codigo REGEXP '^".$codNivel1."\\-..\\-..$'";
        $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
        $rows = mysqli_fetch_assoc($result);
        $count = mysqli_num_rows($result);
        
        return $count;
    }
    
    // Hijos del nivel 2
    if( $nivelCC == 2 ){
        $query = "SELECT ccos_id, ccos_codigo FROM centros_costo WHERE ccos_codigo REGEXP '^".$codNivel1."\\-".$codNivel2."\\-..$'";
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
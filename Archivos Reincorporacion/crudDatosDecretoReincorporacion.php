<?php
require_once 'config.php';
require_once 'requires/gump.class.php';

$userID = $_SESSION['userid'];

$opcion = '';
$gump = new GUMP();
$_POST = $gump->sanitize($_POST);

if( isset($_GET['opcion']) ){
    // en caso q se vaya a leer act_codigo
    $opcion = $_GET['opcion'];
}else{
    $opcion = $_POST['opcion'];
}

$gump->filter_rules(array(
    'derei_nombreOrg' => 'sanitize_string|ms_word_characters',
    'derei_nombreDireccion' => 'sanitize_string|ms_word_characters',
    'derei_nombreSecre' => 'sanitize_string|ms_word_characters',
    'derei_porOrdenFirma1' => 'sanitize_string|ms_word_characters',
    'derei_cargoFirma1' => 'sanitize_string|ms_word_characters',
    'derei_nombreAlcalde' => 'sanitize_string|ms_word_characters',
    'derei_porOrdenFirma2' => 'sanitize_string|ms_word_characters',
    'derei_cargoFirma2' => 'sanitize_string|ms_word_characters',
    'derei_iniciales' => 'sanitize_string|ms_word_characters'
));

$errores = array();

if( $opcion != 'leer' ){
    if ($opcion=='editar'){

        $gump->validation_rules(array(
            'derei_id'=>'required|numeric',
            'derei_nombreOrg' => 'required|alpha_space|max_len,60|min_len,5',
            'derei_nombreDireccion' => 'alpha_space|max_len,100|min_len,5',
            'derei_nombreSecre' => 'required|alpha_space|max_len,60|min_len,5',
            'derei_porOrdenFirma1' => 'alpha_space|max_len,60|min_len,3',
            'derei_cargoFirma1' => 'alpha_space|max_len,60|min_len,3',
            'derei_nombreAlcalde' => 'required|alpha_space|max_len,60|min_len,5',
            'derei_porOrdenFirma2' => 'alpha_space|max_len,60|min_len,3',
            'derei_cargoFirma2' => 'alpha_space|max_len,60|min_len,3',
            'derei_iniciales' => 'max_len,20|min_len,3',
            'imagen' => 'extension,png;jpg'
        ));

    }elseif($opcion=='insertar'){

        $gump->validation_rules(array(
            'derei_nombreOrg' => 'required|alpha_space|max_len,60|min_len,5',
            'derei_nombreDireccion' => 'alpha_space|max_len,100|min_len,5',
            'derei_nombreSecre' => 'required|alpha_space|max_len,60|min_len,5',
            'derei_porOrdenFirma1' => 'alpha_space|max_len,60|min_len,3',
            'derei_cargoFirma1' => 'alpha_space|max_len,60|min_len,3',
            'derei_nombreAlcalde' => 'required|alpha_space|max_len,60|min_len,5',
            'derei_porOrdenFirma2' => 'alpha_space|max_len,60|min_len,3',
            'derei_cargoFirma2' => 'alpha_space|max_len,60|min_len,3',
            'derei_iniciales' => 'max_len,20|min_len,3',
            'imagen' => 'extension,png;jpg'
        ));

    }elseif($opcion=='eliminar'){

        $gump->validation_rules(array(
        'derei_id' => 'required|numeric'
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
           $derei_id = $_POST['derei_id'];
        }

        if($opcion=='editar'){
            $derei_id = $_POST['derei_id'];
            $derei_nombreOrg = utf8_decode($_POST['derei_nombreOrg']);
            $derei_nombreDireccion = utf8_decode($_POST['derei_nombreDireccion']);
            $derei_nombreSecre = utf8_decode($_POST['derei_nombreSecre']);
            $derei_porOrdenFirma1 = utf8_decode($_POST['derei_porOrdenFirma1']);
            $derei_cargoFirma1 = utf8_decode($_POST['derei_cargoFirma1']);
            $derei_nombreAlcalde = utf8_decode($_POST['derei_nombreAlcalde']);
            $derei_porOrdenFirma2 = utf8_decode($_POST['derei_porOrdenFirma2']);
            $derei_cargoFirma2 = utf8_decode($_POST['derei_cargoFirma2']);
            $derei_iniciales = utf8_decode($_POST['derei_iniciales']);
            $derei_modificacion = date("Y-m-d H:i:s");
            $dir_imagenes = 'imagenes_logo/';

            if( !is_dir($dir_imagenes) ){
                mkdir($dir_imagenes, 0755);
            }

            if( !is_writable($dir_imagenes) ){
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
                        echo "El tama�o del archivo de imagen es mayor a los 2MB.";
                        exit;
                    }

                    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $ext = strtolower($ext);

                    if( $ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif' ){
                        echo "El tipo de archivo que intenta subir no est� permitido.";
                        exit;
                    }

                    $tipoMIME = $_FILES['imagen']['type'];

                    if( $tipoMIME !== 'image/jpeg' && $tipoMIME !== 'image/png' && $tipoMIME !== 'image/gif'  ){
                        echo "Tipo de formato MIME que intenta subir no est� permitido.";
                        exit;
                    }

                    $nombre_archivo = $derei_nombreOrg.'.'.$ext;
                    $ruta_destino = $dir_imagenes.$nombre_archivo;

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

        if($opcion=='insertar'){
            $derei_nombreOrg = utf8_decode($_POST['derei_nombreOrg']);
            $derei_nombreDireccion = utf8_decode($_POST['derei_nombreDireccion']);
            $derei_nombreSecre = utf8_decode($_POST['derei_nombreSecre']);
            $derei_porOrdenFirma1 = utf8_decode($_POST['derei_porOrdenFirma1']);
            $derei_cargoFirma1 = utf8_decode($_POST['derei_cargoFirma1']);
            $derei_nombreAlcalde = utf8_decode($_POST['derei_nombreAlcalde']);
            $derei_porOrdenFirma2 = utf8_decode($_POST['derei_porOrdenFirma2']);
            $derei_cargoFirma2 = utf8_decode($_POST['derei_cargoFirma2']);
            $derei_iniciales = utf8_decode($_POST['derei_iniciales']);
            $dir_imagenes = 'imagenes_logo/';

            if( !is_dir($dir_imagenes) ){
                mkdir($dir_imagenes, 0755);
            }

            if( !is_writable($dir_imagenes) ){
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
                        echo "El tama�o del archivo de imagen es mayor a los 2MB.";
                        exit;
                    }

                    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $ext = strtolower($ext);

                    if( $ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif' ){
                        echo "El tipo de archivo que intenta subir no est� permitido.";
                        exit;
                    }

                    $tipoMIME = $_FILES['imagen']['type'];

                    if( $tipoMIME !== 'image/jpeg' && $tipoMIME !== 'image/png' && $tipoMIME !== 'image/gif'  ){
                        echo "Tipo de formato MIME que intenta subir no est� permitido.";
                        exit;
                    }

                    $nombre_archivo = $derei_nombreOrg.'.'.$ext;
                    $ruta_destino = $dir_imagenes.$nombre_archivo;

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
            }//IMAGEN
        }
    }
  // if validate
}

switch ( $opcion ){
	case "insertar":
    $cantidad = verificaExistencia($derei_nombreOrg, $conexion);

    if( $cantidad > 0 ){
        exit("existe");
    }else{
        insertarData(
        $derei_nombreOrg, $derei_nombreDireccion, $derei_nombreSecre, $derei_porOrdenFirma1, $derei_cargoFirma1,
        $derei_nombreAlcalde, $derei_porOrdenFirma2, $derei_cargoFirma2, $derei_iniciales, $ruta_destino, $userID, $conexion
        );
    }
	break;

	case "leer":
        leerData($conexion);
	break;

	case "editar":
        editarData(
        $derei_id, $derei_nombreOrg, $derei_nombreDireccion, $derei_nombreSecre, $derei_porOrdenFirma1, $derei_cargoFirma1,
        $derei_nombreAlcalde, $derei_porOrdenFirma2, $derei_cargoFirma2, $derei_iniciales, $ruta_destino, $userID, $derei_modificacion, $conexion
        );
	break;

	case "eliminar":
        eliminarData($derei_id, $conexion);
	break;

	default:
        echo utf8_encode("Error, no existe una opci�n para ejecutar");
    break;
}

function leerData($conexion)
{
    $query = "SELECT derei_id, derei_nombreOrg, derei_nombreDireccion, derei_nombreSecre, derei_porOrdenFirma1, derei_cargoFirma1,
             derei_nombreAlcalde, derei_porOrdenFirma2, derei_cargoFirma2, derei_iniciales, derei_creacion FROM param_decreto_reincorporacion";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));

    $arreglo["data"] = array(); // asigna un array vac�o si no hay registros en bd.
    while( $data = mysqli_fetch_assoc($result) ){
        $arreglo["data"][] = array_map("utf8_encode", $data);
    }

    // encode para el dataTable derei_id
    echo json_encode($arreglo);
    mysqli_free_result($result);
    mysqli_close($conexion);
}

function insertarData(
            $derei_nombreOrg, $derei_nombreDireccion, $derei_nombreSecre, $derei_porOrdenFirma1, $derei_cargoFirma1,
            $derei_nombreAlcalde, $derei_porOrdenFirma2, $derei_cargoFirma2, $derei_iniciales, $ruta_destino, $userID, $conexion
        )
{
    $query = "INSERT INTO param_decreto_reincorporacion(derei_nombreOrg, derei_nombreDireccion, derei_nombreSecre, derei_porOrdenFirma1, derei_cargoFirma1,
             derei_nombreAlcalde, derei_porOrdenFirma2, derei_cargoFirma2, derei_iniciales, derei_rutaLogo, derei_creador)
             VALUES('".$derei_nombreOrg ."', '".$derei_nombreDireccion."', '".$derei_nombreSecre."', '".$derei_porOrdenFirma1."', '".$derei_cargoFirma1."',
             '".$derei_nombreAlcalde."', '".$derei_porOrdenFirma2."', '".$derei_cargoFirma2."', '".$derei_iniciales."', '".$ruta_destino."', '".$userID."')";

    $result = mysqli_query($conexion, $query);

    resultadoQuery( $result );

    mysqli_close($conexion);
}

function editarData(
            $derei_id, $derei_nombreOrg, $derei_nombreDireccion, $derei_nombreSecre, $derei_porOrdenFirma1, $derei_cargoFirma1,
            $derei_nombreAlcalde, $derei_porOrdenFirma2, $derei_cargoFirma2, $derei_iniciales, $ruta_destino, $userID, $derei_modificacion, $conexion
        )
{
    $query = "UPDATE param_decreto_reincorporacion SET derei_nombreOrg='".$derei_nombreOrg."', derei_nombreDireccion='".$derei_nombreDireccion."',
             derei_nombreSecre='".$derei_nombreSecre."', derei_porOrdenFirma1='".$derei_porOrdenFirma1."', derei_cargoFirma1='".$derei_cargoFirma1."',
             derei_nombreAlcalde='".$derei_nombreAlcalde."', derei_porOrdenFirma2='".$derei_porOrdenFirma2."', derei_cargoFirma2='".$derei_cargoFirma2."', ";
    $query.= ( !empty($ruta_destino) ) ? "derei_rutaLogo='".$ruta_destino."', " : '';
    $query.= "derei_iniciales='".$derei_iniciales."', derei_creador='".$userID."', derei_modificacion='".$derei_modificacion."' WHERE derei_id='".$derei_id."'";

    $result = mysqli_query($conexion, $query);

    resultadoQuery( $result );

    mysqli_close($conexion);
}

function eliminarData($idRegistro, $conexion)
{
    $query = "DELETE FROM param_decreto_reincorporacion WHERE derei_id=".$idRegistro." LIMIT 1";
    $result = mysqli_query($conexion, $query);

    resultadoQuery( $result );

    mysqli_close($conexion);
}

function verificaExistencia($campoClave, $conexion)
{
    $query = "SELECT derei_id FROM param_decreto_reincorporacion WHERE derei_id='".$campoClave."' LIMIT 1";
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

<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

//reanuda sesión
session_start();

//check if session variable is set
if(empty($_SESSION['logged_in']))
{
    header("location:index.php");
}

//variables de conexion
$servidor = 'localhost';
$usuario  = 'root';
$clave    = '';
$BD       = $_SESSION['BD'];

//conexión a bd con identificador de enlace.
$conexion = mysqli_connect($servidor, $usuario, $clave) or die("Error de conexion ". mysqli_connect_error());
mysqli_select_db($conexion, $BD) or die("Error de Acceso a Base de Datos");

//zona horaria predeterminada del sistema
date_default_timezone_set('America/Santiago');
//date_default_timezone_set('America/Sao_Paulo');

$script_tz = date_default_timezone_get();
/**
if (strcmp($script_tz, ini_get('date.timezone'))){
    echo 'La zona horaria del sistema difiere de la zona horaria de la configuración ini.';
} else {
    echo 'La zona horaria del sistema y la zona horaria de la configuración ini coinciden.';
}
**/

/***********************************************************
***********************************************************/
// establecemos el tiempo de espera en segundos
$inactivo = 7200; // 2 hora x default

// verificamos que ya exista un valor para timeout
if(isset($_SESSION["timeout"])){
    // calculamos el tiempo que lleva la sesión
    $tiempoSession = time() - $_SESSION["timeout"];

    // si se pasó el umbral de inactividad
    if ($tiempoSession > $inactivo) {

        // destruimos la sesión y desconectamos al usuario
        session_destroy();
        header("Location:index.php?err=4");
    }
}

// el usuario interactúa por primera vez
$_SESSION["timeout"] = time();
#echo $_SESSION["timeout"];
/***********************************************************
***********************************************************/

//Evitar doble Post de Form en PHP
class Post_Block{
    function startPost() {
        echo "<input type='hidden' name='postID' ";
        echo "value='".md5(uniqid(rand(), true))."'>";
    }
 
    function postBlock($postID) {
        //session_start();
        if(isset($_SESSION['postID'])) {
            if ($postID == $_SESSION['postID']) {
                return false;
            } else {
                $_SESSION['postID'] = $postID;
                return true;
            }
        } else {
            $_SESSION['postID'] = $postID;
            return true;
        }
    }
}

/**
 * Funcion que dado un valor timestamp, devuelve el numero de dias, horas, minutos y segundos
 * Ejemplo: timestampToHuman( strtotime(date1) - strtotime(date2) ) 
 * http://www.lawebdelprogramador.com 
 */

function timestampToHuman($timestamp){

    $return = "";
    # Obtenemos el numero de dias 
#    $days = floor((($timestamp/60)/60)/24); 
#    
#    if( $days > 0 ){ 
#        $timestamp -= $days*24*60*60; 
#        $return .= $days." días "; 
#    }
    
    # Obtenemos el numero de horas 
    $hours = floor(($timestamp/60)/60); 
    
    if( $hours > 0 ){
        $timestamp -= $hours*60*60; 
        $return .= str_pad($hours, 2, "0", STR_PAD_LEFT).":";
    }else{
        $return .= "00:"; 
    }
    # Obtenemos el numero de minutos 
    $minutes = floor($timestamp/60); 
    
    if( $minutes > 0 ){ 
        $timestamp -= $minutes*60; 
        $return .= str_pad($minutes, 2, "0", STR_PAD_LEFT).":"; 
    }else{
        $return.="00:"; 
    }
    # Obtenemos el numero de segundos 
    $return .= str_pad($timestamp, 2, "0", STR_PAD_LEFT);
    
    return $return;
}
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

//reanuda sesión
//session_start();

//check if session variable is set
if(empty($_SESSION['logged_in']))
{
    header("location:index.php");
}

//variables de conexion
$db_server = 'localhost';
$db_user   = 'root';
$db_pwd    = '';
$db_name   = 'af_temuco_centralizada';

//conexión a bd con identificador de enlace.
$link = mysqli_connect($db_server, $db_user, $db_pwd) or die("Error de conexion ". mysqli_connect_error());
mysqli_select_db($link, $db_name) or die("Error de Acceso a Base de Datos Central");

//zona horaria predeterminada del sistema
date_default_timezone_set('America/Santiago');
//date_default_timezone_set('America/Sao_Paulo');

$script_tz = date_default_timezone_get();
?>
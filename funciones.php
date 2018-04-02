<?php
function isRut($rutCompleto){
    /** 
    Verifica que un RUT tenga el formato 00.000.000-X, despues de comprobar el formato será necesario validar su dígito verificador.
    Otras expReg alternativas encontradas para verificar el Formato del RUT son:
    /(^0?[1-9]{1,2})(?>((\.\d{3}){2}\-)|((\d{3}){2}\-)|((\d{3}){2}))([\dkK])$/
    /^0*(\d{1,3}(\.?\d{3})*)\-?([\dkK])$/
    Importante es empezar y terminar expReg con /
    Fuente: http://www.regxlib.com/REDetails.aspx?regexp_id=1597
     **/
    if( preg_match('/^\d{1,2}\.\d{3}\.\d{3}[-][0-9kK]{1}$/', $rutCompleto) === 1 ){
        return false;
    }else{
        return true;
    }
}

function isMonthYear($month_year){
    if( preg_match('/^0?[0-9]{2}[-][0-9]{4}$/', $month_year) === 1 ){
        return true;
    }else{
        return false;
    }        
}

#le entrego el rut y calcula dv!
function dv($r){
    $s=1;
    for($m=0;$r!=0;$r/=10)
    $s=($s+$r%10*(9-$m++%6))%11;

    return chr($s?$s+47:75);
}

function formatDate($dateTime){
    $revision = checkearDate($dateTime);

    if( $revision ){
        return date('d/m/Y', strtotime($dateTime));
    }else{
        return '';
    }
}

function checkearDate($mydate){
    $mydate = trim($mydate);  

    list($yy, $mm, $dd) = explode("-", $mydate);
    if (is_numeric($dd) && is_numeric($mm) && is_numeric($yy)){
        return checkdate($mm,$dd,$yy);
    }

    return false;
}

function timetampDiff($unit, $datetimeMin, $datetimeMax, $conex){    
    
    #TIMESTAMPDIFF retorna un entero, diferencia entre la fecha Min y Max (exp2-exp1)
    $sql = "SELECT TIMESTAMPDIFF(".$unit.", '".$datetimeMin."','".$datetimeMax."')";
    $res = mysqli_query($conex, $sql) or die(mysqli_error());
    $datetimeDiff = mysqli_fetch_row($res);

    return $datetimeDiff[0];
}

function dateDiff($fechaFinal, $fechaInicial){
    global $conexion;

    #DATEDIFF retorna el # de días entre la fecha inicial y fecha final.
    $sql = "SELECT DATEDIFF('".$fechaFinal."','".$fechaInicial."')";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error());
    $diasTranscurridos = mysqli_fetch_row($res);

    return $diasTranscurridos[0];
}

function checkDateIn($date){
    $date = trim($date);
    
    list($mm, $dd, $yy) = explode("-", $date); //separador es -    

    if (is_numeric($dd) && is_numeric($mm) && is_numeric($yy))
    {   #si fecha es ok, retorno false
        return !checkdate($mm,$dd,$yy);#si no cumple, envia true...
    }

    return true; #retorna true para entrar al if y marcar como error.

}

#Validar una fecha gregoriana
function validarFecha($mydate){
    $mydate = trim($mydate); 

    list($dd, $mm, $yy) = explode("-", $mydate);    

    if (is_numeric($dd) && is_numeric($mm) && is_numeric($yy)){
        return checkdate($mm,$dd,$yy);
    }

    return false;
} 

function validarMesAnio($dd_mm_yyyy){
    $dd_mm_yyyy = trim($dd_mm_yyyy); 

    list($dd, $mm, $yyyy) = explode("-", $dd_mm_yyyy);    

    if (is_numeric($dd) && is_numeric($mm) && is_numeric($yyyy)){
        return checkdate($mm,$dd,$yyyy);
    }

    return false;
}

function formatDateToMySql($date){

    list($dia, $mes, $anio) = explode('-', $date);
    $dateMysql = $anio.'-'.$mes.'-'.$dia;
    
    return $dateMysql;
}

function formatDateToHtml($date){
    
    list($anio, $mes, $dia) = explode('-', $date);
    $dateHtml = $dia.'-'.$mes.'-'.$anio;

    return $dateHtml;
}

function formatDateEuroToMySql($date){

    list($dia, $mes, $anio) = explode('/', $date);
    $dateMysql = $anio.'-'.$mes.'-'.$dia;

    return $dateMysql;
}

#función para limpiar strings
function limpiarString($string){
    global $conexion;
    
    $string = strip_tags($string);
    #$string = htmlentities($string); // ..., ENT_QUOTES,'UTF-8') así de sencillo
    $string = stripslashes($string);
    #si llevaremos esto a mySQL deberímos agregar al final...
    $string = mysqli_real_escape_string($conexion, $string);   

    return $string;
}
?>
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
    'deal_nombreOrg' => 'sanitize_string|ms_word_characters',
    'deal_nombreDireccion' => 'sanitize_string|ms_word_characters',
    'deal_nombreSecre' => 'sanitize_string|ms_word_characters',
    'deal_porOrdenFirma1' => 'sanitize_string|ms_word_characters',
    'deal_cargoFirma1' => 'sanitize_string|ms_word_characters',
    'deal_nombreAlcalde' => 'sanitize_string|ms_word_characters',
    'deal_porOrdenFirma2' => 'sanitize_string|ms_word_characters',
    'deal_cargoFirma2' => 'sanitize_string|ms_word_characters',
    'deal_iniciales' => 'sanitize_string|ms_word_characters'
));

$errores = array();

if( $opcion != 'leer' ){
    if ($opcion=='editar'){

        $gump->validation_rules(array(
            'deal_id'=>'required|numeric',
            'deal_nombreOrg' => 'required|alpha_space|max_len,60|min_len,5',
            'deal_nombreDireccion' => 'required|alpha_space|max_len,100|min_len,5',
            'deal_nombreSecre' => 'required|alpha_space|max_len,60|min_len,5',
            'deal_porOrdenFirma1' => 'alpha_space|max_len,60|min_len,3',
            'deal_cargoFirma1' => 'alpha_space|max_len,60|min_len,3',
            'deal_nombreAlcalde' => 'required|alpha_space|max_len,60|min_len,5',
            'deal_porOrdenFirma2' => 'alpha_space|max_len,60|min_len,3',
            'deal_cargoFirma2' => 'alpha_space|max_len,60|min_len,3',
            'deal_iniciales' => 'max_len,20|min_len,3',
            'imagen' => 'extension,png;jpg'
        ));

    }elseif($opcion=='insertar'){

        $gump->validation_rules(array(
            'deal_nombreOrg' => 'required|alpha_space|max_len,60|min_len,5',
            'deal_nombreDireccion' => 'required|alpha_space|max_len,100|min_len,5',
            'deal_nombreSecre' => 'required|alpha_space|max_len,60|min_len,5',
            'deal_porOrdenFirma1' => 'alpha_space|max_len,60|min_len,3',
            'deal_cargoFirma1' => 'alpha_space|max_len,60|min_len,3',
            'deal_nombreAlcalde' => 'required|alpha_space|max_len,60|min_len,5',
            'deal_porOrdenFirma2' => 'alpha_space|max_len,60|min_len,3',
            'deal_cargoFirma2' => 'alpha_space|max_len,60|min_len,3',
            'deal_iniciales' => 'max_len,20|min_len,3',
            'imagen' => 'extension,png;jpg'
        ));

    }elseif($opcion=='eliminar'){

        $gump->validation_rules(array(
        'deal_id' => 'required|numeric'
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
           $deal_id = $_POST['deal_id'];
        }

        if($opcion=='editar'){
            $deal_id = $_POST['deal_id'];
            $deal_nombreOrg = utf8_decode($_POST['deal_nombreOrg']);
            $deal_nombreDireccion = utf8_decode($_POST['deal_nombreDireccion']);
            $deal_nombreSecre = utf8_decode($_POST['deal_nombreSecre']);
            $deal_porOrdenFirma1 = utf8_decode($_POST['deal_porOrdenFirma1']);
            $deal_cargoFirma1 = utf8_decode($_POST['deal_cargoFirma1']);
            $deal_nombreAlcalde = utf8_decode($_POST['deal_nombreAlcalde']);
            $deal_porOrdenFirma2 = utf8_decode($_POST['deal_porOrdenFirma2']);
            $deal_cargoFirma2 = utf8_decode($_POST['deal_cargoFirma2']);
            $deal_iniciales = utf8_decode($_POST['deal_iniciales']);
            $deal_modificacion = date("Y-m-d H:i:s");
            $dir_imagenes 
<?php
require_once 'config.php';

// llena selects html
function obtieneNiveles1Ubicaciones($conexion)
{   
    $sql = "SELECT SUBSTRING(ubi_codigo, 1, 2) AS nivel_1, ubi_descripcion 
            FROM ubicaciones GROUP BY nivel_1 ORDER BY nivel_1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $options = '';
    $options.= "<option value=''>-- Vacio para nuevo --</option>\n";
    
    while( $rows = mysqli_fetch_assoc($res) ){
        $nivel_1 = $rows['nivel_1'];
        $descripcion = $rows['ubi_descripcion'];
        $options.= "<option value='".$nivel_1."' title='".$nivel_1."'>".$descripcion."</option>\n";
    }

    echo $options;
}

function obtieneNivel0AreaNegocios($conexion)
{   
	$sql = "SELECT aneg_id, aneg_codigo nivel_0, aneg_descripcion 
            FROM areas_negocios ORDER BY nivel_0";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $options = '';
    $options.= "<option value=''>-- Vacio para nuevo --</option>\n";
    
    while( $rows = mysqli_fetch_assoc($res) ){
        $nivel_0 = $rows['nivel_0'];
        $descripcion = $rows['aneg_descripcion'];
		$aneg_id = $rows['aneg_id'];
		
        $options.= "<option value='".$aneg_id."' title='".$nivel_0."'>".$descripcion."</option>\n";
    }

    echo $options;
}

if( isset($_GET['buscar']) && $_GET['buscar'] == 'Responsables' )
{   
	
	$sqlResponsables = "SELECT DISTINCT * FROM responsables ORDER BY resp_nombre ASC";
	$resultResponsables = mysqli_query($conexion, $sqlResponsables) or die(mysqli_error($conexion));
	
	$options = '';
    $options.= "<option value=''>-- Vacio para nuevo --</option>\n";
	
    while($filaResponsables = mysqli_fetch_assoc($resultResponsables)){
        $options .= "<option value='".$filaResponsables["resp_id"]."'>".$filaResponsables["resp_codigo"]." - ".$filaResponsables["resp_nombre"]."</option>\n";
    }
	
	echo $options;
}



if( isset($_GET['buscar']) && $_GET['buscar'] == 'nivel2' )
{   
    $nivel1 = mysqli_real_escape_string($conexion, trim($_GET['nivel1'])); //Aplicar validaciones
    
    $sql = "SELECT SUBSTRING(ubi_codigo, 1, 2) AS nivel_1, SUBSTRING(ubi_codigo, 1, 5) AS nivel_2, ubi_descripcion 
            FROM ubicaciones GROUP BY nivel_2 HAVING nivel_1='".$nivel1."' ORDER BY nivel_2";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));  
    
    $options = '';
    $options.= "<option value=''>-- Vacio para nuevo --</option>\n";
    
    while( $rows = mysqli_fetch_assoc($res) ){
        $raizNivel2 = substr($rows['nivel_2'], 3, 2);
        
        if( $raizNivel2 != '00' ){
            $nivel_2 = $rows['nivel_2'];
            $descripcion = $rows['ubi_descripcion'];        
            $options.= "<option value='".$nivel_2."' title='".$nivel_2."'>".$descripcion."</option>\n";
        }
    }
    
    echo $options; 
}

if( isset($_GET['buscar']) && $_GET['buscar'] == 'nivel1' )
{   // Recarga nivel1 al crear
    obtieneNiveles1Ubicaciones($conexion);
}
    
if( isset($_GET['buscar']) && $_GET['buscar'] == 'nivelRaiz' )
{
    $codigo = mysqli_real_escape_string($conexion, trim($_GET['codigo'])); //Aplicar validaciones
    
    $nivel1 = substr($codigo, 0, 2).'-00-00';
    $nivel2 = substr($codigo, 0, 5).'-00';

	
    $sqlN1 = "SELECT ubi_codigo AS nivel_1, ubi_descripcion FROM ubicaciones 
              WHERE ubi_codigo='".$nivel1."' ORDER BY ubi_id LIMIT 1";
    $resN1 = mysqli_query($conexion, $sqlN1) or die(mysqli_error($conexion));
    //$rowN1 = mysqli_fetch_assoc($resN1); si no exite cc no devolvera nada !
    $numN1 = mysqli_num_rows($resN1);

    $sqlN2 = "SELECT ubi_codigo AS nivel_2, ubi_descripcion FROM ubicaciones 
              WHERE ubi_codigo='".$nivel2."' ORDER BY ubi_id LIMIT 1";
    $resN2 = mysqli_query($conexion, $sqlN2) or die(mysqli_error($conexion));
    //$rowN2 = mysqli_fetch_assoc($resN2); si no exite cc no devolvera nada !
    $numN2 = mysqli_num_rows($resN2);	
    
    if( $codigo == '00-00-00' ){
        $msj = "La ubicación 00-00-00 esta reservado por el sistema, no se puede crear.";
        $msj = utf8_encode($msj);
        echo json_encode(array("nivel0" => "0", "cc_cod" => "00-00-00", "msj" => $msj));
        exit;
    }elseif( $numN1 == 0 && $nivel1 != $codigo ){
        $msj = "Aún no existe la raíz Nivel 1: " .$nivel1. ", debe crearlo !!";
        $msj = utf8_encode($msj);
        echo json_encode(array("nivel1" => "nulo", "cc_cod" => $nivel1, "msj" => $msj));
        exit;
    }elseif( $numN2 == 0 && $nivel2 != $codigo ){
        $msj = "Aún no existe la raíz Nivel 2: " .$nivel2. ", debe crearlo !!";
        $msj = utf8_encode($msj);
        echo json_encode(array("nivel2" => "nulo", "cc_cod" => $nivel2, "msj" => $msj));
        exit;                
    }else{
        echo json_encode(array("result" => "ok"));
        exit;
    }
}

// obtiene nota(s) de un  activo
function obtieneNotas($idActivo, $conexion)
{/*
    $sql = "SELECT nota_id, nota_fecha, nota_descripcion, nota_detalles FROM notas_bienes 
            WHERE bie_id=".$idActivo." ORDER BY nota_id DESC";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $html = '';
    
    while( $rows = mysqli_fetch_assoc($res) ){
        $html.= "<tr>\n";
        $html.= "\t<td>".$rows['nota_fecha']."</td>\n";
        $html.= "\t<td>".$rows['nota_descripcion']."</td>\n";
        $html.= "</tr>\n";
    }

    echo utf8_encode($html);
    */
    echo "<i>Respuesta: </i>".$_GET['id'];
}

if( isset($_GET['buscar']) && $_GET['buscar'] == 'funcion' )
{
    obtieneNotas($_GET['id'], $conexion);    
}
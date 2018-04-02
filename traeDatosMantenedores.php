<?php
require_once 'config.php';

// llena selects html
function obtieneNiveles1CC($conexion)
{   
    $sql = "SELECT SUBSTRING(ccos_codigo, 1, 2) AS nivel_1, ccos_descripcion 
            FROM centros_costo GROUP BY nivel_1 ORDER BY nivel_1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $options = '';
    $options.= "<option value=''>-- Vacio para nuevo --</option>\n";
    
    while( $rows = mysqli_fetch_assoc($res) ){
        $nivel_1 = $rows['nivel_1'];
        $descripcion = $rows['ccos_descripcion'];
        $options.= "<option value='".$nivel_1."' title='".$descripcion."'>".$nivel_1."</option>\n";
    }

    echo $options;
}

if( isset($_GET['buscar']) && $_GET['buscar'] == 'nivel2' )
{   
    $nivel1 = mysqli_real_escape_string($conexion, trim($_GET['nivel1'])); //Aplicar validaciones
    
    $sql = "SELECT SUBSTRING(ccos_codigo, 1, 2) AS nivel_1, SUBSTRING(ccos_codigo, 1, 5) AS nivel_2, ccos_descripcion 
            FROM centros_costo GROUP BY nivel_2 HAVING nivel_1='".$nivel1."' ORDER BY nivel_2";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));  
    
    $options = '';
    $options.= "<option value=''>-- Vacio para nuevo --</option>\n";
    
    while( $rows = mysqli_fetch_assoc($res) ){
        $raizNivel2 = substr($rows['nivel_2'], 3, 2);
        
        if( $raizNivel2 != '00' ){
            $nivel_2 = $rows['nivel_2'];
            $descripcion = $rows['ccos_descripcion'];        
            $options.= "<option value='".$nivel_2."' title='".$descripcion."'>".$nivel_2."</option>\n";
        }
    }
    
    echo $options; 
}

if( isset($_GET['buscar']) && $_GET['buscar'] == 'nivel1' )
{   // Recarga nivel1 al crear
    obtieneNiveles1CC($conexion);
}
    
if( isset($_GET['buscar']) && $_GET['buscar'] == 'nivelRaiz' )
{
    $codigo = mysqli_real_escape_string($conexion, trim($_GET['codigo'])); //Aplicar validaciones
    
    $nivel1 = substr($codigo, 0, 2).'-00-00';
    $nivel2 = substr($codigo, 0, 5).'-00';
    
    $sqlN1 = "SELECT ccos_codigo AS nivel_1, ccos_descripcion FROM centros_costo 
              WHERE ccos_codigo='".$nivel1."' ORDER BY ccos_id LIMIT 1";
    $resN1 = mysqli_query($conexion, $sqlN1) or die(mysqli_error($conexion));
    //$rowN1 = mysqli_fetch_assoc($resN1); si no exite cc no devolvera nada !
    $numN1 = mysqli_num_rows($resN1);

    $sqlN2 = "SELECT ccos_codigo AS nivel_2, ccos_descripcion FROM centros_costo 
              WHERE ccos_codigo='".$nivel2."' ORDER BY ccos_id LIMIT 1";
    $resN2 = mysqli_query($conexion, $sqlN2) or die(mysqli_error($conexion));
    //$rowN2 = mysqli_fetch_assoc($resN2); si no exite cc no devolvera nada !
    $numN2 = mysqli_num_rows($resN2);
    
    if( $codigo == '00-00-00' ){
        $msj = "El C.C 00-00-00 esta reservado por el sistema, no se puede crear.";
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

if( isset($_GET['buscar']) && $_GET['buscar'] == 'codaNiveles' )
{
    $nivel = mysqli_real_escape_string($conexion, trim($_GET['nivel'])); //Aplicar validaciones
    $codigo = mysqli_real_escape_string($conexion, trim($_GET['codigo'])); //Aplicar validaciones
	
	if( $codigo != "" && $codigo != "null" )
	{
		if($nivel == 4)
		{
			$largoCodigo = 5;
			$largoCodRecibido = strlen($codigo);
		}	
		elseif ($nivel == 5)
		{
			$largoCodigo = 6;
			$largoCodRecibido = strlen($codigo);
		}
		else
		{
			$largoCodigo = $nivel;
			$largoCodRecibido = strlen($codigo);
		}
		
		$query = "SELECT coda_id, coda_codigo, coda_descripcion 
		FROM
			codificador_analiticos
				WHERE
					( LENGTH(coda_codigo) = " . $largoCodigo . "";
                    if( $nivel == 5 ){
                        $query.= " OR LENGTH(coda_codigo) = 7";
                    }
					$query.= " ) AND
							     SUBSTR(coda_codigo,1," . $largoCodRecibido . ") = " . $codigo . "; ";
	
	
		$codifiRows = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
	
    
		$options = '';
		$options.= "<option value=''>-- Seleccione --</option>\n";
    
		while( $rows = mysqli_fetch_assoc($codifiRows) ){
			$codigo = $rows['coda_codigo'];
			$descripcion = $rows['coda_descripcion'];
			$options.= "<option value='".$codigo."' title='".$codigo."'>".$descripcion."</option>\n";
		}
        
		echo $options;
	}
	else echo "<option value=''>-- Seleccione --</option>\n";
}

function obtieneNiveles1Coda($conexion)
{
	$query = "SELECT coda_id, coda_codigo, coda_descripcion 
		FROM
			codificador_analiticos
				WHERE
					LENGTH(coda_codigo) = 1";
						
	
	
	$codifiRows = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
	
	$options = '';
    $options.= "<option value=''>-- Seleccione --</option>\n";
    
    while( $rows = mysqli_fetch_assoc($codifiRows) ){
        $codigo = $rows['coda_codigo'];
        $descripcion = $rows['coda_descripcion'];
        $options.= "<option value='".$codigo."' title='".$codigo."'>".$descripcion."</option>\n";
    }

    echo $options;
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
<?php
require_once 'config.php';
require_once 'funciones.php';

$userID = $_SESSION['userid'];

// Para el caso que se necesite 'editar'        
$fecha_modificacion = date("Y-m-d H:i:s");
        
// llena selects html
function llenaOptions($tabla, $prefix, $conexion)
{
    switch ($tabla){ 
	case 'proveedores':
        $campoDesc = $prefix."razonSocial";
	break;
	default :
        $campoDesc = $prefix."descripcion";
    break;
    }
    
    $campoID = $prefix."id";
    $campoCod = $prefix."codigo";
    
    $sql = "SELECT " .$campoID. ", " .$campoCod. ", " .$campoDesc. " ";
    $sql.= ( $prefix == 'anac_' ) ? ", coda_codigo " : "";
    $sql.= "FROM " .$tabla;
    
    if( $prefix == 'ccos_' ){
        $sql.= " WHERE ccos_nivel=3";
    }
    
    if( $prefix == 'ubi_' ){
        $sql.= " WHERE ubi_nivel=3";
    }
    
    $sql.=" ORDER BY ";
    $sql.= ( $prefix == 'anac_' ) ? $campoDesc.", coda_codigo" : $campoCod.", ".$campoID;
    
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $options  = '';
    while( $rows = mysqli_fetch_assoc($res) ){

        if( $prefix == 'ubi_' ){
            $options.= "<option value='".$rows["ubi_id"]."' ";
            
            if( $rows[$campoDesc] != '-' ){
                $options.= "title='".$rows[$campoCod]."'>";
            }else{
                $options.= "title='".$rows[$campoCod]."'>Sin Descripci&oacute;n";
            }
        }else{
            $options.= "<option value='".$rows[$campoID]."' ";
            
            if( $prefix == 'anac_' ){
                $options.= "title='".$rows[$campoDesc].' - '.$rows['coda_codigo']."'>";
            }elseif( $prefix == 'aneg_' ){
                $options.= "title='".$rows[$campoDesc]."'>";
            }else{
                $options.= "title='".$rows[$campoDesc]."'>".$rows[$campoCod];
            }
        }
        
        // En caso q tabla no tiene valor en campo código o no se quiere mostrar el código concatenado a la descripción
        if( $prefix == 'cond_' || $prefix == 'tdoc_' || $prefix == 'umed_' || $prefix == 'ubi_' || $prefix == 'aneg_' )
        {
            $options.= $rows[$campoDesc];
        }
        elseif( $prefix == 'anac_' )
        {
            $options.= $rows[$campoDesc]. ' - '.$rows['coda_codigo'];
        }
        elseif( $prefix != 'ccos_' )
        {
            $options.=  " - ".$rows[$campoDesc];
        }
        
        $options.= "</option>\n";
    }

    echo $options;
}

function llenaUnidadDistinct($tabla, $prefix, $conexion)
{
    $campoID = $prefix."id";
    $campoCod = $prefix."codigo";
    $campoDesc = $prefix."descripcion";

    $sql = "SELECT DISTINCT(" .$campoDesc. "), " .$campoID. ", " .$campoCod. " FROM " .$tabla. " ORDER BY ". $campoDesc;
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));

    $options  = '';
    
    while( $rows = mysqli_fetch_assoc($res) ){
        $options.= "<option value='".$rows[$campoID]."' title='".$rows[$campoDesc]."'>".$rows[$campoDesc]."</option>\n";
    }
    
    echo $options;
}

function llenarOptionsVidaUtil($conexion){
    
    $sql = "SELECT DISTINCT vutil_anios FROM vida_util ORDER BY vutil_anios ASC";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $options = "<option value='0'>-- Seleccione --</option>\n";
    
    while( $row = mysqli_fetch_assoc($res) ){
        $options.= "<option value='".$row['vutil_anios']."' title='".$row['vutil_anios']."'>".$row['vutil_anios']."</option>\n";
    }
    
    echo $options;
}

function llenarPeriodosIPC($conexion){
    
    $sql = "SELECT fipc_id, fipc_anio FROM factor_ipc_anual ORDER BY fipc_anio ASC";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $options  = '';
    
    while( $row = mysqli_fetch_assoc($res) ){
        $options.= "<option value='".$row['fipc_anio']."' title='".$row['fipc_anio']."'>".$row['fipc_anio']."</option>\n";
    }
    
    echo $options;
}

if( !empty($_GET['proveedores']) ){
     // get RUT q inicien con GET[]
    $sql = "SELECT aux_id, aux_codigo, CONCAT_WS(' / ', aux_rut, aux_razonSocial) AS prov FROM proveedores WHERE aux_rut LIKE '".$_GET['proveedores']."%'";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $proveedor = array();
        
    while( $data = mysqli_fetch_assoc($res) ){
        $proveedor[] = array_map("utf8_encode", $data);
    }
    
    echo json_encode($proveedor);
    
    mysqli_free_result($res);
}

if( !empty($_GET['anac_id']) ){
    $sql = "SELECT anac_descripcion FROM analiticos_cuentas WHERE anac_id=".$_GET['anac_id']." LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $row = mysqli_fetch_assoc($res);
    
    echo $row['anac_descripcion'];
    
    mysqli_close($conexion);
}

if( !empty($_POST['rut_prov']) ){
    $rutAux = mysqli_real_escape_string($conexion, trim($_POST['rut_prov']));
    
    $sql = "SELECT aux_rut FROM proveedores WHERE aux_rut='".$rutAux."' LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $aux = mysqli_fetch_assoc($res);
    
    echo ( !empty($aux['aux_rut']) ) ? 'ok' : 'error';
    
    mysqli_close($conexion);
}

if( !empty($_GET['getNewBarCode']) && $_GET['getNewBarCode'] == 'nuevo' ){
    
    require 'configBDCentral.php';
    
    // Obtiene últ barcode de la BD central para generar uno nuevo
    $query  = "SELECT ultCodigoBarra FROM control_ultimos_num LIMIT 1";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $cifra  = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    
    $barcode = ( empty($cifra['ultCodigoBarra']) ) ? 1 : ( (int) $cifra['ultCodigoBarra'] ) + 1;    
    
    $cantCeros = ( strlen($barcode) < 13 ) ? ( 13 - strlen($barcode) ) : 0;
    //echo $cantCeros; exit;
    $stringCeros = '';
    
    for($i=0; $i<$cantCeros; $i++){
        $stringCeros.= '0';
    }

    $barcode = $stringCeros.$barcode;
    
    $sql = "SELECT codigo_activo_fijo FROM control_codigos_af WHERE codigo_activo_fijo='".$barcode."' LIMIT 1"; 
    $res = mysqli_query($link, $sql) or die(mysqli_error($link));
    $rows = mysqli_fetch_row($res);
    mysqli_free_result($res);
    
    $sql = "SELECT MAX(codigo_activo_fijo) AS maxBarcode FROM control_codigos_af"; 
    $res = mysqli_query($link, $sql) or die(mysqli_error($link));
    $max = mysqli_fetch_assoc($res);
    mysqli_free_result($res);
    
    if( $rows == 0 ){
        echo $barcode;
    }else{
        $maxBarcode = ( (int) $max['maxBarcode'] ) + 1;
        
        $cantCeros = ( strlen($maxBarcode) < 13 ) ? ( 13 - strlen($maxBarcode) ) : 0;
        
        $stringCeros = '';
        
        for($i=0; $i<$cantCeros; $i++){
            $stringCeros.= '0';
        }
    
        $maxBarcode = $stringCeros.$maxBarcode;        
        
        echo $maxBarcode;
    }
    
    mysqli_close($link);
}

function nuevoFolioDecreto($tipoDecreto)
{
    require 'configBDCentral.php';
    
    if( $tipoDecreto == 'alta' ){
        $colUltDectreto = 'ultDecretoAlta';
    }elseif( $tipoDecreto == 'baja' ){
        $colUltDectreto = 'ultDecretoBaja';
    }else{
        $colUltDectreto = 'ultDecretoTraslado';
    }
    
    // Obtiene últ folio de BD central para crear nuevo decreto según el tipo
    $query  = "SELECT ".$colUltDectreto." FROM control_ultimos_num LIMIT 1";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $cifra  = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    
    $folio = ( empty($cifra[$colUltDectreto]) ) ? 1 : ( (int) $cifra[$colUltDectreto] ) + 1;
    
    return $folio;
    
    mysqli_close($link);
}

if( isset($_GET['periodo']) && $_GET['periodo'] != 'ini' ){
    
    $periodo = mysqli_real_escape_string($conexion, trim($_GET['periodo']));
    
    $sql = "SELECT depcm_periodoConsulta FROM depreciacion_y_cm WHERE depcm_periodoConsulta='".$periodo."' LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $cant = mysqli_num_rows($res);
    
    echo $cant;
    
    mysqli_close($conexion);    
}

if( isset($_GET['buscar']) && $_GET['buscar'] == 'subgrupo' )
{
    $grupoID = mysqli_real_escape_string($conexion, trim($_GET['id']));
    $sql = "SELECT sgru_id, sgru_codigo, sgru_descripcion FROM sub_grupos 
            WHERE grup_id='".$grupoID."' ORDER BY sgru_codigo, sgru_id";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $options = "<option value='0'>-- Seleccione --</option>\n";
    while( $subgrup = mysqli_fetch_assoc($res) ){
        $options.= "<option value='".$subgrup['sgru_id']."' title='".utf8_encode($subgrup['sgru_descripcion'])."'>"
        .$subgrup['sgru_codigo'].' - ' .$subgrup['sgru_descripcion']."</option>\n";
    }
    
    echo $options;    
}

function buscaHijosUbicaciones($codigo, $nivel, $conexion)
{
    // Extrae parte del nivel a buscar
    $codNivel1 = substr($codigo, 0, 2);
    $codNivel2 = substr($codigo, 3, 2);
    
    // Hijos del nivel 1
    if( $nivel == 1 ){
        $query = "SELECT ubi_id, ubi_codigo, ubi_descripcion FROM ubicaciones WHERE ubi_codigo REGEXP '^".$codNivel1."\\-..\\-..$' AND ubi_nivel=2";
        $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));                
        $count = mysqli_num_rows($result);

        $options = "<option value=''>-- Seleccione --</option>\n";
        while( $rows = mysqli_fetch_assoc($result) ){
            $options.= "<option value='".$rows['ubi_codigo']."' title='".$rows['ubi_codigo']."'>"
            .$rows['ubi_descripcion']."</option>\n";
        }
        
        echo $options;                
    }
    
    // Hijos del nivel 2
    if( $nivel == 2 ){
        $query = "SELECT ubi_id, ubi_codigo, ubi_descripcion FROM ubicaciones WHERE ubi_codigo REGEXP '^".$codNivel1."\\-".$codNivel2."\\-..$'AND ubi_nivel=3";
        $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));                
        $count = mysqli_num_rows($result);

        $options = "<option value=''>-- Seleccione --</option>\n";
        while( $rows = mysqli_fetch_assoc($result) ){
            $options.= "<option value='".$rows['ubi_id']."' title='".$rows['ubi_codigo']."'>"
            .$rows['ubi_descripcion']."</option>\n";
        }
        
        echo $options;                
    }
    
    return 0;
}

// Filtrado de Ubi para Datos Básicos
if( isset($_GET['filtroNiveles']) )
{
    $codigo = mysqli_real_escape_string($conexion, trim($_GET['filtroNiveles']));
    $nivel  = mysqli_real_escape_string($conexion, trim($_GET['nivel']));
    
    buscaHijosUbicaciones($codigo, $nivel, $conexion);
}

function buscaHijosUbicacionesReub($codigo, $nivel, $conexion)
{
    // Extrae parte del nivel a buscar
    $codNivel1 = substr($codigo, 0, 2);
    $codNivel2 = substr($codigo, 3, 2);
    
    // Hijos del nivel 1
    if( $nivel == 1 ){
        $query = "SELECT ubi_id, ubi_codigo, ubi_descripcion FROM ubicaciones WHERE ubi_codigo REGEXP '^".$codNivel1."\\-..\\-..$' AND ubi_nivel=2";
        $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));                
        $count = mysqli_num_rows($result);

        $options = "<option value=''>-- Seleccione --</option>\n";
        while( $rows = mysqli_fetch_assoc($result) ){
            $options.= "<option value='".$rows['ubi_codigo']."' title='".$rows['ubi_codigo']."'>"
            .$rows['ubi_descripcion']."</option>\n";
        }
        
        echo $options;                
    }
    
    // Hijos del nivel 2
    if( $nivel == 2 ){
        $query = "SELECT ubi_id, ubi_codigo, ubi_descripcion FROM ubicaciones WHERE ubi_codigo REGEXP '^".$codNivel1."\\-".$codNivel2."\\-..$'AND ubi_nivel=3";
        $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));                
        $count = mysqli_num_rows($result);

        $options = "<option value='0'>-- Seleccione --</option>\n";
        while( $rows = mysqli_fetch_assoc($result) ){
            $options.= "<option value='".$rows['ubi_id']."' title='".$rows['ubi_codigo']."'>"
            .$rows['ubi_descripcion']."</option>\n";
        }
        
        echo $options;                
    }
    
    return 0;
}

// Filtrado de Ubi para Reubicaciones
if( isset($_GET['filtroNivelesReub']) )
{
    $codigo = mysqli_real_escape_string($conexion, trim($_GET['filtroNivelesReub']));
    $nivel  = mysqli_real_escape_string($conexion, trim($_GET['nivel']));
    
    buscaHijosUbicacionesReub($codigo, $nivel, $conexion);
}

// Filtrado de Unidad y Ubicaciones en Forms
if( isset($_GET['filtroUnidadUbicaciones']) )
{
    if( !empty($_GET['nivel']) ){
        $nivel = mysqli_real_escape_string($conexion, trim($_GET['nivel']));
    }
    
    if( !empty($_GET['id']) ){
        $codigo = mysqli_real_escape_string($conexion, trim($_GET['id']));
    }else{
        $codigo = 0;
    }
    
    $unidad = mysqli_real_escape_string($conexion, trim($_GET['filtroUnidadUbicaciones']));
    
    // -1 Para evitar un 'Warning' al devolver ''
    $unidad = ( empty($unidad) ) ? -1 : $unidad; 
    
    // Con -1 $unidad existe para empty y
    // permite exista $result en el while            
    if( !empty($unidad) && empty($nivel) ){
        $sql = "SELECT DISTINCT(aneg_descripcion) AS Unidad, AN.aneg_id, NV1.ubi_id, NV1.ubi_descripcion
                FROM areas_negocios AN JOIN anegunidades_ubiniveles UN ON AN.aneg_id=UN.aneg_id 
                JOIN ubicaciones NV1 ON UN.ubi_id_nv1=NV1.ubi_id WHERE UN.aneg_id=".$unidad;
        $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    }elseif( !empty($unidad) && $nivel == 1 ){
        $sql = "SELECT DISTINCT(aneg_descripcion) AS Unidad, AN.aneg_id, NV2.ubi_id, NV2.ubi_descripcion
                FROM areas_negocios AN JOIN anegunidades_ubiniveles UN ON AN.aneg_id=UN.aneg_id 
                JOIN ubicaciones NV2 ON UN.ubi_id_nv2=NV2.ubi_id WHERE UN.aneg_id=" .$unidad. " AND UN.ubi_id_nv1=".$codigo;
        $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    }elseif( !empty($unidad) && $nivel == 2 ){
        $sql = "SELECT DISTINCT(aneg_descripcion) AS Unidad, AN.aneg_id, NV3.ubi_id, NV3.ubi_descripcion
                FROM areas_negocios AN JOIN anegunidades_ubiniveles UN ON AN.aneg_id=UN.aneg_id 
                JOIN ubicaciones NV3 ON UN.ubi_id_nv3=NV3.ubi_id WHERE UN.aneg_id=" .$unidad. " AND UN.ubi_id_nv2=".$codigo;
        $result = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    }
    
    // Si no es una reubicación
    if( !isset($_GET['reub']) && $_GET['reub'] != 'yes' ){
        $options = "<option value=''>-- Seleccione --</option>\n";
    }else{
        $options = "<option value='0'>-- Seleccione --</option>\n";
    }
    
    while( $rows = mysqli_fetch_assoc($result) ){
        $options.= "<option value='".$rows['ubi_id']."' title='".$rows['ubi_descripcion']."'>".$rows['ubi_descripcion']."</option>\n";
    }
    
    echo $options;
    
    mysqli_free_result($result);
}

// Filtrado de Ubi x SP para Plancheta
if( isset($_GET['filtroPlancheta']) )
{
    if( !empty($_GET['nivel']) ){
        $nivel = mysqli_real_escape_string($conexion, trim($_GET['nivel']));
    }
    
    if( !empty($_GET['id']) ){
        $codigo = mysqli_real_escape_string($conexion, trim($_GET['id']));
    }else{
        $codigo = 0;
    }
    
    $unidad = mysqli_real_escape_string($conexion, trim($_GET['filtroPlancheta']));
    
    // -1 Para evitar un 'Warning' al devolver ''
    $unidad = ( empty($unidad) ) ? -1 : $unidad; 
    
    // Con -1 $unidad existe para empty y
    // permite exista $result en el while
    if( !empty($unidad) && empty($nivel) ){
        $result = mysqli_query($conexion, "CALL p_get_listaDireccion(".$unidad.")") or die(mysqli_error($conexion));
    }elseif( !empty($unidad) && $nivel == 1 ){
        $result = mysqli_query($conexion, "CALL p_get_listaDepartamento(".$unidad.", ".$codigo.")") or die(mysqli_error($conexion));
    }elseif( !empty($unidad) && $nivel == 2 ){
        $result = mysqli_query($conexion, "CALL p_get_listaOficina(".$unidad.", ".$codigo.")") or die(mysqli_error($conexion));
    }
    
    $options = "<option value='0'>-- Seleccione --</option>\n";
    
    while( $rows = mysqli_fetch_assoc($result) ){
        $options.= "<option value='".$rows['ubi_id']."' title='".$rows['ubi_descripcion']."'>".$rows['ubi_descripcion']."</option>\n";
    }
    
    echo $options;
    
    mysqli_free_result($result);
    mysqli_next_result($conexion);    
}

if( isset($_GET['niveles']) && $_GET['niveles'] == 'ubicacion' )
{
    $codUbi = mysqli_real_escape_string($conexion, trim($_GET['cod_ubi']));
    
    list($nivel1, $nivel2, $nivel3) = explode('-', $codUbi);
    
    $nv_1 = $nivel1.'-00-00';
    $nv_2 = $nivel1.'-'.$nivel2.'-00';
    $nv_3 = $nivel1.'-'.$nivel2.'-'.$nivel3;
    
    $sql = "SELECT ubi_codigo, ubi_descripcion, ubi_nivel FROM ubicaciones WHERE ubi_codigo='".$nv_1."' LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $nv1 = mysqli_fetch_assoc($res);
    mysqli_free_result($res);

    $sql = "SELECT ubi_codigo, ubi_descripcion, ubi_nivel FROM ubicaciones WHERE ubi_codigo='".$nv_2."' LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $nv2 = mysqli_fetch_assoc($res);
    mysqli_free_result($res);

    $sql = "SELECT ubi_codigo, ubi_descripcion, ubi_nivel FROM ubicaciones WHERE ubi_codigo='".$codUbi."' LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $nv3 = mysqli_fetch_assoc($res);
    mysqli_free_result($res);
    
    if( $nivel1 == 'XX' ){
        $infoNivel1 = '(N/A) ';
    }else{
        $infoNivel1 = $nv1['ubi_descripcion'];
    }
    
    if( $nivel2 == 'XX' ){
        $infoNivel2 = ' (N/A) ';
    }else{
        $infoNivel2 = $nv2['ubi_descripcion'];
    }
    
    if( $nivel3 == 'XX' ){
        $infoNivel3 = ' (N/A)';
    }else{
        $infoNivel3 = $nv3['ubi_descripcion'];
    }
    
    $infoNiveles = $infoNivel1.' - '.$infoNivel2.' - '.$infoNivel3;
    
    echo $infoNiveles;
}

// obtiene historico de reubicaciones del activo
function obtieneReubicaciones($idActivo, $conexion)
{
    $idActivo = mysqli_real_escape_string($conexion, trim($idActivo));
//    $sql = "SELECT reub_id, reub_fecha, reub_hora, aneg_descripcion, ccos_descripcion, NV1.ubi_descripcion AS desc_ubi_nivel_1, 
//            NV2.ubi_descripcion AS desc_ubi_nivel_2, NV3.ubi_descripcion, NV3.ubi_codigo 
//            FROM reubicaciones_activos RA 
//            JOIN ubicaciones NV1 ON NV1.ubi_codigo=CONCAT(SUBSTR(NV1.ubi_codigo, 1, 2), '-00-00') 
//            JOIN ubicaciones NV2 ON NV2.ubi_codigo=CONCAT(SUBSTR(NV2.ubi_codigo, 1, 5), '-00') 
//            JOIN ubicaciones NV3 ON RA.ubi_id=NV3.ubi_id JOIN areas_negocios AN ON RA.aneg_id=AN.aneg_id JOIN centros_costo CC ON RA.ccos_id=CC.ccos_id 
//            WHERE RA.act_id='".$idActivo."' ORDER BY reub_id DESC";
            
    $sql = "SELECT reub_id, reub_fecha, reub_hora, aneg_descripcion, ccos_descripcion, NV1.ubi_descripcion AS desc_ubi_nivel_1, 
            NV1.ubi_codigo AS ubi_codigo_nv1, NV2.ubi_descripcion AS desc_ubi_nivel_2, NV2.ubi_codigo AS ubi_codigo_nv2, NV3.ubi_descripcion, 
            NV3.ubi_codigo AS ubi_codigo_nv3 FROM reubicaciones_activos RA JOIN ubicaciones NV1 
            ON NV1.ubi_codigo=CONCAT(SUBSTR(NV1.ubi_codigo, 1, 2), '-00-00') JOIN ubicaciones NV2 
            ON NV2.ubi_codigo=CONCAT(SUBSTR(NV2.ubi_codigo, 1, 5), '-00') JOIN ubicaciones NV3 
            ON RA.ubi_id=NV3.ubi_id JOIN areas_negocios AN 
            ON RA.aneg_id=AN.aneg_id JOIN centros_costo CC 
            ON RA.ccos_id=CC.ccos_id 
            WHERE RA.act_id='".$idActivo."' 
            AND CONCAT(SUBSTR(NV1.ubi_codigo, 1, 2), '-00-00')=CONCAT(SUBSTR(NV3.ubi_codigo, 1, 2), '-00-00') 
            AND CONCAT(SUBSTR(NV2.ubi_codigo, 1, 5), '-00')=CONCAT(SUBSTR(NV3.ubi_codigo, 1, 5), '-00') 
            ORDER BY reub_id DESC";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $html = '';
    
    while( $fila = mysqli_fetch_assoc($res) ){
        $fecha = date("d-m-Y", strtotime($fila['reub_fecha']));
        list($hh, $mm, $ss) = explode(":", $fila['reub_hora']);
    $html.= "<tr id='".$fila['reub_id']."'>\n
                \t<td class='text-center'>".$fecha."</td>\n
                \t<td class='text-center'>".$hh.":".$mm."</td>\n
                \t<td>".$fila['aneg_descripcion']."</td>\n
                \t<td>".$fila['ccos_descripcion']."</td>\n
                \t<td>".$fila['desc_ubi_nivel_1']."</td>\n
                \t<td>".$fila['desc_ubi_nivel_2']."</td>\n
                \t<td>".$fila['ubi_descripcion']."</td>\n                
            </tr>\n";
    }

    echo $html;
}

if( isset($_GET['buscar']) && $_GET['buscar'] == 'reubicaciones' )
{
    obtieneReubicaciones($_GET['act'], $conexion); // GET x .load()
}

if( isset($_POST['insertarReub']) && $_POST['insertarReub'] == 'si' )
{
    $actID = mysqli_real_escape_string($conexion, trim($_POST['act']));
    $fecha = mysqli_real_escape_string($conexion, trim($_POST['rehuFecha']));
    $hora = mysqli_real_escape_string($conexion, trim($_POST['rehuHora'].':00'));
    $ubiID = mysqli_real_escape_string($conexion, $_POST['rehuUbiID']);
    $ccosID = mysqli_real_escape_string($conexion, $_POST['rehuCcosID']);
    $anegID = mysqli_real_escape_string($conexion, $_POST['rehuAnegID']);
    
    $fecha = formatDateToMySql($fecha);   
    
    $query = "INSERT INTO reubicaciones_activos(reub_id, act_id, reub_fecha, reub_hora, ubi_id, aneg_id, ccos_id, reub_creador) 
              VALUES(NULL, '".$actID."', '".$fecha."', '".$hora."', '".$ubiID."', '".$anegID."', '".$ccosID."', '".$userID."')";

    $result = mysqli_query($conexion, $query);
    
    resultadoQuery($result);
    
    /** actualiza datos en la tabla AF **/
    if( $result ){
        $query = "UPDATE activos_fijos SET ubi_id='".$ubiID."', aneg_id='".$anegID."', ccos_id='".$ccosID."', 
                  act_modificador='".$userID."', act_modificacion='".$fecha_modificacion."' 
                  WHERE act_id=".$actID." ORDER BY act_id DESC LIMIT 1";
        $result = mysqli_query($conexion, $query);
    }
    
    mysqli_close($conexion);    
    
}

function verificaCantRehuAct($campoClave, $conexion)
{
    $query = "SELECT reub_id FROM reubicaciones_activos WHERE act_id='".$campoClave."'";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($result);
    
    return $rows;            
}

if( isset($_GET['buscar']) && $_GET['buscar'] == 'cant_ubi' )
{   //Retorna num de reubicaciones para desabilitar combobox
    $cantUbi = verificaCantRehuAct($_GET['act'], $conexion);
    
    echo $cantUbi;
}

function eliminarReubicar($rehuID, $actID, $conexion)
{
    $cantidad = verificaCantRehuAct($actID, $conexion);
    
    if( $cantidad == 1 ){
        echo "La actual ubicación es la única, no puede ser eliminada.";
        mysqli_close($conexion);
        exit;
    }else{
        $rehuID = mysqli_real_escape_string($conexion, $rehuID);
        $query = "DELETE FROM reubicaciones_activos WHERE reub_id=".$rehuID." LIMIT 1";
        $result = mysqli_query($conexion, $query);
        
        resultadoQuery($result);
        mysqli_close($conexion);        
    }
}

if( isset($_POST['borrarReubicar']) )
{
    $actID = mysqli_real_escape_string($conexion, trim($_POST['act']));
    
    eliminarReubicar($_POST['borrarReubicar'], $actID, $conexion); // POST x el method de ajax  
}

// obtiene los últimos movimientos de reubicaciones
function refrescarAnegCcosUbi($idActivo, $conexion)
{
    $idActivo = mysqli_real_escape_string($conexion, trim($idActivo));
    $sql = "SELECT reub_id, ubi_id, aneg_id, ccos_id FROM reubicaciones_activos WHERE act_id='".$idActivo."' ORDER BY reub_id DESC LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $data = mysqli_fetch_assoc($res);
    
    return $data;
    
    mysqli_close($conexion);
}

if( isset($_GET['refresh']) && $_GET['refresh'] == 'lugares' )
{   //Retorna nuevos valores de AN, CC y UBI
    $arreglo = refrescarAnegCcosUbi($_GET['act'], $conexion);
    
    echo json_encode($arreglo);
}

// obtiene nota(s) de un  activo
function obtieneNotas($idActivo, $conexion)
{
    $idActivo = mysqli_real_escape_string($conexion, trim($idActivo));
    $sql = "SELECT nota_id, nota_fecha, nota_descripcion, nota_detalles FROM notas_activos 
            WHERE act_id='".$idActivo."' ORDER BY nota_fecha DESC, nota_id DESC";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    
    $html = '';
    
    while( $fila = mysqli_fetch_assoc($res) ){
        $fechaNota = date("d-m-Y", strtotime($fila['nota_fecha']));
        //$html.= "\t<td style='width: 10px;'><input type='checkbox' name='id_nota[]' value='".$fila['nota_id']."'/></td>\n";
        //<button type='button' class='btn btn-danger btn-xs borrar' data-toggle='modal' data-target='#modalBorrar' value='".$fila['nota_id']."'>
    $html.= "<tr>\n
                \t<td class='text-center'>".$fechaNota."</td>\n
                \t<td>".$fila['nota_descripcion']."</td>\n
                \t<td>".substr($fila['nota_detalles'], 0, 100)."</td>\n
                \t<td>
                    <button type='button' class='btn btn-danger btn-xs borrar' value='".$fila['nota_id']."'>
                        <span class='glyphicon glyphicon-trash'></span>
                    </button></td>\n
            </tr>\n";
    }

    echo $html;
}

if( isset($_GET['buscar']) && $_GET['buscar'] == 'notas' )
{
    obtieneNotas($_GET['act'], $conexion); // GET x .load()    
}

function eliminarNotas($idNota, $conexion)
{
    $idNota = mysqli_real_escape_string($conexion, trim($idNota));
    $query = "DELETE FROM notas_activos WHERE nota_id=".$idNota." LIMIT 1";
    $result = mysqli_query($conexion, $query);
    
    resultadoQuery($result);
    mysqli_close($conexion);
}

if( isset($_POST['borrarNota']) )
{
    eliminarNotas($_POST['borrarNota'], $conexion); // POST x el method de ajax  
}

if( isset($_POST['insertarNota']) && $_POST['insertarNota'] == 'si' )
{
    $actID = mysqli_real_escape_string($conexion, trim($_POST['act']));
    $fecha = mysqli_real_escape_string($conexion, trim($_POST['notaFecha']));
    $detalles = mysqli_real_escape_string($conexion, utf8_decode(trim($_POST['notaDetalles'])));
    $descripcion = mysqli_real_escape_string($conexion, utf8_decode(trim($_POST['notaDescripcion'])));
    
    $fecha = formatDateToMySql($fecha);   
    
    $query = "INSERT INTO notas_activos(nota_id, nota_fecha, nota_descripcion, nota_detalles, act_id, nota_creador) 
              VALUES(NULL, '".$fecha."', '".$descripcion."', '".$detalles."', '".$actID."', '".$userID."')";
    $result = mysqli_query($conexion, $query);
    
    resultadoQuery($result);
    mysqli_close($conexion);    
    
}

if( isset($_POST['bajarActivo']) && $_POST['bajarActivo'] == 'si' )
{
    $baja = 'B'; // Código de baja en BD     
    $fecha_modificacion = date("Y-m-d H:i:s");
    $actID = mysqli_real_escape_string($conexion, trim($_POST['act']));
    $fecha = mysqli_real_escape_string($conexion, trim($_POST['fechaBaja']));
    $concepto = mysqli_real_escape_string($conexion, utf8_decode(trim($_POST['conceptoBaja'])));
    $descripcion = mysqli_real_escape_string($conexion, utf8_decode(trim($_POST['descripcionBaja'])));
    
    $fecha = formatDateToMySql($fecha);   
    
    $query = "UPDATE activos_fijos SET act_codEstado='".$baja."', act_fechaDeBaja='".$fecha."', baja_id='".$concepto."', 
              act_glosaDeBaja='".$descripcion."', act_modificador='".$userID."', act_modificacion='".$fecha_modificacion."' WHERE act_id=".$actID." LIMIT 1";
    $result = mysqli_query($conexion, $query);
    
    resultadoQueryEstado($result);
    mysqli_close($conexion);    
}

if( isset($_POST['altaActivo']) && $_POST['altaActivo'] == 'si' )
{
    $alta = 'V'; // Código de alta en BD      
    $fecha_modificacion = date("Y-m-d H:i:s");
    $actID = mysqli_real_escape_string($conexion, trim($_POST['act']));
    
    $query = "UPDATE activos_fijos SET act_codEstado='".$alta."', act_fechaDeBaja=NULL, baja_id=NULL, 
              act_glosaDeBaja=NULL, act_modificador='".$userID."', act_modificacion='".$fecha_modificacion."' WHERE act_id=".$actID." LIMIT 1";
    $result = mysqli_query($conexion, $query);
    
    resultadoQueryEstado($result);
    mysqli_close($conexion);    
}

if( isset($_POST['codUbi3']) )
{
    $codUbi3 = mysqli_real_escape_string($conexion, trim($_POST['codUbi3']));
    $nv1_nv2 = substr($codUbi3, 0, 5);
    
    $raizNivel2 = $nv1_nv2.'-00';
    
    $sql = "SELECT ubi_id, ubi_codigo, ubi_descripcion FROM ubicaciones WHERE ubi_codigo='".$raizNivel2."' AND ubi_nivel=2 LIMIT 1";
    $res = mysqli_query($conexion, $sql) or die(mysqli_error($conexion));
    $nv2 = mysqli_fetch_assoc($res);

    echo $nv2['ubi_descripcion'];
}

function resultadoQueryEstado($resultado)
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

function resultadoQuery($resultado)
{
    if( $resultado ){
        $respuesta = 'Operación satisfactoria !!';
    }else{
        $respuesta = 'Hubo un problema, vuelva a intentarlo.';
        // en caso de querer hacer debug...
        //$respuesta = mysqli_error($conexion);
    }
    
    echo $respuesta;
}
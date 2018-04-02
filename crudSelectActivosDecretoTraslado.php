<?php
require_once 'config.php';
require_once 'funciones.php';

if( isset($_GET['opcion']) ){
    // En caso q vaya a leer.
   $opcion = $_GET['opcion'];
}else{
   $opcion = $_POST['opcion'];
}

switch ($opcion){
	case "leer":
        leerData($conexion);
	break; 

    case "filtro":
       $ane=$_GET['ane']; 
       $cc=$_GET['cc'];
       $ub=$_GET['ub'];
       filtroData($ane,$cc,$ub,$conexion);
    break;
    
	default:
        echo utf8_encode("Error, no existe una opción para ejecutar");
    break;
}

function leerData($conexion)
{
	//para que muestre todos los decretos faltantes por AF, comentar el min(concat(reub_fecha,'-',reub_hora)) en el select y el group by  act_id al final del query
    $query = "SELECT 
    AF.act_id,
    reub_id,
    act_codigo,
    act_codEstado,
    act_descripcion,
    anac_descripcion,
    aneg_codigo,
    aneg_descripcion,
    ccos_descripcion,
    ubi_descripcion,
    resp_nombre,
    act_fechaIngreso,
    act_codBarras,
    act_tipoControl,
    act_revalorizable,
    act_depreciable,
    act_tipoDepreciacion,
    act_unidadMedidaVidaUtil,
    act_porLote,
    act_cantidadLote,
    act_situacionDePartida,
    act_fechaAdquisicion,
    act_vidaUtilTributaria,
    act_valorAdquisicion,
    act_inicioRevalorizacion,
    reub_fecha AS ultFecha,
    reub_hora AS ultHora,
	    min(concat(reub_fecha,'-',reub_hora))
FROM
    activos_fijos AF
        JOIN
    reubicaciones_activos REU ON AF.act_id = REU.act_id
        JOIN
    areas_negocios AN ON REU.aneg_id = AN.aneg_id
        JOIN
    centros_costo CC ON REU.ccos_id = CC.ccos_id
        JOIN
    ubicaciones UB ON REU.ubi_id = UB.ubi_id
        JOIN
    responsables RE ON UB.resp_id = RE.resp_id
        JOIN
    analiticos_cuentas AC ON AF.anac_id = AC.anac_id
WHERE
    act_decretoAlta = 1
        AND reub_id NOT IN (SELECT 
            reub_id
        FROM
            reubicaciones_activos
        GROUP BY act_id
        HAVING COUNT(reub_id) = 1)
        AND reub_id NOT IN (SELECT 
            MIN(REU.reub_id) AS idReuInicial
        FROM
            activos_fijos AF
                JOIN
            reubicaciones_activos REU ON AF.act_id = REU.act_id
        GROUP BY REU.act_id
        HAVING COUNT(REU.act_id) > 1)
        AND reub_id NOT IN (SELECT 
            reub_id
        FROM
            activos_fijos AF
                JOIN
            decretos_traslado DT ON AF.act_id = DT.act_id
        WHERE
            DT.tras_estado = 1
        GROUP BY reub_id)
        
       group by  act_id
  ";
                                                           
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
       
    $arreglo["data"] = array(); // asigna un array vacío si no hay registros en bd.
    while( $data = mysqli_fetch_assoc($result) ){
        $data['act_codEstado']=( $data['act_codEstado']=='V' ) ? 'VIGENTE' : 'BAJA';
        $arreglo["data"][] = array_map("utf8_encode", $data);
    }
    
    // json para el dataTable
    echo json_encode($arreglo);
    
    mysqli_free_result($result);
    mysqli_close($conexion);    
}





<?php
require_once 'funciones.php';

leerData();

function leerData()
{
    // DB table to use
    $table = 'activos_fijos';

    // Table's primary key
    $primaryKey = 'act_id';

    // db = campo de la bd, dt = columna del datatable
    $columns = array(
        array( 'db' => 'AF.act_id', 'dt' => null, 'field' => 'act_id' ),
    	array( 'db' => 'AF.act_id', 'dt' => 'act_id', 'field' => 'act_id' ),
    	array( 'db' => 'act_codigo', 'dt' => 'act_codigo', 'field' => 'act_codigo' ),
    	array( 'db' => 'anac_descripcion', 'dt' => 'anac_descripcion', 'field' => 'anac_descripcion' ),
    	array( 'db' => 'aneg_descripcion', 'dt' => 'aneg_descripcion', 'field' => 'aneg_descripcion' ),
    	array( 'db' => 'ccos_descripcion', 'dt' => 'ccos_descripcion', 'field' => 'ccos_descripcion' ),
        array( 'db' => 'ubi_descripcion', 'dt' => 'ubi_descripcion', 'field' => 'ubi_descripcion' ),
        array( 'db' => 'resp_nombre', 'dt' => 'resp_nombre', 'field' => 'resp_nombre' ),
        array(
               'db' => 'act_codEstado',
               'dt' => 'act_codEstado',
               'field' => 'act_codEstado',
               'formatter' => function( $d, $row ){
                   return ( $d == 'V' ) ? 'VIGENTE' : 'BAJA';
               }
             ),
    	array(
               'db' => 'act_fechaIngreso',
               'dt' => 'act_fechaIngreso',
               'field' => 'act_fechaIngreso',
               'formatter' => function( $d, $row ){
                    return date( 'd-m-Y', strtotime($d) );
               }
             )
        );

    // SQL server conex info
    require_once 'config.php';
    $sql_details = array(
    	'user' => $usuario,
    	'pass' => $clave,
    	'db'   => $BD,
    	'host' => $servidor
    );

    // require( 'ssp.class.php' );
    require_once 'requires/ssp.customized.class.php';

    $joinQuery = "FROM activos_fijos AS AF
                    JOIN analiticos_cuentas AC
                      ON AF.anac_id=AC.anac_id
                        JOIN areas_negocios AS AN
                          ON AF.aneg_id=AN.aneg_id
                            JOIN centros_costo AS CC
                              ON AF.ccos_id=CC.ccos_id
                                JOIN ubicaciones AS UB
                                  ON AF.ubi_id=UB.ubi_id
                                    JOIN responsables AS RE
                                      ON UB.resp_id=RE.resp_id";

    $extraWhere = "act_codEstado='B' AND act_decretoBaja=0"; // AND act_codigo=act_idLote
    $groupBy = ''; // "`u`.`office`";
    $having = ''; // "`u`.`salary` >= 140000";

    echo json_encode(
    	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $groupBy, $having )
    );
}

/*
funcion para el modo cliente
function leerData($conexion)
{
    $query = "SELECT AF.act_id, act_codigo, act_codEstado, act_descripcion, aneg_codigo, aneg_descripcion, ccos_codigo, ubi_codigo, resp_nombre,
             act_fechaIngreso, act_codBarras, act_descripcionDetallada, act_venceUltimaGarantia, act_tipoControl, AF.aneg_id, AF.cing_id, AF.ccos_id,
             AF.grup_id, AF.sgru_id, AF.ubi_id, AF.aux_id, act_revalorizable, act_depreciable, act_tipoDepreciacion, act_unidadMedidaVidaUtil,
             act_umed_id, act_porLote, act_cantidadLote, act_situacionDePartida, act_fechaAdquisicion, act_vidaUtilTributaria, act_presupuesto,
             act_vidaUtilFinanciera, act_valorAdquisicion, act_numDocCompra, tdoc_id, act_numOrdenCompra, act_valorResidual, act_inicioRevalorizacion,
             act_inicioDepreciacion, act_bajoNormaPublica, act_bajoNormaTributaria, act_bajoNormaIFRS, act_decretoAlta, act_decretoBaja, AF.cond_id
             FROM activos_fijos AF
                JOIN areas_negocios AN
                    ON AF.aneg_id=AN.aneg_id
                        JOIN centros_costo CC
                            ON AF.ccos_id=CC.ccos_id
                                JOIN ubicaciones UB
                                    ON AF.ubi_id=UB.ubi_id
                                        JOIN responsables RE
                                            ON UB.resp_id=RE.resp_id
                                                WHERE act_codEstado='B' AND act_decretoBaja=0
                                                    ORDER BY act_descripcion ASC";
    $result = mysqli_query($conexion, $query) or die(mysqli_error($conexion));

    $arreglo["data"] = array(); // asigna un array vac�o si no hay registros en bd.
    while( $data = mysqli_fetch_assoc($result) ){
        $data['act_codEstado']=( $data['act_codEstado']=='V') ? 'VIGENTE' : 'BAJA';
        $arreglo["data"][] = array_map("utf8_encode", $data);
    }

    // json para el dataTable
    echo json_encode($arreglo);

    mysqli_free_result($result);
    mysqli_close($conexion);
}
*/

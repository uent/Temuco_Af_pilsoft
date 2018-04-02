<?php
require_once 'traeDatosActivo.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Activos - Decretos de Baja</title>
    <?php include_once 'tagsMeta.php'; ?>
    <?php include_once 'tagsCSS.php'; ?>
    <?php include_once 'tagsFixJS.php'; ?>
    <link href="css/select.bootstrap.min.css" rel="stylesheet" />
    <link href="css/select.dataTables.min.css" rel="stylesheet" />
  </head>
  <body>
    <div class="container">      
        <?php include_once 'menuInicio.php'; ?>
      
        <div class="row">
            <div class="col-sm-12">
                <h4 class="well well-sm text-center"><b>ACTIVOS - DECRETOS DE BAJA</b></h4>
            </div>
        </div>

        <!-- Capa de registros -->
        <div id="capaRegistros">
            <!-- DataTables -->
            <form id="activosDecreto">
            <table id="dtActivos" class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>C&oacute;digo</th>
                    <th>Anal&iacute;tico</th>
                    <th>&Aacute;rea de Negocio</th>
                    <th>Centro de Costo</th>
                    <th>Ubicaci&oacute;n</th>
                    <th>Responsable</th>                    
                    <th>Estado</th>
                    <th>Fecha Ingreso</th>
                </tr>
                </thead>
                <tfoot>
                <tr class="active">
                    <th></th>
                    <th>ID</th>                    
                    <th>C&oacute;digo</th>
                    <th>Anal&iacute;tico</th>
                    <th>&Aacute;rea de Negocio</th>
                    <th>Centro de Costo</th>
                    <th>Ubicaci&oacute;n</th>
                    <th>Responsable</th>                    
                    <th>Estado</th>
                    <th>Fecha Ingreso</th>
                </tr>                
                </tfoot>                
            </table> <!-- /DataTables -->
            </form> <!-- /Form Checkbox -->
        </div> <!-- /Capa de registros -->      
    </div> <!-- /Container --> 

    <?php include_once 'tagsJS.php'; ?>
    <!-- Opción select DataTables -->
    <script src="js/dataTables.select.min.js"></script>
    <script type="text/javascript">
    $(document).on("ready", function(){
        listar();
    });

    var listar = function(){
        var table = $("#dtActivos").DataTable({            
            "destroy": true,            
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "order": ([[2, 'asc'], [3, 'asc']]), //The rows are shown in the order they are read by DataTables            
            "ajax":{
                url: "crudSelectActivosDecretoBaja.php",
                type: "POST"
            },
            "deferRender": true,
            "columnDefs":[
                 {
                     targets: [0],                  
                     orderable: false,
                     className: "select-checkbox"                     
                 },
                 {
                     targets: [1],
                     visible: false,
                     className: "actID"
                 },                 
                 {
                    targets: [8,9],
                    className: "text-center"
                 }
            ],
            "columns":[
                {
                    "data": null,
                    "defaultContent": ""
                },
                {"data":"act_id"},
                {"data":"act_codigo"},
                {"data":"anac_descripcion"},
                {"data":"aneg_descripcion"},
                {"data":"ccos_descripcion"},
                {"data":"ubi_descripcion"},
                {"data":"resp_nombre"},
                {"data":"act_codEstado"},
                {
                    "data":"act_fechaIngreso",
                    "searchable": false
                }                
            ],
            "language":{
                "url": "js/dataTables_es.json",
                select:{                    
                    rows:{
                        _: " | %d filas selecionadas",
                        0: " | 0 filas selecionadas",
                        1: " | 1 fila selecionada"
                    }
                }
            },
            "dom": "<'row'<'col-xs-12 col-md-6'B><'col-xs-12 col-md-6'f>>" +
                   "<'row'<'col-xs-12'tr>>" +
                   "<'row'<'col-xs-12 col-md-3'l><'col-xs-12 col-md-4 small text-center text-primary'i><'col-xs-12 col-md-5'p>>",
            "select":{
                 "style": "multi",
                 "selector": "td:first-child"                                                  
            },
            "buttons":[
                <?php if( $_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 2 ){ ?>
                {
                    "text": "Generar Decreto Baja",
                    "titleAttr": "Generar Decreto",
                    "className": "btn btn-danger",
                    "action": function(){
                        //var count = table.rows( { selected: true } ).count();
                        var datos = table.rows( {selected: true} ).data();
                        var total = datos.length;
                        var obj = {}; // Obj json
                        
                        if( total >= 1 ){
                            for( var i=0; i < total; i++ ){
                                obj[i] = datos[i].act_id;
                            }
                            
                            var resp = confirm("¿Estimado(a), a continuación se generará el Decreto de Baja tomando en consideración los Activos Fijos previamente seleccionados.\n\n ¿Está seguro de realizar esta acción?\n\n Una vez emitido el Decreto no podrá modificarlo.");
                            
                            if( resp ){
                                popup('crear', obj, table);
                            }else{
                                table.ajax.reload();
                                alert("Acción cancelada por el usuario.");
                            }                            
                        }else{
                            alert("Favor, debe seleccionar uno o más activos.");
                        }
                    }
                },
                <?php } ?>
                {
                    "text": "<span class='glyphicon glyphicon-eye-open'></span> Vista Previa",
                    "titleAttr": "Ver Vista Previa",
                    "className": "btn btn-primary",
                    "action": function(){
                        var datos = table.rows( {selected: true} ).data();
                        var total = datos.length;
                        var obj = {}; // Obj json                        
                        
                        if( total >= 1 ){
                            for( var i=0; i < total; i++ ){
                                //alert(datos[i].act_id);
                                obj[i] = datos[i].act_id;
                            }
                            
                            popup('preview', obj, table);
                            
                        }else{
                            alert("Favor, debe seleccionar uno o más activos.");
                        }
                    }
                },                
                {
                    "text": "<span class='glyphicon glyphicon-pencil'></span>",
                    "titleAttr": "Datos Generales",
                    "className": "btn btn-success",
                    "action": function(){
                        parametros('datosDecretoBajas.php');
                        
                    }
                },
                {
                    "text": "<span class='glyphicon glyphicon-pencil'></span>",
                    "titleAttr": "Vistos de Alta",
                    "className": "btn btn-default",
                    "action": function(){
                        parametros('vistosBaja.php');
                        
                    }
                },
                {
                    "text": "<span class='glyphicon glyphicon-pencil'></span>",
                    "titleAttr": "Distribución de Alta",
                    "className": "btn btn-warning",
                    "action": function(){
                        parametros('distribucionBaja.php');
                        
                    }
                }                                
            ],
            "lengthMenu": [ [ 5, 100, 500, 1000, 2500 ], [ '5', '100', '500', '1000', '2500' ] ]
        });
    }
    
    function popup(opc, json, table){
        var queryString = $.param(json);
        var url = 'decretoBaja.php?opc='+opc+'&'+queryString;
        //alert(url);
        if( opc == 'preview' ){        
            var w = window.open(url, 'Borrador', 'width='+screen.width+', height='+screen.height+', scrollbars=yes');
            w.moveTo(0, 0);
            w.focus();                        
        }else{            
            var w = window.open(url, 'Dectreto_Baja');
            table.ajax.reload();
        }
    }
    
    function parametros(mantenedor){
        var w = window.open(mantenedor, 'Parametros de Baja', 'width='+screen.width+', height='+screen.height+', scrollbars=yes');
        w.moveTo(0, 0);
        w.focus();
    }
</script>
  </body>
</html>
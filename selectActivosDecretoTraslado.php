<?php
require_once 'traeDatosActivo.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Activos - Decretos de Traslado</title>
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
                <h4 class="well well-sm text-center"><b>ACTIVOS - DECRETOS DE TRASLADO</b></h4>
            </div>
        </div>
      
        <div class="row hide">
            <form id="formActivo" class="form-horizontal" role="form" novalidate>
            <div class="form-group">            
                <label for="fechafin" class="control-label col-md-1 col-md-offset-4"><b>Fecha:</b></label>
                <div class="col-md-2">
                    <input type="text" name="fechafin" id="fechafin" class="form-control input-sm" placeholder="" title="Fecha fin" />
                </div>
            </div>
            </form>
        </div>

        <div class="row hide">
            <form id="formActivo" class="form-horizontal" role="form" novalidate>
            <div class="form-group">
                <div class="col-sm-12 col-md-12 text-center">
                    <label for="aneg_id" class="control-label col-sm-1 col-md-1 col-md-offset-1"><b>A. Negocio:</b></label>
                    <div class="col-sm-2 col-md-2">
                        <select class="form-control input-sm" name="aneg_id" id="aneg_id" title="A. Negocio">
                            <option value="">-- Seleccione --</option>
                            <?php llenaOptions('areas_negocios', 'aneg_', $conexion); ?>
                        </select>
                    </div>
                    
                    <label for="ccos_id" class="control-label col-sm-1 col-md-1"><b>C. Costo:</b></label>
                    <div class="col-sm-2 col-md-2">
                        <select class="form-control input-sm" name="ccos_id" id="ccos_id" title="C. Costo">
                            <option value="">-- Seleccione --</option>
                            <?php llenaOptions('centros_costo', 'ccos_', $conexion); ?>                          
                        </select>                    
                    </div>
                    
                    <label for="ubi_id" class="control-label col-sm-1 col-md-1"><b>Ubicaci&oacute;n:</b></label>
                    <div class="col-sm-2 col-md-2">
                        <select class="form-control input-sm" name="ubi_id" id="ubi_id" title="Ubicaci&oacute;n">
                            <option value="">-- Seleccione --</option>
                            <?php llenaOptions('ubicaciones', 'ubi_', $conexion); ?>                           
                        </select>
                    </div>
                </div>
            </div>
            </form>
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
					<th>reubID</th>
                    <th>C&oacute;digo</th>
                    <th>Anal&iacute;tico</th>
                    <th>Descripci&oacute;n</th>                    
                    <th>&Aacute;rea de Negocio</th>
                    <th>Centro de Costo</th>
                    <th>Ubicaci&oacute;n Actual</th>
                    <th>Responsable</th>                    
                    <th>Estado</th>
                    <th>Fecha Traslado</th>
                    <th> Hora </th>
                </tr>
                </thead>
                <tfoot>
                <tr class="active">
                    <th></th>
                    <th>ID</th>
					<th>reubID</th>
                    <th>C&oacute;digo</th>
                    <th>Anal&iacute;tico</th>
                    <th>Descripci&oacute;n</th>                    
                    <th>&Aacute;rea de Negocio</th>
                    <th>Centro de Costo</th>
                    <th>Ubicaci&oacute;n Actual</th>
                    <th>Responsable</th>                    
                    <th>Estado</th>
                    <th>Fecha Traslado</th>
                    <th> Hora </th>
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
        $("#fechainicio").datepicker({ onSelect: function(){}, changeMonth: true, changeYear: true });
        $("#fechafin").datepicker({ onSelect: function(){}, changeMonth: true, changeYear: true});
        
        var table = $('#dtActivos').DataTable();
        
        $('#fechainicio, #fechafin').change(function(){
            //table.draw(); 
            var fechain=$("#fechainicio").val();
            var fechafin=$("#fechafin").val();
            
            if(fechafin===""){
            
            }else{
                $("#aneg_id").val("");
                $("#ccos_id").val("");
                $("#ubi_id").val("");
                listarfecha(fechafin);
            }
        });
        
        $('#aneg_id,#ccos_id,#ubi_id').change(function(){
            var an=$("#aneg_id").val();
            var cc=$("#ccos_id").val();
            var ub=$("#ubi_id").val();
            
            if(an==="" && cc==="" && ub===""){
                var fechafin=$("#fechafin").val();
                listarfecha(fechafin);
            }else{
                filtrar(an,cc,ub);
            }
        });
    }); 

    var listar = function(){
        var table = $("#dtActivos").DataTable({            
            "destroy": true,            
            "responsive": true,
            //"processing": true,
            "order": [], //The rows are shown in the order they are read by DataTables            
            "ajax":{
                method: "GET",
                url: "crudSelectActivosDecretoTraslado.php?opcion=leer"
            },
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
                     targets: [2],
                     visible: false,
                     className: "reubID"
                 },  	
                 {
                    targets: [10],
                    className: "text-center"
                 },
                 {
                    targets: [11],
                    className: "text-center",
                    render: $.fn.dataTable.render.moment( "YYYY-MM-DD", "DD-MM-YYYY", "es" )
                }
            ],
            "columns":[
                {
                    "data": null,
                    "defaultContent": ""
                },
                {"data":"act_id"},
				{"data":"reub_id"},
                {"data":"act_codigo"},
                {"data":"anac_descripcion"},
                {"data":"act_descripcion"},
                {"data":"aneg_descripcion"},
                {"data":"ccos_descripcion"},
                {"data":"ubi_descripcion"},
                {"data":"resp_nombre"},
                {"data":"act_codEstado"},
                {"data":"ultFecha"},
                {"data":"ultHora"}               
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
                    "text": "Generar Decreto Traslado",
                    "titleAttr": "Generar Decreto",
                    "className": "btn btn-warning",
                    "action": function(){                        
                        //var count = table.rows( { selected: true } ).count();
                        var datos = table.rows( {selected: true} ).data();
                        var total = datos.length;
                        var obj = {}; // Obj json
                        
                        if( total >= 1 ){                            
                            for( var i=0; i < total; i++ ){
                                obj[i] = datos[i].reub_id;
                            }
                            
                            var resp = confirm("¿Estimado(a), a continuación se generará el Decreto de Traslado tomando en consideración los Activos Fijos previamente seleccionados.\n\n ¿Está seguro de realizar esta acción?\n\n Una vez emitido el Decreto no podrá modificarlo.");
                            
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
                                obj[i] = datos[i].reub_id;
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
                        parametros('datosDecretoTraslados.php');
                        
                    }
                },
                {
                    "text": "<span class='glyphicon glyphicon-pencil'></span>",
                    "titleAttr": "Vistos de Traslado",
                    "className": "btn btn-default",
                    "action": function(){
                        parametros('vistosTraslado.php');
                        
                    }
                },
                {
                    "text": "<span class='glyphicon glyphicon-pencil'></span>",
                    "titleAttr": "Distribución de Traslado",
                    "className": "btn btn-warning",
                    "action": function(){
                        parametros('distribucionTraslado.php');
                        
                    }
                }                
            ],
            "lengthMenu": [ [ 5, 10, 25, 50, 100, -1 ], [ '5', '10', '25', '50', '100', 'Todo' ] ]            
        });
    }
    
    var listarfecha = function(fechafin){
        var table = $("#dtActivos").DataTable({            
            "destroy": true,
            "responsive": true,
            //"processing": true,
            "order": [], //The rows are shown in the order they are read by DataTables            
            "ajax":{
                method: "GET",
                url: "crudSelectActivosDecretoTraslado.php?opcion=leer&fechfin="+ fechafin +""
            },
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
                    targets: [8],
                    className: "text-center"
                 },
                 {
                    targets: [9],
                    className: "text-center",
                    render: $.fn.dataTable.render.moment( "YYYY-MM-DD", "DD-MM-YYYY", "es" )
                }
            ],
            "columns":[
                {
                    "data": null,
                    "defaultContent": ""
                },
                {"data":"act_id"},
                {"data":"act_codigo"},
                
                {"data":"act_descripcion"},
                {"data":"aneg_descripcion"},
                {"data":"ccos_descripcion"},
                {"data":"ubi_descripcion"},
                {"data":"resp_nombre"},
                {"data":"act_codEstado"},
                {"data":"ultFecha"},
                {"data":"ultHora"}                 
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
                    "text": "Generar Decreto Traslado",
                    "titleAttr": "Generar Decreto",
                    "className": "btn btn-warning",
                    "action": function(){                        
                        //var count = table.rows( { selected: true } ).count();
                        var datos = table.rows( {selected: true} ).data();
                        var total = datos.length;
                        var obj = {}; // Obj json
                        
                        if( total >= 1 ){                            
                            for( var i=0; i < total; i++ ){
                                obj[i] = datos[i].act_id;
                            }
                            
                            var resp = confirm("¿Estimado(a), a continuación se generará el Decreto de Traslado tomando en consideración los Activos Fijos previamente seleccionados.\n\n ¿Está seguro de realizar esta acción?\n\n Una vez emitido el Decreto no podrá modificarlo.");
                            
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
                }
            ],
            "lengthMenu": [ [ 5, 10, 25, 50, 100, -1 ], [ '5', '10', '25', '50', '100', 'Todo' ] ]            
        });
    }

    var filtrar = function(ane,cc,ub){
        var table = $("#dtActivos").DataTable({            
            "destroy": true,
            "responsive": true,
            //"processing": true,
            "order": [], //The rows are shown in the order they are read by DataTables            
            "ajax":{
                method: "GET",
                url: "crudSelectActivosDecretoTraslado.php?opcion=filtro&ane="+ane+"&cc="+cc+"&ub="+ub+" "
            },
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
                    targets: [8],
                    className: "text-center"
                 },
                 {
                    targets: [9],
                    className: "text-center",
                    render: $.fn.dataTable.render.moment( "YYYY-MM-DD", "DD-MM-YYYY", "es" )
                }
            ],
            "columns":[
                {
                    "data": null,
                    "defaultContent": ""
                },
                {"data":"act_id"},
                {"data":"act_codigo"},
                {"data":"act_descripcion"},
                {"data":"aneg_descripcion"},
                {"data":"ccos_descripcion"},
                {"data":"ubi_descripcion"},
                {"data":"resp_nombre"},
                {"data":"act_codEstado"},
                {"data":"ultFecha"},
                {"data":"ultHora"}                
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
                    "text": "Generar Decreto Traslado",
                    "titleAttr": "Generar Decreto",
                    "className": "btn btn-warning",
                    "action": function(){                        
                        //var count = table.rows( { selected: true } ).count();
                        var datos = table.rows( {selected: true} ).data();
                        var total = datos.length;
                        var obj = {}; // Obj json
                        
                        if( total >= 1 ){                            
                            for( var i=0; i < total; i++ ){
                                obj[i] = datos[i].act_id;
                            }
                            
                            var resp = confirm("¿Estimado(a), a continuación se generará el Decreto de Traslado tomando en consideración los Activos Fijos previamente seleccionados.\n\n ¿Está seguro de realizar esta acción?\n\n Una vez emitido el Decreto no podrá modificarlo.");
                            
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
                }
            ],
            "lengthMenu": [ [ 5, 10, 25, 50, 100, -1 ], [ '5', '10', '25', '50', '100', 'Todo' ] ]            
        });
    }

    $("#fechaincio").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: "1980:2030",
        minDate: "01-01-1980",
        maxDate: "31-12-2030"
    });
     
    $("#fechafin").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: "1980:2030",
        minDate: "01-01-1980",
        maxDate: "31-12-2030"
    });
    
    function parseDateValue(rawDate){
        var dateArray = rawDate.split("-");
        var parsedDate = dateArray[2]+dateArray[1];
        return parsedDate;
    }
    
    function popup(opc, json, table){
        var queryString = $.param(json);
        var url = 'decretoTraslado.php?opc='+opc+'&'+queryString;
        //alert(url);
        if( opc == 'preview' ){        
            var w = window.open(url, 'Borrador', 'width='+screen.width+', height='+screen.height+', scrollbars=yes');
            w.moveTo(0, 0);
            w.focus();                        
        }else{            
            var w = window.open(url, 'Dectreto_Traslado');
            table.ajax.reload();
        }
    }

    function parametros(mantenedor){
        var w = window.open(mantenedor, 'Parametros de Traslado', 'width='+screen.width+', height='+screen.height+', scrollbars=yes');
        w.moveTo(0, 0);
        w.focus();
    }     
</script>
  </body>
</html>
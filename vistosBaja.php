<?php
require_once 'config.php';
#print_r(date("Y-m-d H:i:s"));
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Vistos Baja</title>
    <?php include_once 'tagsMeta.php'; ?>
    <?php include_once 'tagsCSS.php'; ?>
    <?php include_once 'tagsFixJS.php'; ?>
    <style type="text/css">
    <!--
   	th.boton, th.boton{
        width: 10px;
   	}
    -->
    </style>    
  </head>
  <body>
    <div class="container">      
        <?php include_once 'menuInicio.php'; ?>
      
        <div class="row">
            <div class="col-xs-12">
                <h4 class="well well-sm text-center"><b>VISTOS BAJAS</b></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <p class="text-center msj_respuesta"></p>
            </div>
        </div>
                    
        <!-- Capa de registros -->
        <div id="capaRegistros">
            <!-- DataTables -->
            <table id="dtVistos" class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripci&oacute;n</th>
                    <th>Fecha Creaci&oacute;n</th>
                    <th class="boton"></th>
                    <th class="boton"></th>
                </tr>
                </thead>
                <tfoot>
                <tr class="active">
                    <th>ID</th>
                    <th>Descripci&oacute;n</th>
                    <th>Fecha Creaci&oacute;n</th>
                    <th></th>
                    <th></th>                  
                </tr>                
                </tfoot>                
            </table> <!-- /DataTables -->
    
            <!-- Modal Crear & Editar -->
            <div class="modal fade" id="modalCrearEditar" tabindex="-1" role="dialog" aria-labelledby="myCrearEditarModalLabel" data-backdrop="static" data-keyboard="false">
            <form method="post" id="formCrearEditar" class="form-horizontal" role="form">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                       <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title" id="titleModalCrearEditar"></h4>
                      </div>                  
                      <div class="modal-body">
                        <input type="hidden" name="opcion" id="opcion" value="" />
                        <input type="hidden" name="vbaj_id" id="vbaj_id" value="" />
                        
                        <div class="form-group">
                            <label for="vbaj_descripcion" class="control-label col-md-2 col-md-offset-1">Descripci&oacute;n:</label>
                            <div class="col-md-9">
                                <input type="text" name="vbaj_descripcion" id="vbaj_descripcion" class="form-control input-sm" required minlength="10" maxlength="255" placeholder="Descripci&oacute;n" title="Descripci&oacute;n"/>
                            </div>                
                        </div> 
                        <p class="text-center" id="msj_respuesta"></p>                      
                      </div>                  
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-cancelar" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnModalCrearEditar" class="btn btn-primary"></button>
                      </div>
                    </div>
                </div>
            </form>
            </div> <!-- /Modal Crear & Editar -->            
            
            <!-- Modal Eliminar -->
            <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="myEliminarModalLabel" data-backdrop="static" data-keyboard="false">
            <form method="post" id="frmEliminar" class="form-horizontal" role="form">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="titleModalEliminar"><b>Eliminar</b></h4>
                      </div>
                      <div class="modal-body">                    
                        ¿Seguro desea eliminar el registro c&oacute;digo: <strong id="registro"></strong>?
                        
                        <input type="hidden" name="opcion" id="opcionEliminar" value="eliminar" />
                        <input type="hidden" name="vbaj_id" id="delete_vbaj_id" value="" />
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-cancelar" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnModalEliminar" class="btn btn-danger" data-dismiss="modal">Eliminar</button>
                      </div>
                    </div>
                </div>
            </form>
            </div> <!-- /Modal Eliminar -->            
        </div> <!-- /Capa de registros -->
    </div> <!-- /container -->

    <?php include_once 'tagsJS.php'; ?>
    <script type="text/javascript">
    $(document).on("ready", function(){            
        listar();
        crear_editar();
        eliminar();              
    });
     
    var listar = function(){        
        var table = $("#dtVistos").DataTable({            
            "destroy": true,
            "responsive": true,
            //"processing": true,
            "order": [], //The rows are shown in the order they are read by DataTables            
            "ajax":{
                method: "GET",
                url: "crudVistosBaja.php?opcion=leer"
            },
            "columnDefs":[
                 {
                     targets: [0],
                     visible: false
                 },
                 
                {
                    targets: [2],
                    className: "text-center",
                    render: $.fn.dataTable.render.moment( "YYYY-MM-DD HH:mm:ss", "DD-MM-YYYY", "es" )
                }
            ],
            "columns":[
                {"data":"vbaj_id"},
                {"data":"vbaj_descripcion"},
                {
                    "data": "vbaj_creacion",
                    "searchable": false
                },                    
                {
                    "defaultContent":"<button type='button' class='editar btn btn-primary btn-xs' data-toggle='modal' data-target='#modalCrearEditar' title='Editar'><span class='glyphicon glyphicon-pencil'></span></button>",
                    "sortable": false,
                    "searchable": false                    
                },
                {
                    "defaultContent":"<button type='button' class='eliminar btn btn-danger btn-xs' data-toggle='modal' data-target='#modalEliminar' title='Eliminar'><span class='glyphicon glyphicon-trash'></span></button>",
                    "sortable": false,
                    "searchable": false
                },
            ],
            "language":{
                "url": "js/dataTables_es.json"
            },
            "dom": "<'row'<'col-xs-12 col-md-6'B><'col-xs-12 col-md-6'f>>" +
                   "<'row'<'col-xs-12'tr>>" +
                   "<'row'<'col-xs-12 col-md-3'l><'col-xs-12 col-md-4 small text-center text-primary'i><'col-xs-12 col-md-5'p>>",            
            "buttons":[
                {
                    "text": "<span class='glyphicon glyphicon-plus-sign'></span>",
                    "titleAttr": "Agregar Nuevo",
                    "className": "crear btn btn-success",
                    "action": function()
                    {
                        $(".crear").attr({
                             "data-toggle": "modal",
                             "data-target": "#modalCrearEditar"                             
                        });
                        $("div.has-error").removeClass("has-error");
                        limpiar_form("crear");
                        $("#opcion").val("insertar");
                        cambiarTextosModal("crear");                        
                    }
                },
                'excel', 'pdf',
                {   
                    "extend": "print",
                    "text": "Imprimir"
                }
            ],
            "lengthMenu": [ [ 5, 10, 25, 50, -1 ], [ '5', '10', '25', '50', 'Todo' ] ]            
        });        
        
        $('#dtVistos').find('tbody').off('click');
        obtener_datos("#dtVistos tbody", table);
        obtener_id("#dtVistos tbody", table);
    }
    
    var crear_editar = function(){
        $("#formCrearEditar").on("submit", function(e){
            e.preventDefault();
            var opcion=$("#opcion").val();
            var formData = $(this).serialize();
            var form = $("#formCrearEditar");
            console.log(formData);
            form.validate();
             if (form.valid()==true){
             $.ajax({
                 method: "POST",              
                 url: "crudVistosBaja.php",       
                 data: formData
             }).done(function( resp ){
                 limpiar_form(opcion);
                 mostrar_msj( resp );
                 listar();
                                  
             });
         }
        });        
    }
     
    var eliminar = function(){
        $("#btnModalEliminar").on("click", function(){            
            var formData = $("#frmEliminar").serialize(); 
            //console.log(formData);          
            $.ajax({
                method: "POST",
                url: "crudVistosBaja.php",
                data: formData
            }).done(function( resp ){
                mostrar_msjeli( resp );                
                limpiar_form("eliminar");
                listar();
                
            });            
        });
    }
    
    var esconderModal = function(){
        setTimeout(function(){
            $("#modalCrearEditar").modal("toggle");
            $("#btnModalCrearEditar").prop("disabled",false);
            $(".btn-cancelar").prop("disabled",false);
        }, 5450);

    }
    
    $('#modalCrearEditar').on('shown.bs.modal', function(){
        if( ! $("#vbaj_descripcion").prop("readonly") ){
            $('#vbaj_descripcion').focus();
        }else{
            $("#vbaj_descripcion").focus();
        }
    });

    $(".btn-cancelar").on("click", function(){
        $("#opcion").val("");
        $("#vbaj_id").val("");
        $("#registro").text("");
        $("#delete_vbaj_id").val("");
        $("#formCrearEditar").validate().resetForm();
    });
    
    var cambiarTextosModal = function( accion ){
        if( accion == 'editar' ){            
            $("#titleModalCrearEditar").html("<b>Editar</b>");
            $("#btnModalCrearEditar").text("Guardar Cambios");
        }else if( accion == 'crear' ){
            $("#titleModalCrearEditar").html("<b>Crear Nuevo</b>");
            $("#btnModalCrearEditar").text("Guardar Nuevo");            
        }        
    }
    
    var limpiar_form = function( opcion ){
        if( opcion == 'eliminar' ){            
            $("#delete_vbaj_id").val("");
            $("#registro").text("");
        }else if(opcion=='editar'){    
            
        }else{
            $("#vbaj_id").val("");
            $("#vbaj_descripcion").val("");
        }
        
        $("#opcion").val("");
    }
    var obtener_datos = function(tbody, table){
        $(tbody).on("click", "button.editar", function(){            
            $("#opcion").val("editar");
            cambiarTextosModal('editar');                        
            $("div.has-error").removeClass("has-error");
            var data = table.row( $(this).parents("tr") ).data();
            $("#vbaj_id").val( data.vbaj_id );
            $("#vbaj_descripcion").val( data.vbaj_descripcion);
            //alert(data.aneg_id +" + "+data.aneg_codigo+" + "+data.aneg_descripcion);              
        });        
    }
    
    var obtener_id = function(tbody, table){
        $(tbody).on("click", "button.eliminar", function(){
            var data = table.row( $(this).parents("tr") ).data();
            var vbaj_id = $("#delete_vbaj_id").val( data.vbaj_id ),
            vbaj_descripcion = $("#registro").text( data.vbaj_descripcion );
        });        
    }
    
    var mostrar_msj = function( respuesta ){
        var msj = $("#msj_respuesta");
        var texto = "<b>Resultado:</b> ";
        var alertClass = "";
       console.log(respuesta);
        var opcion= $("#titleModalCrearEditar").text();
        if( respuesta == 'ok' ){
            $("#btnModalCrearEditar").prop("disabled",true);
            $(".btn-cancelar").prop("disabled",true);
            $("div.has-error").removeClass("has-error");
            texto += "La operación se a realizado correctamente.";
            alertClass = "alert alert-success";
        if (!$('#frmEliminar').is(':visible')){ 
                esconderModal();
        }else{
                var msj = $(".msj_respuesta");  
        }
            msj.html(texto).addClass(alertClass);
            msj.fadeOut(5000, function(){
            msj.html("").removeClass(alertClass).fadeIn(3000);
        }); 
        }else if( respuesta == 'error' ){            
            texto += "No a sido posible ejecutar la consulta.";
            alertClass = "alert alert-danger";
            msj.html(texto).addClass(alertClass);
            msj.fadeOut(7000, function(){
            msj.html("").removeClass(alertClass).fadeIn(3000);
        }); 
        }else if( respuesta == 'existe' ){
            $("div.has-error").removeClass("has-error");            
            texto += "El registro que intenta ingresar ya existe.";
            alertClass = "alert alert-warning";
        if (opcion=='Editar'){
            $("#opcion").val("editar");
        }else{
            $("#opcion").val("insertar");
        } 
           msj.html(texto).addClass(alertClass);
           msj.fadeOut(6000, function(){
           msj.html("").removeClass(alertClass).fadeIn(3000);
        }); 
        }else if(JSON.parse(respuesta)){
       
        if (opcion=='Editar'){
            $("#opcion").val("editar");
        }else{
            $("#opcion").val("insertar");
        }
            var arrayjs=JSON.parse(respuesta);
            var str ='';
            texto +="<ul>";
            $("div.has-error").removeClass("has-error");
             for (var i=0;i < arrayjs.invali.length;i++){
                 str=arrayjs.invali[i];
                 str_a="";
                 separador="</span>";
                 limitar=2;
                 str=arrayjs.invali[i];
                 str_a=str.substring(34);
                 str_a=str_a.split(separador,limitar);
                 str_a=str_a[0].replace(" ","_");
                 if (str_a===str_a){
                 $("#"+str_a).parent().addClass('has-error');
                 }
                 texto += "<li>"+ str.replace("vbaj","")+"</li>";        
         }
            texto +="</ul>"; 
            alertClass = "alert alert-danger"; 
            msj.html(texto).addClass(alertClass);
            msj.fadeOut(8000, function(){
            msj.html("").removeClass(alertClass).fadeIn(3000);
        });           
        }else{
            // disponible para otras respuestas desde crud.php
            texto += respuesta;
            alertClass = "alert alert-warning";
           msj.html(texto).addClass(alertClass);
           msj.fadeOut(5000, function(){
           msj.html("").removeClass(alertClass).fadeIn(3000);
        });
        }
        
         
    }
      $("#formCrearEditar").validate({
         submitHandler: function(form){
         }
     });

var mostrar_msjeli = function( respuesta ){
        var msj = $(".msj_respuesta");
        var texto = "<b>Resultado:</b> ";
        var alertClass = "";
        console.log(respuesta);
        if( respuesta == 'ok' ){
            texto += "La operación se a realizado correctamente.";
            alertClass = "alert alert-success";
        }else if( respuesta == 'error' ){            
            texto += "No a sido posible ejecutar la consulta.";
            alertClass = "alert alert-danger";
        }else if( respuesta == 'existe' ){            
            texto += "El registro que intenta ingresar ya existe.";
            alertClass = "alert alert-warning";            
        }else{
            // disponible para otras respuestas desde crud.php
            texto += respuesta;
            alertClass = "alert alert-warning";
        }
        
        msj.html(texto).addClass(alertClass);
        msj.fadeOut(5000, function(){
        msj.html("").removeClass(alertClass).fadeIn(3000);
        });
    }
</script>
  </body>
</html>
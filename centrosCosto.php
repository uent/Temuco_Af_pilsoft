<?php
require_once 'config.php';
require_once 'traeDatosMantenedores.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Centros de Costo</title>
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
                <h4 class="well well-sm text-center"><b>CENTROS DE COSTO</b></h4>
            </div>
        </div>   
        <div class="row">
            <div class="col-xs-12">
                <p class="text-center msj_respuesta" ></p>
            </div>
        </div>          
        <!-- Capa de registros -->
        <div id="capaRegistros">
            <!-- DataTables -->
            <table id="dtCentrosCosto" class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>C&oacute;digo</th>
                    <th>Descripci&oacute;n</th>
                    <th>Nivel</th>
                    <th>Fecha Creaci&oacute;n</th>
                    <th class="boton"></th>
                    <th class="boton"></th>
                </tr>
                </thead>
                <tfoot>
                <tr class="active">
                    <th>ID</th>
                    <th>C&oacute;digo</th>
                    <th>Descripci&oacute;n</th>
                    <th>Nivel</th>
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
                        <input type="hidden" name="ccos_id" id="ccos_id" value="" />

                        <div class="form-group" id="nivel1_group">
                            <label for="ccos_nivel_1" class="control-label col-md-2 col-md-offset-1">Nivel 1:</label>
                            <div class="col-md-4">
                                <select name="ccos_nivel_1" id="ccos_nivel_1" class="form-control input-sm" title="Niveles 1 C.C">                                    
                                    <?php obtieneNiveles1CC($conexion); ?>
                                </select>                                
                            </div>                
                        </div>

                        <div class="form-group" id="nivel2_group">
                            <label for="ccos_nivel_2" class="control-label col-md-2 col-md-offset-1">Nivel 2:</label>
                            <div class="col-md-4">
                                <select name="ccos_nivel_2" id="ccos_nivel_2" class="form-control input-sm" title="Niveles 2 C.C">
                                    <option value="" title="">-- Vacio para nuevo --</option>
                                </select>                                
                            </div>                
                        </div>
                                          
                        <div class="form-group">
                            <label for="ccos_codigo" class="control-label col-md-2 col-md-offset-1">C&oacute;digo:</label>
                            <div class="col-md-4">
                                <input type="text" name="ccos_codigo" id="ccos_codigo" class="form-control input-sm" required minlength="8" maxlength="8" style="text-transform:uppercase" placeholder="XX-XX-XX" title="C&oacute;digo"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="ccos_descripcion" class="control-label col-md-2 col-md-offset-1">Descripci&oacute;n:</label>
                            <div class="col-md-9">
                                <input type="text" name="ccos_descripcion" id="ccos_descripcion" class="form-control input-sm" required minlength="3" maxlength="60" placeholder="Descripci&oacute;n" title="Descripci&oacute;n"/>
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
                        <input type="hidden" name="ccos_id" id="delete_ccos_id" value="" />
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
        var table = $("#dtCentrosCosto").DataTable({            
            "destroy": true,
            "responsive": true,
            //"processing": true,
            "order": [], //The rows are shown in the order they are read by DataTables            
            "ajax":{
                method: "GET",
                url: "crudCentrosCosto.php?opcion=leer"
            },
            "columnDefs":[
                 {
                     targets: [0],
                     visible: false
                 },
                {
                    targets: [3],
                    className: "text-right"                    
                },                 
                {
                    targets: [4],
                    className: "text-center",
                    render: $.fn.dataTable.render.moment( "YYYY-MM-DD HH:mm:ss", "DD-MM-YYYY", "es" )
                }
            ],
            "columns":[
                {"data":"ccos_id"},
                {"data":"ccos_codigo"},
                {"data":"ccos_descripcion"},
                {"data":"ccos_nivel"},
                {
                    "data": "ccos_creacion",
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
        
        $('#dtCentrosCosto').find('tbody').off('click');
        
        obtener_datos("#dtCentrosCosto tbody", table);
        obtener_id("#dtCentrosCosto tbody", table);
    }

    var re = /^(?:[A-Z0-9]{2})([-])(?:[A-Z0-9]{2})\1(?:[A-Z0-9]{2})$/;
    function testCodCC(codigo){
        var result = re.test(codigo);
        
        return result;
    }
        
    var crear_editar = function(){
        $("#formCrearEditar").on("submit", function(e){
            e.preventDefault();         
            var opcion=$("#opcion").val();
            var codigo = $("#ccos_codigo");
            var codVal = codigo.val();
            var codNivel2 = codVal.substring(0, 5);
            var nivel_1 = $("#ccos_nivel_1").val();
            var nivel_2 = $("#ccos_nivel_2").val();
            
            codUpper = codVal.toString().toUpperCase();
            
            var resultExpReg = testCodCC(codUpper);
            
            if(!resultExpReg)
            {
                alert("El formato del c\xf3digo ingresado es incorrecto o incompleto !!");
            }
            else if( (nivel_1 != '' || nivel_2 != '' ) && (codNivel2 !== nivel_2) )
            {
                alert("El c\xf3digo ingresado es distinto a lo(s) seleccionado(s) !!");
            }            
            else
            {
                //Si formato está ok,
                //pasa nuevo val en Mayú
                codigo.val(codUpper);
                var codVal = codigo.val();
                
                $.get("traeDatosMantenedores.php", {buscar: "nivelRaiz", codigo: codVal}, function(data){
                    
                    if( data.nivel0 == "0" ){
                        alert(data.msj);
                    }else if( data.nivel1 == "nulo" ){
                        alert(data.msj);
                    }else if( data.nivel2 == "nulo" ){
                        alert(data.msj);
                    }else if( data.result == "ok" ){
                        var formData = $("#formCrearEditar").serialize();
                        if( $("#opcion").val() == "editar" ){
                           
                        }
                        var form = $("#formCrearEditar");
                        form.validate();
                        if(form.valid()==true){
                            $.ajax({
                                method: "POST",              
                                url: "crudCentrosCosto.php",       
                                data: formData
                            }).done(function( resp ){                                 
                                mostrar_msj( resp );
                                limpiar_form(opcion);
                                if( resp == 'ok' ){
                                    listar();
                                    $("#ccos_nivel_1").load("traeDatosMantenedores.php?buscar=nivel1");
                                }
                                                  
                            });
                        }
                    }
                }, "json"); //Muy importante poner 'json'
            }
        });        
    }
    
    var eliminar = function(){
        $("#btnModalEliminar").on("click", function(){            
            var formData = $("#frmEliminar").serialize();            
            $.ajax({
                method: "POST",
                url: "crudCentrosCosto.php",
                data: formData
            }).done(function( resp ){
                mostrar_msjeli( resp );                
                limpiar_form("eliminar");
                
                if( resp == 'ok' ){
                    listar();
                }
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
        if( ! $("#ccos_codigo").prop("readonly") ){
            $('#ccos_codigo').focus();
        }else{
            $("#ccos_descripcion").focus();
        }
    });

    $(".btn-cancelar").on("click", function(){
        $("#opcion").val("");
        $("#ccos_id").val("");
        $("#ccos_codigo").val("");
        $("#registro").text("");
        $("#delete_ccos_id").val("");
        $("#ccos_descripcion").val("");
        $("#ccos_nivel_1").val("").trigger("change");
        $("#ccos_nivel_2").val("").trigger("change");  
        $("#formCrearEditar").validate().resetForm();      
    });
    
    var cambiarTextosModal = function( accion ){
        var nivel1_group = $("#nivel1_group");
        var nivel2_group = $("#nivel2_group");
            
        if( accion == 'editar' ){
            nivel1_group.hide();
            nivel2_group.hide();
            $("#ccos_codigo").prop("readonly", true);
            $("#titleModalCrearEditar").html("<b>Editar</b>");
            $("#btnModalCrearEditar").text("Guardar Cambios");
        }else if( accion == 'crear' ){
            nivel1_group.show();
            nivel2_group.show();
            $("#ccos_codigo").prop("readonly", false);
            $("#titleModalCrearEditar").html("<b>Crear Nuevo</b>");
            $("#btnModalCrearEditar").text("Guardar Nuevo");            
        }        
    }
    
    var limpiar_form = function( opcion ){
        if( opcion == 'eliminar' ){            
            $("#delete_ccos_id").val("");
            $("#registro").text("");  
        }else if(opcion=='editar'){
         //$("#ccos_descripcion").val("");
        }else{
            //Necesario para persistir modal
            $("#opcion").val("insertar");
            $("#ccos_id").val("");
            $("#ccos_codigo").val("");
            $("#ccos_descripcion").val("");
            $("#ccos_nivel_1").val("").trigger("change");
            $("#ccos_nivel_2").val("").trigger("change");
        }        
    }
       
    var obtener_datos = function(tbody, table){
        $(tbody).on("click", "button.editar", function(){            
            $("#opcion").val("editar");
            cambiarTextosModal('editar');
            $("div.has-error").removeClass("has-error");
            var data = table.row( $(this).parents("tr") ).data();
            $("#ccos_id").val( data.ccos_id );
            $("#ccos_codigo").val( data.ccos_codigo );
            $("#ccos_descripcion").val( data.ccos_descripcion );            
        });        
    }
    
    var obtener_id = function(tbody, table){
        $(tbody).on("click", "button.eliminar", function(){
            var data = table.row( $(this).parents("tr") ).data();
            var ccos_id = $("#delete_ccos_id").val( data.ccos_id ),
            ccos_codigo = $("#registro").text( data.ccos_codigo );
        });        
    }
    
    var mostrar_msj = function( respuesta ){
        var msj = $("#msj_respuesta");
        var texto = "<b>Resultado:</b> ";
        var alertClass = "";
        //console.log(respuesta);
        if( respuesta == 'ok' ){
            texto += "La operación se a realizado correctamente.";
            alertClass = "alert alert-success";
            $("#btnModalCrearEditar").prop("disabled",true);
            $(".btn-cancelar").prop("disabled",true);
            $("div.has-error").removeClass("has-error");
              msj.html(texto).addClass(alertClass);
              msj.fadeOut(5000, function(){
              msj.html("").removeClass(alertClass).fadeIn(3000);
        });
       if (!$('#frmEliminar').is(':visible')){ 
               //console.log('el elemento está visible') 
                esconderModal();
        }else{
            var msj = $(".msj_respuesta");
            $("#btnModalCrearEditar").prop("disabled",false);
            $(".btn-cancelar").prop("disabled",false);
        }
        }else if( respuesta == 'error' ){            
            texto += "No a sido posible ejecutar la consulta.";
            alertClass = "alert alert-danger";
        }else if( respuesta == 'existe' ){  
            $("div.has-error").removeClass("has-error");          
            texto += "El registro que intenta ingresar ya existe.";
            alertClass = "alert alert-warning"; 
              msj.html(texto).addClass(alertClass);
              msj.fadeOut(7000, function(){
              msj.html("").removeClass(alertClass).fadeIn(3000);
        });
        }else if(JSON.parse(respuesta)){
            var opcion= $("#titleModalCrearEditar").text();
        if (opcion=='Editar'){
            $("#opcion").val("editar");
        }else{
            $("#opcion").val("insertar");
        }
            var arrayjs=JSON.parse(respuesta);
            texto +="<ul>";
            $("div.has-error").removeClass("has-error");
             for (var i=0;i < arrayjs.invali.length;i++){
                 str=arrayjs.invali[i];
                 var str ='';
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

            texto += "<li>"+ str.replace("ccos","")+"</li>";        
            }
            texto +="</ul>"; 
            alertClass = "alert alert-danger";
              msj.html(texto).addClass(alertClass);
              msj.fadeOut(8000, function(){
              msj.html("").removeClass(alertClass).fadeIn(3000);
            });
            }else{
            // disponible para otras respuestas
            texto += respuesta;
            alertClass = "alert alert-warning";
            msj.html(texto).addClass(alertClass);
            msj.fadeOut(7000, function(){
            msj.html("").removeClass(alertClass).fadeIn(3000);
            });
            }
            }    

    $("#ccos_nivel_1").on("change", function(){
        var nivel1 = $(this).val();
        
        if( nivel1 == '' ){
            $("#ccos_codigo").val('');
        }else{
            $("#ccos_codigo").val(nivel1 + '-');            
        }        
        
        $(this).prop("disabled", true);        
        $("#ccos_nivel_2").load("traeDatosMantenedores.php?buscar=nivel2&nivel1="+nivel1);
        $(this).prop("disabled", false);
    });
    
    $("#ccos_nivel_2").on("change", function(){
        var nivel2 = $(this).val();
        
        if( nivel2 == '' ){
            $("#ccos_codigo").val('');
        }else{            
            $("#ccos_codigo").val(nivel2 + '-');            
        }
    });

    $("#formCrearEditar").validate({
         submitHandler: function(form){
         }
     });

    var mostrar_msjeli = function( respuesta ){
        var msj = $(".msj_respuesta");
        var texto = "<b>Resultado:</b> ";
        var alertClass = "";
        
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
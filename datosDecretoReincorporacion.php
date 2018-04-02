<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Datos Decreto DE REINCORPORACIÓN</title>
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
                <h4 class="well well-sm text-center"><b>DATOS DECRETO DE REINCORPORACIÓN</b></h4>
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
            <table id="dtDatosDecreto" class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>T&iacute;tulo</th>
                    <th>Direcci&oacute;n</th>
                    <th>Secretario</th>
                    <th>Por Orden</th>
                    <th>Firma 1</th>
                    <th>Alcalde</th>
                    <th>Por Orden</th>
                    <th>Firma 2</th>
                    <th>Iniciales</th>
                    <th>Creaci&oacute;n</th>
                    <th class="boton"></th>
                    <th class="boton"></th>
                </tr>
                </thead>
                <tfoot>
                <tr class="active">
                    <th>ID</th>
                    <th>T&iacute;tulo</th>
                    <th>Direcci&oacute;n</th>
                    <th>Secretario</th>
                    <th>Por Orden</th>
                    <th>Firma 1</th>
                    <th>Alcalde</th>
                    <th>Por Orden</th>
                    <th>Firma 2</th>
                    <th>Iniciales</th>
                    <th>Creaci&oacute;n</th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>
            </table> <!-- /DataTables -->

            <!-- Modal Crear & Editar -->
            <div class="modal fade" id="modalCrearEditar" tabindex="-1" role="dialog" aria-labelledby="myCrearEditarModalLabel" data-backdrop="static" data-keyboard="false">
            <form method="post" enctype="multipart/form-data" id="formCrearEditar"  class="form-horizontal" role="form">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                       <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title" id="titleModalCrearEditar"></h4>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="opcion" id="opcion" value="" />
                        <input type="hidden" name="derei_id" id="derei_id" value="" />
                        <div class="form-group">
                            <label for="derei_nombreOrg" class="control-label col-md-3 col-md-offset-1">Nombre:</label>
                            <div class="col-md-6">
                                <input type="text" name="derei_nombreOrg" id="derei_nombreOrg" class="form-control input-sm" required minlength="5" maxlength="60" placeholder="Nombre" title="Nombre"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="derei_nombreDireccion" class="control-label col-md-3 col-md-offset-1">Direcci&oacute;n:</label>
                            <div class="col-md-6">
                                <input type="text" name="derei_nombreDireccion" id="derei_nombreDireccion" class="form-control input-sm" minlength="5" maxlength="100" placeholder="Direcci&oacute;n" title="direcci&oacute;n"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="derei_nombreSecre" class="control-label col-md-3 col-md-offset-1">Secretario:</label>
                            <div class="col-md-6">
                                <input type="text" name="derei_nombreSecre" id="derei_nombreSecre" class="form-control input-sm" required minlength="5" maxlength="60" placeholder="Secretario" title="Secretario"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="derei_porOrdenFirma1" class="control-label col-md-3 col-md-offset-1">Por Orden de:</label>
                            <div class="col-md-6">
                                <input type="text" name="derei_porOrdenFirma1" id="derei_porOrdenFirma1" class="form-control input-sm" minlength="3" maxlength="60" placeholder="Por Orden Firma 1" title="Por Orden Firma 1"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="derei_cargoFirma1" class="control-label col-md-3 col-md-offset-1">Cargo Firma 1:</label>
                            <div class="col-md-6">
                                <input type="text" name="derei_cargoFirma1" id="derei_cargoFirma1" class="form-control input-sm" minlength="3" maxlength="60" placeholder="Cargo Firma 1" title="Cargo Firma 1"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="derei_nombreAlcalde" class="control-label col-md-3 col-md-offset-1">Alcalde:</label>
                            <div class="col-md-6">
                                <input type="Text" name="derei_nombreAlcalde" id="derei_nombreAlcalde" class="form-control input-sm" required minlength="5" maxlength="60" placeholder="Alcalde" title="Email"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="derei_porOrdenFirma2" class="control-label col-md-3 col-md-offset-1">Por Orden de:</label>
                            <div class="col-md-6">
                                <input type="text" name="derei_porOrdenFirma2" id="derei_porOrdenFirma2" class="form-control input-sm" minlength="3" maxlength="60" placeholder="Por Orden Firma 2" title="Por Orden Firma 2"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="derei_cargoFirma2" class="control-label col-md-3 col-md-offset-1">Cargo Firma 2:</label>
                            <div class="col-md-6">
                                <input type="text" name="derei_cargoFirma2" id="derei_cargoFirma2" class="form-control input-sm" minlength="3" maxlength="60" placeholder="Cargo Firma 2" title="Cargo Firma 2"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="derei_iniciales" class="control-label col-md-3 col-md-offset-1">Iniciales:</label>
                            <div class="col-md-6">
                                <input type="text" name="derei_iniciales" id="derei_iniciales" class="form-control input-sm" minlength="3" maxlength="20" placeholder="Iniciales" title="Iniciales"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="logo" class="control-label col-md-3 col-md-offset-1">Logo:</label>
                           <div class="col-md-6">
                                <input type="file" name="imagen" id="file_url" class="form-control input-sm" title="Seleccione archivo"/>
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
                        &iquest;Seguro desea eliminar del registro de: <strong id="registro"></strong>?

                        <input type="hidden" name="opcion" id="opcionEliminar" value="eliminar" />
                        <input type="hidden" name="derei_id" id="delete_derei_id" value="" />
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
        var table = $("#dtDatosDecreto").DataTable({
            "destroy": true,
            "responsive": true,
            //"processing": true,
            "order": [], //The rows are shown in the order they are read by DataTables
            "ajax":{
                method: "GET",
                url: "crudDatosDecretoReincorporacion.php?opcion=leer"
            },
            "columnDefs":[
                 {
                     targets: [0],
                     visible: false
                 },
                {
                    targets: 10,
                    className: "text-center",
                    render: $.fn.dataTable.render.moment( "YYYY-MM-DD HH:mm:ss", "DD-MM-YYYY", "es" )
                }
            ],
            "columns":[
                {"data":"derei_id"},
                {"data":"derei_nombreOrg"},
                {"data":"derei_nombreDireccion"},
				{"data":"derei_nombreSecre"},
                {"data":"derei_porOrdenFirma1"},
                {"data":"derei_cargoFirma1"},
                {"data":"derei_nombreAlcalde"},
                {"data":"derei_porOrdenFirma2"},
                {"data":"derei_cargoFirma2"},
                {"data":"derei_iniciales"},
                {"data":"derei_creacion",
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
                        $("#campo").append("");
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

        $('#dtDatosDecreto').find('tbody').off('click');

        obtener_datos("#dtDatosDecreto tbody", table);
        obtener_id("#dtDatosDecreto tbody", table);
    }

    var crear_editar = function(){
        $("#formCrearEditar").on("submit", function(e){
            e.preventDefault();
            var opcion=$("#opcion").val();
            var form = $("#formCrearEditar");
            form.validate();
            var formData = new FormData(document.getElementById("formCrearEditar"));
            //var formData = $(this).serialize();
            if(form.valid()==true){
                $.ajax({
                    method: "POST",
                    url: "crudDatosDecretoReincorporacion.php",
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
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
            $.ajax({
                method: "POST",
                url: "crudDatosDecretoReincorporacion.php",
                data: formData
            }).done(function( resp ){
                mostrar_msj( resp );
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
        }, 5500);
    }

    $('#modalCrearEditar').on('shown.bs.modal', function(){
        if( ! $("#derei_nombreOrg").prop("readonly") ){
            $('#derei_nombreOrg').focus();
        }else{
            $("#derei_nombreOrg").focus();
        }
    });

    $(".btn-cancelar").on("click", function(){
        $("#opcion").val("");
        $("#registro").text("");
        $("#delete_derei_id").val("");
        $("#derei_id").val("");
        $("#derei_nombreOrg").val("");
        $("#derei_nombreDireccion").val("");
		$("#derei_nombreSecre").val("");
        $("#derei_porOrdenFirma1").val("");
        $("#derei_cargoFirma1").val("");
		$("#derei_nombreAlcalde").val("");
        $("#derei_porOrdenFirma2").val("");
        $("#derei_cargoFirma2").val("");
        $("#derei_iniciales").val("");
        $("#formCrearEditar").validate().resetForm();
    });

    var cambiarTextosModal = function( accion ){
        if( accion == 'editar' ){
            $("#derei_nombreorg").prop("readonly", true);
            $("#titleModalCrearEditar").html("<b>Editar</b>");
            $("#btnModalCrearEditar").text("Guardar Cambios");
        }else if( accion == 'crear' ){
            $("#derei_nombreOrg").prop("readonly", false);
            $("#titleModalCrearEditar").html("<b>Crear Nuevo</b>");
            $("#btnModalCrearEditar").text("Guardar Nuevo");
        }
    }

    var limpiar_form = function( opcion ){
        if( opcion == 'eliminar' ){
            $("#delete_derei_id").val("");
            $("#registro").text("");
        }else{
        $("#derei_id").val("");
        $("#derei_nombreOrg").val("");
        $("#derei_nombreDireccion").val("");
        $("#derei_nombreSecre").val("");
        $("#derei_porOrdenFirma1").val("");
        $("#derei_cargoFirma1").val("");
        $("#derei_nombreAlcalde").val("");
        $("#derei_porOrdenFirma2").val("");
        $("#derei_cargoFirma2").val("");
        $("#derei_iniciales").val("");
        }

        $("#opcion").val("");
    }

    var obtener_datos = function(tbody, table){
        $(tbody).on("click", "button.editar", function(){
            $("#opcion").val("editar");
            cambiarTextosModal('editar');
            $("div.has-error").removeClass("has-error");
            var data = table.row( $(this).parents("tr") ).data();
            $("#derei_id").val( data.derei_id );
            $("#derei_nombreOrg").val( data.derei_nombreOrg);
			$("#derei_nombreDireccion").val( data.derei_nombreDireccion);
			$("#derei_nombreSecre").val( data.derei_nombreSecre);
            $("#derei_porOrdenFirma1").val(data.derei_porOrdenFirma1);
            $("#derei_cargoFirma1").val(data.derei_cargoFirma1);
			$("#derei_nombreAlcalde").val( data.derei_nombreAlcalde);
            $("#derei_porOrdenFirma2").val(data.derei_porOrdenFirma2);
            $("#derei_cargoFirma2").val(data.derei_cargoFirma2);
            $("#derei_iniciales").val(data.derei_iniciales);
        });
    }

    var obtener_id = function(tbody, table){
        $(tbody).on("click", "button.eliminar", function(){
            var data = table.row( $(this).parents("tr") ).data();
            var derei_id= $("#delete_derei_id").val( data.derei_id ),
                derei_nombreOrg = $("#registro").text( data.derei_nombreOrg );
        });
    }

    var mostrar_msj = function( respuesta ){
        var msj = $("#msj_respuesta");
        var texto = "<b>Resultado:</b> ";
        var alertClass = "";
        var opcion= $("#titleModalCrearEditar").text();
        console.log(respuesta);
        if( respuesta == 'ok' ){
            $("#btnModalCrearEditar").prop("disabled",true);
            $(".btn-cancelar").prop("disabled",true);
            $("div.has-error").removeClass("has-error");
            texto += "La operaci�n se a realizado correctamente.";
            alertClass = "alert alert-success";
        if (!$('#frmEliminar').is(':visible')){
                esconderModal();
        }else{
          var msj = $(".msj_respuesta");
          $("#btnModalCrearEditar").prop("disabled",false);
          $(".btn-cancelar").prop("disabled",false);
        }
        msj.html(texto).addClass(alertClass);
            msj.fadeOut(5000, function(){
            msj.html("").removeClass(alertClass).fadeIn(3000);
        });
        }else if( respuesta == 'error' ){
            texto += "No a sido posible ejecutar la consulta.";
            alertClass = "alert alert-danger";
            msj.html(texto).addClass(alertClass);
            msj.fadeOut(5000, function(){
            msj.html("").removeClass(alertClass).fadeIn(3000);
        });
        }else if( respuesta == 'existe' ){
            $("div.has-error").removeClass("has-error");
            texto += "El registro que intenta ingresar ya existe.";
            alertClass = "alert alert-warning";
            msj.html(texto).addClass(alertClass);
            msj.fadeOut(7000, function(){
            msj.html("").removeClass(alertClass).fadeIn(3000);
        });
        if (opcion=='Editar'){
            $("#opcion").val("editar");
        }else{
            $("#opcion").val("insertar");
        }
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
                 texto += "<li>"+ str.replace("deba","")+"</li>";
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
     $("#formCrearEditar").validate({
         submitHandler: function(form){
         }
     });
</script>
  </body>
</html>

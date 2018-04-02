<?php
require_once 'config.php';
require_once 'traeDatosMantenedores.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Analiticos Cuentas</title>
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
                <h4 class="well well-sm text-center"><b>Analiticos Cuentas</b></h4>
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
            <table id="dtAnaliticoCuenta" class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>C&oacute;digo</th>
                    <th>Descripci&oacute;n</th>  
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
                        <input type="hidden" name="anac_id" id="anac_id" value="" />

                        <div class="form-group" id="nivel1_group">
                            <label for="coda_nivel_1" class="control-label col-md-2 col-md-offset-1">Nivel 1:</label>
                            <div class="col-md-4">
                                <select name="coda_nivel_1" id="coda_nivel_1" class="form-control input-sm" title="Niveles 1">                                    
                                    <?php obtieneNiveles1Coda($conexion); ?> 
                                </select>                                
                            </div>                
                        </div>

                        <div class="form-group" id="nivel2_group">
                            <label for="coda_nivel_2" class="control-label col-md-2 col-md-offset-1">Nivel 2:</label>
                            <div class="col-md-4">
                                <select name="coda_nivel_2" id="coda_nivel_2" class="form-control input-sm" title="Niveles 2">
                                    <option value="" title="">-- Seleccione --</option>
                                </select>                                
                            </div>                
                        </div>

                        <div class="form-group" id="nivel3_group">
                            <label for="coda_nivel_3" class="control-label col-md-2 col-md-offset-1">Nivel 3:</label>
                            <div class="col-md-4">
                                <select name="coda_nivel_3" id="coda_nivel_3" class="form-control input-sm" title="Niveles 3">
                                    <option value="" title="">-- Seleccione --</option>
                                </select>                                
                            </div>                
                        </div>

                        <div class="form-group" id="nivel4_group">
                            <label for="coda_nivel_4" class="control-label col-md-2 col-md-offset-1">Nivel 4:</label>
                            <div class="col-md-4">
                                <select name="coda_nivel_4" id="coda_nivel_4" class="form-control input-sm" title="Niveles 4">
                                    <option value="" title="">-- Seleccione --</option>
                                </select>                                
                            </div>                
                        </div>

                        <div class="form-group" id="nivel5_group">
                            <label for="coda_nivel_5" class="control-label col-md-2 col-md-offset-1">Nivel 5:</label>
                            <div class="col-md-4">
                                <select name="coda_nivel_5" id="coda_nivel_5" class="form-control input-sm" title="Niveles 5">
                                    <option value="" title="">-- Seleccione --</option>
                                </select>                                
                            </div>                
                        </div>
			
                        <div class="form-group">
                            <label for="coda_codigo" class="control-label col-md-2 col-md-offset-1">C&oacute;digo:</label>
                            <div class="col-md-4">
                                <input type="text" name="coda_codigo" id="coda_codigo" class="form-control input-sm" required minlength="8" readonlymaxlength="8" style="text-transform:uppercase" placeholder="XXXXXX" title="C&oacute;digo" />
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="anac_descripcion" class="control-label col-md-2 col-md-offset-1">Descripci&oacute;n:</label>
                            <div class="col-md-9">
                                <input type="text" name="anac_descripcion" id="anac_descripcion" class="form-control input-sm" required minlength="3" maxlength="60" placeholder="Descripci&oacute;n" title="Descripci&oacute;n"/>
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
                        &#191;Seguro desea eliminar el registro c&oacute;digo: <strong id="registro"></strong>?
                        
                        <input type="hidden" name="opcion" id="opcionEliminar" value="eliminar" />
                        <input type="hidden" name="anac_id" id="delete_anac_id" value="" />
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
        var table = $("#dtAnaliticoCuenta").DataTable({            
            "destroy": true,
            "responsive": true,
            //"processing": true,
            "order": [], //The rows are shown in the order they are read by DataTables            
            "ajax":{
                method: "GET",
                url: "crudAnaliticoCuentas.php?opcion=leer"
            },
            "columnDefs":[
                 {
                     targets: [0],
                     visible: false
                 },
				                 {
                    targets: [1],
                    className: "text-center"                    
                }, 
                {
                    targets: [2],
                    className: "text-left"                    
                },                 
                {
                    targets: [3],
                    className: "text-center",
                    render: $.fn.dataTable.render.moment( "YYYY-MM-DD HH:mm:ss", "DD-MM-YYYY", "es" )
                }
            ],
            "columns":[
                {"data":"anac_id"},
                {"data":"coda_codigo"},
                {"data":"anac_descripcion"},
                {
                    "data": "anac_creacion",
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
        
        $('#dtAnaliticoCuenta').find('tbody').off('click');
        
        obtener_datos("#dtAnaliticoCuenta tbody", table);
        obtener_id("#dtAnaliticoCuenta tbody", table);
    }

    var re = /^(?:[A-Z0-9]{2})([-])(?:[A-Z0-9]{2})\1(?:[A-Z0-9]{2})$/;

        
    var crear_editar = function(){
        $("#formCrearEditar").on("submit", function(e){
            e.preventDefault();         
            var opcion=$("#opcion").val();
            var codigo = $("#coda_codigo");
            var codVal = codigo.val();
            var codNivel2 = codVal.substring(0, 5);
            var nivel_1 = $("#coda_nivel_1").val();
            var nivel_2 = $("#coda_nivel_2").val();
			var nivel_3 = $("#coda_nivel_3").val();
            var nivel_4 = $("#coda_nivel_4").val();
            var nivel_5 = $("#coda_nivel_5").val();
			
            codUpper = codVal.toString().toUpperCase();
            
            //var resultExpReg = testCodCC(codUpper);
            
            if( nivel_5 == '' && opcion == 'insertar')
            {
                alert("El formato del c\xf3digo ingresado esta incompleto !!");
            }            
            else
            {

                        var formData = $("#formCrearEditar").serialize();

						
                        var form = $("#formCrearEditar");
                        form.validate();
                        if(form.valid()==true){
                            $.ajax({
                                method: "POST",              
                                url: "crudAnaliticoCuentas.php",       
                                data: formData
                            }).done(function( resp ){                                 
                                mostrar_msj( resp );
                                limpiar_form(opcion);
                                if( resp == 'ok' ){
                                    listar();
                                    //$("#coda_nivel_1").load("traeDatosMantenedores.php?buscar=codaNiveles&nivel="+"2"+"&codigo="+nivel1);
                                }
                                                  
                            });
                        }
                    
            }
        });        
    }
    
    var eliminar = function(){
        $("#btnModalEliminar").on("click", function(){            
            var formData = $("#frmEliminar").serialize();            
            $.ajax({
                method: "POST",
                url: "crudAnaliticoCuentas.php",
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
        if( ! $("#coda_codigo").prop("readonly") ){
            $('#coda_codigo').focus();
        }else{
            $("#anac_descripcion").focus();
        }
    });

    $(".btn-cancelar").on("click", function(){
        $("#opcion").val("");
        $("#anac_id").val("");
        $("#coda_codigo").val("");
        $("#registro").text("");
        $("#delete_anac_id").val("");
        $("#anac_descripcion").val("");
        $("#coda_nivel_1").val("").trigger("change");
        $("#coda_nivel_2").val("").trigger("change");  
		$("#coda_nivel_3").val("").trigger("change");
        $("#coda_nivel_4").val("").trigger("change"); 
		$("#coda_nivel_5").val("").trigger("change");
				
        $("#formCrearEditar").validate().resetForm();      
    });
    
    var cambiarTextosModal = function( accion ){
        var nivel1_group = $("#nivel1_group");
        var nivel2_group = $("#nivel2_group");
		var nivel3_group = $("#nivel3_group");
		var nivel4_group = $("#nivel4_group");
		var nivel5_group = $("#nivel5_group");
		
            
        if( accion == 'editar' ){
            nivel1_group.hide();
            nivel2_group.hide();
			nivel3_group.hide();
			nivel4_group.hide();
			nivel5_group.hide();
            $("#coda_codigo").prop("readonly", true);
            $("#titleModalCrearEditar").html("<b>Editar</b>");
            $("#btnModalCrearEditar").text("Guardar Cambios");
        }else if( accion == 'crear' ){
            nivel1_group.show();
            nivel2_group.show();
			nivel3_group.show();
			nivel4_group.show();
			nivel5_group.show();
            $("#coda_codigo").prop("readonly", true);
            $("#titleModalCrearEditar").html("<b>Crear Nuevo</b>");
            $("#btnModalCrearEditar").text("Guardar Nuevo");            
        }        
    }
    
    var limpiar_form = function( opcion ){
        if( opcion == 'eliminar' ){            
            $("#delete_anac_id").val("");
            $("#registro").text("");  
        }else if(opcion=='editar'){
         //$("#anac_descripcion").val("");
        }else{
            //Necesario para persistir modal
            $("#opcion").val("insertar");
            $("#anac_id").val("");
            $("#coda_codigo").val("");
            $("#anac_descripcion").val("");
            $("#coda_nivel_1").val("");
            $("#coda_nivel_1").val("").trigger("change");
            $("#coda_nivel_2").val("").trigger("change");
			$("#coda_nivel_3").val("").trigger("change");
            $("#coda_nivel_4").val("").trigger("change");
			$("#coda_nivel_5").val("").trigger("change");
            
        }        
    }
       
    var obtener_datos = function(tbody, table){
        $(tbody).on("click", "button.editar", function(){            
            $("#opcion").val("editar");
            cambiarTextosModal('editar');
            $("div.has-error").removeClass("has-error");
            var data = table.row( $(this).parents("tr") ).data();
            $("#anac_id").val( data.anac_id );
            $("#coda_codigo").val( data.coda_codigo );
            $("#anac_descripcion").val( data.anac_descripcion );            
        });        
    }
    
    var obtener_id = function(tbody, table){
        $(tbody).on("click", "button.eliminar", function(){
            var data = table.row( $(this).parents("tr") ).data();
            var anac_id = $("#delete_anac_id").val( data.anac_id ),
            coda_codigo = $("#registro").text( data.coda_codigo );
        });        
    }
    
    var mostrar_msj = function( respuesta ){
        var msj = $("#msj_respuesta");
        var texto = "<b>Resultado:</b> ";
        var alertClass = "";
        //console.log(respuesta);
        if( respuesta == 'ok' ){
            texto += "La operaci&oacute;n se a realizado correctamente.";
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

            texto += "<li>"+ str.replace("anac","")+"</li>";        
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

    $("#coda_nivel_1").on("change", function(){
        var nivel1 = $(this).val();
        
        if( nivel1 == '' ){
            $("#coda_codigo").val('');
			$("#coda_nivel_2").val('');
			$("#coda_nivel_2").val("").trigger("change"); 
        }else{
            $("#coda_codigo").val(nivel1);            
        }        
        
        $(this).prop("disabled", true);        
        $("#coda_nivel_2").load("traeDatosMantenedores.php?buscar=codaNiveles&nivel="+"2"+"&codigo="+nivel1);
        $(this).prop("disabled", false);
    });
    
	
    $("#coda_nivel_2").on("change", function(){
        var nivel2 = $(this).val();
        
        if( nivel2 == '' ){
            $("#coda_codigo").val('');
			$("#coda_nivel_3").val('');
			$("#coda_nivel_3").val("").trigger("change");
        }else{
            $("#coda_codigo").val(nivel2);            
        }        
        
        $(this).prop("disabled", true);        
        $("#coda_nivel_3").load("traeDatosMantenedores.php?buscar=codaNiveles&nivel="+"3"+"&codigo="+nivel2);
        $(this).prop("disabled", false);
    });
	
	$("#coda_nivel_3").on("change", function(){
        var nivel3 = $(this).val();
        
        if( nivel3 == '' ){
            $("#coda_codigo").val('');
			$("#coda_nivel_4").val('');
			$("#coda_nivel_4").val("").trigger("change"); 
        }else{
            $("#coda_codigo").val(nivel3);            
        }        
        
        $(this).prop("disabled", true);        
        $("#coda_nivel_4").load("traeDatosMantenedores.php?buscar=codaNiveles&nivel="+"4"+"&codigo="+nivel3);
        $(this).prop("disabled", false);
    });
	
	$("#coda_nivel_4").on("change", function(){
        var nivel4 = $(this).val();
        
        if( nivel4 == '' ){
            $("#coda_codigo").val('');
			$("#coda_nivel_5").val('');
			$("#coda_nivel_5").val("").trigger("change"); 
        }else{
            $("#coda_codigo").val(nivel4);            
        }        
        
        $(this).prop("disabled", true);        
        $("#coda_nivel_5").load("traeDatosMantenedores.php?buscar=codaNiveles&nivel="+"5"+"&codigo="+nivel4);
        $(this).prop("disabled", false);
    });
	
	$("#coda_nivel_5").on("change", function(){
        var nivel5 = $(this).val();
        
        if( nivel5 == '' ){
            $("#coda_codigo").val('');
        }else{
            $("#coda_codigo").val(nivel5);            
        }        
    });
	
    /*$("#coda_nivel_2").on("change", function(){
        var nivel2 = $(this).val();
        
        if( nivel2 == '' ){
            $("#coda_codigo").val('');
        }else{            
            $("#coda_codigo").val(nivel2 + '-');            
        }
    });*/

    $("#formCrearEditar").validate({
         submitHandler: function(form){
         }
     });

    var mostrar_msjeli = function( respuesta ){
        var msj = $(".msj_respuesta");
        var texto = "<b>Resultado:</b> ";
        var alertClass = "";
        
        if( respuesta == 'ok' ){
            texto += "La operaci&oacute;n se a realizado correctamente.";
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
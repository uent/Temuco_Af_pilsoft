<?php
require_once 'config.php';
require_once 'traeDatosMantenedoresUbicaciones.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Ubicaciones</title>
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
                <h4 class="well well-sm text-center"><b>UBICACIONES</b></h4>
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
            <table id="dtUbicaciones" class="table table-bordered table-condensed table-striped table-hover">
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
                        <input type="hidden" name="ubi_id" id="ubi_id" value="" />
						
						<div class="form-group" id="nivel0_group">
                            <label for="aneg_nivel_0" class="control-label col-md-2 col-md-offset-1">Unidad:</label>
                            <div class="col-md-4">
                                <select name="aneg_nivel_0" id="aneg_nivel_0" class="form-control input-sm" title="Niveles 0 Ubicaciones">                                    
                                    <?php obtieneNivel0AreaNegocios($conexion); ?>
                                </select>                                
                            </div>                
                        </div>
						
                        <div class="form-group" id="nivel1_group">
                            <label for="ubi_nivel_1" class="control-label col-md-2 col-md-offset-1">Direcci&oacute;n:</label>
                            <div class="col-md-4">
                                <select name="ubi_nivel_1" id="ubi_nivel_1" class="form-control input-sm" title="Niveles 1 Ubicaciones">                                    
                                    <?php obtieneNiveles1Ubicaciones($conexion); ?>
                                </select>                                
                            </div>                
                        </div>

                        <div class="form-group" id="nivel2_group">
                            <label for="ubi_nivel_2" class="control-label col-md-2 col-md-offset-1">Depto:</label>
                            <div class="col-md-4">
                                <select name="ubi_nivel_2" id="ubi_nivel_2" class="form-control input-sm" title="Niveles 2 Ubicaciones">
                                    <option value="" title="">-- Vacio para nuevo --</option>
                                </select>                                
                            </div>                
                        </div>
                                          
                        <div class="form-group">
                            <label for="ubi_codigo" class="control-label col-md-2 col-md-offset-1">Oficina:</label>
                            <div class="col-md-4">
                                <input type="text" name="ubi_codigo" id="ubi_codigo" class="form-control input-sm" required minlength="8" maxlength="8" style="text-transform:uppercase" placeholder="XX-XX-XX" title="C&oacute;digo"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="ubi_descripcion" class="control-label col-md-2 col-md-offset-1">Descripci&oacute;n:</label>
                            <div class="col-md-9">
                                <input type="text" name="ubi_descripcion" id="ubi_descripcion" class="form-control input-sm" required minlength="3" maxlength="60" placeholder="Descripci&oacute;n" title="Descripci&oacute;n"/>
                            </div>                                            
                        </div>
                        
						<div class="form-group" id="responsable_group">
                            <label for="resp_id" class="control-label col-md-2 col-md-offset-1">Responsable:</label>
                            <div class="col-md-4">
                                <select name="resp_id" id="resp_id" class="form-control input-sm" title="Responsable">
                                    <option value="" title="">-- Vacio para nuevo --</option>
                                </select>                                
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
                        <input type="hidden" name="ubi_id" id="delete_ubi_id" value="" />
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
		
		$("#responsable_group").hide();
		$("#nivel0_group").hide();
    });
     
    var listar = function(){        
        var table = $("#dtUbicaciones").DataTable({            
            "destroy": true,
            "ubionsive": true,
            //"processing": true,
            "order": [], //The rows are shown in the order they are read by DataTables            
            "ajax":{
                method: "GET",
                url: "crudUbicaciones.php?opcion=leer"
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
                {"data":"ubi_id"},
                {"data":"ubi_codigo"},
                {"data":"ubi_descripcion"},
                {"data":"ubi_nivel"},
                {
                    "data": "ubi_creacion",
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
        
        $('#dtUbicaciones').find('tbody').off('click');
        obtener_datos("#dtUbicaciones tbody", table);
        obtener_id("#dtUbicaciones tbody", table);
    }

    var re = /^(?:[A-Z0-9]{2})([-])(?:[A-Z0-9]{2})\1(?:[A-Z0-9]{2})$/;
    function testCodUbicaciones(codigo){
        var result = re.test(codigo);
        
        return result;
    }
    
    var crear_editar = function(){
        $("#formCrearEditar").on("submit", function(e){
            e.preventDefault(); 
            var opcion=$("#opcion").val();
            var codigo = $("#ubi_codigo");
            var codVal = codigo.val();
            var codNivel2 = codVal.substring(0, 5);
			var nivel_0 = $("#aneg_nivel_0").val();
            var nivel_1 = $("#ubi_nivel_1").val();
            var nivel_2 = $("#ubi_nivel_2").val();
			var responsable = $("#resp_id").val();
			
			var penultimoDigito = codVal.substring(6, 7);
			var ultimoDigito = codVal.substring(7, 8);
            
			//console.log(penultimoDigito);
            codUpper = codVal.toString().toUpperCase();
            
            var resultExpReg = testCodUbicaciones(codUpper);
            
            if(!resultExpReg )
            {
                alert("El formato del c\xf3digo ingresado es incorrecto o incompleto !!");
            }
			/*|| ((nivel_1 != '' || nivel_2 != '') && (penultimoDigito == 0 && ultimoDigito == 0)) */
			/*else if((nivel_1 == '' || nivel_2 == '') && (penultimoDigito != 0 && ultimoDigito != 0))
            {
                alert("El formato del c\xf3digo ingresado es incorrecto o incompleto !!");
            }
            else if( (nivel_1 != '' || nivel_2 != '' ) && (codNivel2 !== nivel_2) )
            {
                alert("El c\xf3digo ingresado es distinto a lo(s) seleccionado(s) !!");
            }            
            else if(nivel_0 == '' && (nivel_1 != '' || nivel_2 != '' ))
			{
				alert("Debe seleccionar una unidad !!");
			}
			else if(responsable == '' && (nivel_1 != '' || nivel_2 != '' ))
			{
				alert("Debe seleccionar un responsable !!");
			}*/
			else if( !(penultimoDigito == 0 && ultimoDigito == 0) && nivel_0 == '')
			{
				alert("Debe seleccionar una unidad !!");
			}
			else if( !(penultimoDigito == 0 && ultimoDigito == 0) && responsable == '')
			{
				alert("Debe seleccionar una responsable !!");
			}
			else			
            {
                //Si formato está ok,
                //pasa nuevo val en Mayú
                codigo.val(codUpper);
                var codVal = codigo.val();
                
                $.get("traeDatosMantenedoresUbicaciones.php", {buscar: "nivelRaiz", codigo: codVal, aneg_id: nivel_0}, function(data){
                    
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
                                url: "crudUbicaciones.php",       
                                data: formData
                            }).done(function( ubi ){                                  
                                mostrar_msj( ubi );
                                limpiar_form(opcion);          
                                if( ubi == 'ok' ){
                                    listar();
                                    $("#ubi_nivel_1").load("traeDatosMantenedoresUbicaciones.php?buscar=nivel1");
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
                url: "crudUbicaciones.php",
                data: formData
            }).done(function( ubi ){
                mostrar_msjeli( ubi );                
                limpiar_form("eliminar");
                
                if( ubi == 'ok' ){
                    listar();
					$("#ubi_nivel_1").load("traeDatosMantenedoresUbicaciones.php?buscar=nivel1");
                }
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
        if( ! $("#ubi_codigo").prop("readonly") ){
            $('#ubi_codigo').focus();
        }else{
            $("#ubi_descripcion").focus();
        }
    });

    $(".btn-cancelar").on("click", function(){
        $("#opcion").val("");
        $("#ubi_id").val("");
        $("#ubi_codigo").val("");
        $("#registro").text("");
        $("#delete_ubi_id").val("");
        $("#ubi_descripcion").val("");
		$("#aneg_nivel_0").val("");
		$("#nivel0_group").hide();
        $("#ubi_nivel_1").val("").trigger("change");
        $("#ubi_nivel_2").val("").trigger("change"); 
        $("#formCrearEditar").validate().resetForm();       
    });
    
    var cambiarTextosModal = function( accion ){
		var nivel0_group = $("#nivel0_group");
        var nivel1_group = $("#nivel1_group");
        var nivel2_group = $("#nivel2_group");
            
        if( accion == 'editar' ){
			nivel1_group.hide();
            nivel2_group.hide();
			nivel0_group.hide();

            $("#ubi_codigo").prop("readonly", true);
            $("#titleModalCrearEditar").html("<b>Editar</b>");
            $("#btnModalCrearEditar").text("Guardar Cambios");
        }else if( accion == 'crear' ){
            nivel1_group.show();
            nivel2_group.show();

            $("#ubi_codigo").prop("readonly", false);
            $("#titleModalCrearEditar").html("<b>Crear Nuevo</b>");
            $("#btnModalCrearEditar").text("Guardar Nuevo");            
        }        
    }
    
    var limpiar_form = function( opcion ){
        if( opcion == 'eliminar' ){            
            $("#delete_ubi_id").val("");
            $("#registro").text(""); 
        }else if(opcion=='editar'){ 
        //$("#ubi_descripcion").val("");           
        }else{
            //Necesario para persistir modal
            $("#opcion").val("insertar");
            $("#ubi_id").val("");
            $("#ubi_codigo").val("");
            $("#ubi_descripcion").val("");
			$("#aneg_nivel_0").val("");
			$("#nivel0_group").hide();
            $("#ubi_nivel_1").val("").trigger("change");
            $("#ubi_nivel_2").val("").trigger("change");
			$("#resp_id").val("").trigger("change");
			$("#resp_id").removeAttr("required");
			$("#aneg_id").removeAttr("required");
			$("#responsable_group").hide(); 
        }        
    }
       
    var obtener_datos = function(tbody, table){
        $(tbody).on("click", "button.editar", function(){            
            $("#opcion").val("editar");
            cambiarTextosModal('editar');
            $("div.has-error").removeClass("has-error");
            var data = table.row( $(this).parents("tr") ).data();
            $("#ubi_id").val( data.ubi_id );
            $("#ubi_codigo").val( data.ubi_codigo );
            $("#ubi_descripcion").val( data.ubi_descripcion );  
			$("#resp_id").val(data.resp_id).trigger("change");    
			var niveles = $("#ubi_codigo").val();
			$("#nivel0_group").hide();	
			var nivel = niveles.split("-");    
			if( nivel[2] == 0 || nivel[2] == '' || typeof nivel[2] === 'undefined' ){
				$("#resp_id").removeAttr("required");
				
				$("#nivel0_group").hide(); 
				$("#responsable_group").hide(); 
			}else{            
				//$("#nivel0_group").fadeIn();
				//$("#responsable_group").fadeIn();				
				$("#resp_id").prop("required", true);   
				$("#aneg_id").prop("required", true);   				
			}
        });        
    }
    
    var obtener_id = function(tbody, table){
        $(tbody).on("click", "button.eliminar", function(){
            var data = table.row( $(this).parents("tr") ).data();
            var ubi_id = $("#delete_ubi_id").val( data.ubi_id ),
                ubi_codigo = $("#registro").text( data.ubi_codigo );
        });        
    }
    
    var mostrar_msj = function( respuesta ){
        var msj = $(".msj_respuesta, #msj_respuesta");
        var texto = "<b>Resultado:</b> ";
        var alertClass = "";
        var opcion= $("#titleModalCrearEditar").text();
        if( respuesta == 'ok' ){
            $("div.has-error").removeClass("has-error");
            texto += "La operaci&oacute;n se a realizado correctamente.";
            alertClass = "alert alert-success";
            if(!$('#frmEliminar').is(':visible')){ 
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
              
            if(opcion=='Editar'){
                $("#opcion").val("editar");
            }else{
                $("#opcion").val("insertar");
            }
             
        }else if(JSON.parse(respuesta)){
            //console.log(respuesta);
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
                 texto += "<li>"+ str.replace("ubi","")+"</li>";        
            }
            texto +="</ul>"; 
            alertClass = "alert alert-danger"; 
            msj.html(texto).addClass(alertClass);
            msj.fadeOut(5000, function(){
                msj.html("").removeClass(alertClass).fadeIn(3000);
            });                
        }else{
            // disponible para otras respuestas
            texto += respuesta;
            alertClass = "alert alert-warning";
        }
        msj.html(texto).addClass(alertClass);
        msj.fadeOut(12000, function(){
        msj.html("").removeClass(alertClass).fadeIn(3000);
        });
    }

    $("#ubi_nivel_1").on("change", function(){
        var nivel1 = $(this).val();
        
        if( nivel1 == '' ){
            $("#ubi_codigo").val('');
        }else{
            $("#ubi_codigo").val(nivel1 + '-');            
        }        
        
        $(this).prop("disabled", true);        
        $("#ubi_nivel_2").load("traeDatosMantenedoresUbicaciones.php?buscar=nivel2&nivel1="+nivel1);
        $(this).prop("disabled", false);
    });
    
    $("#ubi_nivel_2").on("change", function(){
        var nivel2 = $(this).val();
        
        if( nivel2 == '' ){
            $("#ubi_codigo").val('');
        }else{            
            $("#ubi_codigo").val(nivel2 + '-');            
        }
    });
	
    $("#ubi_codigo").on("keyup", function(){
        var niveles = $(this).val();
        var nivel = niveles.split("-");
		//alert (nivel[2]);
        if( nivel[2] == 0 || nivel[2] == '' || typeof nivel[2] === 'undefined' || nivel[2].toString().length != 2 ){
            $("#resp_id").removeAttr("required");
			$("#responsable_group").hide(); 
			$("#nivel0_group").hide(); 
			
        }else{
            $("#responsable_group").fadeIn();
			$("#nivel0_group").fadeIn();
			
			$("#resp_id").load("traeDatosMantenedoresUbicaciones.php?buscar=Responsables");
			$("#resp_id").prop("required", true); 
			$("#aneg_id").prop("required", true);        
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
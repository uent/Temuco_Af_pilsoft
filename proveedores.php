<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Proveedores</title>
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
        <?php 
        if( !isset($_GET['nomenu']) ){
            include_once 'menuInicio.php';
        }
        ?>
      
        <div class="row">
            <div class="col-xs-12">
                <h4 class="well well-sm text-center"><b>PROVEEDORES</b></h4>
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
            <table id="dtProveedores" class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>C&oacute;digo Auxiliar</th>
                    <th>RUT Proveedor</th>
                    <th>Raz&oacute;n Social</th>
                    <th>Descripci&oacute;n</th>
                    <th>Fecha Creaci&oacute;n</th>
                    <th>Estado</th>
                    <th class="boton"></th>
                    <th class="boton"></th>
                </tr>
                </thead>
                <tfoot>
                <tr class="active">
                    <th>ID</th>
                    <th>C&oacute;digo Auxiliar</th>
                    <th>RUT Proveedor</th>
                    <th>Raz&oacute;n Social</th>
                    <th>Descripci&oacute;n</th>
                    <th>Fecha Creaci&oacute;n</th>
                    <th>Estado</th>
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
                      <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title" id="titleModalCrearEditar"></h4>
                      </div>                  
                      <div class="modal-body">
                        <input type="hidden" name="opcion" id="opcion" value="" />
                        <input type="hidden" name="aux_id" id="aux_id" value="" />

                        <div class="form-group">
                            <label for="aux_codigo" class="control-label col-md-2 col-md-offset-1">C&oacute;digo Auxiliar:</label>
                            <div class="col-md-5">
                                <input type="text" name="aux_codigo" id="aux_codigo" class="form-control input-sm" required minlength="9" maxlength="10" placeholder="C&oacute;digo" title="C&oacute;digo"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="aux_rut" class="control-label col-md-2 col-md-offset-1">Rut Auxiliar:</label>
                            <div class="col-md-5">
                                <input type="text" name="aux_rut" id="aux_rut" class="form-control input-sm" required minlength="9" maxlength="10" placeholder="Rut" title="Rut"/>
                            </div>                
                        </div>
                                          
                        <div class="form-group">
                            <label for="aux_razonSocial" class="control-label col-md-2 col-md-offset-1">Raz&oacute;n Social:</label>
                            <div class="col-md-5">
                                <input type="text" name="aux_razonSocial" id="aux_razonSocial" class="form-control input-sm" required minlength="3" maxlength="100" placeholder="Raz&oacute;n Social" title="Raz&oacute;n Social"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="aux_descripcion" class="control-label col-md-2 col-md-offset-1">Descripci&oacute;n:</label>
                            <div class="col-md-9">
                                <input type="text" name="aux_descripcion" id="aux_descripcion" class="form-control input-sm" required minlength="3" maxlength="200" placeholder="Descripci&oacute;n" title="Descripci&oacute;n"/>
                            </div>                
                        </div>     
                        
                        <div class="form-group">
                            <label for="aux_direccion" class="control-label col-md-2 col-md-offset-1">Direcci&oacute;n:</label>
                            <div class="col-md-5">
                                <input type="text" name="aux_direccion" id="aux_direccion" class="form-control input-sm" required minlength="3" maxlength="255" placeholder="Direcci&oacute;n" title="Direcci&oacute;n"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="comu_id" class="control-label col-md-2 col-md-offset-1">Comuna:</label>
                            <div class="col-md-5">
                            	<select name="comu_id" id="comu_id" class="form-control input-sm" required>
                                    <option value="0">Seleccione...</option>
                                    <?php
									$sqlComunas = "SELECT DISTINCT * FROM comunas ORDER BY comu_descripcion ASC";
									$resultComunas = mysqli_query($conexion, $sqlComunas) or die(mysqli_error($conexion));
                                    while( $filaComunas = mysqli_fetch_array($resultComunas) ){
                                        echo "<option value='".$filaComunas["comu_id"]."'>". $filaComunas["comu_codigo"]." - ".$filaComunas["comu_descripcion"]."</option>";
                                    }
                                    ?>
                                </select>  
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="giro_id" class="control-label col-md-2 col-md-offset-1">Giro:</label>
                            <div class="col-md-5">
                            	<select name="giro_id" id="giro_id" class="form-control input-sm" required>
                                    <option value="0">Seleccione...</option>
                                    <?php
									$sqlGiros = "Select DISTINCT * FROM giros ORDER BY giro_descripcion ASC";
									$resultGiros = mysqli_query($conexion, $sqlGiros) or die(mysqli_error($conexion));
                                    while( $filaGiros = mysqli_fetch_array($resultGiros) ){
                                        echo "<option value='".$filaGiros["giro_id"]."'>".$filaGiros["giro_codigo"]." - ".$filaGiros["giro_descripcion"]."</option>";
                                    }
                                    ?>
                                </select>  
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="aux_email" class="control-label col-md-2 col-md-offset-1">Email:</label>
                            <div class="col-md-5">
                                <input type="email" name="aux_email" id="aux_email" class="form-control input-sm" required minlength="8" maxlength="100" email placeholder="Email" title="Email"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="aux_fonoFijo" class="control-label col-md-2 col-md-offset-1">Tel&eacute;fono Fijo Contacto:</label>
                            <div class="col-md-5">
                                <input type="text" name="aux_fonoFijo" id="aux_fonoFijo" class="form-control input-sm" maxlength="30" placeholder="Tel&eacute;fono Fijo Contacto" title="Tel&eacute;fono Fijo Contacto"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="aux_fonoMovil" class="control-label col-md-2 col-md-offset-1">Tel&eacute;fono M&oacute;vil Contacto:</label>
                            <div class="col-md-5">
                                <input type="text" name="aux_fonoMovil" id="aux_fonoMovil" class="form-control input-sm" maxlength="30" placeholder="Tel&eacute;fono M&oacute;vil Contacto" title="Tel&eacute;fono M&oacute;vil Contacto"/>
                            </div>                
                        </div>
                          
                        <div class="form-group">
                            <label for="aux_estado" class="control-label col-md-2 col-md-offset-1">Estado:</label>
                            <div class="col-md-1">
                                <input type="checkbox" name="aux_estado" id="aux_estado" class="form-control input-sm" checked="checked" value="1" alt="Estado" title="Estado"/>
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
                        ¿Seguro desea eliminar el registro de c&oacute;digo: <strong id="registro"></strong>?
                        
                        <input type="hidden" name="opcion" id="opcionEliminar" value="eliminar" />
                        <input type="hidden" name="aux_id" id="delete_aux_id" value="" />
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
        var table = $("#dtProveedores").DataTable({            
            "destroy": true,            
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "order": ([[3, 'asc']]), //The rows are shown in the order they are read by DataTables
            "ajax":{                
                url: "crudProveedores.php?opcion=leer",
                type: "POST"
            },
            "deferRender": true,
            "columnDefs":[
                 {
                     targets: [0],
                     visible: false
                 },
                {
                    targets: [5,6],
                    className: "text-center"
                }
            ],
            "columns":[
                {"data":"aux_id"},
                {"data":"aux_codigo"},
                {"data":"aux_rut"},
                {"data":"aux_razonSocial"},
                {"data":"aux_descripcion"},
                {
                    "data": "aux_creacion",
                    "searchable": false
                },    
				{"data":"aux_estado"},              
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
                        $("#aux_codigo").prop("readonly", false);
                        $("#aux_estado").prop("checked", true);
                        cambiarTextosModal("crear");                        
                    }
                },
                'excel',
                {
                    "extend": "pdf",
                    "orientation": "landscape",
                    "pageSize": "TABLOID"
                },
                {
                    "extend": "print",
                    "text": "Imprimir"
                }
            ],
            "lengthMenu": [ [ 5, 100, 500, 1000, 2500 ], [ '5', '100', '500', '1000', '2500' ] ]
        });        
        
        $('#dtProveedores').find('tbody').off('click');
        
        obtener_datos("#dtProveedores tbody", table);
        obtener_id("#dtProveedores tbody", table);
    }
    
    var crear_editar = function(){
        $("#formCrearEditar").on("submit", function(e){
            var opcion=$("#opcion").val();
            e.preventDefault();
             var form = $("#formCrearEditar");
             form.validate();
            var formData = $(this).serialize();
            if (form.valid()==true){
             $.ajax({
                 method: "POST",              
                 url: "crudProveedores.php",       
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
            $.ajax({
                method: "POST",
                url: "crudProveedores.php",
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
        }, 5500);
    }
    
    $('#modalCrearEditar').on('shown.bs.modal', function(){
        if( ! $("#aux_codigo").prop("readonly") ){
            $('#aux_codigo').focus();
        }else{
            $("#aux_razonSocial").focus();
        }
    });
    $(".btn-cancelar").on("click", function(){
        $("#opcion").val("");
        $("#aux_id").val("");
        $("#registro").text("");
        $("#delete_aux_id").val("");
        $("#formCrearEditar").validate().resetForm();
    });
    var cambiarTextosModal = function( accion ){
        if( accion == 'editar' ){
            $("#aux_codigo").prop("readonly", true);
            $("#aux_rut").prop("readonly", true);
            $("#titleModalCrearEditar").html("<b>Editar</b>");
            $("#btnModalCrearEditar").text("Guardar Cambios");
        }else if( accion == 'crear' ){
            $("#aux_codigo").prop("readonly", false);
            $("#aux_rut").prop("readonly", false);
            $("#titleModalCrearEditar").html("<b>Crear Nuevo</b>");
            $("#btnModalCrearEditar").text("Guardar Nuevo");            
        }        
    }
    var limpiar_form = function( opcion ){
        if( opcion == 'eliminar' ){            
            $("#delete_aux_id").val("");
            $("#registro").text("");
        }else if(opcion=='editar'){
            //$("#aux_codigo").val("");
           // $("#aux_razonSocial").val("");
            //$("#aux_descripcion").val("");
        }else{
            $("#aux_id").val("");
            $("#aux_rut").val("");
            $("#aux_codigo").val("");
            $("#aux_razonSocial").val("");
            $("#aux_descripcion").val("");
			$("#aux_direccion").val("");
			$("#comu_id").val(0).trigger('change');
			$("#giro_id").val(0).trigger('change');
			$("#aux_email").val("");
			$("#aux_fonoFijo").val("");
			$("#aux_fonoMovil").val("");
			$("#aux_estado").prop("checked", true);            
        }
        $("#opcion").val("");
    }  
    var obtener_datos = function(tbody, table){
        $(tbody).on("click", "button.editar", function(){            
            $("#opcion").val("editar");
            cambiarTextosModal('editar');  
            $("div.has-error").removeClass("has-error");                      
            var data = table.row( $(this).parents("tr") ).data();
            $("#aux_id").val( data.aux_id );
            $("#aux_codigo").val( data.aux_codigo );
			$("#aux_rut").val( data.aux_rut );
            $("#aux_razonSocial").val( data.aux_razonSocial );
            $("#aux_descripcion").val( data.aux_descripcion );
			$("#aux_direccion").val( data.aux_direccion );
			$("#comu_id").val( data.comu_id ).trigger('change');
			$("#giro_id").val( data.giro_id ).trigger('change');
			$("#aux_email").val( data.aux_email );
			$("#aux_fonoFijo").val( data.aux_fonoFijo );
			$("#aux_fonoMovil").val( data.aux_fonoMovil );
			if(data.aux_estado=="Activo"){
				$("#aux_estado").prop("checked", true);
			}else{
				$("#aux_estado").prop("checked", false);
			}            
        });        
    }
    
    var obtener_id = function(tbody, table){
        $(tbody).on("click", "button.eliminar", function(){
            var data = table.row( $(this).parents("tr") ).data();
            var aux_id = $("#delete_aux_id").val( data.aux_id ),
                aux_razonSocial = $("#registro").text( data.aux_razonSocial );
        });        
    }
    var mostrar_msj = function( respuesta ){
        var msj = $("#msj_respuesta");
        var texto = "<b>Resultado:</b> ";
        var alertClass = "";
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
        msj.fadeOut(7000, function(){
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
                 texto += "<li>"+ str.replace("aux","")+"</li>";        
         }
            texto +="</ul>"; 
            alertClass = "alert alert-danger";
             msj.html(texto).addClass(alertClass);
             msj.fadeOut(5000, function(){
             msj.html("").removeClass(alertClass).fadeIn(3000);
        });            
        }else{
            // disponible para otras respuestas desde crud.php
            texto += respuesta;
            alertClass = "alert alert-warning";
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
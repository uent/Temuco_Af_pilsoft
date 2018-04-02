<?php
require_once 'traeDatosActivo.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Decretos de Traslado Emitidos</title>
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
                <h4 class="well well-sm text-center"><b>DECRETOS DE TRASLADO EMITIDOS</b></h4>
            </div>
        </div>

        <!-- Capa de registros -->
        <div id="capaRegistros">
            <!-- DataTables -->
            <form id="listaDecretos">
			<input type="hidden" name="opcion" id="opcion" value="" />
            <table id="dtdecretosTraslado" class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
				
                    <th></th>
                    <th>ID</th>
                    <th>Nro Folio</th>
                    <th>Nombre Org</th>
                    <th>Direcci&oacute;n</th>
					<th>Acalde</th>
                    <th>Secretario</th>
					<th>Creaci&oacute;n</th>
					<th>&Uacute;ltima Modificaci&oacute;n</th>
					<th class="boton btn-editar"></th>
					<!--<th class="boton btn-eliminar"></th>-->		

					
                </tr>
                </thead>
                <tfoot>
                <tr class="active">
                    <th></th>
                    <th>ID</th>
                    <th>Nro Folio</th>
                    <th>Nombre Org</th>
                    <th>Direcci&oacute;n</th>
					<th>Acalde</th>
                    <th>Secretario</th>
					<th>Creaci&oacute;n</th>
					<th>Ultima Modificacion</th>
					<th class="boton btn-editar"></th>
					<!--<th class="boton btn-eliminar"></th>-->
					
					
                </tr>                
                </tfoot>                
            </table> <!-- /DataTables -->
            </form> <!-- /Form Checkbox -->
        </div> <!-- /Capa de registros -->      
    </div> <!-- /Container --> 
	
	            <!-- Modal Crear & Editar -->
            <div class="modal fade" id="modalCrearEditar" tabindex="-1" role="dialog" aria-labelledby="myCrearEditarModalLabel" data-backdrop="static" data-keyboard="false">
            <form method="post" enctype="multipart/form-data" id="formCrearEditar"  class="form-horizontal" role="form">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                       <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                        <h4 class="modal-title" id="titleModalCrearEditar"></h4>
                      </div>                  
                      <div class="modal-body">
                        <input type="hidden" name="opcionE" id="opcionE" value="" />
                        <input type="hidden" name="dadetr_id" id="dadetr_id" value="" />
						<input type="hidden" name="dadetr_descripVistos" id="dadetr_descripVistos" value="" />
						<input type="hidden" name="dadetr_descripDistri" id="dadetr_descripDistri" value="" />
						
                        <div class="form-group">
                            <label for="dadetr_nombreOrg" class="control-label col-md-2 ">Nombre organizaci&oacute;n:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadetr_nombreOrg" id="dadetr_nombreOrg" class="form-control input-sm" required minlength="5" maxlength="60" placeholder="Nombre Org" title="Nombre Org"/>
                            </div>                
                        </div>   
                        
                        <div class="form-group">
                            <label for="dadetr_nombreDireccion" class="control-label col-md-2 ">Direcci&oacute;n:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadetr_nombreDireccion" id="dadetr_nombreDireccion" class="form-control input-sm" required minlength="5" maxlength="100" placeholder="Direcci&oacute;n" title="direcci&oacute;n"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadetr_nombreSecre" class="control-label col-md-2 ">Secretario:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadetr_nombreSecre" id="dadetr_nombreSecre" class="form-control input-sm" required minlength="5" maxlength="60" placeholder="Secretario" title="Secretario"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadetr_porOrdenFirma1" class="control-label col-md-2 ">Por Orden de:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadetr_porOrdenFirma1" id="dadetr_porOrdenFirma1" class="form-control input-sm"minlength="3" maxlength="60" maxlength="60" placeholder="Por Orden Firma 1" title="Por Orden Firma 1"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadetr_cargoFirma1" class="control-label col-md-2 ">Cargo Firma 1:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadetr_cargoFirma1" id="dadetr_cargoFirma1" class="form-control input-sm" minlength="3" maxlength="60"  maxlength="60" placeholder="Cargo Firma 1" title="Cargo Firma 1"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadetr_nombreAlcalde" class="control-label col-md-2 ">Alcalde:</label>
                            <div class="col-md-10">
                                <input type="Text" name="dadetr_nombreAlcalde" id="dadetr_nombreAlcalde" class="form-control input-sm"  required minlength="5" maxlength="60"  placeholder="Alcalde" title="Email"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadetr_porOrdenFirma2" class="control-label col-md-2 ">Por Orden de:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadetr_porOrdenFirma2" id="dadetr_porOrdenFirma2" class="form-control input-sm" minlength="3" maxlength="60" maxlength="60" placeholder="Por Orden Firma 2" title="Por Orden Firma 2"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadetr_cargoFirma2" class="control-label col-md-2 ">Cargo Firma 2:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadetr_cargoFirma2" id="dadetr_cargoFirma2" class="form-control input-sm"  minlength="3" maxlength="60"  maxlength="60" placeholder="Cargo Firma 2" title="Cargo Firma 2"/>
                            </div>                
                        </div>
                                                
                        <div class="form-group">
                            <label for="dadetr_iniciales" class="control-label col-md-2 ">Iniciales:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadetr_iniciales" id="dadetr_iniciales" class="form-control input-sm" minlength="3" maxlength="20" placeholder="Iniciales" title="Iniciales"/>
                            </div>                
                        </div>
                        
                        <!--<div class="form-group">
                            <label for="logo" class="control-label col-md-2 ">Logo:</label>
                           <div class="col-md-10">
                                <input type="file" name="dadetr_rutaLogo" id="dadetr_rutaLogo" class="form-control input-sm" title="Seleccione archivo"/>     
                            </div> 
                        </div>-->
						
						
						
						<div class="form-group" name="divVistos" id="divVistos" >
							

						</div>
						
						<div class="form-group" name="div" id="divDistri" >
							

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
			
		<!-- Modal Eliminar AF -->
        <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="myEliminarModalLabel" data-backdrop="static" data-keyboard="false">
            <form id="frmEliminar" role="form">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                    <h4 class="modal-title" id="myEliminarModalLabel"><b>Eliminar</b></h4>
                  </div>
                  <div class="modal-body">
                    Seguro desea eliminar el decreto seleccionado: <span><b id="cod_act_eliminar"></b></span>?
                    <input type="hidden" name="dadetr_id" id="id_act_eliminar" value="" />
					<input type="hidden" name="opcionE" id="opcionE" value="eliminar" />
                  </div>
                  <div class="modal-footer">
                    <button type="button" id="btnCancelEliminar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnModalEliminar" class="btn btn-danger" data-dismiss="modal">Eliminar</button>
                  </div>
                </div>
            </div>
            </form>
        </div>  <!-- /Modal Eliminar AF -->
			
	
	
    <?php include_once 'tagsJS.php'; ?>
    <!-- Opción select DataTables -->
    <script src="js/dataTables.select.min.js"></script>
    <script type="text/javascript">
    $(document).on("ready", function(){            
        listar();
        modificarDecreto();
        eliminar();              
    });
	
	var mostrar_msj = function( respuesta ){
        var msj = $("#msj_respuesta");
        var texto = "<b>Resultado:</b> ";
        var alertClass = "";
        var opcion= $("#titleModalCrearEditar").text();
        //console.log(respuesta);
        if( respuesta == 'ok' ){
            $("#btnModalCrearEditar").prop("disabled",true);
            $(".btn-cancelar").prop("disabled",true);
            $("div.has-error").removeClass("has-error");
            texto += "La operaci&oacute;n se a realizado correctamente.";
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
                 texto += "<li>"+ str.replace("dadeba","")+"</li>";        
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
	
	
	var limpiar_form = function( opcion ){
        if( opcion == 'eliminar' ){            
            $("#delete_deba_id").val("");
            $("#registro").text(""); 
        }else{
			$("#dadetr_id").val("");
			$("#dadetr_nombreOrg").val("");
			$("#tras_folio").val("");
			$("#dadetr_nombreDireccion").val("");
			$("#dadetr_nombreSecre").val("");
			$("#dadetr_porOrdenFirma1").val("");
			$("#dadetr_cargoFirma1").val("");
			$("#dadetr_nombreAlcalde").val("");
			$("#dadetr_porOrdenFirma2").val("");
			$("#dadetr_cargoFirma2").val("");
			$("#dadetr_iniciales").val("");
			//$("#dadetr_creacion").val("");
			
			//$("#dadetr_rutaLogo").val("");
		
			var stringVistos = $('#dadetr_descripVistos').val();
			var stringVistos = stringVistos.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
			var flagPrimeroEncontrado = 0;	
			for (i=0;i < stringVistos.length + 1;i++)
			{
				$("#dadetr_descripVistos"+(i+1)).val("");
			}	
			
			var stringDistri = $('#dadetr_descripDistri').val();
			var stringDistri = stringDistri.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
			var flagPrimeroEncontrado = 0;	
			for (i=0;i < stringDistri.length + 1;i++)
			{
				$("#dadetr_descripDistri"+(i+1)).val("");
			}	
		
			$("#dadetr_descripDistri").val("");
			$("#dadetr_descripVistos").val("");
			
			
        }
       
        $("#opcion").val("");
    }
	
    var listar = function(){
		
        var table = $("#dtdecretosTraslado").DataTable({
				
            "destroy": true,            
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "order": ([[2, 'asc'], [3, 'asc']]), //The rows are shown in the order they are read by DataTables            
            "ajax":{
                url: "crudDecretoTrasladoEmitidos.php?opcion=leer",
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
                     className: "ID'"
                 },                 
                 {
                    targets: [8],
                    className: "text-center"
                 }
            ],
            "columns":[
                {
                    "data": null,
                    "defaultContent": ""
                },
                {"data":"dadetr_id"},
                {"data":"tras_folio"},
                {"data":"dadetr_nombreOrg"},
                {"data":"dadetr_nombreDireccion"},      
				{"data":"dadetr_nombreAlcalde"},
				{"data":"dadetr_nombreSecre"},
				{"data":"dadetr_creacion"},
				{"data":"dadetr_modificacion"},
                {
                    "defaultContent":"<button type='button' class='editar btn btn-primary btn-xs' data-toggle='modal' data-target='#modalCrearEditar' title='Editar'><span class='glyphicon glyphicon-pencil'></span></button>",
                    "sortable": false,
					className: "text-center",
                    "searchable": false
                },
                /*{
                    "defaultContent":"<button type='button' class='eliminar btn btn-danger btn-xs' title='Eliminar' data-toggle='modal' data-target='#modalEliminar'><span class='glyphicon glyphicon-trash'></span></button>",
                    "sortable": false,
					className: "text-center",
                    "searchable": false
                }*/				
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
                    "className": "btn btn-success",
                    "action": function(){
                        //var count = table.rows( { selected: true } ).count();
                        var datos = table.rows( {selected: true} ).data();
                        var total = datos.length;
                        var obj = {}; // Obj json
						
                        if( total == 1 ){
							
                            for( var i=0; i < total; i++ ){
								//console.log(datos[i].dadetr_id);
                                obj[i] = datos[i].dadetr_id;
                            }
							
                            
                            var resp = confirm("¿Estimado(a), a continuación se generará el Decreto de Traslado tomando en consideración los Activos Fijos previamente seleccionados.\n\n ¿Está seguro de realizar esta acción?\n\n Una vez emitido el Decreto no podrá modificarlo.");
                            
                            if( resp ){
                                popup('crear','ReGenerarInforme' , obj, table);
                            }else{
                                table.ajax.reload();
                                alert("Acción cancelada por el usuario.");
                            }                            
                        }else if(total == 0){
                            alert("Favor, debe seleccionar un decretos.");
                        }
						else{
							alert("Favor, debe seleccionar solo un decreto.");
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
						
                        if( total == 1 ){
							                        
							//obj["opcion2"] = "ReGenerarInforme";
                            
							for( var i=0; i < total; i++ ){
                                //alert(datos[i].dadetr_id);
                                obj[i] = datos[i].dadetr_id;
                            }
                            
							
                            popup('preview','ReGenerarInforme', obj, table);
                            
                        }else if(total == 0){
                            alert("Favor, debe seleccionar un decreto.");
                        }
						else{
							alert("Favor, debe seleccionar solo un decreto.");
						}
                    }
                }              
              
            ],
            "lengthMenu": [ [ 5, 100, 500, 1000, 2500 ], [ '5', '100', '500', '1000', '2500' ] ]
        });
		
		$('#dtdecretosTraslado').find('tbody').off('click');
        obtener_datos("#dtdecretosTraslado tbody", table);
        obtener_id("#dtdecretosTraslado tbody", table);
    }
    
	var obtener_id = function(tbody, table){
        $(tbody).on("click", "button.eliminar", function(){
            var data = table.row( $(this).parents("tr") ).data();
            var actID = $("#id_act_eliminar").val( data.dadetr_id),
                act_codigo = $("#cod_act_eliminar").text( data.tras_folio);
        });
    }
	
	var esconderModal = function(){
        setTimeout(function(){
            $("#modalCrearEditar").modal("toggle");
            $("#btnModalCrearEditar").prop("disabled",false);
            $(".btn-cancelar").prop("disabled",false); 
        }, 5500);
    }
	
    var modificarDecreto = function(){
        $("#formCrearEditar").on("submit", function(e){
            e.preventDefault();
			$("#opcionE").val("editar");
            var opcion=$("#opcionE").val();
            var formData = $(this);
			

			var stringVistos = $('#dadetr_descripVistos').val();
		
			var stringVistos = stringVistos.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
         
			var nuevoVistos = ""; 
			var flagPrimeroEncontrado = 0;	
		
			for (i=0;i < stringVistos.length + 1;i++)
			{
				var visto = $("#dadetr_descripVistos" + (i+1) ).val();
			
				if(visto != "")
				{
					if(flagPrimeroEncontrado != 0)
					{
						nuevoVistos += ".|";
					}
					//console.log(visto);
					nuevoVistos += visto;
					
					flagPrimeroEncontrado++;
				}	
			}
		
			//console.log(nuevoVistos);
			$("#dadetr_descripVistos").val(nuevoVistos);					   
						   
			var stringDistri = $('#dadetr_descripDistri').val();
		
			var stringDistri = stringDistri.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
         
			var nuevoDistri = ""; 
			var flagPrimeroEncontrado = 0;	
		
			for (i=0;i < stringDistri.length + 1;i++)
			{
				var distri = $("#dadetr_descripDistri" + (i+1) ).val();
			
				if(distri != "")
				{
					if(flagPrimeroEncontrado != 0)
					{
						nuevoDistri += ".|";
					}
					//console.log(distri);
					nuevoDistri += distri;
					
					flagPrimeroEncontrado++;
				}	
			}
		
			//console.log(nuevoDistri);
			$("#dadetr_descripDistri").val(nuevoDistri);					   
					
						   
			
			
			var formData = formData.serialize();
			
			//console.log(formData);
            var form = $("#formCrearEditar");

              
            form.validate();  
            if(form.valid()==true){                
                $.ajax({
                    method: "POST",              
                    url: "crudDecretoTrasladoEmitidos.php",       
                    dataType: "html",
                    data: formData,
                    cache: false,
                }).done(function( resp ){
                    limpiar_form(opcionE);
                    mostrar_msj( resp );
                    listar();                                  
                });
            }
        });
    }
	
	
	
	var obtener_datos = function(tbody, table){
		
        $(tbody).on("click", "button.editar", function(){            
            $("#opcion").val("editar");
            //cambiarTextosModal('editar');
            //$("#deba_nombreOrg").prop("readonly", true);            
            $("#titleModalCrearEditar").html("<b>Editar</b>");
            $("#btnModalCrearEditar").text("Guardar Cambios");
            $("div.has-error").removeClass("has-error");
            var data = table.row( $(this).parents("tr") ).data();
			
            $("#dadetr_id").val( data.dadetr_id );
            $("#dadetr_nombreOrg").val( data.dadetr_nombreOrg);
			$("#dadetr_nombreDireccion").val( data.dadetr_nombreDireccion);
			$("#dadetr_nombreSecre").val( data.dadetr_nombreSecre);
            $("#dadetr_porOrdenFirma1").val(data.dadetr_porOrdenFirma1);
            $("#dadetr_cargoFirma1").val(data.dadetr_cargoFirma1);
			$("#dadetr_nombreAlcalde").val( data.dadetr_nombreAlcalde);
            $("#dadetr_porOrdenFirma2").val(data.dadetr_porOrdenFirma2);
            $("#dadetr_cargoFirma2").val(data.dadetr_cargoFirma2);
            $("#dadetr_iniciales").val(data.dadetr_iniciales);
			//$("#dadetr_rutaLogo").val(data.dadetr_rutaLogo);
			
			$("#dadetr_descripVistos").val( data.dadetr_descripVistos );
			$("#dadetr_descripDistri").val(data.dadetr_descripDistri);
			
		

        });        
    }
	
    function popup(opc,opc2, json, table){
        var queryString = $.param(json);
				console.log(table);

        var url = 'decretoTraslado.php?opc='+opc+'&'+ 'opc2='+opc2+'&'+queryString;
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
    
    var eliminar = function(){
        $("#btnModalEliminar").on("click", function(){
            var frm = $("#frmEliminar").serialize();
            $.ajax({
                method: "POST",
                url: "crudDecretoTrasladoEmitidos.php",
                data: frm
            }).done(function( resp ){
                mostrar_msj( resp );
                $("#modalmensaje").modal("show");
                limpiarHiddens("eliminar");
                listar();
            });
        });
    }
	
	var limpiarHiddens = function( opcion ){
        if( opcion == "eliminar" ){
            $("#id_act_eliminar").val("");
            $("#cod_act_eliminar").text("");
        }
    }
	
    function parametros(mantenedor){
        var w = window.open(mantenedor, 'Parametros de Traslado', 'width='+screen.width+', height='+screen.height+', scrollbars=yes');
        w.moveTo(0, 0);
        w.focus();
    }
	
	$( "#modalCrearEditar" ).on('shown.bs.modal', function(){
    	
		$("#divVistos").children("#divInputVistos").remove();
		$("#divVistos").children("#labelVistos").remove();
		
		var stringVistos = $('#dadetr_descripVistos').val();
		
	
		var stringVistos = stringVistos.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
		
		var htmlVistos = 	"<label id='labelVistos' class='control-label col-md-2 '>Vistos:</label>"
							+ "<div id='divInputVistos' class='col-md-10'>" ;
                                
		//console.log(stringVistos[0]);
		for (i=0;i < stringVistos.length ;i++)
		{
			//console.log(i);
			htmlVistos += "<input type='text' name='dadetr_descripVistos" + (i+1) + "' id='dadetr_descripVistos" + (i+1) + "' value='" + stringVistos[i] + "' class='form-control input-sm' minlength='3' title='Vistos" + (i+1) + "' />"
		}
		
		htmlVistos += "<input type='text' name='dadetr_descripVistos" + (i+1) + "' id='dadetr_descripVistos" + (i+1) + "' class='form-control input-sm' minlength='3' title='Vistos" + (i+1) + "' />"
		
		+ " </div> ";

		$("#divVistos").append(htmlVistos);         
});
	

	$( "#modalCrearEditar" ).on('shown.bs.modal', function(){
    	
		$("#divDistri").children("#divInputDistri").remove();
		$("#divDistri").children("#labelDistri").remove();
		
		var stringDistri = $('#dadetr_descripDistri').val();
		
	
		var stringDistri = stringDistri.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
		
		var htmlDistri = 	"<label id='labelDistri' class='control-label col-md-2 '>Distribucion:</label>"
							+ "<div id='divInputDistri' class='col-md-10'>" ;
                                
		//console.log(stringDistri[0]);
		for (i=0;i < stringDistri.length ;i++)
		{
			//console.log(i);
			htmlDistri += "<input type='text' name='dadetr_descripDistri" + (i+1) + "' id='dadetr_descripDistri" + (i+1) + "' value='" + stringDistri[i] + "' class='form-control input-sm' minlength='3' title='Distri" + (i+1) + "' />"
		}
		
		htmlDistri += "<input type='text' name='dadetr_descripDistri" + (i+1) + "' id='dadetr_descripDistri" + (i+1) + "' class='form-control input-sm' minlength='3' title='Distri" + (i+1) + "' />"
		
		+ " </div> ";

		$("#divDistri").append(htmlDistri);         
});


	
	
</script>
  </body>
</html>
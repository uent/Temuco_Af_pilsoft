<?php
require_once 'traeDatosActivo.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Decretos de Baja Emitidos</title>
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
                <h4 class="well well-sm text-center"><b>DECRETOS DE BAJA EMITIDOS</b></h4>
            </div>
        </div>

        <!-- Capa de registros -->
        <div id="capaRegistros">
            <!-- DataTables -->
            <form id="listaDecretos">
			<input type="hidden" name="opcion" id="opcion" value="" />
            <table id="dtdecretosBaja" class="table table-bordered table-condensed table-striped table-hover">
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
					<th>Ultima Modificacion</th>
					<th class="boton btn-editar"></th>
					<th class="boton btn-eliminar"></th>			

					
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
					<th class="boton btn-eliminar"></th>
					
					
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
                        <input type="hidden" name="dadeba_id" id="dadeba_id" value="" />
						<input type="hidden" name="dadeba_descripVistos" id="dadeba_descripVistos" value="" />
						<input type="hidden" name="dadeba_descripDistri" id="dadeba_descripDistri" value="" />
						
                        <div class="form-group">
                            <label for="dadeba_nombreOrg" class="control-label col-md-2 ">Nombre organizaci&oacute;n:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadeba_nombreOrg" id="dadeba_nombreOrg" class="form-control input-sm" required minlength="5" maxlength="60" placeholder="Nombre Org" title="Nombre Org"/>
                            </div>                
                        </div>   
                        
                        <div class="form-group">
                            <label for="dadeba_nombreDireccion" class="control-label col-md-2 ">Direcci&oacute;n:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadeba_nombreDireccion" id="dadeba_nombreDireccion" class="form-control input-sm" required minlength="5" maxlength="100" placeholder="Direcci&oacute;n" title="direcci&oacute;n"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadeba_nombreSecre" class="control-label col-md-2 ">Secretario:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadeba_nombreSecre" id="dadeba_nombreSecre" class="form-control input-sm" required minlength="5" maxlength="60" placeholder="Secretario" title="Secretario"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadeba_porOrdenFirma1" class="control-label col-md-2 ">Por Orden de:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadeba_porOrdenFirma1" id="dadeba_porOrdenFirma1" class="form-control input-sm"minlength="3" maxlength="60" maxlength="60" placeholder="Por Orden Firma 1" title="Por Orden Firma 1"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadeba_cargoFirma1" class="control-label col-md-2 ">Cargo Firma 1:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadeba_cargoFirma1" id="dadeba_cargoFirma1" class="form-control input-sm" minlength="3" maxlength="60"  maxlength="60" placeholder="Cargo Firma 1" title="Cargo Firma 1"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadeba_nombreAlcalde" class="control-label col-md-2 ">Alcalde:</label>
                            <div class="col-md-10">
                                <input type="Text" name="dadeba_nombreAlcalde" id="dadeba_nombreAlcalde" class="form-control input-sm"  required minlength="5" maxlength="60"  placeholder="Alcalde" title="Email"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadeba_porOrdenFirma2" class="control-label col-md-2 ">Por Orden de:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadeba_porOrdenFirma2" id="dadeba_porOrdenFirma2" class="form-control input-sm" minlength="3" maxlength="60" maxlength="60" placeholder="Por Orden Firma 2" title="Por Orden Firma 2"/>
                            </div>                
                        </div>
                        
                        <div class="form-group">
                            <label for="dadeba_cargoFirma2" class="control-label col-md-2 ">Cargo Firma 2:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadeba_cargoFirma2" id="dadeba_cargoFirma2" class="form-control input-sm"  minlength="3" maxlength="60"  maxlength="60" placeholder="Cargo Firma 2" title="Cargo Firma 2"/>
                            </div>                
                        </div>
                                                
                        <div class="form-group">
                            <label for="dadeba_iniciales" class="control-label col-md-2 ">Iniciales:</label>
                            <div class="col-md-10">
                                <input type="text" name="dadeba_iniciales" id="dadeba_iniciales" class="form-control input-sm" minlength="3" maxlength="20" placeholder="Iniciales" title="Iniciales"/>
                            </div>                
                        </div>
                        
                        <!--<div class="form-group">
                            <label for="logo" class="control-label col-md-2 ">Logo:</label>
                           <div class="col-md-10">
                                <input type="file" name="dadeba_rutaLogo" id="dadeba_rutaLogo" class="form-control input-sm" title="Seleccione archivo"/>     
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
                    Seguro desea eliminar el decreto seleccionado: <span><b id="cod_decr_eliminar"></b></span>?
                    <input type="hidden" name="dadeba_id" id="id_decr_eliminar" value="" />
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
			$("#dadeba_id").val("");
			$("#dadeba_nombreOrg").val("");
			$("#baj_folio").val("");
			$("#dadeba_nombreDireccion").val("");
			$("#dadeba_nombreSecre").val("");
			$("#dadeba_porOrdenFirma1").val("");
			$("#dadeba_cargoFirma1").val("");
			$("#dadeba_nombreAlcalde").val("");
			$("#dadeba_porOrdenFirma2").val("");
			$("#dadeba_cargoFirma2").val("");
			$("#dadeba_iniciales").val("");
			//$("#dadeba_creacion").val("");
			
			//$("#dadeba_rutaLogo").val("");
		
			var stringVistos = $('#dadeba_descripVistos').val();
			var stringVistos = stringVistos.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
			var flagPrimeroEncontrado = 0;	
			for (i=0;i < stringVistos.length + 1;i++)
			{
				$("#dadeba_descripVistos"+(i+1)).val("");
			}	
			
			var stringDistri = $('#dadeba_descripDistri').val();
			var stringDistri = stringDistri.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
			var flagPrimeroEncontrado = 0;	
			for (i=0;i < stringDistri.length + 1;i++)
			{
				$("#dadeba_descripDistri"+(i+1)).val("");
			}	
		
			$("#dadeba_descripDistri").val("");
			$("#dadeba_descripVistos").val("");
			
			
        }
       
        $("#opcion").val("");
    }
	
    var listar = function(){
		
        var table = $("#dtdecretosBaja").DataTable({
				
            "destroy": true,            
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "order": ([[2, 'asc'], [3, 'asc']]), //The rows are shown in the order they are read by DataTables            
            "ajax":{
                url: "crudDecretoBajaEmitidos.php?opcion=leer",
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
                    targets: [8,9],
                    className: "text-center"
                 }
            ],
            "columns":[
                {
                    "data": null,
                    "defaultContent": ""
                },
                {"data":"dadeba_id"},
                {"data":"baj_folio"},
                {"data":"dadeba_nombreOrg"},
                {"data":"dadeba_nombreDireccion"},      
				{"data":"dadeba_nombreAlcalde"},
				{"data":"dadeba_nombreSecre"},
				{"data":"dadeba_creacion"},
				{"data":"dadeba_modificacion"},
                {
                    "defaultContent":"<button type='button' class='editar btn btn-primary btn-xs' data-toggle='modal' data-target='#modalCrearEditar' title='Editar'><span class='glyphicon glyphicon-pencil'></span></button>",
                    "sortable": false,
					className: "text-center",
                    "searchable": false
                },
                {
                    "defaultContent":"<button type='button' class='eliminar btn btn-danger btn-xs' title='Eliminar' data-toggle='modal' data-target='#modalEliminar'><span class='glyphicon glyphicon-trash'></span></button>",
                    "sortable": false,
					className: "text-center",
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
                    "className": "btn btn-success",
                    "action": function(){
                        //var count = table.rows( { selected: true } ).count();
                        var datos = table.rows( {selected: true} ).data();
                        var total = datos.length;
                        var obj = {}; // Obj json
						
                        if( total == 1 ){
							
                            for( var i=0; i < total; i++ ){
								//console.log(datos[i].dadeba_id);
                                obj[i] = datos[i].dadeba_id;
                            }
							
                            
                            var resp = confirm("¿Estimado(a), a continuación se generará el Decreto de Baja tomando en consideración los Activos Fijos previamente seleccionados.\n\n ¿Está seguro de realizar esta acción?\n\n Una vez emitido el Decreto no podrá modificarlo.");
                            
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
							                        
							obj["opcion2"] = "ReGenerarInforme";
                            
							for( var i=0; i < total; i++ ){
                                //alert(datos[i].dadeba_id);
                                obj[i] = datos[i].dadeba_id;
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
		
		$('#dtdecretosBaja').find('tbody').off('click');
        obtener_datos("#dtdecretosBaja tbody", table);
        obtener_id("#dtdecretosBaja tbody", table);
    }
    
	var obtener_id = function(tbody, table){
        $(tbody).on("click", "button.eliminar", function(){
            var data = table.row( $(this).parents("tr") ).data();
            var actID = $("#id_decr_eliminar").val( data.dadeba_id),
                act_codigo = $("#cod_decr_eliminar").text( data.baj_folio);
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
			

			var stringVistos = $('#dadeba_descripVistos').val();
		
			var stringVistos = stringVistos.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
         
			var nuevoVistos = ""; 
			var flagPrimeroEncontrado = 0;	
		
			for (i=0;i < stringVistos.length + 1;i++)
			{
				var visto = $("#dadeba_descripVistos" + (i+1) ).val();
			
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
			$("#dadeba_descripVistos").val(nuevoVistos);					   
						   
			var stringDistri = $('#dadeba_descripDistri').val();
		
			var stringDistri = stringDistri.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
         
			var nuevoDistri = ""; 
			var flagPrimeroEncontrado = 0;	
		
			for (i=0;i < stringDistri.length + 1;i++)
			{
				var distri = $("#dadeba_descripDistri" + (i+1) ).val();
			
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
			$("#dadeba_descripDistri").val(nuevoDistri);					   
					
						   
			
			
			var formData = formData.serialize();
			
			//console.log(formData);
            var form = $("#formCrearEditar");

              
            form.validate();  
            if(form.valid()==true){                
                $.ajax({
                    method: "POST",              
                    url: "crudDecretoBajaEmitidos.php",       
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
			
            $("#dadeba_id").val( data.dadeba_id );
            $("#dadeba_nombreOrg").val( data.dadeba_nombreOrg);
			$("#dadeba_nombreDireccion").val( data.dadeba_nombreDireccion);
			$("#dadeba_nombreSecre").val( data.dadeba_nombreSecre);
            $("#dadeba_porOrdenFirma1").val(data.dadeba_porOrdenFirma1);
            $("#dadeba_cargoFirma1").val(data.dadeba_cargoFirma1);
			$("#dadeba_nombreAlcalde").val( data.dadeba_nombreAlcalde);
            $("#dadeba_porOrdenFirma2").val(data.dadeba_porOrdenFirma2);
            $("#dadeba_cargoFirma2").val(data.dadeba_cargoFirma2);
            $("#dadeba_iniciales").val(data.dadeba_iniciales);
			//$("#dadeba_rutaLogo").val(data.dadeba_rutaLogo);
			
			$("#dadeba_descripVistos").val( data.dadeba_descripVistos );
			$("#dadeba_descripDistri").val(data.dadeba_descripDistri);
			
		

        });        
    }
	
    function popup(opc,opc2, json, table){
        var queryString = $.param(json);
				console.log(table);

        var url = 'decretoBaja.php?opc='+opc+'&'+ 'opc2='+opc2+'&'+queryString;
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
    
    var eliminar = function(){
        $("#btnModalEliminar").on("click", function(){
            var frm = $("#frmEliminar").serialize();
            $.ajax({
                method: "POST",
                url: "crudDecretoBajaEmitidos.php",
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
            $("#id_decr_eliminar").val("");
            $("#cod_decr_eliminar").text("");
        }
    }
	
    function parametros(mantenedor){
        var w = window.open(mantenedor, 'Parametros de Baja', 'width='+screen.width+', height='+screen.height+', scrollbars=yes');
        w.moveTo(0, 0);
        w.focus();
    }
	
	$( "#modalCrearEditar" ).on('shown.bs.modal', function(){
    	
		$("#divVistos").children("#divInputVistos").remove();
		$("#divVistos").children("#labelVistos").remove();
		
		var stringVistos = $('#dadeba_descripVistos').val();
		
	
		var stringVistos = stringVistos.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
		
		var htmlVistos = 	"<label id='labelVistos' class='control-label col-md-2 '>Vistos:</label>"
							+ "<div id='divInputVistos' class='col-md-10'>" ;
                                
		//console.log(stringVistos[0]);
		for (i=0;i < stringVistos.length ;i++)
		{
			//console.log(i);
			htmlVistos += "<input type='text' name='dadeba_descripVistos" + (i+1) + "' id='dadeba_descripVistos" + (i+1) + "' value='" + stringVistos[i] + "' class='form-control input-sm' minlength='3' title='Vistos" + (i+1) + "' />"
		}
		
		htmlVistos += "<input type='text' name='dadeba_descripVistos" + (i+1) + "' id='dadeba_descripVistos" + (i+1) + "' class='form-control input-sm' minlength='3' title='Vistos" + (i+1) + "' />"
		
		+ " </div> ";

		$("#divVistos").append(htmlVistos);         
});
	

	$( "#modalCrearEditar" ).on('shown.bs.modal', function(){
    	
		$("#divDistri").children("#divInputDistri").remove();
		$("#divDistri").children("#labelDistri").remove();
		
		var stringDistri = $('#dadeba_descripDistri').val();
		
	
		var stringDistri = stringDistri.split(".|"); //separa el string completo en distintas partas cortando donde esta el elemento .|
		
		var htmlDistri = 	"<label id='labelDistri' class='control-label col-md-2 '>Distribucion:</label>"
							+ "<div id='divInputDistri' class='col-md-10'>" ;
                                
		//console.log(stringDistri[0]);
		for (i=0;i < stringDistri.length ;i++)
		{
			//console.log(i);
			htmlDistri += "<input type='text' name='dadeba_descripDistri" + (i+1) + "' id='dadeba_descripDistri" + (i+1) + "' value='" + stringDistri[i] + "' class='form-control input-sm' minlength='3' title='Distri" + (i+1) + "' />"
		}
		
		htmlDistri += "<input type='text' name='dadeba_descripDistri" + (i+1) + "' id='dadeba_descripDistri" + (i+1) + "' class='form-control input-sm' minlength='3' title='Distri" + (i+1) + "' />"
		
		+ " </div> ";

		$("#divDistri").append(htmlDistri);         
});


	
	
</script>
  </body>
</html>
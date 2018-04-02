<?php
require_once 'traeDatosActivo.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Informe Param&eacute;trico - Activos Fijos</title>
    <?php include_once 'tagsMeta.php'; ?>
    <?php include_once 'tagsCSS.php'; ?>
    <?php include_once 'tagsFixJS.php'; ?>
  </head>
  <body>
    <div class="container">      
        <?php include_once 'menuInicio.php'; ?>
      
        <div class="row">
            <div class="col-sm-12">
                <h4 class="well well-sm text-center"><b>INFORME PARAM&Eacute;TRICO - ACTIVOS FIJOS</b></h4>
            </div>
        </div>       

        <!-- Capa Form -->
        <div id="capaForm" class="well well-sm">
            <form action="excelInforParamAF.php" method="post" id="formInfomeParamAF" class="form-horizontal" role="form">
                <input type="hidden" name="informe_excel" value="informe_param" />
                <fieldset>                        
                <div class="form-group">
                    <label for="fDesde" class="control-label col-sm-1 col-md-1 col-md-offset-3"><b>Desde:</b></label>
                    <div class="col-sm-2 col-md-2">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            <input type="text" class="form-control input-sm input-fecha" name="fechaIngresoDesde" id="fDesde" required placeholder="Fecha ingreso" title="Fecha Desde"/>
                        </div>
                    </div>
                    
                    <label for="fHasta" class="control-label col-sm-1 col-md-1"><b>Hasta:</b></label>
                    <div class="col-sm-2 col-md-2">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            <input type="text" class="form-control input-sm input-fecha" name="fechaIngresoHasta" id="fHasta" required placeholder="Fecha ingreso" title="Fecha Hasta"/>
                        </div>
                    </div>                    
                </div>
                
                <div class="form-group">     
	                

				
                    <label for="ubiNivel1" class="control-label col-sm-1 col-md-1 col-md-offset-1">Direcci&oacute;n:</label>
                    <div class="col-sm-2 col-md-2">
                        <select class="form-control input-sm" name="ubiNivel1" id="ubiNivel1" title="Direcci&oacute;n (Nivel 1)">
                            <option value="">-- Seleccione --</option>
                            <?php llenaOptions('ubicaciones', 'ubi_', $conexion); ?>                      
                        </select>                    
                    </div>
 	
                    <label for="ubiNivel2" class="control-label col-sm-1 col-md-1">Depto. :</label>
                    <div class="col-sm-3 col-md-3">
                        <select class="form-control input-sm" name="ubiNivel2" id="ubiNivel2" title="Departamento (Nivel 2)">
                            <option value="">-- Seleccione --</option>                      
                        </select>
                    </div>

					
                    <label for="ubiNivel3" class="control-label col-sm-1 col-md-1">Oficina:</label>
                    <div class="col-sm-2 col-md-2">
                        <select class="form-control input-sm" name="ubiNivel3" id="ubiNivel3" title="Oficina (Nivel 3)" >
                            <option value="">-- Seleccione --</option>                          
                        </select>                    
                    </div>                            
                </div>
                                
                <div class="form-group hide">
                    <label for="grup_id" class="control-label col-sm-1 col-md-1">Familia:</label>
                    <div class="col-sm-2 col-md-2">
                        <select class="form-control input-sm" name="grup_id" id="grup_id">
                            <option value="0">-- Seleccione --</option>
                            <?php llenaOptions('grupos', 'grup_', $conexion); ?>
                        </select>                    
                    </div>

                    <label for="sgru_id" class="control-label col-sm-1 col-md-1">Subfamilia:</label>
                    <div class="col-sm-2 col-md-2">
                        <select class="form-control input-sm" name="sgru_id" id="sgru_id">
                            <option value="0">-- Seleccione --</option>                        
                        </select>                    
                    </div>
                
                    <label for="cing_id" class="control-label col-sm-1 col-md-1">Concepto:</label>
                    <div class="col-sm-2 col-md-2">
                        <select class="form-control input-sm" name="cing_id" id="cing_id" title="Concepto">
                            <option value="">-- Seleccione --</option>
                            <?php llenaOptions('conceptos_ingreso', 'cing_', $conexion); ?>                        
                        </select>                    
                    </div>
                    
                    <label for="tdoc_id" class="control-label col-sm-1 col-md-1">Tipo Doc.:</label>
                    <div class="col-sm-2 col-md-2">
                        <select class="form-control input-sm" name="tdoc_id" id="tdoc_id">
                            <option value="0">-- Seleccione --</option>
                            <?php llenaOptions('tipo_documento', 'tdoc_', $conexion); ?>             
                        </select>                    
                    </div>                                                                            
                </div>                

                <div class="form-group">
				
                    <label for="aneg_id" class="control-label col-sm-1 col-md-1 col-md-offset-2">A. Negocio:</label>
                    <div class="col-sm-4 col-md-4">
                        <select class="form-control input-sm" name="aneg_id" id="aneg_id">
                            <option value="0">-- Seleccione --</option>
                            <?php llenaOptions('areas_negocios', 'aneg_', $conexion); ?>                         
                        </select>                    
                    </div>

                    <label for="act_codEstado" class="control-label col-sm-1 col-md-1">Estado AF:</label>
                    <div class="col-sm-2 col-md-2">
                        <select class="form-control input-sm" name="act_codEstado" id="act_codEstado">
                            <option value="">-- Seleccione --</option>
                            <option value="V">VIGENTE</option>
                            <option value="B">DE BAJA</option>
                        </select>                    
                    </div>                    
                </div>
                                
                <div class="row hide">
                    <div class="col-sm-2 col-md-2">
                    <fieldset>
                        <legend class="small">Tipo Control:</legend>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_tipoControl" id="act_tipoCtrlAF" value="Activo Fijo" checked="checked"/><span> Activo Fijo</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_tipoControl" id="act_tipoCtrlAdm" value="Ctrl Adm"/><span> Ctrl. Adm.</span>
                            </label>
                        </div>
                    </fieldset>
                    </div>
                                            
                    <div class="col-sm-2 col-md-2">
                    <fieldset>
                        <legend class="small">Revalorizable seg&uacute;n IPC:</legend>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_revalorizable" id="act_revalorizable_si" value="SI" checked="checked"/><span> SI</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_revalorizable" id="act_revalorizable_no" value="NO"/><span> NO</span>
                            </label>
                        </div>
                    </fieldset>
                    </div>
                    
                    <div class="col-sm-1 col-md-1">
                    <fieldset>
                        <legend class="small">Depreciable:</legend>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_depreciable" id="act_depreciable_si" value="SI" checked="checked"/><span> SI</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_depreciable" id="act_depreciable_no" value="NO"/><span> NO</span>
                            </label>
                        </div>
                    </fieldset>
                    </div>

                    <div class="col-sm-2 col-md-2">
                    <fieldset>
                        <legend class="small">Tipo de Depreciaci&oacute;n:</legend>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_tipoDepreciacion" id="act_tipoDepreciacion_l" value="L" checked="checked"/><span> Linieal</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_tipoDepreciacion" id="act_tipoDepreciacion_a" value="A"/><span> Acelerada</span>
                            </label>
                        </div>
                    </fieldset>
                    </div>

                    <div class="col-sm-2 col-md-2">
                    <fieldset>
                        <legend class="small">Unidad de medida Vida &Uacute;til:</legend>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_unidadMedidaVidaUtil" id="act_unidadMedidaVidaUtil_m" value="M" checked="checked"/><span> Mensual</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_unidadMedidaVidaUtil" id="act_unidadMedidaVidaUtil_p" value="P"/><span> Unidad de Prod.</span>
                            </label>
                        </div>
                    </fieldset>
                    </div>
                    
                    <div class="col-sm-1 col-md-1">
                    <fieldset>
                        <legend class="small">AF x Lote:</legend>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_porLote" id="act_porLote_no" value="NO" checked="checked"/><span> NO</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_porLote" id="act_porLote_si" value="SI"/><span> SI</span>
                            </label>
                        </div>
                    </fieldset>
                    </div>                    
                    
                    <div class="col-sm-2 col-md-2">
                    <fieldset>
                        <legend class="small">Bajo Norma(s):</legend>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_bajoNormaPublica" id="act_bajoNormaNicsp" value="Nicsp" checked="checked"/><span> NICSP</span>
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="act_bajoNormaPublica" id="act_bajoNormaPCGN" value="PCGN"/><span> PCGN</span>
                            </label>
                        </div>
                    </fieldset>
                    </div>                                                       
                </div>
                                                            
                <div class="form-group hide">
                    <label for="act_fechaAdquisicion" class="control-label col-sm-1 col-md-1 col-md-offset-1">Adquisici&oacute;n:</label>
                    <div class="col-sm-2 col-md-2">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            <input type="text" class="form-control input-sm input-fecha" name="act_fechaAdquisicion" id="act_fechaAdquisicion" minlength="10" maxlength="10" placeholder="Fecha compra" title="Fecha compra"/>
                        </div>
                    </div>
                    
                    <label for="act_inicioDepreciacion" class="control-label col-sm-1 col-md-1">Depreci&oacute;n:</label>
                    <div class="col-sm-2 col-md-2">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            <input type="text" class="form-control input-sm input-mesAnio" name="act_inicioDepreciacion" id="act_inicioDepreciacion" placeholder="Mes / A&ntilde;o"/>
                        </div>
                    </div>
                                  
                    <label for="act_inicioRevalorizacion" class="control-label col-sm-2 col-md-2">Revalorizai&oacute;n:</label>
                    <div class="col-sm-2 col-md-2">
                        <div class="input-group">
                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                            <input type="text" class="form-control input-sm input-mesAnio" name="act_inicioRevalorizacion" id="act_inicioRevalorizacion" placeholder="Mes / A&ntilde;o"/>
                        </div>
                    </div>                            
                </div>
                <hr />
                
                <div class="form-group">                    
                    <div class="col-sm-2 col-md-2 col-md-offset-8">
                        <button type="reset" id="btnResetearForm" class="form-control btn btn-default">
                            <span class="glyphicon glyphicon-refresh"></span> Resetear
                        </button>
                    </div>   
                    <div class="col-sm-2 col-md-2">                        
                        <button type="submit" id="btnGuardarForm" class="form-control btn btn-primary">
                            <span class="glyphicon glyphicon-save"></span> Generar Excel
                        </button>
                    </div>
                </div>                                                                         
                </fieldset>            
            </form>                        
        </div>
        <!-- /Fin Capa Form -->        
    </div> <!-- /container -->

    <?php include_once 'tagsJS.php'; ?>
    <script type="text/javascript">
    /*
    $(".input-fecha#fDesde").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: "1980:2030",
        minDate: "01-01-1980",
        maxDate: "31-12-2030"
    });

    $(".input-fecha#fHasta").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: "1980:2030",
        minDate: "01-01-1980",
        maxDate: "31-12-2030"
    });
    */

    $(document).on("ready", function(){
        var dateFormat = "dd-mm-yy";

        from = $( "#fDesde" )
        .datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            yearRange: "1980:2030",
        })
        .on( "change", function(){
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
        to = $( "#fHasta" ).datepicker({
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 2,
            yearRange: "1980:2030",
        })
        .on( "change", function(){
        from.datepicker( "option", "maxDate", getDate( this ) );
        });

        function getDate( element ){
            var date;
            try{
                date = $.datepicker.parseDate( dateFormat, element.value );
            }catch( error ){
                date = null;
            }

            return date;
        };

        $("#ubiNivel1").on("change", function(){
    var codUbiNv1 = $(this).val();
    //alert(codUbiNv1);
    //resetea siempre el nivel3 cuando cambia una opción del nivel1
    $("#ubiNivel3").html("<option value=''>-- Seleccione --</option>");

    $("#ubiNivel2").load("traeDatosActivo.php?filtroNiveles="+codUbiNv1+"&nivel=1");

  });

  $("#ubiNivel2").on("change", function(){
    var codUbiNv2 = $(this).val();
    //alert(codUbiNv2);
    $("#ubiNivel3").load("traeDatosActivo.php?filtroNiveles="+codUbiNv2+"&nivel=2");
  });

});
</script>
  </body>
</html>

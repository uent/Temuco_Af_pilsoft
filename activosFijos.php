<?php
require_once 'traeDatosActivo.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Activos Fijos</title>
    <?php include_once 'tagsMeta.php'; ?>
    <?php include_once 'tagsCSS.php'; ?>
    <?php include_once 'tagsFixJS.php'; ?>
    <style type="text/css">
    <!--
   	th.boton{
        width: 10px;
   	}
    -->
    </style>
  </head>
  <body>
    <div class="container">
        <?php include_once 'menuInicio.php'; ?>

        <div class="row">
            <div class="col-sm-12">
                <h4 class="well well-sm text-center"><b>ACTIVOS FIJOS</b></h4>
            </div>
        </div>

        <!-- Modal Recibo Bienes -->
        <div class="modal fade" id="modalReciboBienes" tabindex="-1" role="dialog" aria-labelledby="mylReciboBienesModalLabel" data-backdrop="static" data-keyboard="false">
        <form id="formReciboBienes" class="form-horizontal" role="form">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                   <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                    <h4 class="modal-title" id="titleModallReciboBienes"><b>Emitir Recibo Cargo de Bienes</b></h4>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="recibo_actID" id="recibo_actID" value="" />
                    <div class="form-group">
                        <label for="recibo_rutFunc" class="control-label col-md-3 col-md-offset-1">RUT:</label>
                        <div class="col-md-6">
                            <input type="text" name="recibo_rutFunc" id="recibo_rutFunc" class="form-control input-sm" required minlength="9" maxlength="10" placeholder="RUT" title="RUT"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="recibo_nombreFunc" class="control-label col-md-3 col-md-offset-1">Nombre:</label>
                        <div class="col-md-6">
                            <input type="text" name="recibo_nombreFunc" id="recibo_nombreFunc" class="form-control input-sm" required minlength="3" maxlength="60" placeholder="Nombre" title="Nombre"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="recibo_contrataPlantaFunc" class="control-label col-md-3 col-md-offset-1">Contrata/Planta:</label>
                        <div class="col-md-6">
                            <input type="text" name="recibo_contrataPlantaFunc" id="recibo_contrataPlantaFunc" class="form-control input-sm" required minlength="5" maxlength="60" placeholder="Contrata o Planta" title="Contrata o Planta"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="recibo_cargoFunc" class="control-label col-md-3 col-md-offset-1">Cargo:</label>
                        <div class="col-md-6">
                            <input type="text" name="recibo_cargoFunc" id="recibo_cargoFunc" class="form-control input-sm" required minlength="3" maxlength="60" placeholder="Cargo" title="Cargo"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nombreEmisor" class="control-label col-md-3 col-md-offset-1">Nombre Emisor:</label>
                        <div class="col-md-6">
                            <input type="text" name="nombreEmisor" id="nombreEmisor" class="form-control input-sm" minlength="3" maxlength="60" placeholder="Nombre del Emisor" title="Nombre del Emisor"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="unidadEmisora" class="control-label col-md-3 col-md-offset-1">Unidad Emisora:</label>
                        <div class="col-md-6">
                            <input type="text" name="unidadEmisora" id="unidadEmisora" class="form-control input-sm" minlength="3" maxlength="60" placeholder="Unidad Emisora" title="Unidad Emisora"/>
                        </div>
                    </div>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-cancelar" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnModalReciboBienes" class="btn btn-primary">Emitir Recibo</button>
                  </div>
                </div>
            </div>
          </div>
        </form>
        </div> <!-- /Modal Recibo Bienes -->

        <!-- Modal Muestra Imagen Activo -->
        <div class="modal fade" id="modalImagenAF" tabindex="-1" role="dialog" aria-labelledby="myImagenModalLabel" data-keyboard="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <!-- <h4 class="modal-title" id="myImagenModalLabel"><b>Imagen AF</b></h4> -->
                  </div>
                  <div class="modal-body thumbnail text-center">
                    <img src="#" id="imagenAF" alt="Imagen_AF"/>
                  </div>
                </div>
            </div>
        </div> <!-- /Modal Muestra Imagen Activo -->

        <!-- Modal Muestra Imagen Doc. Adquisicion -->
        <div class="modal fade" id="modalDocAdquisicion" tabindex="-1" role="dialog" aria-labelledby="myDocAdqModalLabel" data-keyboard="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myDocAdqModalLabel"><b>Doc. de Adquisici&oacute;n</b></h4>
                  </div>
                  <div class="modal-body thumbnail text-center">
                    <img src="#" id="imgDocAdquisicion" alt="Doc_Adq_AF"/>
                  </div>
                </div>
            </div>
        </div> <!-- /Modal Muestra Imagen Doc. Adquisicion -->

         <!-- Modal Muestra mensaje -->
        <div class="modal fade" id="modalmensaje" tabindex="-1" role="dialog" aria-labelledby="myMjsModalLabel" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                  </div>
                  <div class="modal-body">
                    <p class="text-center" id="msj_respuesta"></p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" id="btnCerrarMsj" class="btn btn-primary" data-dismiss="modal"> Cerrar </button>
                  </div>
                </div>
            </div>
        </div> <!-- /Modal Muestra mensaje -->

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
                    �Seguro desea eliminar el activo seleccionado: <span><b id="cod_act_eliminar"></b></span>?
                    <input type="hidden" name="opcion" value="eliminar" />
                    <input type="hidden" name="act_id" id="id_act_eliminar" value="" />
                  </div>
                  <div class="modal-footer">
                    <button type="button" id="btnCancelEliminar" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnModalEliminar" class="btn btn-danger" data-dismiss="modal">Eliminar</button>
                  </div>
                </div>
            </div>
            </form>
        </div>  <!-- /Modal Eliminar AF -->

        <!-- Capa Form -->
        <div id="capaForm" class="hide well well-sm">
            <form id="formFichaActivo" enctype="multipart/form-data" class="form-horizontal" role="form" novalidate>
                <input type="hidden" name="opcion" id="opcion" value="" />
                <input type="hidden" name="act_id" id="actID" value="" />
                <fieldset>
                    <legend>Ficha Activo</legend>
                    <div class="row">
                        <div class="col-sm-9 col-md-9">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="act_codigo" class="control-label col-sm-2 col-md-2"><b>C&oacute;digo del Activo:</b></label>
                                        <div class="col-sm-5 col-md-5">
                                            <input type="text" class="form-control input-sm" name="act_codigo" id="act_codigo" required minlength="5" maxlength="20" placeholder="C&oacute;digo" title="C&oacute;digo"/>
                                        </div>
										
                                <div class="col-sm-2 col-md-2">
                                    <button type="button" id="boton_pdf_activo" name="boton_pdf_activo" class="btn btn-info btn-block btn-sm">
                                        <span class="glyphicon glyphicon-file"></span> Detalles<br>Activo
                                    </button>
                                </div>
                                        
										<!--<div class="col-sm-2 col-md-2">
                                            <button type="button" id="btnCopiarActivo" class="hide form-control btn btn-info btn-sm">
                                                <span class="glyphicon glyphicon-duplicate"></span> Copiar
                                            </button>
                                        </div>-->
										
                                        <div id="grupo-baja">
                                        <label for="act_codEstado" class="control-label col-sm-1 col-md-1">Estado:</label>
                                        <div class="col-sm-2 col-md-2">
                                            <input type="text" id="act_codEstado" value="VIGENTE" class="form-control input-sm" readonly="readonly" />
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <!-- campo descripci�n representa el anal�tico de cuentas para la clasificaci�n p�blica -->
                                        <label for="anac_id" class="control-label col-sm-2 col-md-2">Anal&iacute;tico:</label>
                                        <div class="col-sm-7 col-md-7">
                                            <select class="form-control input-sm" name="anac_id" id="anac_id" title="Anal&iacute;tico" required>
                                                <option value="">-- Seleccione --</option>
                                                <?php llenaOptions('analiticos_cuentas', 'anac_', $conexion); ?>
                                            </select>
                                            <input type="text" class="form-control input-sm hide" name="act_descripcion" id="act_descripcion" readonly="readonly" minlength="3" maxlength="60" placeholder="Descripci&oacute;n" title="Descripci&oacute;n"/>
                                        </div>
                                        <div class="col-sm-3 col-md-3">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></div>
                                                <input type="text" class="form-control input-sm" name="act_codBarras" id="act_codBarras" placeholder="C&oacute;digo de barras"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-md-3">
                            <div class="thumbnail">
                                <a id="linkMuestraImg" data-toggle="modal" href="#modalImagenAF">
                                    <img src="#" data-src="" id="img_thumbnail" alt="Imagen" style="max-height: 85px;" />
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- La sgte. estructura de row es s�lo para emparejar cajas con row anterior de img aneg_id -->
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="row">
                                <div class="col-sm-9 col-md-9">
                                    <div class="form-group">
                                        <label for="act_descripcionDetallada" class="control-label col-sm-2 col-md-2">Detalles:</label>
                                        <div class="col-sm-10 col-md-10">
                                            <input type="text" class="form-control input-sm" name="act_descripcionDetallada" id="act_descripcionDetallada" placeholder="Detalles del activo"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="glyphicon glyphicon-camera"></span></div>
                                        <input type="file" name="imagen" id="file_url" class="form-control input-sm" title="Seleccione archivo"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fin explicaci�n -->

                    <ul class="nav nav-tabs">
                      <li class="my-tab active"><a href="#tabDatosBasicos">Datos b&aacute;sicos</a></li>
                      <li class="my-tab"><a href="#tabSituacionInicial">Situaci&oacute;n inicial</a></li>
                      <li class="my-tab"><a href="#tabReubicaciones">Reubicaciones</a></li>
                      <li class="my-tab"><a href="#tabFichaTecnica">Ficha t&eacute;cnica</a></li>
                      <li class="my-tab"><a href="#tabNotas">Notas</a></li>
                      <li class="my-tab"><a href="#tabVehiculos">Veh&iacute;culos</a></li>
                      <li class="my-tab"><a href="#tabInmuebles">Inmuebles</a></li>
                      <li class="my-tab"><a href="#tabInfoContable">Informaci&oacute;n contable</a></li>
                      <li class="my-tab" id="liTabBaja"><a href="#tabBaja">Baja</a></li>
                      <!-- <li class="my-tab disabled"><a href="#">Valoraci&oacute;n al</a></li> -->
                    </ul>

                    <br />

                    <div id="tabDatosBasicos" class="div-tab">
                        <div class="form-group">
                            <label for="act_fechaIngreso" class="control-label col-sm-1 col-md-1">Ingreso:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                    <input type="text" class="form-control input-sm input-fecha" name="act_fechaIngreso" id="act_fechaIngreso" required placeholder="Fecha ingreso" title="Fecha ingreso"/>
                                </div>
                            </div>

                            <label for="cing_id" class="control-label col-sm-1 col-md-1">Concepto:</label>
                            <div class="col-sm-2 col-md-2">
                                <select class="form-control input-sm" name="cing_id" id="cing_id" title="Concepto" required>
                                    <option value="">-- Seleccione --</option>
                                    <?php llenaOptions('conceptos_ingreso', 'cing_', $conexion); ?>
                                </select>
                            </div>

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
                        </div>

                        <div class="form-group">
                            <label for="aneg_id" class="control-label col-sm-1 col-md-1"><b>Unidad:</b></label>
                            <div class="col-sm-2 col-md-2">
                                <select class="form-control input-sm" name="aneg_id" id="aneg_id" title="A.Negocio" required>
                                    <option value="">-- Seleccione --</option>
                                    <?php llenaOptions('areas_negocios', 'aneg_', $conexion); ?>
                                </select>
                            </div>

                            <label for="ubiNivel1" class="control-label col-sm-1 col-md-1"><b>Direcci&oacute;n:</b></label>
                            <div class="col-sm-2 col-md-2">
                                <select class="form-control input-sm" name="ubiNivel1" id="ubiNivel1" title="Direcci&oacute;n (Nivel 1)">
                                    <option value="">-- Seleccione --</option>
                                    <?php llenaOptions('ubicaciones', 'ubi_', $conexion); ?>
                                </select>
                            </div>

                            <label for="ubiNivel2" class="control-label col-sm-1 col-md-1"><b>Depto. :</b></label>
                            <div class="col-sm-2 col-md-2">
                                <select class="form-control input-sm" name="ubiNivel2" id="ubiNivel2" title="Departamento (Nivel 2)">
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>

                            <label for="ubi_id" class="control-label col-sm-1 col-md-1"><b>Oficina:</b></label>
                            <div class="col-sm-2 col-md-2">
                                <select class="form-control input-sm" name="ubi_id" id="ubi_id" title="Oficina (Nivel 3)" required>
                                    <option value="">-- Seleccione --</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="ccos_id" class="control-label col-sm-1 col-md-1">C. Costo:</label>
                            <div class="col-sm-2 col-md-2">
                                <select class="form-control input-sm" name="ccos_id" id="ccos_id" title="C.costo" required>
                                    <option value="">-- Seleccione --</option>
                                    <?php llenaOptions('centros_costo', 'ccos_', $conexion); ?>
                                </select>
                            </div>

                            <label for="act_venceUltimaGarantia" class="control-label col-sm-1 col-md-1">Garant&iacute;a:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                    <input type="text" class="form-control input-sm input-fecha" name="act_venceUltimaGarantia" id="act_venceUltimaGarantia" placeholder="Vcto. Garant&iacute;a"/>
                                </div>
                            </div>

                            <!--
                            <label for="ubi_id" class="control-label col-sm-1 col-md-1">Ubicaci&oacute;n:</label>
                            <div class="col-sm-2 col-md-2">
                                <select class="form-control input-sm" name="ubi_id" id="ubi_id" title="Ubicaci&oacute;n" required>
                                    <?php //llenaOptions('ubicaciones', 'ubi_', $conexion); ?>
                                </select>
                                <div id="tooltip_ubicacion" class="small text-primary"></div>
                            </div>
                            -->
                        </div>

                        <div class="row">
                            <div class="col-sm-2 col-md-2">
                            <fieldset>
                                <legend class="small">Tipo Control:</legend>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="act_tipoControl" id="act_tipoCtrlAF" value="Activo Fijo" checked="checked"/><span> Activo Fijo</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="act_tipoControl" id="act_tipoCtrlAdm" value="Ctrl Adm"/><span> Ctrl. Adm.</span>
                                    </label>
                                </div>
                            </fieldset>
                            </div>

                            <div class="col-sm-2 col-md-2">
                            <fieldset>
                                <legend class="small">Revalorizable seg&uacute;n IPC:</legend>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="act_revalorizable" id="act_revalorizable_si" value="SI" checked="checked"/><span> SI</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="act_revalorizable" id="act_revalorizable_no" value="NO"/><span> NO</span>
                                    </label>
                                </div>
                            </fieldset>
                            </div>

                            <div class="col-sm-2 col-md-2">
                            <fieldset>
                                <legend class="small">Depreciable:</legend>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="act_depreciable" id="act_depreciable_si" value="SI" checked="checked"/><span> SI</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="act_depreciable" id="act_depreciable_no" value="NO"/><span> NO</span>
                                    </label>
                                </div>
                            </fieldset>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="row">
                                    <fieldset>
                                    <div class="col-sm-4 col-md-4">
                                        <legend class="small">Tipo de Depreciaci&oacute;n:</legend>
                                    </div>
                                    <div class="col-sm-8 col-md-8">
                                        <label class="radio-inline">
                                            <input type="radio" name="act_tipoDepreciacion" id="act_tipoDepreciacion_l" value="L" checked="checked"/><span> Linieal</span>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="act_tipoDepreciacion" id="act_tipoDepreciacion_a" value="A"/><span> Acelerada</span>
                                        </label>
                                    </div>
                                    </fieldset>
                                </div>

                                <div class="row">
                                    <div class="col-sm-11 col-sm-offset-1 col-md-11 col-md-offset-1">
                                        <fieldset>
                                            <legend class="small">Unidad de medida vida &uacute;til para <u>Depreciaci&oacute;n</u>:</legend>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="act_unidadMedidaVidaUtil" id="act_unidadMedidaVidaUtil_m" value="M" checked="checked"/><span> Mensual</span>
                                                </label>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <div class="row">
                                                        <div class="col-sm-5 col-md-5">
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" name="act_unidadMedidaVidaUtil" id="act_unidadMedidaVidaUtil_p" value="P"/><span> Unidad de Producci&oacute;n: </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-7 col-md-7">
                                                            <select name="act_umed_id" id="act_umed_id" class="form-control input-sm" disabled="disabled">
                                                                <option value="0">-- Seleccione --</option>
                                                                <?php llenaOptions('unidades_medida', 'umed_', $conexion); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <!-- Activos x lote  -->
                            <div class="col-sm-6 col-md-6">
                                <fieldset>
                                    <div class="col-sm-12 col-md-12">
                                        <legend class="small">Activo por lote:</legend>
                                    </div>
                                    <label class="control-label col-sm-2 col-md-2 radio-inline">
                                        <input type="radio" name="act_porLote" id="act_porLote_no" value="NO" checked="checked"/><span> NO</span>
                                    </label>
                                    <label class="control-label col-sm-2 col-md-2 radio-inline">
                                        <input type="radio" name="act_porLote" id="act_porLote_si" value="SI"/><span> SI</span>
                                    </label>

                                    <label for="act_cantidadLote" class="control-label col-sm-2 col-md-2">Cantidad:</label>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="glyphicon glyphicon-align-center"></span></div>
                                            <input type="number" class="form-control input-sm" name="act_cantidadLote" id="act_cantidadLote" disabled="disabled" title="Cantidad" required min="0" placeholder="Cantidad"/>
                                        </div>
                                    </div>
                                </fieldset>
                            </div> <!-- /fin Activos x lote -->

                            <!-- Activo considerado bajo norma -->
                            <div class="col-sm-6 col-md-6">
                                <fieldset>
                                    <div class="col-sm-12 col-md-12">
                                        <legend class="small">Activo considerado bajo norma(s):</legend>
                                    </div>
                                    <label class="control-label col-sm-2 col-md-2 radio-inline">
                                        <input type="radio" name="act_bajoNormaPublica" id="act_bajoNormaNicsp" value="Nicsp" checked="checked"/><span> Nicsp</span>
                                    </label>
                                    <label class="control-label col-sm-2 col-md-2 radio-inline">
                                        <input type="radio" name="act_bajoNormaPublica" id="act_bajoNormaPCGN" value="PCGN"/><span> PCGN</span>
                                    </label>
                                    <label class="control-label col-sm-3 col-md-3 col-md-offset-1 checkbox-inline">
                                        <input type="checkbox" name="act_bajoNormaTributaria" id="act_bajoNormaTributaria" value="SI" disabled="disabled"/><span> Tributaria</span>
                                    </label>
                                    <label class="control-label col-sm-2 col-md-2 checkbox-inline">
                                        <input type="checkbox" name="act_bajoNormaIFRS" id="act_bajoNormaIFRS" value="SI" disabled="disabled"/><span> IFRS</span>
                                    </label>
                                </fieldset>
                            </div> <!-- /Activo considerado bajo norma -->
                        </div>
                    </div> <!-- /Fin tab datosBasicos Fecha ingreso -->

                    <div id="tabSituacionInicial" class="hide div-tab">
                        <div class="form-group">
                            <label class="control-label col-sm-3 col-md-3">
                                <span class="text-primary"><b>Situaci&oacute;n de partida corresponde a:</b></span>
                            </label>

                            <label class="col-sm-2 col-md-2 col-md-offset-1 radio-inline">
                                <input type="radio" name="act_situacionDePartida" id="act_situacionDePartida_c" value="C" checked="checked"/>
                                <span class="text-primary"> <b>Informaci&oacute;n de compra</b></span>
                            </label>

                            <label class="control-label col-sm-3 col-md-3 radio-inline hide">
                                <input type="radio" name="act_situacionDePartida" id="act_situacionDePartida_s" value="S" disabled="disabled"/>
                                <span class="text-primary"> <b>Situaci&oacute;n contable a una fecha</b></span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="act_fechaAdquisicion" class="control-label col-sm-1 col-md-1">Adquisici&oacute;n:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                    <input type="text" class="form-control input-sm input-fecha" name="act_fechaAdquisicion" id="act_fechaAdquisicion" required  minlength="10" maxlength="10" placeholder="Fecha compra" title="Fecha compra"/>
                                </div>
                            </div>

                            <label for="cond_id" class="control-label col-sm-1 col-md-1">Condici&oacute;n:</label>
                            <div class="col-sm-2 col-md-2">
                                <select class="form-control input-sm" name="cond_id" id="cond_id">
                                    <option value="0">-- Seleccione --</option>
                                    <?php llenaOptions('condiciones_activos', 'cond_', $conexion); ?>
                                </select>
                            </div>
                            <label for="act_vidaUtilTributaria" class="control-label col-sm-1 col-md-1">Vida &Uacute;til:</label>
                            <!-- <label for="act_vidaUtilTributaria" class="control-label col-sm-1 col-md-1">Tributaria:</label> -->
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-time"></span></div>
                                    <input type="number" class="form-control input-sm" name="act_vidaUtilTributaria" id="act_vidaUtilTributaria" min="0" max="1000" placeholder="###"/>
                                </div>
                            </div>

                            <label for="act_vidaUtilFinanciera" class="control-label col-sm-1 col-md-1 hide">Financiera:</label>
                            <div class="col-sm-2 col-md-2 hide">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-time"></span></div>
                                    <input type="number" class="form-control input-sm" name="act_vidaUtilFinanciera" id="act_vidaUtilFinanciera" min="0" max="1000" placeholder="###"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="act_valorAdquisicion" class="control-label col-sm-1 col-md-1">Valor (C):</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></div>
                                    <input type="number" class="form-control input-sm" name="act_valorAdquisicion" id="act_valorAdquisicion" min="0" required minlength="1" title="Valor (C)" maxlength="10" placeholder="###"/>
                                </div>
                            </div>

                            <label for="act_valorResidual" class="control-label col-sm-1 col-md-1">V. Residual:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></div>
                                    <input type="number" class="form-control input-sm" name="act_valorResidual" id="act_valorResidual" min="0" required maxlength="10" title="Valor Residual" placeholder="###"/>
                                </div>
                            </div>

                            <label for="act_presupuesto" class="control-label col-sm-1 col-md-1">Presupuesto:</label>
                            <div class="col-sm-3 col-md-3">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-credit-card"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_presupuesto" id="act_presupuesto" maxlength="30" title="Presupuesto" placeholder=""/>
                                </div>
                            </div>

                            <div class="col-sm-2 col-md-2">
                                <p class="hide" id="containerDocAdq">
                                    <a data-toggle="modal" href="#modalDocAdquisicion" id="linkMuestraDocAdq">
                                        <label class="badge"><b><em>Click Para Ver Documento</em></b></label>
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tdoc_id" class="control-label col-sm-1 col-md-1">Tipo Doc.:</label>
                            <div class="col-sm-2 col-md-2">
                                <select class="form-control input-sm" name="tdoc_id" id="tdoc_id">
                                    <option value="0">-- Seleccione --</option>
                                    <?php llenaOptions('tipo_documento', 'tdoc_', $conexion); ?>
                                </select>
                            </div>

                            <label for="act_numDocCompra" class="control-label col-sm-1 col-md-1">N&deg; Doc.:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-file"></span></div>
                                    <input type="number" class="form-control input-sm" name="act_numDocCompra" id="act_numDocCompra" min="0" maxlength="8" placeholder="###"/>
                                </div>
                            </div>

                            <label for="act_numOrdenCompra" class="control-label col-sm-1 col-md-1">N&deg; Orden:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_numOrdenCompra" id="act_numOrdenCompra" min="0" maxlength="20" placeholder="###"/>
                                </div>
                            </div>

                            <div class="col-sm-3 col-md-3">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-paperclip"></span></div>
                                    <input type="file" name="act_docAdquisicion" id="act_docAdquisicion" class="form-control input-sm" title="Seleccione..."/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="act_marca" class="control-label col-sm-1 col-md-1">Marca:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-registration-mark"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_marca" id="act_marca" maxlength="30" placeholder="Marca" title="Marca"/>
                                </div>
                            </div>

                            <label for="act_modelo" class="control-label col-sm-1 col-md-1">Modelo:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-tags"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_modelo" id="act_modelo" maxlength="45" placeholder="Modelo" title="Modelo"/>
                                </div>
                            </div>

                            <label for="act_serie" class="control-label col-sm-1 col-md-1">N&deg; Serie:</label>
                            <div class="col-sm-5 col-md-5">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_serie" id="act_serie" maxlength="100" placeholder="Serie" title="Serie"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="aux_id" class="control-label col-sm-1 col-md-1">Proveedor:</label>
                            <div class="col-sm-3 col-md-3">
                                <select class="form-control input-sm" name="aux_id" id="aux_id">
                                    <option value="0">-- Seleccione --</option>
                                    <?php llenaOptions('proveedores', 'aux_', $conexion); ?>
                                </select>
                            </div>

                            <label for="act_inicioDepreciacion" class="control-label col-sm-2 col-md-2">Inicio Depreciaci&oacute;n:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                    <input type="text" class="form-control input-sm input-mesAnio" name="act_inicioDepreciacion" id="act_inicioDepreciacion" placeholder="Mes / A&ntilde;o"/>
                                </div>
                            </div>

                            <label for="act_inicioRevalorizacion" class="control-label col-sm-2 col-md-2">Inicio Revalorizaci&oacute;n:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                    <input type="text" class="form-control input-sm input-mesAnio" name="act_inicioRevalorizacion" id="act_inicioRevalorizacion" placeholder="Mes / A&ntilde;o"/>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /Fin tab situaci�n inicial -->

                     <!-- tab Ubicaciones -->
                    <div id="tabReubicaciones" class="hide div-tab">
                        <fieldset>
                            <legend class="small">Reubicar Activo:</legend>
                            <div class="form-group">
                                <label for="reub_fecha" class="control-label col-sm-1 col-md-1">Fecha:</label>
                                <div class="col-sm-2 col-md-2">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                        <input type="text" class="form-control input-sm input-fecha" id="reub_fecha" placeholder="Fecha cambio"/>
                                    </div>
                                </div>

                                <label for="reub_hora" class="control-label col-sm-1 col-md-1">Hora:</label>
                                <div class="col-sm-2 col-md-2">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="glyphicon glyphicon-time"></span></div>
                                        <input type="text" class="form-control input-sm" id="reub_hora" value="<?php echo date('H:i'); ?>" maxlength="5" placeholder="hh:mm"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ubiID_reub" class="control-label col-sm-1 col-md-1">Ubicaci&oacute;n:</label>
                                <div class="col-sm-2 col-md-2">
                                    <select class="form-control input-sm" id="ubiID_reub">
                                        <option value="0">-- Seleccione --</option>
                                        <?php llenaOptions('ubicaciones', 'ubi_', $conexion); ?>
                                    </select>
                                </div>

                                <label for="ccosID_reub" class="control-label col-sm-1 col-md-1">C. costo:</label>
                                <div class="col-sm-2 col-md-2">
                                    <select class="form-control input-sm" id="ccosID_reub">
                                        <option value="0">-- Seleccione --</option>
                                        <?php llenaOptions('centros_costo', 'ccos_', $conexion); ?>
                                    </select>
                                </div>

                                <label for="anegID_reub" class="control-label col-sm-1 col-md-1">A. Negocio:</label>
                                <div class="col-sm-3 col-md-3">
                                    <select class="form-control input-sm" id="anegID_reub">
                                        <option value="0">-- Seleccione --</option>
                                        <?php llenaOptions('areas_negocios', 'aneg_', $conexion); ?>
                                    </select>
                                </div>

                                <div class="col-sm-2 col-md-2">
                                    <button type="button" id="btnGuardarReubicar" class="btn btn-info btn-block btn-sm">
                                        <span class="glyphicon glyphicon-floppy-disk"></span> Reubicar
                                    </button>
                                </div>
                            </div>
                        </fieldset>

                        <!-- registros de Ubicaciones -->
                        <fieldset>
                            <legend class="small">Historial de Ubicaciones:</legend>
                            <div class="row">
                                <div class="col-sm-10 col-md-10">
                                    <table class="table table-bordered table-condensed table-striped" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th style="width: 100px;" class="text-center">Fecha</th>
                                                <th style="width: 50px;" class="text-center">Hora</th>
                                                <th>Descripci&oacute;n</th>
                                                <th class="text-center">Ubicaci&oacute;n</th>
                                                <th class="text-center">Centro de Costo</th>
                                                <th>&Aacute;rea de Negocios</th>
                                            </tr>
                                        </thead>
                                        <tbody id="historialUbicaciones">
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-sm-2 col-md-2">
                                    <button type="button" id="btnBorrarReubicar" class="btn btn-danger btn-block btn-sm">
                                        <span class="glyphicon glyphicon-trash"></span> Eliminar
                                    </button>
                                </div>
                            </div> <!-- /Fin registros Ubicaciones -->
                        </fieldset>
                    </div> <!-- /Fin tab Ubicaciones -->

                     <!-- tab FichaTecnica -->
                    <div id="tabFichaTecnica" class="hide div-tab">
                        <div class="form-group">
                            <label for="ftec_descripcion" class="col-sm-1 col-md-1">Descripci&oacute;n:</label>
                            <div class="col-sm-11 col-md-11">
                                <textarea name="ftec_descripcion" id="ftec_descripcion" form="formFichaActivo" class="form-control" rows="5" maxlength="255" style="width: 100%;" placeholder="Antecedentes t&eacute;cnicos"></textarea>
                            </div>
                        </div>
                    </div> <!-- /Fin tab FichaTecnica -->

                     <!-- tab notas -->
                    <div id="tabNotas" class="hide div-tab">
                        <div class="form-group">
                            <label for="nota_fecha" class="control-label col-sm-1 col-md-1">Fecha:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                    <input type="text" class="form-control input-sm input-fecha" id="nota_fecha" tabindex="1000" placeholder="Fecha registro"/>
                                </div>
                            </div>
                            <label for="nota_descripcion" class="control-label col-sm-1 col-md-1">Descripci&oacute;n:</label>
                            <div class="col-sm-6 col-md-6">
                                <input type="text" class="form-control input-sm" id="nota_descripcion" tabindex="1001" placeholder="Descripci&oacute;n"/>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <button type="button" id="btnGuardarNota" class="btn btn-info btn-block btn-sm" tabindex="1003">
                                    <span class="glyphicon glyphicon-floppy-disk"></span> Grabar
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nota_detalles" class="control-label col-sm-1 col-md-1">Detalle:</label>
                            <div class="col-sm-9 col-md-9">
                                <textarea id="nota_detalles" class="form-control" rows="5" style="width: 100%;" tabindex="1002" placeholder="Detalles"></textarea>
                            </div>
                        </div>

                        <!-- registros de notas -->
                        <div class="row">
                            <div class="col-sm-10 col-md-10">
                                <table class="table table-bordered table-condensed table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px;" class="text-center">Fecha</th>
                                            <th>Descripci&oacute;n</th>
                                            <th>Detalles (extracto)</th>
                                            <th style="width: 20px;"  class="text-center">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="historialNotasActivo">
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /Fin registros notas -->
                    </div> <!-- /Fin tab notas -->

                    <div id="tabVehiculos" class="div-tab">
                        <div class="form-group">
                            <label for="act_patenteVehiculo" class="control-label col-sm-1 col-md-1">Patente:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-road"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_patenteVehiculo" id="act_patenteVehiculo" maxlength="12" placeholder="Patente" title="Patente"/>
                                </div>
                            </div>

                            <label for="act_siglaVehiculo" class="control-label col-sm-1 col-md-1">Sigla:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-font"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_siglaVehiculo" id="act_siglaVehiculo" maxlength="10" placeholder="Sigla" title="Sigla"/>
                                </div>
                            </div>

                            <label for="act_chasisVehiculo" class="control-label col-sm-1 col-md-1">Chasis:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-edit"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_chasisVehiculo" id="act_chasisVehiculo" maxlength="30" placeholder="Chasis" title="Chasis"/>
                                </div>
                            </div>

                            <label for="act_numMotorVehiculo" class="control-label col-sm-1 col-md-1">N&deg; Motor:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-sort-by-order"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_numMotorVehiculo" id="act_numMotorVehiculo" maxlength="30" placeholder="N&deg; Motor" title="N&deg; Motor"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tabInmuebles" class="div-tab">
                        <div class="form-group">
                            <label for="act_numCarpetaInmueble" class="control-label col-sm-1 col-md-1">N&deg; Carpeta:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-folder-open"></span></div>
                                    <input type="number" class="form-control input-sm" name="act_numCarpetaInmueble" id="act_numCarpetaInmueble" placeholder="###"/>
                                </div>
                            </div>

                            <label for="act_numInmueble" class="control-label col-sm-1 col-md-1">N&uacute;mero:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-sort-by-order"></span></div>
                                    <input type="number" class="form-control input-sm" name="act_numInmueble" id="act_numInmueble" placeholder="###"/>
                                </div>
                            </div>

                            <label for="act_fechaEscritura" class="control-label col-sm-1 col-md-1">F. Escritura:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                    <input type="text" class="form-control input-sm input-fecha" name="act_fechaEscritura" id="act_fechaEscritura" maxlength="10" placeholder="Fecha escritura" title="Fecha escritura"/>
                                </div>
                            </div>

                            <label for="act_tipoInmueble" class="control-label col-sm-1 col-md-1">Tipo:</label>
                            <div class="col-sm-2 col-md-2">
                                <select class="form-control input-sm" name="act_tipoInmueble" id="act_tipoInmueble">
                                    <option value="">-- Seleccione --</option>
                                    <option value="Edificio">Edificio</option>
                                    <option value="Inmueble">Inmueble</option>
                                    <option value="Oficina">Oficina</option>
                                    <option value="Propiedad">Propiedad</option>
                                    <option value="Terreno">Terreno</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="act_anioInmueble" class="control-label col-sm-1 col-md-1">A&ntilde;o:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                    <input type="number" class="form-control input-sm" name="act_anioInmueble" id="act_anioInmueble" maxlength="4" placeholder="####"/>
                                </div>
                            </div>

                            <label for="act_sectorInmueble" class="control-label col-sm-1 col-md-1">Sector:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_sectorInmueble" id="act_sectorInmueble" maxlength="60" placeholder="Sector"/>
                                </div>
                            </div>

                            <label for="act_rolInmueble" class="control-label col-sm-1 col-md-1">N&deg; Rol:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-sort-by-order"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_rolInmueble" id="act_rolInmueble" maxlength="40" placeholder="###"/>
                                </div>
                            </div>

                            <label for="act_DAInmueble" class="control-label col-sm-1 col-md-1">D.A. :</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-sort-by-order"></span></div>
                                    <input type="number" class="form-control input-sm" name="act_DAInmueble" id="act_DAInmueble" maxlength="8" placeholder="###"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="act_fojaInmueble" class="control-label col-sm-1 col-md-1">Foja:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-sort-by-order"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_fojaInmueble" id="act_fojaInmueble" maxlength="10" placeholder="###" title="Foja"/>
                                </div>
                            </div>

                            <label for="act_fechaIngresoInmueble" class="control-label col-sm-1 col-md-1">F. Ingreso:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                    <input type="text" class="form-control input-sm input-fecha" name="act_fechaIngresoInmueble" id="act_fechaIngresoInmueble" maxlength="10" placeholder="Fecha ingreso" title="Fecha ingreso"/>
                                </div>
                            </div>

                            <label for="act_medidaTerrenoInmueble" class="control-label col-sm-1 col-md-1">Medidas:</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-fullscreen"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_medidaTerrenoInmueble" id="act_medidaTerrenoInmueble" maxlength="30" placeholder="Medidas terreno" title="Medidas terreno"/>
                                </div>
                            </div>

                            <label for="act_propAnteriorInmueble" class="control-label col-sm-1 col-md-1">Propie. Ant.</label>
                            <div class="col-sm-2 col-md-2">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_propAnteriorInmueble" id="act_propAnteriorInmueble" maxlength="255" placeholder="Propietario Ant." title="Propietario Anterior"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="act_nombreInmueble" class="control-label col-sm-1 col-md-1">Nombre:</label>
                            <div class="col-sm-5 col-md-5">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_nombreInmueble" id="act_nombreInmueble" maxlength="255" placeholder="Nombre(s)" title="Nombre(s)"/>
                                </div>
                            </div>

                            <label for="act_detalleInmueble" class="control-label col-sm-1 col-md-1">Detalles:</label>
                            <div class="col-sm-5 col-md-5">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-list-alt"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_detalleInmueble" id="act_detalleInmueble" maxlength="600" placeholder="Detalles" title="Detalles"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="act_direccionInmueble" class="control-label col-sm-1 col-md-1">Direcci&oacute;n:</label>
                            <div class="col-sm-5 col-md-5">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_direccionInmueble" id="act_direccionInmueble" maxlength="600" placeholder="Direcci&oacute;n" title="Direcci&oacute;n"/>
                                </div>
                            </div>

                            <label for="act_obsInmueble" class="control-label col-sm-1 col-md-1">Observaci&oacute;n:</label>
                            <div class="col-sm-5 col-md-5">
                                <div class="input-group">
                                    <div class="input-group-addon"><span class="glyphicon glyphicon-edit"></span></div>
                                    <input type="text" class="form-control input-sm" name="act_obsInmueble" id="act_obsInmueble" maxlength="600" placeholder="Observaci&oacute;n" title="Observaci&oacute;n"/>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /Fin tabInmuebles -->

                    <!-- tab Cuentas Contables -->
                    <div id="tabInfoContable" class="hide div-tab">
                        <fieldset>
                            <legend class="small">Cuentas Contables:</legend>
                            <div class="form-group">
                                <label for="ccont_id" class="control-label col-sm-1 col-md-1">Compra AF:</label>
                                <div class="col-sm-5 col-md-5">
                                    <select class="form-control input-sm" name="ccont_id" id="ccont_id">
                                        <option value="0">-- Seleccione --</option>
                                        <?php llenaOptions('cuentas_contables', 'ccont_', $conexion); ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div> <!-- Fin tab Cuentas Contables -->

                    <!-- tab Notas del AF -->
                    <div id="tabBaja" class="hide div-tab">
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <fieldset id="fieldBaja">
                                    <legend class="small">Baja del Activo:</legend>
                                        <div class="col-sm-10 col-md-10">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="act_fechaDeBaja" class="control-label col-sm-1 col-md-1">Fecha:</label>
                                                    <div class="col-sm-3 col-md-3">
                                                        <div class="input-group">
                                                            <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                                            <input type="text" class="form-control input-sm input-fecha" id="act_fechaDeBaja" placeholder="Fecha de baja"/>
                                                        </div>
                                                    </div>

                                                    <label for="baja_id" class="control-label col-sm-2 col-md-2">Concepto de baja:</label>
                                                    <div class="col-sm-4 col-md-4">
                                                        <select class="form-control input-sm" id="baja_id">
                                                            <option value="0">-- Seleccione --</option>
                                                            <?php llenaOptions('conceptos_baja', 'baja_', $conexion); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group">
                                                    <label for="act_glosaDeBaja" class="control-label col-sm-1 col-md-1">Descripci&oacute;n:</label>
                                                    <div class="col-sm-9 col-md-9">
                                                        <input type="text" class="form-control input-sm" id="act_glosaDeBaja" placeholder="Motivo o detalle de la baja"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-md-2" style="position: absolute; right:0px; bottom: 20px; z-index: 0;">
                                            <button type="button" id="btnAltaActivo" class="btn btn-success btn-block btn-sm hide" style="position: relative;">
                                                <span class="glyphicon glyphicon-share-alt"></span> Revertir Baja
                                            </button>
                                            <button type="button" id="btnBajaActivo" class="btn btn-info btn-block btn-sm" style="position: relative;">
                                                <span class="glyphicon glyphicon-arrow-down"></span> Dar de Baja
                                            </button>
                                        </div>
                                </fieldset>
                            </div>
                        </div>
                    </div> <!-- /Fin tab Baja -->

                    <hr />

                    <div class="form-group">
                        <div class="col-sm-2 col-md-2 col-md-offset-8">
                            <button type="reset" id="btnCancelarForm" class="form-control btn btn-default">Cancelar</button>
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <button type="reset" id="btnCerrarForm" class="form-control btn btn-primary hide">
                            <span class="glyphicon glyphicon-share-alt"></span> Cerrar
                            </button>
                            <button type="submit" id="btnGuardarForm" class="form-control btn btn-primary">
                                <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <!-- /Fin Capa Form -->

        <!-- Capa de registros -->
        <div id="capaRegistros">
            <!-- DataTables -->
            <table id="dtActivos" class="table table-bordered table-condensed table-striped table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>C&oacute;digo</th>
                    <th>Anal&iacute;tico</th>
                    <th>Descripci&oacute;n</th>
                    <th>&Aacute;rea de Negocio</th>
                    <th>C. Costo</th>
                    <th>Ubicaci&oacute;n</th>
                    <th>Responsable</th>
                    <th>T. Control</th>
                    <th>Estado</th>
                    <th>F. Ingreso</th>
                    <th class="boton btn-print"></th>
                    <th class="boton btn-recibo"></th>
                    <th class="boton btn-ver"></th>
                    <th class="boton btn-editar"></th>
                    <th class="boton btn-eliminar"></th>
                </tr>
                </thead>
                <tfoot>
                <tr class="active">
                    <th>ID</th>
                    <th>C&oacute;digo</th>
                    <th>Anal&iacute;tico</th>
                    <th>Descripci&oacute;n</th>
                    <th>&Aacute;rea de Negocio</th>
                    <th>C. Costo</th>
                    <th>Ubicaci&oacute;n</th>
                    <th>Responsable</th>
                    <th>T. Control</th>
                    <th>Estado</th>
                    <th>F. Ingreso</th>
                    <th class="boton btn-print"></th>
                    <th class="boton btn-recibo"></th>
                    <th class="boton btn-ver"></th>
                    <th class="boton btn-editar"></th>
                    <th class="boton btn-eliminar"></th>
                </tr>
                </tfoot>
            </table> <!-- /DataTables -->
        </div>
    </div> <!-- /container -->

    <?php include_once 'tagsJS.php'; ?>
    <script type="text/javascript" src="js/BrowserPrint-1.0.4.min.js"></script>
    <script type="text/javascript" src="js/paramPrintZebra.js"></script>
    <script type="text/javascript">
    $(document).on("ready", function(){
        listar();
        eliminar();
        crear_editar();
		mostrar_pdf_activos();

        $("#ubiNivel1").on("change", function(){
            var codUbiNv1 = $(this).val();
            //alert(codUbiNv1);
            //resetea siempre el nivel3 cuando cambia una opci�n del nivel1
            $("#ubi_id").html("<option value=''>-- Seleccione --</option>");

            $("#ubiNivel2").load("traeDatosActivo.php?filtroNiveles="+codUbiNv1+"&nivel=1");

        });

        $("#ubiNivel2").on("change", function(){
            var codUbiNv2 = $(this).val();
            //alert(codUbiNv2);
            $("#ubi_id").load("traeDatosActivo.php?filtroNiveles="+codUbiNv2+"&nivel=2");
        });

        $("#ubi_id").on("change", function(){

            //var codUbi = $("#ubi_id :selected").text();

            /*$("#tooltip_ubicacion").load("traeDatosActivo.php?niveles=ubicacion&cod_ubi="+codUbi, function(){
                //alert($(this).text());
                var title  = $("#ubi_id").hover().attr('title', $("#tooltip_ubicacion").text());
            });*/
        });
    });

    // funcionalidad del cambio de tabs
    $(".my-tab").on("click", function(){
        $(".my-tab").removeClass("active");
        var ancla = $(this).addClass("active").find("a");
        var link  = $(ancla).attr("href");

        $(".div-tab").addClass("hide");
        $(link).removeClass("hide");
    });

    $("input[name=act_tipoDepreciacion]").on("change", function(){
        if( $("#act_tipoDepreciacion_a").is(":checked") ){
            $("#act_tipoDepreciacion_a").prop("checked", false);
            alert("Opci�n s�lo disponible para el sector Privado.")
            $("#act_tipoDepreciacion_l").prop("checked", true);
        }
    });

    // funcionalidad del cambio de unidad de medida vida �til (:radio)
    $("input[name=act_unidadMedidaVidaUtil]").on("change", function(){
        if( $("#act_unidadMedidaVidaUtil_p").is(":checked") ){
            $("#act_umed_id").prop("disabled", false);
        }else{
            $("#act_umed_id").val("0").trigger("change");
            $("#act_umed_id").prop("disabled", true);
        }
    });

    // funcionalidad activa caja de cantidad act. x lote
    $("input[name=act_porLote]").on("change", function(){
        if( $("#act_porLote_si").is(":checked") ){
            $("#act_cantidadLote").prop("disabled", false);
        }else{
            $("#act_cantidadLote").prop("disabled", true).val('');
        }
    });

    // funcionalidad desactiva cajas de vida util tributaria y financ.
    $("input[name=act_situacionDePartida]").on("change", function(){
        if( $("#act_situacionDePartida_s").is(":checked") ){
            $("#act_vidaUtilTributaria").prop("disabled", true);
            $("#act_vidaUtilFinanciera").prop("disabled", true);
            $("#act_fechaAdquisicion").prop("disabled", true);
            $("#act_valorAdquisicion").prop("disabled", true);
        }else{
            $("#act_vidaUtilTributaria").prop("disabled", false);
            $("#act_vidaUtilFinanciera").prop("disabled", false);
            $("#act_fechaAdquisicion").prop("disabled", false);
            $("#act_valorAdquisicion").prop("disabled", false);
        }
    });

    var listar = function(){
        var table = $("#dtActivos").DataTable({
            "destroy": true,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "order": ([[10, 'desc'], [2, 'asc'], [3, 'asc']]), //The rows are shown in the order they are read by DataTables
            "ajax":{
                url: "crudActivosFijos.php?opcion=leer",
                type: "POST"
            },
            "deferRender": true,
            "columnDefs":[
                 {
                     targets: [0],
                     visible: false
                 },
                 {
                    targets: [2,5,6,8,9,10,11,12,13,14,15],
                    className: "text-center"
                 },
                 <?php if( $_SESSION['perfil'] == 3 ){ ?>
                 {
                    targets: [13],
                    className: "hide"
                 },
                 {
                    targets: [14],
                    visible: false
                 }
                 <?php } ?>
            ],
            "columns":[
                {"data":"act_id"},
                {"data":"act_codigo"},
                {"data":"anac_descripcion"},
                {"data":"act_descripcion"},
                {"data":"aneg_descripcion"},
                {"data":"ccos_codigo"},
                {"data":"ubi_codigo"},
                {"data":"resp_nombre"},
                {"data":"act_tipoControl"},
                {"data":"act_codEstado"},
                {"data":"act_fechaIngreso"},
                {
                    "defaultContent":"<button type='button' class='print btn btn-default btn-xs' title='Imprimir'><span class='glyphicon glyphicon-barcode'></span></button>",
                    "sortable": false,
                    "searchable": false
                },
                {
                    "defaultContent":"<button type='button' class='recibo btn btn-default btn-xs' title='Recibo Cargo de Bienes' data-toggle='modal' data-target='#modalReciboBienes'><span class='glyphicon glyphicon-user'></span></button>",
                    "sortable": false,
                    "searchable": false
                },
                {
                    "defaultContent":"<button type='button' class='ver btn btn-success btn-xs' title='Ver'><span class='glyphicon glyphicon-eye-open'></span></button>",
                    "sortable": false,
                    "searchable": false
                },
                {
                    "defaultContent":"<button type='button' class='editar btn btn-primary btn-xs' title='Editar'><span class='glyphicon glyphicon-pencil'></span></button>",
                    "sortable": false,
                    "searchable": false
                },
                {
                    "defaultContent":"<button type='button' class='eliminar btn btn-danger btn-xs' title='Eliminar' data-toggle='modal' data-target='#modalEliminar'><span class='glyphicon glyphicon-trash'></span></button>",
                    "sortable": false,
                    "searchable": false
                }
            ],
            "language":{
                "url": "js/dataTables_es.json"
            },
            "dom": "<'row'<'col-xs-12 col-md-6'B><'col-xs-12 col-md-6'f>>" +
                   "<'row'<'col-xs-12'tr>>" +
                   "<'row'<'col-xs-12 col-md-3'l><'col-xs-12 col-md-4 small text-center text-primary'i><'col-xs-12 col-md-5'p>>",
            "buttons":[
                <?php if( $_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 2 ){ ?>
                {
                    "text": "<span class='glyphicon glyphicon-plus-sign'></span>",
                    "titleAttr": "Agregar Nuevo",
                    "className": "btn btn-success hide",
                    "action": function(){
                        $("#opcion").val("insertar");

                        limpiarHiddens();
                        // Al querer crear un registro, se oculta la opci�n de baja del activo
                        $("#liTabBaja").hide();
                        //$("#liTabBaja").addClass("disabled").children().attr("href", "javascript://");
                        $("#tabReubicaciones").find("input, select, button").prop("disabled", true);
                        $("#tabNotas").find("input, textarea, button").prop("disabled", true);

                        /* para crear un AF habilito Anal�tico */
                        $("#act_descripcion").addClass("hide").val('');
                        $("#anac_id").removeClass("hide");

                        $("#img_thumbnail").attr("src", "#");
                        $("#imagenAF").attr("src", "#");

                        $("#linkDocAdq").attr("src", "#");
                        $("#containerDocAdq").addClass("hide");
                        $("#imgDocAdquisicion").attr("src", "#");

                        // En app standar es false para permitir
                        // el ingreso de c�digo al usuario final
                        $("#act_codigo").prop("readonly", true);
                        $("#act_codBarras").prop("readonly", true);
                        $("#grupo-baja").removeClass("has-error");

                        if( ! $("#act_unidadMedidaVidaUtil_p").is(":checked") ){
                            $("#act_umed_id").prop("disabled", true);
                        }

                        if( ! $("#act_porLote_si").is(":checked") ){
                            $("#act_cantidadLote").prop("disabled", true);
                        }

                        //Al Crear, select option es seteado en caso de consulta antetior
                        $("#ubi_id").html("<option value=''>-- Seleccione --</option>");

                        $.get("traeDatosActivo.php", {getNewBarCode: 'nuevo'}, function( newBarCode ){
                            if( !isNaN( newBarCode ) ){
                                $("#act_codigo, #act_codBarras").val(newBarCode);
                            }else{
                                alert("Error: No fue posible generar el C�digo de Barras.");
                            }
                        });

                        hideGridShowForm();
                    }
                },
                <?php } ?>
                'excel', 'csv',
                {
                    "extend": "pdf",
                    "orientation": "landscape",
                    "pageSize": "TABLOID"
                },
            ],
            "lengthMenu": [ [ 5, 100, 500, 1000, 2500, 5000 ], [ '5', '100', '500', '1000', '2500', '5000' ] ]
        });

        $('#dtActivos').find("tbody").off("click");

        obtener_datos("#dtActivos tbody", table);
        obtener_id("#dtActivos tbody", table);
        datos_print("#dtActivos tbody", table);
        datos_recibo("#dtActivos tbody", table);
    }

    var mostrar_pdf_activos = function(){
        $("#boton_pdf_activo").on("click", function(e){
				
				
                //var formData = new FormData(document.getElementById("formFichaActivo"));
                $.ajax({
                    method: "GET",
                    url: "fichaDetallesActivoFijo.php",
                    dataType: "html",
                    data: idAct,
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function( resp ){
                    if( resp == 'ok' ){
                        document.getElementById("formFichaActivo").reset();
                        limpiarHiddens();
                        hideFormShowGrid();
                    }
                    mostrar_msj( resp );
                    $("#modalmensaje").modal("toggle");
                    listar();
                });
        });
    }
	
	$("#boton_pdf_activo").on("click", function(){		
        var queryString = "id=" + $("#formFichaActivo input[name=act_id]").val();
        var url = 'fichaDetallesActivoFijo.php?'+queryString;
		
        popup_datos_decreto(url);
        //$("#formReciboBienes :input").val("");
        //$("#modalReciboBienes").modal("toggle");
    });

    
	
	
    var crear_editar = function(){
        $("#formFichaActivo").on("submit", function(e){
            e.preventDefault();

            var form = $(this);
            form.validate();
            // Adem�s podremos agregar cualquier otro dato, incluso aunque �ste no est� en el formulario.
            // formData.append("dato", "valor"); y formData.append(form.attr("name"), $(this)[0].files[0]);
            if( form.valid()==true ){
                // Enable selects para env�o en caso de haber sido bloqueados
                $("#aneg_id, #ccos_id, #ubi_id").prop("disabled", false);
                // Habilita los campos del C�d. general y de C�d. barras
                $("#act_codigo, #act_codBarras").prop("disabled", false);
                $("#act_porLote_no").prop("disabled", false);
                $("#act_porLote_si").prop("disabled", false);
                $("#act_cantidadLote").prop("disabled", false);
                // Generar el Obj FormData a partir del form y agrega los datos del form
                var formData = new FormData(document.getElementById("formFichaActivo"));
                $.ajax({
                    method: "POST",
                    url: "crudActivosFijos.php",
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function( resp ){
                    if( resp == 'ok' ){
                        document.getElementById("formFichaActivo").reset();
                        limpiarHiddens();
                        hideFormShowGrid();
                    }
                    mostrar_msj( resp );
                    $("#modalmensaje").modal("toggle");
                    listar();
                });
            }
        });
    }

    var eliminar = function(){
        $("#btnModalEliminar").on("click", function(){
            var frm = $("#frmEliminar").serialize();
            $.ajax({
                method: "POST",
                url: "crudActivosFijos.php",
                data: frm
            }).done(function( resp ){
                mostrar_msj( resp );
                $("#modalmensaje").modal("show");
                limpiarHiddens("eliminar");
                listar();
            });
        });
    }

    var obtener_id = function(tbody, table){
        $(tbody).on("click", "button.eliminar", function(){
            var data = table.row( $(this).parents("tr") ).data();
            var actID = $("#id_act_eliminar").val( data.act_id ),
                act_codigo = $("#cod_act_eliminar").text( data.act_codigo );
        });
    }

    // Opci�n obtiene datos para imprimir etiqueta
    var datos_print = function(tbody, table){
        $(tbody).on("click", "button.print", function(){
            /** Datos complementarios para function sendData() **/
            var data = table.row( $(this).parents("tr") ).data();
            var format_area = '<?= ucfirst($_SESSION["emp"]) ?>'+' Temuco';
            var format_fecha = data.act_fechaAdquisicion; // confirmar q fecha es...
            var format_codbar = data.act_codBarras;

            var id_analitico = data.anac_id;

            var format_dir = data.aneg_descripcion; // U. Negocio

            if( id_analitico === null || id_analitico === 0 ){
                var format_afijo = data.act_descripcion;
            }else{
                var desc_analitico = data.anac_descripcion;
                var format_afijo_array = desc_analitico.split("<br />", 1); // Analitico
                var format_afijo = format_afijo_array[0];
            }

            /*
            $.get("traeDatosActivo.php", {anac_id: id_analitico}, function(anac_descripcion){
                if( id_analitico === null || id_analitico === 0 ){
                    var format_afijo = data.act_descripcion;
                }else{
                    var format_afijo = anac_descripcion; // Analitico
                }
            });
            */

            sendData(format_area,format_fecha,format_codbar,format_dir,format_afijo); // llamada a funcion de paramPrintZebra.js
        });
    }

    // Opci�n obtiene datos para recibo bienes
    var datos_recibo = function(tbody, table){
        $(tbody).on("click", "button.recibo", function(){
            var data = table.row( $(this).parents("tr") ).data();

            // Asigna act_id a input hidden del Modal
            $("#recibo_actID").val(data.act_id);
        });
    }

    $("#btnModalReciboBienes").on("click", function(){
        var queryString = $("#formReciboBienes").serialize();
        var url = 'reciboCargoBienes.php?'+queryString;
        popup_recibo(url);
        $("#formReciboBienes :input").val("");
        $("#modalReciboBienes").modal("toggle");
    });

    function popup_recibo(url){
        if( url ){
            var w = window.open(url, 'Recibo_Cargo_Bienes', 'width='+screen.width+', height='+screen.height+', scrollbars=yes');
            w.moveTo(0, 0);
        }
        w.focus();
    }
	
	function popup_datos_decreto(url){
        if( url ){
            var w = window.open(url, 'datos_decreto', 'width='+screen.width+', height='+screen.height+', scrollbars=yes');
            w.moveTo(0, 0);
            w.focus();  
        }
    }

    var obtener_datos = function(tbody, table){
        $(tbody).on("click", "button.editar", function(){
            var data = table.row( $(this).parents("tr") ).data();

            // Cambia opcion para editar
            $("#opcion").val("editar");

            $("#act_codigo").prop("readonly", true);
            $("#act_codBarras").prop("readonly", true);

            var actID = data.act_id;

            $("#actID").val(actID); // Asigna act_id a input del Form

            // Muestra Tab para dar de baja el activo en la opci�n editar
            $("#liTabBaja").show();
            //$("#liTabBaja").removeClass("disabled").children().attr("href", "#tabBaja");
            $("#tabReubicaciones").find("input, select, button").prop("disabled", false);
            $("#tabNotas").find("input, textarea, button").prop("disabled", false);

            // Necesita ir antes de verificar BAJA x el 'disabled'
            var act_umed_id = $("#act_umed_id");
                ( data.act_unidadMedidaVidaUtil == 'P' ) ? act_umed_id.prop("disabled", false).val(data.act_umed_id).trigger("change") : act_umed_id.prop("disabled", true).val('0').trigger("change");

            var act_cantidadLote = $("#act_cantidadLote");

                $("#act_porLote_no").prop("disabled", true);
                $("#act_porLote_si").prop("disabled", true);

                ( data.act_porLote == 'SI' ) ? act_cantidadLote.prop("readonly", true).val(data.act_cantidadLote) : act_cantidadLote.prop("readonly", true).val('');

            if( data.act_codEstado == 'B' ){
                desabilitarForm( actID );
                $("#act_codEstado").val("BAJA");

                /** Muestra valores de Baja **/
                $("#baja_id").val( data.baja_id ).trigger("change");
                var act_fechaBaja = moment(data.act_fechaDeBaja).format("DD-MM-YYYY");
                $("#act_fechaDeBaja").val( act_fechaBaja );
                $("#act_glosaDeBaja").val( data.act_glosaDeBaja );

                $("#btnBajaActivo").addClass("hide");
                $("#grupo-baja").addClass("has-error");
                $("#btnAltaActivo").removeClass("hide").prop("disabled", false);
            }else{
                $("#act_codEstado").val("VIGENTE");
                $("#btnAltaActivo").addClass("hide");
                $("#btnBajaActivo").removeClass("hide");
                $("#grupo-baja").removeClass("has-error");
            }

/*
                 act_fIngreso = moment(data.act_fechaIngreso).format("DD-MM-YYYY"),
                 act_fAdquisicion = moment(data.act_fechaAdquisicion).format("DD-MM-YYYY"),
                 act_fVenceGarantia = moment(data.act_venceUltimaGarantia).format("DD-MM-YYYY"),
 */
                //alert(data.act_fIngreso);
            var act_fIniRevalorizacion = moment(data.act_inicioRevalorizacion).format("MM-YYYY"),
                act_fIniDepreciacion = moment(data.act_inicioDepreciacion).format("MM-YYYY"),
                act_codigo = $("#act_codigo").val( data.act_codigo ),
                act_codBarras = $("#act_codBarras").val( data.act_codBarras ),
                act_rutaImagen = $("#img_thumbnail").attr("src", data.act_rutaImagen),
                act_descripcionDetallada = $("#act_descripcionDetallada").val( data.act_descripcionDetallada ),
                act_fIngreso = $("#act_fechaIngreso").val( data.act_fechaIngreso ),
                act_fAdquisicion = $("#act_fechaAdquisicion").val( data.act_fechaAdquisicion ),
                act_fVenceGarantia = $("#act_venceUltimaGarantia").val( data.act_venceUltimaGarantia );
                ( data.act_tipoControl == 'Activo Fijo' ) ? $("#act_tipoCtrlAF").prop("checked", true) : $("#act_tipoCtrlAdm").prop("checked", true);

                var id_analitico = data.anac_id;
                var selectAnalitico = $("#anac_id");
                var inputDescripcion = $("#act_descripcion");

                /** Determina mostrar descripci�n AF o valor de anal�tico **/
                if( id_analitico === null || id_analitico === 0 ){
                    //alert('AF con Descripci�n Antigua');
                    selectAnalitico.addClass("hide");
                    inputDescripcion.removeClass("hide");

                    var act_descripcion = data.act_descripcion;
                    act_descripcion = act_descripcion.trim();
                    inputDescripcion.val( act_descripcion );
                    //selectAnalitico.html("<option value='"+act_descripcion+"'>"+act_descripcion+"</option>");
                    //selectAnalitico.prepend("<option value='"+act_descripcion+"'>"+act_descripcion+"</option>");
                    //selectAnalitico.prop("disabled", true);
                    //selectAnalitico.prop("selectedIndex", '0');
                }else{
                    //alert('AF con C�digo Anal�tico: ' + id_analitico);
                    inputDescripcion.addClass("hide");
                    selectAnalitico.removeClass("hide");
                    selectAnalitico.val( id_analitico ).trigger("change");
                }

                $("#cing_id").val( data.cing_id ).trigger("change");
                $("#grup_id").val( data.grup_id ).trigger("change");
                $("#cond_id").val( data.cond_id ).trigger("change");
                $("#aux_id").val( data.aux_id ).trigger("change");

                $("#aneg_id").val( data.aneg_id ).trigger("change");
                $("#ccos_id").val( data.ccos_id ).trigger("change");
                //$("#ubi_id").val( data.ubi_id ).trigger("change");
                $("#ccont_id").val( data.ccont_id ).trigger("change");

                //Al Editar, select option toma valor y texto guardado
                $("#ubi_id").html("<option value='"+data.ubi_id+"'>"+data.ubi_descripcion+"</option>");

                $.get("traeDatosActivo.php", {buscar:"cant_ubi", act:actID}, function( reub ){
                    if( reub > 1 ){
                        $("#aneg_id, #ccos_id, #ubi_id").prop("disabled", true);
                    }
                });

                // Al clickear img, abre modal tama�o real
                $("#linkMuestraImg").one("click", function(){
                    $("#imagenAF").attr("src", data.act_rutaImagen);
                });

                if( data.act_rutaDocAdquisicion != '' ){
                    $("#containerDocAdq").removeClass("hide");
                }else{
                    $("#containerDocAdq").addClass("hide");
                }


                // Al clickear link, abre modal tama�o real
                $("#linkMuestraDocAdq").one("click", function(){
                    $("#imgDocAdquisicion").attr("src", data.act_rutaDocAdquisicion);
                });

                // function cambia, luego de x tiempo, el val del subgrupo..
                // debe ser dentro de una func an�nima para q sea compatible
                setTimeout(function(){cambiaSubgrupo(data.sgru_id);}, 1400);

                ( data.act_revalorizable == 'SI' ) ? $("#act_revalorizable_si").prop("checked", true) : $("#act_revalorizable_no").prop("checked", true);
                ( data.act_depreciable == 'SI' ) ? $("#act_depreciable_si").prop("checked", true) : $("#act_depreciable_no").prop("checked", true);
                ( data.act_tipoDepreciacion == 'L' ) ? $("#act_tipoDepreciacion_l").prop("checked", true) : $("#act_tipoDepreciacion_a").prop("checked", true);
                ( data.act_unidadMedidaVidaUtil == 'M' ) ? $("#act_unidadMedidaVidaUtil_m").prop("checked", true) : $("#act_unidadMedidaVidaUtil_p").prop("checked", true);
                ( data.act_porLote == 'NO' ) ? $("#act_porLote_no").prop("checked", true) : $("#act_porLote_si").prop("checked", true);

                ( data.act_bajoNormaPublica == 'Nicsp' ) ? $("#act_bajoNormaNicsp").prop("checked", true) : $("#act_bajoNormaPCGN").prop("checked", true);
                ( data.act_bajoNormaTributaria == 'SI' ) ? $("#act_bajoNormaTributaria").prop("checked", true) : $("#act_bajoNormaTributaria").prop("checked", false);
                ( data.act_bajoNormaIFRS == 'SI' ) ? $("#act_bajoNormaIFRS").prop("checked", true) : $("#act_bajoNormaIFRS").prop("checked", false);

            var act_vidaUtilTributaria = $("#act_vidaUtilTributaria").val( data.act_vidaUtilTributaria ),
                act_vidaUtilFinanciera = $("#act_vidaUtilFinanciera").val( data.act_vidaUtilFinanciera ),
                act_valorAdquisicion = $("#act_valorAdquisicion").val( data.act_valorAdquisicion ),
                act_numDocCompra = $("#act_numDocCompra").val( data.act_numDocCompra ),
                act_numOrdenCompra = $("#act_numOrdenCompra").val( data.act_numOrdenCompra ),
                act_valorResidual = $("#act_valorResidual").val( data.act_valorResidual ),
                act_presupuesto = $("#act_presupuesto").val( data.act_presupuesto ),
                act_fIniRevalorizacion = $("#act_inicioRevalorizacion").val( act_fIniRevalorizacion ),
                act_fIniDepreciacion = $("#act_inicioDepreciacion").val( act_fIniDepreciacion ),
                ftec_descripcion = $("#ftec_descripcion").val( data.ftec_descripcion ),
                act_marca = $("#act_marca").val( data.act_marca ),
                act_modelo = $("#act_modelo").val( data.act_modelo ),
                act_serie = $("#act_serie").val( data.act_serie ),
                act_patenteVehiculo = $("#act_patenteVehiculo").val( data.act_patenteVehiculo ),
                act_siglaVehiculo = $("#act_siglaVehiculo").val( data.act_siglaVehiculo ),
                act_chasisVehiculo = $("#act_chasisVehiculo").val( data.act_chasisVehiculo ),
                act_numMotorVehiculo = $("#act_numMotorVehiculo").val( data.act_numMotorVehiculo ),
                act_numCarpetaInmueble = $("#act_numCarpetaInmueble").val( data.act_numCarpetaInmueble ),
                act_numInmueble = $("#act_numInmueble").val( data.act_numInmueble );
                act_fechaEscritura = moment(data.act_fechaEscritura).format("DD-MM-YYYY"),
                act_fechaEscritura = $("#act_fechaEscritura").val( act_fechaEscritura ),
                act_anioInmueble = $("#act_anioInmueble").val( data.act_anioInmueble ),
                act_sectorInmueble = $("#act_sectorInmueble").val( data.act_sectorInmueble ),
                act_rolInmueble = $("#act_rolInmueble").val( data.act_rolInmueble ),
                act_DAInmueble = $("#act_DAInmueble").val( data.act_DAInmueble ),
                act_fojaInmueble = $("#act_fojaInmueble").val( data.act_fojaInmueble ),
                act_fechaIngresoInmueble = moment(data.act_fechaIngresoInmueble).format("DD-MM-YYYY"),
                act_fechaIngresoInmueble = $("#act_fechaIngresoInmueble").val( act_fechaIngresoInmueble ),
                act_medidaTerrenoInmueble = $("#act_medidaTerrenoInmueble").val( data.act_medidaTerrenoInmueble ),
                act_propAnteriorInmueble = $("#act_propAnteriorInmueble").val( data.act_propAnteriorInmueble ),
                act_nombreInmueble = $("#act_nombreInmueble").val( data.act_nombreInmueble ),
                act_detalleInmueble = $("#act_detalleInmueble").val( data.act_detalleInmueble ),
                act_direccionInmueble = $("#act_direccionInmueble").val( data.act_direccionInmueble ),
                act_obsInmueble = $("#act_obsInmueble").val( data.act_obsInmueble );

                $("#tdoc_id").val( data.tdoc_id ).trigger("change");
                $("#act_tipoInmueble").val( data.act_tipoInmueble ).trigger("change");

                $("#btnGuardarReubicar").off("click"); // necesario para desacoplar evento al cambiar de activo

                $("#btnGuardarReubicar").on("click", function(){
                    var inputActID = $("#actID");
                    var inputFecha = $("#reub_fecha");
                    var inputHora = $("#reub_hora");
                    var inputUbi = $("#ubiID_reub");
                    var inputCCos = $("#ccosID_reub");
                    var inputAneg = $("#anegID_reub");

                    var actID = inputActID.val();
                    var fecha = inputFecha.val();
                    var hora = inputHora.val();
                    var ubiID = inputUbi.val();
                    var ccosID = inputCCos.val();
                    var anegID = inputAneg.val();

                    if( ! (moment(fecha, "DD-MM-YYYY", true).isValid()) ){
                        inputFecha.focus();
                        alert("La fecha ingresada no es v�lida");
                    }else if( ! (moment(hora, "HH:mm", true).isValid()) ){
                        inputHora.focus();
                        alert("La Hora ingresada no es v�lida (HH:mm)");
                    }else if( ubiID == 0 ){
                        inputUbi.focus();
                        alert("Debe seleccionar una Ubicaci�n.");
                    }else if( ccosID == 0 ){
                        inputCCos.focus();
                        alert("Debe seleccionar un Centro de Costo.");
                    }else if( anegID == 0 ){
                        inputAneg.focus();
                        alert("Debe seleccionar un Area de Negocios.");
                    }else{
                        $.ajax({
                            method: "POST",
                            url: "traeDatosActivo.php",
                            data: {insertarReub: 'si', rehuFecha: fecha, rehuHora: hora, rehuUbiID: ubiID, rehuCcosID: ccosID, rehuAnegID: anegID, act: actID}
                        }).done(function( resp ){
                            alert(resp);
                            inputFecha.val('');
                            inputHora.val('');
                            inputUbi.val(0).trigger("change");
                            inputCCos.val(0).trigger("change");
                            inputAneg.val(0).trigger("change");

                            $.get("traeDatosActivo.php", {refresh:"lugares", act:actID}, function( data ){
                                var datos = JSON.parse(data);
                                $("#aneg_id").val( datos.aneg_id ).trigger("change");
                                $("#ccos_id").val( datos.ccos_id ).trigger("change");
                                $("#ubi_id").val( datos.ubi_id ).trigger("change");
                            });


                            $.get("traeDatosActivo.php", {buscar:"cant_ubi", act:actID}, function( reub ){
                                if( reub > 1 ){
                                    $("#aneg_id, #ccos_id, #ubi_id").prop("disabled", true);
                                }
                            });

                            // Refresca la tabla con el nuevo registro de nota insertada
                            $("#historialUbicaciones").load("traeDatosActivo.php?buscar=reubicaciones&act="+actID);
                        });
                    }
                });

                $("#btnBorrarReubicar").off("click"); // necesario para desacoplar evento al cambiar de activo

                // Trae el historial de reubicaciones del activo al momento de seleccionarlo en la grilla
                $("#historialUbicaciones").load("traeDatosActivo.php?buscar=reubicaciones&act="+actID, function(){
                    // Enlaza btn para borrar la "�ltima" ubicaci�n
                    $("#btnBorrarReubicar").on("click", function(){
                        var idUltUbi = $("#historialUbicaciones tr").filter(":first").attr("id"); // get id for first tr

                        respuesta = confirm("�Seguro desea eliminar la �ltima ubicaci�n ingresada?");

                        if( respuesta ){
                            $.ajax({
                                method: "POST",
                                url: "traeDatosActivo.php",
                                data: {borrarReubicar: idUltUbi, act: actID}
                            }).done(function( resp ){
                                alert(resp);

                                $.get("traeDatosActivo.php", {refresh:"lugares", act:actID}, function( data ){
                                    var datos = JSON.parse(data);
                                    $("#aneg_id").val( datos.aneg_id ).trigger("change");
                                    $("#ccos_id").val( datos.ccos_id ).trigger("change");
                                    $("#ubi_id").val( datos.ubi_id ).trigger("change");
                                });

                                $.get("traeDatosActivo.php", {buscar:"cant_ubi", act:actID}, function( reub ){
                                    if( reub == 1 ){
                                        $("#aneg_id, #ccos_id, #ubi_id").prop("disabled", false);
                                    }
                                });

                                $("#historialUbicaciones").load("traeDatosActivo.php?buscar=reubicaciones&act="+actID);
                            });
                        }
                    });
                });

                $("#tabNotas").off("click", "button.borrar"); // necesario para desacoplar evento al cambiar de activo

                // Trae todas las notas del activo al momento de seleccionarlo en la grilla
                $("#historialNotasActivo").load("traeDatosActivo.php?buscar=notas&act="+actID, function(){
                    // Se dejan enlazados los btns para luego borrar notas
                    $("#tabNotas").on("click", "button.borrar", function(){
                        respuesta = confirm("�Seguro desea eliminar la nota seleccionada?");
                        var idNota = $(this).val();
                        if( respuesta ){
                            $.ajax({
                                method: "POST",
                                url: "traeDatosActivo.php",
                                data: {borrarNota: idNota}
                            }).done(function( resp ){
                                alert(resp);
                                // Una vez recibida la respuesta del evento eliminar, refresca la tabla notas
                                $("#historialNotasActivo").load("traeDatosActivo.php?buscar=notas&act="+actID);
                            });
                        }
                    });
                });

                /* Pasa una variable con el id del activo para traer las notas de �ste. VERSION MODAL (al cancelar guarda en cache, lo q borra todo)
                $("#historialNotasActivo").load("traeDatosActivo.php?buscar=notas&act="+data.act_id, function(){
                    $("#tabNotas").on("click", "button.borrar", function(){
                        var idNota = $(this).val();
                        //respuesta = confirm("�Seguro desea eliminar la nota seleccionada?");
                        var actID = $("#actID").val();
                        $("#btnModalBorrar").on("click", function(){
                            $.ajax({
                                method: "GET",
                                cache: false,
                                url: "traeDatosActivo.php",
                                data: {borrarNota: idNota}
                            }).done(function( resp ){
                                $("#btnModalBorrar").off("click");
                                $("#tabNotas").off("click", "button.borrar");
                                $("#historialNotasActivo").load("traeDatosActivo.php?buscar=notas&act="+actID);
                            });
                        });
                    });
                });
                */
                $("#btnGuardarNota").off("click"); // necesario para desacoplar evento al cambiar de activo

                $("#btnGuardarNota").on("click", function(){
                    var inputActID = $("#actID");
                    var inputFecha = $("#nota_fecha");
                    var inputDesc = $("#nota_descripcion");
                    var inputDetalle = $("#nota_detalles");

                    var actID = inputActID.val();
                    var fecha = inputFecha.val();
                    var descripcion = inputDesc.val();
                    var detalles = inputDetalle.val();

                    if( ! (moment(fecha, "DD-MM-YYYY", true).isValid()) ){
                        inputFecha.focus();
                        alert("La fecha ingresada no es v�lida");
                    }else if( descripcion.length == 0 ){
                        inputDesc.focus();
                        alert("Debe ingresar una Descripci�n.");
                    }else if( detalles.length == 0 ){
                        inputDetalle.focus();
                        alert("Debe ingresar los detalles.");
                    }else{
                        $.ajax({
                            method: "POST",
                            url: "traeDatosActivo.php",
                            data: { insertarNota: 'si', notaFecha: fecha, notaDetalles: detalles, notaDescripcion: descripcion, act: actID }
                        }).done(function( resp ){
                            alert(resp);
                            inputFecha.val('');
                            inputDesc.val('');
                            inputDetalle.val('');
                            // Refresca la tabla con el nuevo registro de nota insertada
                            $("#historialNotasActivo").load("traeDatosActivo.php?buscar=notas&act="+actID);
                        });
                       }
                });

                $("#btnBajaActivo").off("click"); // necesario para desacoplar evento al cambiar de activo

                $("#btnBajaActivo").on("click", function(){
                    var inputActID = $("#actID");
                    var inputFecha = $("#act_fechaDeBaja");
                    var inputDesc = $("#act_glosaDeBaja");
                    var inputConceptoID = $("#baja_id");

                    var actID = inputActID.val();
                    var fecha = inputFecha.val();
                    var descripcion = inputDesc.val();
                    var concepto = inputConceptoID.val();

                    if( ! (moment(fecha, "DD-MM-YYYY", true).isValid()) ){
                        inputFecha.focus();
                        alert("La fecha ingresada no es v�lida");
                    }else if( concepto == '0' ){
                        inputConceptoID.focus();
                        alert("Debe seleccionar un concepto de Baja.");
                    }else if( descripcion.length == 0 ){
                        inputDesc.focus();
                        alert("Debe ingresar una Descripci�n.");
                    }else{
                        $.ajax({
                            method: "POST",
                            url: "traeDatosActivo.php",
                            data: { bajarActivo: 'si', fechaBaja: fecha, conceptoBaja: concepto, descripcionBaja: descripcion, act: actID }
                        }).done(function( resp ){
                            if( resp == 'ok' ){
                                alert("Activo dado de Baja Corretamente.");
                                inputFecha.val('');
                                inputDesc.val('');
                                inputConceptoID.val(0).trigger("change");

                                hideFormShowGrid();
                                listar();
                            }else{
                                alert("Error al intentar dar de Baja el Activo.");
                            }
                        });
                    }
                });

                $("#btnAltaActivo").off("click"); // necesario para desacoplar evento al cambiar de activo

                $("#btnAltaActivo").on("click", function(){
                    var respuesta = confirm("�Seguro desea revertir la baja del Activo?");

                    if( respuesta ){
                        var actID = $("#actID").val();
                        $.ajax({
                            method: "POST",
                            url: "traeDatosActivo.php",
                            data: { altaActivo: 'si', act: actID }
                        }).done(function( resp ){
                            if( resp == 'ok' ){
                                alert("El Activo a pasado nuevamente a Vigente.");
                                hideFormShowGrid();
                                //Desabilita form y quita btn guardar
                                $("#btnCerrarForm").trigger("click");
                                listar();
                            }else{
                                alert("Hubo un error al intentar cambiar el Estado.");
                            }
                        });
                    }
                });

                hideGridShowForm();
        });

        // Opci�n para ver ficha en modo s�lo lectura
        $(tbody).on("click", "button.ver", function(){
            var data = table.row( $(this).parents("tr") ).data();
            var btnEditar = $(this).parents("tr").find("button.editar");
            btnEditar.trigger("click");

            desabilitarForm( data.act_id );
        });
    }

    var habilitarForm = function(){
        var fichaDelActivo = $("#formFichaActivo");
        // Reactiva los input, select, textarea y button del form
        fichaDelActivo.find("input, textarea").prop("disabled", false);
        fichaDelActivo.find("select, button").prop("disabled", false);
		
        $("#act_bajoNormaTributaria, #act_bajoNormaIFRS").prop("disabled", true);
    }

    var desabilitarForm = function( actID ){
        $("#btnCerrarForm").removeClass("hide");
        var fichaDelActivo = $("#formFichaActivo");
        $("#btnCancelarForm, #btnGuardarForm").hide();
        // Desactiva los input, select, textarea y button del form
        fichaDelActivo.find("input, textarea").prop("disabled", true);
        fichaDelActivo.find("select, button").not("#btnCerrarForm").prop("disabled", true);
		
        // necesario recargar nuevamente la tabla de notas para desabilitar los botones de eliminar
        $("#historialNotasActivo").load("traeDatosActivo.php?buscar=notas&act="+actID, function(){
            $("button.borrar").prop("disabled", true);
        });
		
		$("#boton_pdf_activo").prop("disabled",false);
		
	}

    $("#btnCerrarForm").on("click", function(){
        $(this).addClass("hide");
        $("#btnCancelarForm, #btnGuardarForm").show();

        habilitarForm();
    });

    function cambiaSubgrupo(idSubGrupo){
         $("#sgru_id").val( idSubGrupo ).trigger("change");
    }

    $("#grup_id").on("change", function(){
        var id = $(this).val();
        $("#sgru_id").load("traeDatosActivo.php?buscar=subgrupo&id="+id);
    });

    var refrescarLugares = function( actID ){

    }

    var mostrar_msj = function( respuesta ){
        var msj = $("#msj_respuesta");
        var texto = "<b>Resultado: </b>";
        var alertClass = "";
        msj.html("").removeClass("alert alert-success");
        msj.html("").removeClass("alert alert-danger");
        msj.html("").removeClass("alert alert-warning");

        if( respuesta == 'ok' ){
            $("div.has-error").removeClass("has-error");
            texto += "La operaci�n se a realizado correctamente.";
            alertClass = "alert alert-success";
        }else if( respuesta == 'error' ){
            texto += "No a sido posible ejecutar la consulta.";
            alertClass = "alert alert-danger";
        }else if( respuesta == 'existe' ){
            $("div.has-error").removeClass("has-error");
            texto += "El c�digo que intenta ingresar ya existe !!";
            alertClass = "alert alert-warning";
            if (opcion=='Editar'){
                $("#opcion").val("editar");
            }else{
                $("#opcion").val("insertar");
            }
        }else if( respuesta == 'no_eliminar' ){
            texto += "El activo que intenta eliminar ya cuenta con movimientos o decretos emitidos.";
            alertClass = "alert alert-warning";
        }else if(JSON.parse(respuesta)){
            var arrayjs=JSON.parse(respuesta);
            var str ='';
            texto +="<ul>";
            $("div.has-error").removeClass("has-error");
            for(var i=0; i < arrayjs.invali.length; i++){
                 str_a="";
                 separador="</span>";
                 limitar=2;
                 str=arrayjs.invali[i];
                 str_a=str.substring(34);
                 str_a=str_a.split(separador,limitar);
                 str_a=str_a[0].replace(" ","_");
                 if(str_a===str_a){
                 $("#"+str_a).parent().addClass('has-error');
                 }
                 texto += "<li>"+ str.replace("act","")+"</li>";
            }
            texto +="</ul>";
            alertClass = "alert alert-danger";
        }else{
            // disponible para otras respuestas
            texto += respuesta;
            alertClass = "alert alert-warning";
        }

        msj.html(texto).addClass(alertClass);
    }

    var limpiarHiddens = function( opcion ){
        if( opcion == "eliminar" ){
            $("#id_act_eliminar").val("");
            $("#cod_act_eliminar").text("");
        }else{
            $("#actID").val("");
            $("#historialUbicaciones").html("");
            $("#historialNotasActivo").html("");
            $("#tooltip_ubicacion").html("");
        }
    }

    function mostrarImagen(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            reader.onload = function (e){
                $('#img_thumbnail').attr('src', e.target.result);
                $('#img_thumbnail').attr('data-src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#file_url").on("change", function(){
        mostrarImagen(this);
    });

    var resetFormTabs = function(){
        var tabs  = $("li.my-tab").removeClass("active");
        var ancla = tabs.filter(":first").addClass("active").find("a");
        var id = $(ancla).attr("href");
        $(".div-tab").addClass("hide");
        $(id).removeClass("hide");
    }

    var hideFormShowGrid = function(){
        $("#capaForm").slideUp("slow");
        $("#capaRegistros").slideDown("slow");
    }

    var hideGridShowForm = function(){
        resetFormTabs();
        $("#capaRegistros").slideUp("slow");
        $("#capaForm").slideDown("slow").removeClass("hide");
    }

    $("#btnCancelarForm, #btnCerrarForm").on("click", function(){
        $("#formFichaActivo").validate().resetForm();
        limpiarHiddens();
        hideFormShowGrid();
        $("#opcion").val("");
    });

    $("#btnCancelEliminar").on("click", function(){
        limpiarHiddens("eliminar");
    });

    $("#btnCerrarMsj").on("click", function(){
        $("#msj_respuesta").html("");
    });

    $(".input-fecha").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        yearRange: "1980:2030",
        minDate: "01-01-1980",
        maxDate: "31-12-2030"
    });

    $(".input-mesAnio").datepicker({
        dateFormat: "mm-yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: "1980:2030",
        onClose: function(dateText, inst){
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
        },
        beforeShow : function(input, inst){
            if ((datestr = $(this).val()).length > 0) {
                year = datestr.substring(datestr.length-4, datestr.length);
                month = datestr.substring(0, 2);
                $(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
                $(this).datepicker('setDate', new Date(year, month-1, 1));
            }
        }
    }).focus(function(){
        $("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: $(this)
        });
    });

    if($.datepicker._get(inst, "dateFormat") === "mm-yy"){
        $(".ui-datepicker-calendar").hide();
    }

    var validator = $("#formFichaActivo").validate({
        submitHandler: function(form){
        }
    });
    </script>
  </body>
</html>

<!-- Static navbar -->
      <!-- men� pegado al techo a�adir a class navbar-fixed-top -->
      <nav class="navbar navbar-default navbar-inverse" role="navigation">
          <div class="navbar-header">

          <span class="navbar-text"><em><?php if( isset($_SESSION['fullname']) ) echo $_SESSION['fullname']; ?></em></span>

          <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          </div>

          <div class="collapse navbar-collapse navHeaderCollapse" id="navbar">
            <ul class="nav navbar-nav">
              <!-- Inicio -->
              <li class=""><a href="inicio.php">Inicio</a></li>
              <!-- Men� Desplegable -->
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Activos <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <!-- <li class="dropdown-header">:: Header</li> -->
                    <li><a href="nuevoActivoFijo.php">Nuevo Activo Fijo</a></li>
                    <li><a href="activosFijos.php">Listar Activos Fijos</a></li>
                    <?php if( $_SESSION['perfil'] == 1 || $_SESSION['perfil'] == 2 ){ ?>
                    <li><a href="informeCalcDepreCM.php">C&aacute;lc. Depreciaci&oacute;n &amp; CM</a></li>
                    <!--
                    <li><a href="informeDeprActivos.php">C&aacute;lculo Depreciaci&oacute;n</a></li>
                    <li class="divider"></li>
                    <li class="dropdown-header">:: Procesos</li>
                    <li class="divider"></li>
                    <li><a href="cargaMasivaAF.php">Carga Masiva de AF</a></li>
                     -->
                    <?php } ?>
                  </ul>
              </li>
              <!-- Men� Decretos & Movimientos -->
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Decretos <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="selectActivosDecretoAlta.php">Decretos de Alta</a></li>
                    <li><a href="selectActivosDecretoBaja.php">Decretos de Baja</a></li>
                    <li><a href="selectActivosDecretoTraslado.php">Decretos de Traslado</a></li>
                    <li><a href="selectActivosDecretoReincorporacion.php">Decretos de Reincorporaci&oacute;n</a></li>
                  </ul>
              </li>
              <!-- Men� Informes -->
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Informes <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <!-- <li class="dropdown-header">:: Header</li> -->
                    <li><a href="informeAltaActivos.php">Alta de Activos</a></li>
                    <li><a href="informeBajaActivos.php">Baja de Activos</a></li>
                    <li><a href="selectPlanchetaUbicacion.php">Plancheta Ubicaciones</a></li>
                    <li><a href="informeParametricoAF.php">Informe Param&eacute;trico</a></li>
                    <!-- <li class="divider"></li> -->
                  </ul>
              </li>
			  <!-- Menu Decretos Realizados-->		
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Modificar Decretos Emitidos <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <!-- <li class="dropdown-header">:: Header</li> -->
                    <li><a href="selectModificarDecretoAlta.php">Decretos Alta Realizados</a></li>
                    <li><a href="selectModificarDecretoBaja.php">Decretos Baja Realizados</a></li>
                    <li><a href="selectModificarInformeTraslado.php">Decretos Traslado Realizados</a></li>
                    <li><a href="selectModificarInformeReincorporacion.php">Informe Param&eacute;trico</a></li>
                    <!-- <li class="divider"></li> -->
                  </ul>
              </li>

              <!-- <li><a href="printBarcode.php" class="">Imprimir</a></li> -->
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <!--
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Procesos <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="tomaInventario.php">Toma de Inventario</a></li>
                  </ul>
              </li>
              -->

              <!-- <li class="active">
                <a href="Manual_de_Uso_Sistema.pdf" target="_blank">Manual</a>
              </li> -->
              <?php if( $_SESSION['perfil'] == 1 ){ ?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Par&aacute;metros <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li class="dropdown-submenu">
                      <a tabindex="-1" href="#">Decretos</a>
                        <ul class="dropdown-menu">
                          <!-- <li><a tabindex="-1" href="#">Second level</a></li> -->
                          <li class="dropdown-submenu">
                            <a href="#">Altas</a>
                            <ul class="dropdown-menu">
                            	<li><a href="datosDecretoAltas.php">Decreto Alta</a></li>
                            	<li><a href="vistosAlta.php">Vistos Alta</a></li>
                                <li><a href="distribucionAlta.php">Distribuci&oacute;n Alta</a></li>
                            </ul>
                          </li>

                          <li class="dropdown-submenu">
                            <a href="#">Bajas</a>
                            <ul class="dropdown-menu">
                            	<li><a href="datosDecretoBajas.php">Decreto Baja</a></li>
                            	<li><a href="vistosBaja.php">Vistos Baja</a></li>
                                <li><a href="distribucionBaja.php">Distribuci&oacute;n Baja</a></li>
                            </ul>
                          </li>

                          <li class="dropdown-submenu">
                            <a href="#">Traslados</a>
                            <ul class="dropdown-menu">
                                <li><a href="datosDecretoTraslados.php">Decreto Traslado</a></li>
                                <li><a href="vistosTraslado.php">Vistos Traslado</a></li>
                                <li><a href="distribucionTraslado.php">Distribuci&oacute;n Traslado</a></li>
                            </ul>
                          </li>
                          <!-- <li><a href="#">Second level</a></li> -->
                        </ul>
                    </li>
                    <li><a href="datosPlanchetaAF.php">Plancheta</a></li>
                    <!-- <li><a href="paramBasicEmpresa.php">Param. Empresa</a></li> -->
                  </ul>
              </li>

              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Entidades <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="areasNegocios.php">&Aacute;reas de Negocio</a></li>
                    <li><a href="centrosCosto.php">Centros de Costo</a></li>
                    <li><a href="cargos.php">Cargos</a></li>
					<li><a href="AnaliticosCuentas.php">Analiticos Cuentas</a></li>
                    <li><a href="conceptosIngreso.php">Conceptos de Ingreso</a></li>
                    <li><a href="conceptosBaja.php">Conceptos de Baja</a></li>
                    <li><a href="grupos.php">Grupos</a></li>
                    <li><a href="subGrupos.php">SubGrupos</a></li>
                    <li><a href="proveedores.php">Proveedores</a></li>
                    <li><a href="usuarios.php">Usuarios</a></li>
                    <li><a href="giros.php">Giros</a></li>
                    <li><a href="comunas.php">Comunas</a></li>
                    <li><a href="ubicaciones.php">Ubicaciones</a></li>
                    <li><a href="responsables.php">Responsables</a></li>
                    <li><a href="unidadesMedida.php">Unidades Medida</a></li>
                    <li><a href="condicionActivo.php">Condiciones del Activo</a></li>
                    <li><a href="tipoDocumento.php">Tipo de Documento</a></li>
                    <li><a href="factorAnualIPC.php">Factor Anual IPC</a></li>
                    <!-- <li><a href="ipc.php">IPC</a></li> -->
                  </ul>
              </li>

              <!--
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Administraci&oacute;n <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="usuarios.php">Cuentas de Usuarios</a></li>
                    <li><a href="logAccesoUsuarios.php">Log de Acceso Usuarios</a></li>
                  </ul>
              </li>
              -->
              <?php } ?>
              <li class="active"><a href="logout.php?logout">Cerrar Sesi&oacute;n</a></li>
            </ul>
          </div><!--/.nav-collapse -->
      </nav>

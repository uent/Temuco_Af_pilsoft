<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Inventario de Activo Fijo</title>    
    <?php include_once 'tagsMeta.php'; ?>
    <?php include_once 'tagsFixJS.php'; ?>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet" />
  </head>
  <body>
    <div class="container">
      <div class="text-center" style="padding-top: 20px; margin-bottom: 10px;">
        <img src="images/logo_fya.png" class="img-thumbnail" />
      </div>

        <div class="signin-descripcion bg-primary">
            <span class="">
                <em>Control y Gesti&oacute;n de Inventario Activo Fijo</em>
            </span>
        </div>

      <form action="checkLogin.php" method="post" class="form-signin" role="form">
       <div class="well">
        <h2 class="form-signin-heading text-primary">Iniciar Sesi&oacute;n</h2>        
        <?php
        if(isset($_GET['logout'])){
            echo '<label class="control-label text-primary">Sesi&oacute;n Finalizada Correctamente !!</label>';
        }
        
        if(isset($_GET['err'])){
            if($_GET['err']==1){
                echo '<label class="control-label text-primary">Por favor, Ingrese Usuario y Contrase&ntilde;a</label>';
            }
            if($_GET['err']==2){
                echo '<label class="control-label text-danger">Usuario Incorrecto</label>';
            }
            if($_GET['err']==3){
                echo '<label class="control-label text-danger">Contrase&ntilde;a Incorrecta</label>';
            }            
            if($_GET['err']==4){
                echo '<label class="control-label text-danger"><strong>El tiempo de la sessi&oacute;n ha caducado.</strong></label>';
            }
            if($_GET['err']==5){
                echo '<label class="control-label text-primary">Por favor, seleccione un <b><em>Departamento</em></b></label>';
            }                        
        }
        ?>        
        <label for="inputUser" class="sr-only">Usuario</label>
        <input name="username" id="inputUser" class="form-control input-sm" placeholder="Usuario" required="" autofocus="" type="text" />
        <label for="inputPassword" class="sr-only">Password</label>
        <input name="password" id="inputPassword" class="form-control input-sm" placeholder="Contrase&ntilde;a" required="" type="password"  min="6" max="30" />
        <select name="empresa" id="empresa" class="form-control input-sm" required="">
            <option value=""> -- Seleccione -- </option>
            <option value="salud">Salud</option>
            <option value="municipal">Municipalidad</option>
            <option value="educacion">Educaci&oacute;n</option>            
            <option value="cementerio">Cementerio</option>
        </select>
        <div class="checkbox">
          <label>
            <input value="remember-me" type="checkbox" /> Recordarme
          </label>
        </div>
        <button class="btn btn-primary btn-md btn-block" type="submit">Acceder</button>
        <input type="hidden" name="login" value="" />
       </div> <!-- /pozo -->
      </form>

    </div> <!-- /container -->

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
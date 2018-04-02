<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Control y Gesti&oacute;n de Activo Fijo</title>
    <?php include_once 'tagsMeta.php'; ?>
    <?php include_once 'tagsCSS.php'; ?>    
    <?php include_once 'tagsFixJS.php'; ?>
  </head>
  <body>
    <div class="container">      
      <?php include_once 'menuInicio.php'; ?>
      <div class="jumbotron">
        <div class="text-center">        
            <div><img src="images/TEMUCO.png" alt="Logo Empresa" class="img-thumbnail" title="Logo Empresa" /></div>
            <h3><em>Control y Gesti&oacute;n de Activo Fijo</em></h3>
        </div>               
        <br />
        
        <div class="row text-center">
            <div class="col-xs-12 col-md-4 col-md-offset-4">
                <button type="button" class="btn btn-primary btn-lg btn-block">AREA <?= strtoupper($_SESSION['emp']) ?></button>
                <!--           
                <div class="btn-group dropdown">              
                  <button type="button" class="btn btn-primary btn-lg">Seleccione Empresa</button>
                  <button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Expandir Me&uacute;</span>
                  </button>              
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Empresa Demo #1</a></li>
                    <li><a href="#">Empresa Demo #2</a></li>
                  </ul>
                </div>
                -->                            
            </div>
        </div>        
        <!-- /Split button -->                                  
        <div class="clearfix visible-xs-block"></div>       
      </div> <!-- /jumbotron -->
    </div> <!-- /container -->
    
<?php include_once 'tagsJS.php'; ?>    
<script type="text/javascript">
$(document).ready(function(){    
    function parpadear(){
        $('#idElem').fadeIn(800).delay(400).fadeOut(200, parpadear);
    }
    //parpadear();
});
</script>
  </body>
</html>
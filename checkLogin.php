<?php
//check if form is submitted
if( isset($_POST['login']) )
{
    $bd = '';
    $prefix  = "af_temuco_area_";
    $empresa = stripslashes(trim($_POST['empresa']));
        
    if( $empresa == 'salud' ){
        $bd = $prefix.$empresa;
        $emp_codigo = 3;
    }elseif( $empresa == 'municipal' ){
        $bd = $prefix.$empresa;
        $emp_codigo = 1;
    }elseif( $empresa == 'educacion' ){
        $bd = $prefix.$empresa;
        $emp_codigo = 2;
    }elseif( $empresa == 'cementerio' ){
        $bd = $prefix.$empresa;
        $emp_codigo = 4;
    }else{
        header("location:index.php?err=5");
        exit();
    }

    //inicia sesión
    session_start();
    
    $_SESSION['BD'] = $bd;
    $_SESSION['emp'] = $empresa;
    $_SESSION['emp_codigo'] = $emp_codigo;
        
    if( empty($_SESSION['BD']) ){
        header("location:index.php?err=5");
        exit();
    }

    //conexión a bd con identificador de enlace.
    $conexion = mysqli_connect('localhost', 'root', '') or die("Error de conexion ". mysqli_connect_error());
    mysqli_select_db($conexion, $_SESSION['BD']) or die("Error de Acceso a Base de Datos");
    
    $inicio = "SELECT usu_usuario FROM usuarios";
    $res = mysqli_query($conexion, $inicio) or die(mysqli_error($conexion));
    $rows = mysqli_num_rows($res);  

    if( $rows == 0 )
    {
        $pass = "f0rtun1t0";
        //$sal  = "admin";
        $pwdHash = password_hash($pass, PASSWORD_DEFAULT); //hash("sha1", $pass.$sal);  
        
        $sql = "INSERT INTO usuarios (usu_nombre, usu_apePaterno, usu_apeMaterno, usu_usuario, perf_id, usu_estado, usu_password, usu_creador)
                VALUES ('Admin', 'System', '', 'admin', '1', '1', '".$pwdHash."', '1')";         
        mysqli_query($conexion, $sql) or die("Error en el intento del Default User !! ".mysqli_error($conexion));
    }

	//check if every field is entered
	if( empty($_POST['username']) || empty($_POST['password']) )
	{
        mysqli_close($conexion);
		header("location:index.php?err=1");
        exit();
	}
    else
	{
		$username = stripslashes(trim($_POST['username']));
		$password = stripslashes(trim($_POST['password']));

        // ...To protect MySQL injection
        $username = mysqli_real_escape_string($conexion, $username);
        $password = mysqli_real_escape_string($conexion, $password);

        // encrypt password
        //$enc_password = hash("sha1", $password.$username);
        //$passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        //check if username exists in db
        $query = "SELECT usu_id, usu_nombre, usu_apePaterno, usu_apeMaterno, usu_usuario, perf_id, usu_password 
                  FROM usuarios WHERE usu_usuario='".$username."' AND usu_estado='1' LIMIT 1";
                  //WHERE usu_usuario='".$username."' AND usu_password='".$passwordHash."' AND usu_estado='1' LIMIT 1";
		$res = mysqli_query($conexion, $query) or die(mysqli_error($conexion));
        $usuario = mysqli_fetch_assoc($res);
		$count = mysqli_num_rows($res);
        
        $hash  = $usuario['usu_password'];
        
        if( $count != 1 )
        {   
            mysqli_close($conexion);
            // user incorrecto o inexistente
            header("location:index.php?err=2");
        }
        elseif( !password_verify($password, $hash) )
		{
            mysqli_close($conexion);
			// if not, redirect to login page
			header("location:index.php?err=3");		  
		}
		else
		{
			//if yes, start session and set a variable
			$_SESSION['logged_in']  = 1;
            $_SESSION['userid']     = $usuario['usu_id'];
            $_SESSION['username']   = $usuario['usu_usuario'];
            $_SESSION['nombre']     = $usuario['usu_nombre'];
            $_SESSION['apePaterno'] = $usuario['usu_apePaterno'];
            $_SESSION['apeMaterno'] = $usuario['usu_apeMaterno'];
            $_SESSION['fullname']   = $_SESSION['nombre'].' '.$_SESSION['apePaterno'];
            $_SESSION['perfil']     = $usuario['perf_id'];
            
            $sqlLog = "INSERT INTO log_access_users (log_userID, log_addressIP, log_creador) 
                       VALUES (".$usuario['usu_id'].", '".$_SERVER['REMOTE_ADDR']."', ".$usuario['usu_id'].")";
            mysqli_query($conexion, $sqlLog) or die(mysqli_error($conexion));           
            
            header("location:inicio.php");
            
            /**
             if( $usuario['perfil'] == 'Administrador' ){
                 header("location:inicio.php");
             }elseif( $usuario['perfil'] == 'Junaeb' ){
                 header("location:inicio.php");
             }elseif( $usuario['perfil'] == 'Jefe Proyecto' ){
                 header("location:inicio.php");                            
             }elseif( $usuario['perfil'] == 'Supervisor' ){
                 header("location:inicio.php");
             }elseif( $usuario['perfil'] == 'Revisor' ){
                 header("location:inicio.php");
             }
             */
		}
        
        mysqli_close($conexion);
	}
}else{
    header("location:index.php");
}
?>
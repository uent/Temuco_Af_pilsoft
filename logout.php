<?php
	//start session and check if session variable is set
	session_start();
	if(!isset($_SESSION['logged_in']))
	{
		header("location:index.php");
	}
	// if yes, show status and an logout button
	else
	{
		//if logout link is clicked, unset the session variable and redirect to index.php?logout
		if(isset($_GET['logout']))
		{
			#elimina el dato espec�fico utilizado en la sesi�n actual
            unset($_SESSION['logged_in']);
            
            #elimina todos los datos de la sesi�n al mismo tiempo
            session_unset();
            
            #cerramos definitivamente la sesi�n misma
            session_destroy();
            
			header("location:index.php?logout");
		}
	}
?>
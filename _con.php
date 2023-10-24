<<<<<<< HEAD
<?php
	@header('Content-Type: text/html; charset=utf-8');
	//Conexão com banco de dados php7
	
	$localhost = false;

	if($localhost):
		$servername 	= "localhost";
		$username	  	= "root";
		$password		= '';
		$db_name		= "meupedido";
	else:
		$servername = "igrejaemanuel1.mysql.dbaas.com.br";
		$username	= "igrejaemanuel1";
		$password	= 'Jesussalva@01';
		$db_name	= "igrejaemanuel1";

		$con	= mysqli_connect($servername, $username, $password, $db_name);
		mysqli_query($con, "SET NAMES 'utf8'");
		mysqli_query($con, 'SET character_set_connection=utf8');
		mysqli_query($con, 'SET character_set_client=utf8');
		mysqli_query($con, 'SET character_set_results=utf8');
	
		if($con):
			$_SESSION['conectado'] = true;
		else:
			$servername2 	= "igrejaemanuel.mysql.dbaas.com.br";
			if($con = mysqli_connect($servername2, $username, $password, $db_name)):
				mysqli_query($con, "SET NAMES 'utf8'");
				mysqli_query($con, 'SET character_set_connection=utf8');
				mysqli_query($con, 'SET character_set_client=utf8');
				mysqli_query($con, 'SET character_set_results=utf8');
			endif;
		endif;
	endif;
=======
<?php
	@header('Content-Type: text/html; charset=utf-8');
	//Conexão com banco de dados php7
	
	$localhost = false;

	if($localhost):
		$servername 	= "localhost";
		$username	  	= "root";
		$password		= '';
		$db_name		= "meupedido";
	else:
		$servername = "igrejaemanuel1.mysql.dbaas.com.br";
		$username	= "igrejaemanuel1";
		$password	= 'Jesussalva@01';
		$db_name	= "igrejaemanuel1";

		$con	= mysqli_connect($servername, $username, $password, $db_name);
		mysqli_query($con, "SET NAMES 'utf8'");
		mysqli_query($con, 'SET character_set_connection=utf8');
		mysqli_query($con, 'SET character_set_client=utf8');
		mysqli_query($con, 'SET character_set_results=utf8');
	
		if($con):
			$_SESSION['conectado'] = true;
		else:
			$servername2 	= "igrejaemanuel.mysql.dbaas.com.br";
			if($con = mysqli_connect($servername2, $username, $password, $db_name)):
				mysqli_query($con, "SET NAMES 'utf8'");
				mysqli_query($con, 'SET character_set_connection=utf8');
				mysqli_query($con, 'SET character_set_client=utf8');
				mysqli_query($con, 'SET character_set_results=utf8');
			endif;
		endif;
	endif;
>>>>>>> 052f01176de140ff06d1d892da20401e024f3803

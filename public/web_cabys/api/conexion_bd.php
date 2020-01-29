<?php

	include("../env/.env.php");
	//Conectar a BD//
	$server = isset($SERVER) ? $SERVER : "localhost";
	$usuario = isset($USUARIO) ?  $USUARIO : "root";
	$clave= isset($CLAVE) ?  $CLAVE : "123456";
	$base= isset($BASE) ?  $BASE : "cabys";	

	$conexion = mysqli_connect($server,$usuario,$clave,$base);
	
	mysqli_set_charset($conexion,"utf8");

	?>
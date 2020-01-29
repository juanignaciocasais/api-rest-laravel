<?php

include("conexion_bd.php"); 

$fecha_hora = $_POST['fecha_hora'];
$estado = $_POST['estado'];
$id_cliente = $_POST['id_cliente'];
$dispositivo = $_POST['dispositivo'];
$datos = $_POST['datos'];

$sql = "INSERT INTO `eventos`(`estado`, `fecha_hora`,`id_cliente`, `dispositivo`, `datos`) VALUES ('".$estado."', '".$fecha_hora."', '".$id_cliente."', '".$dispositivo."', '".$datos."')";

mysqli_query($conexion,$sql); 

$chequeo = mysqli_affected_rows($conexion);  

if ($chequeo > 0){
echo "Listo el pollo -> ".$chequeo;
}
else {
	echo "Acá falta cocción...";
}

mysqli_close($conexion);

?>
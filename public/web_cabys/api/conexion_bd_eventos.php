<?php

function getEventos($dispositivo) {
	include("conexion_bd.php"); 

	$sql = "SELECT * FROM eventos WHERE dispositivo='".$dispositivo."' ORDER BY fecha_hora DESC LIMIT 10";

	$resultado = mysqli_query($conexion,$sql) or die (mysqli_error($conexion));

	$data = array();

	while($fila=mysqli_fetch_assoc($resultado)){

		$fila['datos'] = json_decode($fila['datos']);
		$data[] = $fila;

	}

	mysqli_close($conexion);		
	return $data;
}

$dispositivo = isset($_GET["dispositivo"]) ? $_GET["dispositivo"] : '';
$data = getEventos($dispositivo);
$data_json = json_encode($data);

echo $data_json;

?>
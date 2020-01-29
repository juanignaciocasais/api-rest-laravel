var procesoActualizacion;

window.onload = function(){

	var botonGas = document.getElementById("sensorGas");

	botonGas.onclick = function(){
		actualizarSetInterval("conexion_bd_gas.php","Ultimos Registros - Sensor de Gas","<th>ESTADO</th><th>FECHA y HORA</th>");
	}
		
	var botonHumo = document.getElementById("sensorHumo");

	botonHumo.onclick = function(){
		procesarAJAX("conexion_bd_humo.php","Ultimos Registros - Sensor de Humo","<th>ESTADO</th><th>FECHA y HORA</th>");
	}

	var botoninundacion = document.getElementById("sensorInundacion");

	botoninundacion.onclick = function(){
		procesarAJAX("conexion_bd_inundacion.php","Ultimos Registros - Sensor de Inundación","<th>ESTADO</th><th>FECHA y HORA</th>");
	}

	var botonfalta220 = document.getElementById("falta220");

	botonfalta220.onclick = function(){
		procesarAJAX("conexion_bd_falta220.php","Ultimos Registros - Falta 220v","<th>ESTADO</th><th>FECHA y HORA</th>");
	}

	function actualizarSetInterval(recurso, titulo, encabezado_tabla){

		function procesarAJAX(recurso, titulo, encabezado_tabla){
			var url = "http://localhost/web_cabys/api/" + recurso; //direccion del archivo php
			var method = "GET";
			var request = new XMLHttpRequest(); //creación del objeto request

			request.open(method, url); // creación del mensaje HTTP

			request.send(null); //Envío del request

			request.onload = function(){
				if(this.readyState == 4 && this.status == 200){
				
					json_txt = this.responseText;

					json_objeto = JSON.parse(json_txt);

					//limpiar la tabla alimentandolas con el objeto JSON
					var tabla = document.getElementById("tabla_registros");
					var filas = tabla.getElementsByTagName("tr");
					var q_filas = filas.length;
					for (var i = 1; i < q_filas; i++){
					filas[1].parentNode.removeChild(filas[1]);
					};

					var contenidoTabla = document.getElementById("tbody_registros").innerHTML;

					for(i=0; i < json_objeto.length; i++){
					var nuevaFila = "<tr><td>" + json_objeto[i].estado + "</td><td>" + json_objeto[i].fecha_hora + "</td></tr>";
					contenidoTabla = contenidoTabla + nuevaFila;
					}		

				document.getElementById("tbody_registros").innerHTML = contenidoTabla;
				document.getElementsByTagName("h3")[0].innerHTML = titulo;
				document.getElementById("columnas").innerHTML = encabezado_tabla;
				}
			}
		}

		if(procesoActualizacion){
			clearInterval(procesoActualizacion);
		}

		procesoActualizacion = setInterval(procesarAJAX,5000);
	}
}//fin de programa


/*
function procesarAJAX(recurso, titulo, encabezado_tabla){
	var url = "http://localhost/web_cabys/api/" + recurso; //direccion del archivo php
	var method = "GET";
	var request = new XMLHttpRequest(); //creación del objeto request

	request.open(method, url); // creación del mensaje HTTP

	request.send(null); //Envío del request

	request.onload = function(){
		if(this.readyState == 4 && this.status == 200){
		
			json_txt = this.responseText;

			json_objeto = JSON.parse(json_txt);

			//limpiar la tabla alimentandolas con el objeto JSON
			var tabla = document.getElementById("tabla_registros");
			var filas = tabla.getElementsByTagName("tr");
			var q_filas = filas.length;
			for (var i = 1; i < q_filas; i++){
			filas[1].parentNode.removeChild(filas[1]);
			};

			var contenidoTabla = document.getElementById("tbody_registros").innerHTML;

			for(i=0; i < json_objeto.length; i++){
			var nuevaFila = "<tr><td>" + json_objeto[i].estado + "</td><td>" + json_objeto[i].fecha_hora + "</td></tr>";
			contenidoTabla = contenidoTabla + nuevaFila;
			}		

		document.getElementById("tbody_registros").innerHTML = contenidoTabla;
		document.getElementsByTagName("h3")[0].innerHTML = titulo;
		document.getElementById("columnas").innerHTML = encabezado_tabla;
		}
	}
}
*/	
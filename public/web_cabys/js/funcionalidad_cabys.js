window.onload = function(){

	var botonGas = document.getElementById("sensorGas");

	botonGas.onclick = function(){
		var url = "http://localhost/web_cabys/api/conexion_bd_gas.php"; //direccion del archivo php
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
			document.getElementsByTagName("h3")[0].innerHTML = "Ultimos Registros - Sensor de Gas";
			document.getElementById("columnas").innerHTML = "<th>ESTADO</th><th>FECHA y HORA</th>";
			}
		}	 		
	}

var botonHumo = document.getElementById("sensorHumo");

		botonHumo.onclick = function(){
			var url = "http://localhost/web_cabys/api/conexion_bd_humo.php"; //direccion del archivo php
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
				document.getElementsByTagName("h3")[0].innerHTML = "Ultimos Registros - Sensor de Humo";
				document.getElementById("columnas").innerHTML = "<th>ESTADO</th><th>FECHA y HORA</th>";
				}
			}
		}

	var botoninundacion = document.getElementById("sensorInundacion");

		botoninundacion.onclick = function(){
			var url = "http://localhost/web_cabys/api/conexion_bd_inundacion.php"; //direccion del archivo php
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
				document.getElementsByTagName("h3")[0].innerHTML = "Ultimos Registros - Sensor de Inundación";
				document.getElementById("columnas").innerHTML = "<th>ESTADO</th><th>FECHA y HORA</th>";
				}
			}
		}	

	var botonfalta220 = document.getElementById("falta220");

		botonfalta220.onclick = function(){
			var url = "http://localhost/web_cabys/api/conexion_bd_falta220.php"; //direccion del archivo php
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
				document.getElementsByTagName("h3")[0].innerHTML = "Ultimos Registros - Falta 220v";
				document.getElementById("columnas").innerHTML = "<th>ESTADO</th><th>FECHA y HORA</th>";
				}
			}
		}
}//fin de programa
/*var botonUser = document.getElementById("botonUser");

	botonUser.onclick = function(){
		var user = prompt("Ingresa usuario");
		var pass = prompt("Ingresa contraseña");
		
		if(user == "juan" && pass == "1234"){
			window.location.href = "http://localhost/web_cabys/cabys.html"
		}

		else {

			alert("Usuario y/o Contraseña incorrectos!");
		}
	}*/
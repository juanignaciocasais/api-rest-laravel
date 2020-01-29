var lasthour = 0;

window.onload = function(){

	
	activarProceso();		
}//fin de programa

function activarProceso(){
	setInterval(procesarAJAX, 5000);
}

function procesarAJAX(){

	var url = "http://localhost/web_cabys/api/conexion_bd_gas.php"; //direccion del archivo php
	var method = "GET";
	var request = new XMLHttpRequest(); //creación del objeto request

	request.open(method, url); // creación del mensaje HTTP

	request.send(null); //Envío del request

	request.onload = function(){
		if(this.readyState == 4 && this.status == 200){
		
			json_txt = this.responseText;

			json_objeto = JSON.parse(json_txt);

			i = 0;

			if(json_objeto[i].fecha_hora != lasthour){
			
				lasthour = json_objeto[i].fecha_hora;

				if(json_objeto[i].estado=="ACTIVADO"){

					document.getElementById('sensorGas').style.background='#FF0000';
					
					audioalarma();
				} else {
						document.getElementById('sensorGas').style.background='green'; 
					}
			}
		}
	}
}
function audioalarma(){
	var context = new (window.AudioContext || window.webkitAudioContext)();
		var osc = context.createOscillator(); // instantiate an oscillator
		osc.type = 'square'; // this is the default - also square, sawtooth, triangle
		osc.frequency.value = 200; // Hz
		osc.connect(context.destination); // connect it to the destination
		osc.start(); // start the oscillator
		osc.stop(context.currentTime + 2); // stop 2 seconds after the current time

}	
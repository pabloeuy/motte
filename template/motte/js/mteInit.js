/**
 * Javascript no-obstructivo
 */

/**
 * Carga scripts al inicio
 */
window.onload = function(){

	// control de submits
	if(document.forms && eval("typeof window.mteSubmits") == "function"){
		mteSubmits();
	}

	// control de onchange en los select
	if(document.forms && eval("typeof window.mteChanges") == "function"){
		mteChanges();
	}

	// control de onkeyup de algunos fields
	if(document.forms && eval("typeof window.mteKeyups") == "function"){
		mteKeyups();
	}

	// funciones que acceden a elementos
	if(document.getElementById && document.getElementsByTagName){
		mteClicks();
	}
}

/**
 * Carga scripts al salir
 */
window.onunload = function(){
	// ....
}

/**
 * Controla los clics en las paginas
 */
function mteClicks(){
	
	// variables
	links = document.getElementsByTagName("a");

	// se recorren
	for(i = 0; i < links.length; i++){
		
		enlace = links[i];
		
		linkFunction = lnkFunctionName(enlace);
		if(linkFunction == "")continue;
		
		if(linkFunction.indexOf("(") > -1){
			enlace.onclick = function(){
				linkFunction = lnkFunctionName(this);
				eval(linkFunction);
				return false;
			}
		}
		else{
			enlace.href = "javascript:;";
			enlace.onclick = eval(linkFunction);
		}
	}
}

function mteClicks2(){

	// variables
	links = document.getElementsByTagName("a");

	// se recorren
	for(i = 0; i < links.length; i++){

		// se averigua en donde se llama a la funcion
		identificador = "";
		if(links[i].id != ""){
			link_id = (links[i].id.indexOf("(") > -1)?links[i].id.substring(0, links[i].id.indexOf("(")):links[i].id;
			identificador = (eval("typeof window."+link_id) == "function")?"id":identificador;
		}
		if(links[i].name != ""){
			link_name = (links[i].name.indexOf("(") > -1)?links[i].name.substring(0, links[i].name.indexOf("(")):links[i].name;
			identificador = (eval("typeof window."+link_name) == "function")?"name":identificador;
		}
		if(links[i].className != ""){
			link_className = (links[i].className.indexOf("(") > -1)?links[i].className.substring(0, links[i].className.indexOf("(")):links[i].className;
			identificador = (eval("typeof window."+link_className) == "function")?"className":identificador;
		}

		// se llama a alguna funcion?
		if(identificador != ""){

			links[i].onclick = function(){

			// de vuelta, en donde esta la funcion?
			link_id = (this.id.indexOf("(") > -1)?this.id.substring(0, this.id.indexOf("(")):this.id;
			link_name = (this.name.indexOf("(") > -1)?this.name.substring(0, this.name.indexOf("(")):this.name;
			link_className = (this.className.indexOf("(") > -1)?this.className.substring(0, this.className.indexOf("(")):this.className;
			
			nombre = "";
			nombre = (link_id != "" && eval("typeof window."+link_id) == "function")?link_id:nombre;
			nombre = (link_name != "" && eval("typeof window."+link_name) == "function")?link_name:nombre;
			nombre = (link_className != "" && eval("typeof window."+link_className) == "function")?link_className:nombre;

			//nombre_funcion = (nombre.indexOf("(") > -1)?nombre:nombre+"()";
			nombre_funcion = "";
			nombre_funcion = (this.id != "" && eval("typeof window."+link_id) == "function")?this.id:nombre_funcion;
			nombre_funcion = (this.name != "" && eval("typeof window."+link_name) == "function")?this.name:nombre_funcion;
			nombre_funcion = (this.className != "" && eval("typeof window."+link_classname) == "function")?this.className:nombre_funcion;
			
			// se suma el enlace a los argumentos de la funcion
			pos_abre = nombre_funcion.indexOf("(");
			pos_cierra = nombre_funcion.indexOf(")");
			nombre_funcion = (pos_cierra == pos_abre + 1)?nombre_funcion.substring(0, pos_cierra)+"'"+this+"')":nombre_funcion.substring(0, pos_cierra)+", '"+this+"')";
			
			// se llama a la funcion
			eval(nombre_funcion);

			// y se detiene la cosa
			return false;
			}
		}
	}
}

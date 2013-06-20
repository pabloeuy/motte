/**
 * ------------------------------------------------------
 *         O T R A S   F U N C I O N E S
 * ------------------------------------------------------
 */


/**
 * Provee a JS de la preciada funciÃ³n trim
 *
 * @param string str
 * @return string
 */
function trim(str){
	return str.replace(/^\s*|\s*$/g, "");
}

/**
 * Devuelve el nombre de la función de
 * un enlace
 * 
 * @param {Object} obj
 */
function lnkFunctionName(obj){

	tmp = "";
	
	// control
	if(!obj)return tmp;
	
	// se obtiene algún posible nombre
	if(obj.name != ""){
		corto = (obj.name.indexOf("(") > -1)?obj.name.substring(0, obj.name.indexOf("(")):obj.name;
		if(corto.indexOf(' ') == -1){
			tmp = (eval("typeof window."+corto) == "function")?obj.name:tmp;
		}
	}
	if(obj.className != ""){
		corto = (obj.className.indexOf("(") > -1)?obj.className.substring(0, obj.className.indexOf("(")):obj.className;
		tmp = (eval("typeof window."+corto) == "function")?obj.className:tmp;
	}
	if(obj.id != ""){
		corto = (obj.id.indexOf("(") > -1)?obj.id.substring(0, obj.id.indexOf("(")):obj.id;
		tmp = (eval("typeof window."+corto) == "function")?obj.id:tmp;
	}
	
	return(tmp);	
}

/**
 * Confirma si un año es bisiesto
 * 
 * @param int year
 */
function bisiesto(year){
	if ((year % 4 == 0) && (( year % 100 != 0) || (year % 400 ==0))){
		return true;
	}
	else{
		return false;
	}
}

/**
 * Une nodos del DOM
 */
function joinNodeLists() {

	if (!arguments.length) {
		return;
	}
	
	var newList = [];
	for (var i = 0; i < arguments.length; i++) {
		var list = arguments[i];
		for (var j = 0; j < list.length; j++) {
			// Don't use push() for IE 5 compatibility
			// newList.push(list[j]);
			newList[newList.length] = list[j];
		}
	}
	
	return newList;
}


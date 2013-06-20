/**
 * Funciones para operaciones con selects 
 */ 
 
 
/**
 * IMPORTANTE! le falta corregir que borre el campo hasta en blanco
 * 
 * @param string formu
 * @param string desde
 * @param string hasta
 * @param bool repetidos
 */
function select_mover(formu, desde, hasta, repetidos){
	
	// control de variables
	if(!formu || !desde || !hasta){
		alert("ADVERTENCIA\n\nFaltan argumentos.");
		return;
		}
		
	// variables
	formu2 = formu;
	hasta2 = hasta;
	formu = document.forms[formu];
	desde = formu.elements[desde];
	hasta = formu.elements[hasta];
	larga = hasta.length;
	
	// controla que esté algo seleccionado
	if(desde.selectedIndex == -1){
		alert('Debe seleccionar un registro para pasar');
		return;
		}
	
	// se toma el elemento seleccionado
	valor = desde.options[desde.selectedIndex].value;
	nombre = desde.options[desde.selectedIndex].text;

	// control de repetidos
	if(repetidos && select_buscar_valor(formu2, hasta2, valor)){
		return;
		}
	
	// se carga el otro select
	opcion = new Option(nombre, valor);
	hasta.options[larga] = opcion;
	
	}

/**
 * Ordena una select
 * 
 * @param string formu
 * @param {Object} select
 * @param {Object} direccion
 */
function select_orden(formu,select,direccion){

	// control de variables
	if(!formu||!select||!direccion){
		alert("ADVERTENCIA\n\nFaltan argumentos.");
		return;
		}
		
	// algunas variables
	formu=document.forms[formu];
	select=formu.elements[select];
	pos=select.options.selectedIndex;
	cantidad=select.length-1;
	
	// controles para evitar cosas no permitidas
	if(pos<0){
		alert("Debe seleccionar un registro para ordenar.");
		return;
		}
	if(pos==0&&direccion=='subir'){
		return;
		}
	if(pos==cantidad&&direccion=='bajar'){
		return;
		}
	
	// otras variables
	valor=select.options[pos].value;
	texto=select.options[pos].text;
	pos_nueva=(direccion=='subir')?pos-1:pos+1;

	// valores de switch
	valor2=select.options[pos_nueva].value;
	texto2=select.options[pos_nueva].text;
	
	
	// se carga el valor donde debe ir
	opcion=new Option(texto,valor);
	opcion2=new Option(texto2,valor2);
	select.options[pos_nueva]=opcion;
	select.options[pos]=opcion2;
	
	// se vuelve el foco al cuadro
	select.options[pos_nueva].selected=true;
	
	}

/**
 * Agrega una nueva opción
 * 
 * @param string formu
 * @param string select
 * @param string valor
 * @param string texto
 * @param bool [repetidos]
 */
function select_agregar(formu, select, valor, texto, repetidos){
	
	// control de variables
	if(!formu || !select || !texto){
		//alert("ADVERTENCIA\n\nFaltan argumentos.");
		return;
		}
	
	// algunas variables
	formu = document.forms[formu];
	select = formu.elements[select];
	pos = select.length;
	
	// control de repetidos
	if(repetidos && select_buscar_valor(formu, select, valor)){
		return;
		}
	

	// se crea la opción
	opcion = new Option(texto, valor);	
	select.options[pos] = opcion;
	
	// se selecciona la opci?n
	select.options.selectedIndex = pos;
	
	}

/**
 * Selecciona todos los elementos de un selecty
 * 
 * @param string formu
 * @param string select
 */
function select_seleccionar(formu, select){

	// control de variables
	if(!formu || !select){
		alert("ADVERTENCIA\n\nFaltan argumentos.");
		return;
		}

	// algunas variables
	formu= document.forms[formu];
	select = formu.elements[select];
	cantidad = select.length;
	
	// se recorre el select
	for(i = 0; i < cantidad; i ++){
		select.options[i].selected = true;
		}

	}

/**
 * Quita el elemento seleccionado de un select
 * 
 * @param string formu
 * @param string select
 * @param string confirma
 */
function select_quitar(formu, select, confirma){

	// control de variables
	if(!formu || !select){
		alert("ADVERTENCIA\n\nFaltan argumentos.");
		return;
		}

	// algunas variables
	formu = document.forms[formu];
	select = formu.elements[select];
	pos = select.options.selectedIndex;

	// otro control
	if(pos < 0){
		alert("Debe seleccionar un registro para eliminar.");
		return;
		}
	
	// m?s variables
	valor = select.options[pos].text;
	letras_cant = valor.length;

	if(confirma && !confirm('Desea eliminar el registro \''+ valor +'\'?')){
		return;
		}
	
	// se quita la opci?n con efecto especial
	select.options.selectedIndex = -1;	// se quita la selecci?n para que se vea la selecci?n
	select2 = select;
	pos2 = pos;
	select.options[pos].style.color = "#D66";
	select.options[pos].style.fontStyle = "italic";
	window.setTimeout("select2.options[pos2] = null", 700);
	window.setTimeout("select2.options.selectedIndex = 0", 1050);
	
	}

/**
 * busca el value para un correspondiente text
 * 
 * @param string formu
 * @param string select
 * @param string texto
 */
function select_buscar(formu, select, texto){
	
	// control de variables
	if(!formu || !select){
		alert("ADVERTENCIA\n\nFaltan argumentos.");
		return;
		}
	
	// variables
	formu = document.forms[formu];
	select = formu.elements[select];
	
	for(i = 0; i < select.length; i ++){
		if(select.options[i].text == texto){
			return select.options[i].value;
			}
		}
	}

/**
 * busca si existe un valor
 * 
 * @param string formu
 * @param string select
 * @param string valor
 */
function select_buscar_valor(formu, select, valor){
	
	// control de variables
	if(!formu || !select){
		alert("ADVERTENCIA\n\nFaltan argumentos.");
		return;
		}
	
	// variables
	formu = document.forms[formu];
	select = formu.elements[select];
	
	for(i=  0;i < select.length; i++){
		if(select.options[i].value == valor){
			return true;
			}
		}
	
	return false;
	}

/**
 * Borra todas las opciones de un select
 * @param string formu
 * @param string select
 * @param string confirma
 */
function select_vaciar(formu, select, confirma){

	// control de variables
	if(!formu || !select){
		return;
		}

	// algunas variables
	formu = document.forms[formu];
	select = formu.elements[select];

	if(confirma && !confirm('¿Desea vaciar las opciones?')){
		return;
		}
	
	// se quita las opciones
	select.options.length = 0;
	
	}

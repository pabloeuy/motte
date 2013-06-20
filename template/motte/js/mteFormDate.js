/**
 * Muestra el calendario posicionado en la fecha pasada
 * 
 * @param int dia
 * @param int m
 * @param int y
 * @param string cM
 * @param string cH
 * @param string cDW
 * @param string cD
 * @param string formu
 * @param string inputName
 */
function buildCal(dia, m, y, cM, cH, cDW, cD, formu, inputName){

	var diasSemana = "DLMMJVS";
	var mn = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
	var dim = [31, 0, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	
	var oD = new Date(y, m-1, 1);	//DD replaced line to fix date bug when current day is 31st
	oD.od = oD.getDay() + 1;		//DD replaced line to fix date bug when current day is 31st
	
	var todaydate = new Date();		//DD added
	var scanfortoday = (y == todaydate.getFullYear() && m == todaydate.getMonth() + 1)?todaydate.getDate():0; //DD added
	
	dim[1] = (((oD.getFullYear() % 100 != 0) && (oD.getFullYear() % 4 == 0))||(oD.getFullYear() % 400 == 0))?29:28;

	var t = '<div class="'+ cM +'"><table class="'+ cM +'">';

	// título del mes / año
	t += '<tr><th colspan="7" class="'+ cH +'"><a href="javascript:;" class="mteDatePrev"  onclick="mteDateLinkPrev(\''+ formu +'\', \''+ inputName +'\')" title="Mes anterior"><span>ant.</span></a>';
	t += '<span>'+ mn[m-1] +' - '+ y +'</span>';
	t += '<a href="javascript:;" class="mteDateProx" onclick="mteDateLinkProx(\''+ formu +'\', \''+ inputName +'\')" title="Mes siguiente"><span>prox.</span></a></th></tr>';
	
	// títulos de los días
	t += '<tr>';
	for(s = 0; s < 7; s ++){
		t += '<td class="'+ cDW +'">'+ diasSemana.substr(s, 1) +'</td>';
	}
	t += '</tr>';
	
	t += '<tr>';
	for(i = 1; i <= 42; i ++){
		var x = ((i - oD.od >= 0) && (i - oD.od < dim[m - 1]))?i - oD.od + 1:'&nbsp;';
		x_txt = (x == scanfortoday)?'<span class="mteDateToday">'+ x +'</span>':x;
		x_txt = (x == dia)?'<span class="mteDateSelected">'+ x +'</span>':x_txt;
		t += (x != '&nbsp;')?'<td class="'+ cD +'"><a href="javascript:;" onclick="mteDateSelect(\''+ formu +'\', \''+ inputName +'\', '+ y +', '+ (m * 1) +', '+ x +')" class="mteDateDay">'+ x_txt +'</a></td>':'<td class="'+ cD +'"></td>';
		if(((i) % 7 == 0) && ( i < 36)){
			t += '</tr><tr>';
		}
	}
	t += '</tr></table></div>'

	return t;
}


/**
 * Muestra el calendario
 * 
 * @param string elemento
 */
function mteDate(elemento){
	
	// elemento a mostrar el calendario
	contenedor = document.getElementById("mteFormDateSelect_"+ elemento);
	if(contenedor.className == "mteFormDateContainer"){
		contenedor.className = "mteFormDateContainerHidden";
		return;
	}
	else{
		contenedor.className = "mteFormDateContainer";
	}

	// se averigua qué formulario contiene cositas
	formus = document.forms;
	formu = "";
	for(i = 0; i < formus.length; i++){
		if(formus[i].elements[elemento+"Year"]){
			formu = formus[i]; 
		}
	}
	if(formu == "")return;
	
	// se crea el calendario
	anio = formu.elements[elemento+"Year"].value;
	mes = formu.elements[elemento+"Month"].value;
	dia = formu.elements[elemento+"Day"].value;
	calendario = buildCal(dia, mes, anio, "", "mteFormDateMonth", "mteFormDateWeek", "mteFormDateDay", formu.name, elemento);
	contenedor.innerHTML = calendario;
	
}

/**
 * Mueve el calendario un mes hacia atrás
 *
 * @param string formu
 * @param string elemento
 */
function mteDateLinkPrev(formu, elemento){
	
	// se vuelve invisible x un segundo
	contenedor = document.getElementById("mteFormDateSelect_"+ elemento);
	contenedor.className = "mteFormDateContainerHidden";

	formu = document.forms[formu];
	anio = formu.elements[elemento+"Year"].value;
	mes = formu.elements[elemento+"Month"].value * 1;
	if(mes == 1){
		formu.elements[elemento+"Month"].value = 12;
		formu.elements[elemento+"Year"].value = anio - 1;
	}
	else{
		mes = mes - 1;
		mes = (mes < 10)?"0"+ mes +"":mes;
	 	formu.elements[elemento+"Month"].value = mes;
	 }
	
	mteDate(elemento);
}

/**
 * Mueve el calendario un mes hacia adelante
 *
 * @param string formu
 * @param string elemento
 */
function mteDateLinkProx(formu, elemento){
	
	// se vuelve invisible x un segundo
	contenedor = document.getElementById("mteFormDateSelect_"+ elemento);
	contenedor.className = "mteFormDateContainerHidden";

	formu = document.forms[formu];
	anio = formu.elements[elemento +"Year"].value * 1;
	mes = formu.elements[elemento +"Month"].value * 1;
	if(mes == 12){
		formu.elements[elemento +"Month"].value = "01";
		formu.elements[elemento +"Year"].value = anio + 1;
	}
	else{
		mes = mes + 1;
		mes = (mes < 10)?"0"+ mes +"":mes;
	 	formu.elements[elemento +"Month"].value = mes;
	 }
	
	mteDate(elemento);
}

/**
 * Es llamada cuando el usuario da clic en algún día
 * 
 * @param string formu
 * @param string elemento
 * @param int dia
 * @param int mes
 * @param int anio
 */
function mteDateSelect(formu, elemento, anio, mes, dia){

	// variables
	formu = document.forms[formu];
	mes = (mes < 10)?"0"+ mes +"":mes;
	
	// se asignan al select
	formu.elements[elemento +"Year"].value = anio;
	formu.elements[elemento +"Month"].value = mes;
	formu.elements[elemento +"Day"].value = dia;
	
	// se vuelve invisible
	contenedor = document.getElementById("mteFormDateSelect_"+ elemento);
	contenedor.className = "mteFormDateContainerHidden";
	
}

/**
 * Cuando uno de los combos de fecha cambia,
 * se actualiza el campo hidden y controla
 * que sea una fecha válida
 *  
 * @param string inputName
 */
function mteFormDateChange(inputName){
	
	formu = getForm(inputName);
	if(!formu)return;
	
	year = formu.elements[inputName +'Year'].value;
	month = formu.elements[inputName +'Month'].value;
	day = formu.elements[inputName +'Day'].value;
	
	// control de fechas
	if(day == "31" && (month == "02" || month == "04" || month == "06" || month == "09" || month == "11")){
		day = "30";
	}
	if(day == "30" && month == "02"){
		day = (bisiesto(year))?"29":"28";
	}
	if(day == "29" && month == "02" && !bisiesto(year)){
		day = "28";
	}
	
	formu.elements[inputName +'Day'].value = day;
	date = year +'-'+ month +'-'+ day; 
	formu.elements[inputName].value = date;
	
}


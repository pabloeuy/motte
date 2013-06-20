/**
 * Es llamada cuando se selecciona el 
 * número de página en un browser
 */
function mteGridNavPage(elemento){
	formu = getForm(elemento);
	input = formu.elements[elemento];
	location.href = input.value;
}
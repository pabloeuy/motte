/**
 * Es llamada cuando se selecciona el 
 * n�mero de p�gina en un browser
 */
function mteGridNavPage(elemento){
	formu = getForm(elemento);
	input = formu.elements[elemento];
	location.href = input.value;
}
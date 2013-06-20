/**
 * Sends submits
 */
function mteSubmits(){

    formus = document.getElementsByTagName("form");
    for(i = 0; i < formus.length; i++){
        formu = formus[i];
        formu.onsubmit = function(){
            // Calls the function which name is equal to element's name
            if(this.name && this.name != ""){
                vale = eval("typeof window." + this.name);
                if(vale == "function"){
                    eval(this.name + "();");	// removes () from form
                    return false;
                }
                else{
                    // calls generic valdite function
                    if (mteFormsValidate(this)){
                        if (this.elements['mteNeedConfirm'].value){
                            if(confirm(this.elements['mteConfirmMsg'].value)){
                                return true;
                            }
                        }
                        else {
                             return true;
                        }
                    }
                    return false;
                }
            }
        }
    }
}

/**
 * Sends changes
 */
function mteChanges(){
    selectFields = document.getElementsByTagName("select");
    inputFields = document.getElementsByTagName("input");
    allFields = joinNodeLists(selectFields, inputFields);

    for(i = 0; i < allFields.length; i++){
        select = allFields[i];
        select.onchange = function(){
            functionName = lnkFunctionName(this);
            if(functionName != ""){
                functionName = (functionName.indexOf('(') > 0)?functionName:functionName +'("'+ this.name +'")';
                eval(functionName);
                return;
            }
        }
    }
}


/**
 * Sends keyups
 */
function mteKeyups(){

    inputFields = document.getElementsByTagName("input");

    for(i = 0; i < inputFields.length; i++){
        select = inputFields[i];
        select.onkeyup = function(e){
            functionName = lnkFunctionName(this);
            if(functionName != ""){
                functionName = functionName.substring(0, functionName.indexOf(')'));
                if(document.all)e = event;
                functionName = functionName +", '"+ e.keyCode +"')";
                eval(functionName);
                return;
            }
        }
    }
}

/**
 * Validate Forms
 */
function mteFormsValidate(formu){

    // variables
    formFields = formu.elements;
    llave = 0;

    // iterates form fields
    for(i = 0; i < formFields.length; i++){

        //record being checked
        thisField = formFields[i];
        fieldClass = thisField.className;
        fieldValue = thisField.value;

        if(fieldClass.indexOf("mteFormsValidateInput") > -1){
            if(thisField.value == "" && thisField.type != "select-multiple"){
                thisField.className = "mteFormsValidateInput pgFormsValidateCorrect";
                if(llave == 0)thisField.focus();
                llave++;
            }
            else{
                thisField.className = "mteFormsValidateInput";
            }

            // se fija en formFields multiple
            if(thisField.type == "select-multiple"){
                llave_multiple = 1;
                for(j = 0; j < thisField.options.length; j++){
                    if(thisField.options[j].selected && thisField.options[j].value != ""){
                        llave_multiple = 0;
                    }
                }
                if(llave_multiple){
                    llave ++;
                    thisField.className = "mteFormsValidateInput pgFormsValidateCorrect";
                }
                else{
                    thisField.className = "mteFormsValidateInput";
                }
            }
        }else{
            if(fieldClass.indexOf("mteFormsValidateCaptcha") > -1){
                var temp = new Array();
                temp = formFields['answer'].value.split(':');

                if(temp.indexOf(thisField.value) < 0){
                    thisField.className = "mteFormsValidateCaptcha pgFormsValidateCorrect";
                    if(llave == 0)thisField.focus();
                    llave++;
                }
                else{
                    thisField.className = "mteFormsValidateCaptcha";
                }
            }
        }
    }


    // todo bien?
    if(llave == 0){
        return true;
    }
    else{
        mteFormsAlert(formu, "Debe completar los campos marcados.");
        return false;
    }


}

/**
 * Notifica mensajes desde la funcion validar
 *
 * @param string formulario
 * @param string mensaje
 */
function mteFormsAlert(formu, mensaje){

    // control
    if(!mensaje || mensaje == "")return;
    if(!document.getElementsByTagName || !document.createElement){
        alert(mensaje);
        return;
    }

    // variables
    cuerpo = formu.getElementsByTagName("fieldset")[0];
    if(!cuerpo){
        alert(mensaje);
        return;
    }

    avisos = cuerpo.getElementsByTagName("div");

    for(i = 0; i < avisos.length; i++){
        if(avisos[i].className == "mteWarning"){
            cuerpo.removeChild(avisos[i]);
        }
    }

    // se prepara el aviso
    aviso = document.createElement("div");
    aviso.className = "mteWarning";
    aviso.innerHTML = mensaje;

    // se coloca el aviso despu�s de un segundo
    aviso2 = aviso;
    window.setTimeout("cuerpo.appendChild(aviso2);", 250);

}


/**
 * Devuelve el formulario en el
 * que est� determinado elemento
 *
 * @param string inputName
 */
function getForm(inputName){

    if(!inputName)return;

    formus = document.forms;
    formu = "";
    for(i = 0; i < formus.length; i++){
        if(formus[i].elements[inputName]){
            return formus[i];
        }
    }
    return;
}

/**
 * Help
 */
function mteFormHelp(){

    enlace = this;
    contenedor = enlace.getElementsByTagName('span');
    contenido = contenedor[0].innerHTML;

    if(contenido == '')return;

    if(enlace.innerHTML.indexOf('mteFormHelpCont') == -1){
        enlace.innerHTML += '<div class="mteFormHelpCont"><a href="#" onclick="mteFormHelp">x</a>'+ contenido +'</div>';
    }
    else{
        enlace.innerHTML = '<span>'+ contenido +'</span>';
        //enlace.parentNode.parentNode.innerHTML = '';
    }

}
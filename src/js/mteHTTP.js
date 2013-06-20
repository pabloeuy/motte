/**
 * Ajax (js)
 *
 * @filesource
 * @package motte
 * @subpackage view
 * @version 1.0
 * @license http://opensource.org/licenses/gpl-license.php GNU Public license
 * @author 	Pedro Gauna (pgauna@gmail.com) /
 * 		Carlos Gagliardi (carlosgag@gmail.com) /
 * 		Mauro Dodero (maurodo@gmail.com) /
 * 		Pablo Erartes (pabloeuy@gmail.com) /
 * 		GBoksar/Perro (gustavo@boksar.info)
 */
// Ajax Variables
var mteXMLDoc = null ;  // URL Object
var mteValorLoad = null ;       // Obtained value

function mteLoad(url) {

        mteValorLoad = null;

        if (typeof window.ActiveXObject != 'undefined' ) {
                mteXMLDoc = new ActiveXObject("Microsoft.XMLHTTP");
                mteXMLDoc.onreadystatechange = process ;
                }
        else {
                mteXMLDoc = new XMLHttpRequest();
                mteXMLDoc.onload = mteProcess;
                }
        mteXMLDoc.open("GET", url, true);
        mteXMLDoc.send(null);
        }

function mteProcess(){
        if (mteXMLDoc.readyState == 4 ){
                if(mteXMLDoc.status == 200){
                        mteValorLoad = mteXMLDoc.responseText;
                        }
                }
        else{
                return;
                }
        }

/**
 * Ajax driven object
 *
 * var myRequest = new ajaxObject('http://motte.codigolibre.net');
 * myRequest.update('VAR1=111&VAR2=222', 'POST', 'dato exta 1|dato extra 2');
 * myRequest.callback = function(responseVal, state, error, datosExtra){
 * 	if(state == 1){
 * 		alert(responseVal)
 * 	}
 * 	else{
 * 		alert(error);
 * 		}
 * }
 *
 * @param string url
 * @param string [callbackFunction]
 */
function ajaxObject(url, callbackFunction) {
    var that = this;
    this.updating = false;
    this.extraData = '';
    if(url.indexOf('?') > 0){
        url = url.substring(0, url.indexOf('?')) +'?Q='+ url.substring(url.indexOf('?') + 1, url.length);
    }


    /**
     * Cancela la conexi�n
     */
    this.abort = function(){
        if(that.updating){
            that.updating = false;
            that.AJAX.abort();
            that.AJAX = null;
        }
    }

    /**
     * Realiza el intercambio de datos
     *
     * @param string passData - Par�metros del GET
     * @param string postMethod - GET|POST
     * @param string [extraData] - Puede ser cualquier cosa
     */
    this.update = function(passData, postMethod, extraData){
        // variables
        passData = (/post/i.test(postMethod))?passData:'?'+ passData;
        this.extraData = (extraData != '')?extraData:'';
        that.AJAX = null;

        // ya est� corriendo?
        if(that.updating){
            return false;
        }

        // IE o el mundo?
        if (window.XMLHttpRequest){
            that.AJAX = new XMLHttpRequest();
        }
        else{
            that.AJAX = new ActiveXObject("Microsoft.XMLHTTP");
        }
        if(that.AJAX == null) {
            return false;
        }
        else{
            // Notify
            var oAviso = new mteNotify(1);
            that.AJAX.onreadystatechange = function(){
                // termin� la actualizaci�n
	      	if(that.AJAX.readyState == 4){
                    that.updating = false;

                    // algunas variables
                    var message = '';
                    var notify_text = '';
                    var error = that.AJAX.responseText;
                    var state = 0;

                    // vienen datos
                    if(that.AJAX.responseXML){

                        var feedback = that.AJAX.responseXML;
                        state = feedback.getElementsByTagName('status').item(0).firstChild.data;
                        var responseVal = (feedback.getElementsByTagName('result').item(0))?feedback.getElementsByTagName('result').item(0).firstChild.data:'';
                        error = (feedback.getElementsByTagName('error').item(0))?feedback.getElementsByTagName('error').item(0).firstChild.data:'';

                        // todo ok
                        if(state == 1){
                            message = responseVal.replace(/\r\n/g, "");
                            message = message.replace(/\n/g, "");
                            message = message.replace(/\\r\\n/g, "");
                            message = message.replace(/\\n/g, "");
                            message = message.replace(/\t/g, " ");
                            message = message.replace(/\\t/g, " ");
                            notify_text = 'procedimiento OK';
                        }
                    }

                    // ups, hay errores, se avisa
                    if(state != 1){
                        error = (error == '')?'Ocurri� un error, vuelva a intentarlo':error;
                    }

                    // ups, hay errores, se avisa
                    if(state != 1){
                        error = (error == '')?'Ocurri� un error, vuelva a intentarlo':error;
                        oAviso.showAlert(error);
                    }

                    // se llama a la funci�n de callback
                    that.callback(message, state, error, that.extraData);
                    that.AJAX = null;
                }
            }

            // para tener la hora
            that.updating = new Date();

            // se prepara lo que se va a pasar
            var uri=urlCall+''+passData;
            that.AJAX.open(postMethod.toUpperCase(), uri, true);

            // si es POST
            if (/post/i.test(postMethod)){
                that.AJAX.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                that.AJAX.setRequestHeader("Content-Length", passData.length);
            }

            // se hace la llamada
            that.AJAX.send(passData);
            return true;
        }
    }

    var urlCall = url;
    this.callback = callbackFunction || function(){ };
}

/**
 * Clase mteNotify
 * muestra una aviso en alg�n lado
 */
var mteNotify = function (id){

    if(!id)return;
    this.texto = '';
    this.id = id;

    /**
     * Define el texto a mostrar
     *
     * @param string texto
     */
    this.setText = function(txt){

        // ya existe
        if(document.getElementById('mteNotify_'+ this.id)){
            var aviso = document.getElementById('mteNotify_'+ this.id);
            aviso.innerHTML = setHtml(txt);
        }
        else{ // es nuevo
            this.texto = txt;
        }
    }

    /**
     * Se utiliza cuando hay problemas
     *
     * @param string texto
     */
    this.showAlert = function(texto){

        texto = texto +'';
        texto = texto.split('|');
        if(texto[0] != '0' && texto[0] != ''){
            alert("########################################\n\n"+ texto[0] +"\n\n########################################");
            return;
        }
        if((texto[0] != '0' && trim(texto[0]) == '') || (texto[0] == '0' && texto[1] == '')){
            alert("Ocurri� un error, vuelva a intentarlo.");
            return;
        }
        if(texto[0] == '0' || texto[1] != ''){
            alert("########################################\n\n"+ texto[1] +"\n\n########################################");
            return;
        }
    }

}

/**
 * Realiza una llamada a ajax,
 * y el resultado lo pasa a
 * la funci�n dada
 *
 * @param string url
 * @param string functionName
 */
function rapidAjax(url, vars, functionName, methodName){
    if(!url || !functionName || url == '' || functionName == '')return;

    var vars = (!vars)?'':vars;
    var methodName = (!methodName || methodName == '')?'GET':methodName;

    var oAjax = new ajaxObject(url);
    oAjax.update(vars, methodName);
    oAjax.callback = function(responseVal, state, error){
        if(state == 1){
            responseVal = responseVal.replace(/\'/g, '`');
            eval(functionName +"('"+ responseVal +"')");
        }
    }
}
/**
*/
function frm_login(){

	// variables
	var loginform   = document.forms["frm_login"];
	var username = formu.elements["USERNAME"].value;
	var passwd   = formu.elements["PASSWD"].value;
	var urlajax = formu.elements["URLAJAX"].value;
	var session = formu.elements["SESSION"].value;
	var notice   = document.getElementById("error");
	
	// check received values
	if(username == "" || passwd == ""){
		notice.innerHTML = document.forms["frm_login"].elements["EMPTYFIELDS"].value;
		return false;
	}
	
	// Ask App for login
	mteLoad(urlajax + "?USERNAME="+ username + "&PASSWD=" + passwd + "&SESSION=" + session);	
	// display searching laber...
	notice.innerHTML = document.forms["frm_login"].elements["SEARCHING"].value;
	// delegate control to next function
	timeLogin = window.setTimeout("frm_login_load(0);", 500);
}
	
function frm_login_load(times){
	var notice  = document.getElementById("error");
	var url    = "index.php";
	window.clearTimeout(timeLogin);

	if(times > 20){
		notice.innerHTML = document.forms["frm_login"].elements["PROBLEMS"].value;
		document.forms["frm_login"].elements["PASSWD"].value = '';
		return false;
	}

	if(mteValorLoad == 1){
		divPadlock = document.getElementById("mtePadlock");
		divPadlock.className = "Login";
		window.setTimeout("location.href='"+url+"'", 500);
		return false;
	}
	else if(mteValorLoad == 0){
		aviso.innerHTML = document.forms["frm_login"].elements["NOLOGIN"].value;
		document.forms["frm_login"].elements["PASSWD"].value = '';
		return false;
	}
	else{
		times2 = times + 1;
		timeLogin = window.setTimeout("frm_login_load(times2);",400);
	}
}
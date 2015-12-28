function ifempty(value) {
	if(value!="") {
		retvalue=value;
	} else {
		retvalue="&nbsp; ";
	}
return retvalue;
}

function rhtmlspecialchars(str) {
	if(typeof(str)=="string") {
		str=str.replace(/&gt;/ig, ">");
		str=str.replace(/&lt;/ig, "<");
		str=str.replace(/&#039;/g, "'");
		str=str.replace(/&quot;/ig, '"');
		str=str.replace(/&amp;/ig, '&');
	}
return str;
}

function onlynumbers(e) {
	if(window.event)
		var keyCode=window.event.keyCode;
	else
		var keyCode=e.which;
	if(keyCode > 31 && (keyCode < 48 || keyCode > 57))
return false;
return true;
}

checked=false;
function checkedallprof(value) {
	var aa=document.getElementById(value);
		if(checked==false) {
			checked=true
		} else {
			checked=false
		}
		for(var i=0; i < aa.elements.length; i++) {
			aa.elements[i].checked = checked;
		}
}

function alphanumeric(inputtxt) { 
	var letters = /^[0-9a-zA-Z]+$/;
		if(letters.test(inputtxt)) {
			return true;
		} else {
			return false;
		}
}
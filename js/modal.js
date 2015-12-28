//
// keycode functions
//
function submitChpassAdmin(e) {
	if(window.event)
		var keyCode=window.event.keyCode;
	else
		var keyCode=e.which;
	if(keyCode==13) {
		checkchpassadminname();
	}
}
function submitEditAdmin(e) {
	if(window.event)
		var keyCode=window.event.keyCode;
	else
		var keyCode=e.which;
	if(keyCode==13) {
		checkeditadminname();
	}
}
function submitEditGroup(e) {
	if(window.event)
		var keyCode=window.event.keyCode;
	else
		var keyCode=e.which;
	if(keyCode==13) {
		checkeditgroupname();
	}
}
function submitEditProfile(e) {
	if(window.event)
		var keyCode=window.event.keyCode;
	else
		var keyCode=e.which;
	if(keyCode==13) {
		checkeditprofilename();
	}
}
function submitNewAdmin(e) {
	if(window.event)
		var keyCode=window.event.keyCode;
	else
		var keyCode=e.which;
	if(keyCode==13) {
		checkadminname();
	}
}
function submitNewGroup(e) {
	if(window.event)
		var keyCode=window.event.keyCode;
	else
		var keyCode=e.which;
	if(keyCode==13) {
		checkgroupname();
	}
}
function submitNewProfile(e) {
	if(window.event)
		var keyCode=window.event.keyCode;
	else
		var keyCode=e.which;
	if(keyCode==13) {
		checkprofilename();
	}
}
function submitCspSendOsd(e) {
	if(window.event)
		var keyCode=window.event.keyCode;
	else
		var keyCode=e.which;
	if(keyCode==13) {
		checkcspsendosd();
	}
}

//
// autofocus functions
//
$('#modalNewAdmin').on('shown', function () {
	$('#newadmin input[name=user]').focus()
});

$('#modalEditAdmin').on('shown', function () {
	$('#editadmin input[name=user]').focus()
});

$('#modalChpassAdmin').on('shown', function () {
	$('#chpassadmin input[name=pass1]').focus()
});

$('#modalNewGroup').on('shown', function () {
	$('#newgroup input[name=name]').focus()
});

$('#modalEditGroup').on('shown', function () {
	$('#editgroup input[name=name]').focus()
});

$('#modalNewProfile').on('shown', function () {
	$('#newprofile input[name=name]').focus()
});

$('#modalEditProfile').on('shown', function () {
	$('#editprofile input[name=name]').focus()
});

//
// modal cleanup functions
//
function cleanmodalNewGroup() {
	$('#newgroup input[name=name]').val('');
	$('#newgroup input[name=comment]').val('');
}

function cleanmodalNewProfile() {
	$('#newprofile input[name=name]').val('');
	$('#newprofile input[name=cspvalue]').val('');
	$('#newprofile input[name=comment]').val('');
}

function cleanmodalNewAdmin() {
	$('#newadmin input[name=user]').val('');
	$('#newadmin input[name=pass]').val('');
	$('#newadmin input[name=name]').val('');
	$('#newadmin select[name=admlvl]').val('0');
	$('#newadmin select[name=ugroup]').val('0');
}
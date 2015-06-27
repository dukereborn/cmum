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

$('#modalChpassAdmin').on('show', function() {
	setTimeout(function() {$('#chpassadmin input[name=pass1]').focus();}, 700);
});

$('#modalEditAdmin').on('show', function() {
	setTimeout(function() {$('#editadmin input[name=user]').focus();}, 700);
});

$('#modalEditGroup').on('show', function() {
	setTimeout(function() {$('#editgroup input[name=name]').focus();}, 700);
});

$('#modalEditProfile').on('show', function() {
	setTimeout(function() {$('#editprofile input[name=name]').focus();}, 700);
});

$('#modalNewAdmin').on('show', function() {
	setTimeout(function() {$('#newadmin input[name=user]').focus();}, 700);
});

$('#modalNewGroup').on('show', function() {
	setTimeout(function() {$('#newgroup input[name=name]').focus();}, 700);
});

$('#modalNewProfile').on('show', function() {
	setTimeout(function() {$('#newprofile input[name=name]').focus();}, 700);
});

$('#modalSearch').on('show', function() {
	setTimeout(function() {$('#newsearch input[name=searchfor]').focus();}, 700);
});
$('#modalCspSendOsd').on('show', function() {
	setTimeout(function() {$('input[name=osdmsg]').focus();}, 700);
});

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
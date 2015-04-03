function checkinstall() {
	var mysql_host=$('#install input[name=mysql_host]').val();
	var mysql_name=$('#install input[name=mysql_name]').val();
	var mysql_user=$('#install input[name=mysql_user]').val();
	var mysql_pass=$('#install input[name=mysql_pass]').val();
	var inst_seckey=$('#install input[name=inst_seckey]').val();
	var inst_charset=$('#install select[name=inst_charset]').val();
	var inst_timezone=$('#install select[name=inst_timezone]').val();
	var admin_name=$('#install input[name=admin_name]').val();
	var admin_pass=$('#install input[name=admin_pass]').val();
		if(mysql_host=="" || mysql_name=="" || mysql_user=="" || mysql_pass=="" || inst_seckey=="" || inst_charset=="" || inst_timezone=="" || admin_name=="" || admin_pass=="") {
			toastr.error('Please fill out all of the fields');
			install.mysql_host.focus();
		} else {
			jQuery.ajax({
				type: 'post',
				url: 'functions/install.php',
				data: 'function=dbcheck&host='+mysql_host+'&user='+mysql_user+'&pass='+mysql_pass,
				cache: false,
				timeout: 8000,
				success: function(response) {
					if(response==0) {
						toastr.error('Error connecting to MySQL server');
						install.mysql_host.focus();
					} 
					if(response==1) {
						jQuery.ajax({
							type: 'post',
							url: 'functions/install.php',
							data: 'function=tblcheck&host='+mysql_host+'&user='+mysql_user+'&pass='+mysql_pass+'&name='+mysql_name,
							cache: false,
							timeout: 8000,
							success: function(response2) {
								if(response2==0) {
									toastr.error('Database allready exists');
									install.mysql_host.focus();
								}
								if(response2==1) {
									jQuery.ajax({
										type: 'post',
										url: 'functions/install.php',
										data: 'function=dbinstall&host='+mysql_host+'&name='+mysql_name+'&user='+mysql_user+'&pass='+mysql_pass+'&charset='+inst_charset+'&aname='+admin_name+'&apass='+admin_pass+'&skey='+inst_seckey,
										cache: false,
										timeout: 8000,
										success: function(response3) {
											if(response3==1) {
												$('#install').trigger('submit', true);
											} else {
												toastr.error('Something went wrong, try again');
											}
											
										}
									});
								}
							}
						});
					}
				}
			});
		}
}
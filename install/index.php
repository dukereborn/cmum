<?php
require("../includes/settings.php");
require("functions/install.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
		<meta name="robots" content="NOODP, NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
		<title><?php print(CMUM_TITLE); ?> - Installation</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="../css/styles.css">
		<link rel="stylesheet" href="../css/toastr.min.css">
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="apple-touch-icon" href="../favicon.png">
		<!--[if lt IE 9]>
			<script src="../js/html5shiv.js"></script>
			<script src="../js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div id="in-nav">
			<div class="container">
				<div class="row">
					<div class="span12">
						<h4><span id="headerlight">CSP</span><span id="headerstrong"><strong>MYSQL</strong></span><span id="headerlight">USER</span><span id="headerstrong"><strong>MANAGER</strong></span> - INSTALLATION</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="span6 offset2">
					<div class="login">
						<form class="form-horizontal" name="install" id="install" method="post" action="done.php">
							<div class="control-group">
								<div class="controls">
									<h4>MySQL Server</h4>
								</div>
							</div>
							<div class="control-group">
								<label for="mysql_host" class="control-label">IP/Hostname </label>
								<div class="controls">
									<input id="mysql_host" type="text" name="mysql_host" autofocus>
								</div>
							</div>
							<div class="control-group">
								<label for="mysql_name" class="control-label">Database </label>
								<div class="controls">
									<input id="mysql_name" type="text" name="mysql_name">
								</div>
							</div>
							<div class="control-group">
								<label for="mysql_user" class="control-label">Username </label>
								<div class="controls">
									<input id="mysql_user" type="text" name="mysql_user">
								</div>
							</div>
							<div class="control-group">
								<label for="mysql_pass" class="control-label">Password </label>
								<div class="controls">
									<input id="mysql_pass" type="text" name="mysql_pass">
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<h4>General</h4>
								</div>
							</div>
							<div class="control-group">
								<label for="inst_seckey" class="control-label">Secret key </label>
								<div class="controls">
									<input id="inst_seckey" type="text" name="inst_seckey" value="<?php print(uniqid()); ?>">
								</div>
								<div class="controls checkbox">
									<input id="inst_seckeyasgxk" type="checkbox" name="inst_seckeyasgxk"> Also use as genxml key
								</div>
							</div>
							<div class="control-group">
								<label for="inst_charset" class="control-label">Charset </label>
								<div class="controls">
									<select name="inst_charset" id="inst_charset">
										<option value="utf-8" selected>UTF-8</option>
										<option value="utf-16">UTF-16</option>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label for="inst_timezone" class="control-label">Timezone </label>
								<div class="controls">
									<select name="inst_timezone" id="inst_timezone">
										<option value="" selected></option>
										<?php
											$fh=fopen("includes/timezones.list","r");
												$data=fread($fh,filesize("includes/timezones.list"));
												$dataline=preg_split("/\r\n|[\r\n]/",$data);
												$i=0;
											fclose($fh);
											foreach($dataline as $i => $value) {
												print("<option value=\"".$dataline[$i]."\">".$dataline[$i]."</option>");
												$i++;
											}
										?>
									</select>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<h4>Admin account</h4>
								</div>
							</div>
							<div class="control-group">
								<label for="admin_name" class="control-label">Username </label>
								<div class="controls">
									<input id="admin_name" type="text" name="admin_name">
								</div>
							</div>
							<div class="control-group">
								<label for="admin_pass" class="control-label">Password </label>
								<div class="controls">
									<input id="admin_pass" type="text" name="admin_pass">
								</div>
							</div>
						</form>
						<div class="form-horizontal control-group">
							<div class="controls">
								<button class="btn" name="bsave" id="bsave" onclick="checkinstall();">Start installation</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			require("../includes/footer.php");
		?>
		<script src="../js/jquery.js"></script>
		<script src="../js/toastr.min.js"></script>
		<script src="js/ajaxcalls.js"></script>
	</body>
</html>
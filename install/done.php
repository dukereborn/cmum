<!DOCTYPE html>
<?php
if(empty($_POST["mysql_host"]) || empty($_POST["mysql_name"]) || empty($_POST["mysql_user"]) || empty($_POST["mysql_pass"]) || empty($_POST["inst_charset"]) || empty($_POST["inst_timezone"]) || empty($_POST["inst_seckey"])) {
	header("Location: index.php");
}

require("../includes/settings.php");
require("functions/install.php");

if(isset($_POST["dlconfig"]) && $_POST["dlconfig"]=="Download config") {
	downloadconfig($_POST["mysql_host"],$_POST["mysql_name"],$_POST["mysql_user"],$_POST["mysql_pass"],$_POST["inst_charset"],$_POST["inst_timezone"],$_POST["inst_seckey"]);
}
?>
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
							<input type="hidden" name="mysql_host" id="mysql_host" value="<?php print($_POST["mysql_host"]); ?>">
							<input type="hidden" name="mysql_name" id="mysql_name" value="<?php print($_POST["mysql_name"]); ?>">
							<input type="hidden" name="mysql_user" id="mysql_user" value="<?php print($_POST["mysql_user"]); ?>">
							<input type="hidden" name="mysql_pass" id="mysql_pass" value="<?php print($_POST["mysql_pass"]); ?>">
							<input type="hidden" name="inst_seckey" id="inst_seckey" value="<?php print($_POST["inst_seckey"]); ?>">
							<input type="hidden" name="inst_charset" id="inst_charset" value="<?php print($_POST["inst_charset"]); ?>">
							<input type="hidden" name="inst_timezone" id="inst_timezone" value="<?php print($_POST["inst_timezone"]); ?>">
							<div class="control-group">
								<div class="controls">
									<h4>Installation completed</h4>
								</div>
								<div class="controls">
									Download the config.php file below and place it in your www-root to complete the installation
								</div>
									&nbsp;
								<div class="controls">
									<input class="btn" id="dlconfig" type="submit" name="dlconfig" value="Download config">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
			require("../includes/footer.php");
		?>
	</body>
</html>
<!DOCTYPE html>
<?php
if(file_exists("config.php")) {
	require("config.php");
} elseif(file_exists("../config.php")) {
	require("../config.php");
} else {
	require("../../config.php");
}

require("../includes/settings.php");
require("functions/upgrade.php");

if(isset($_POST["bupg"]) && $_POST["bupg"]=="Start upgrade") {
	$upgstatus=upgradecmumdb($dbhost,$dbuser,$dbpass,$dbname,$_POST["cmumver"],$charset);
}
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
		<meta name="robots" content="NOODP, NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
		<title><?php print(CMUM_TITLE); ?> - Upgrade</title>
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
						<h4><span id="headerlight">CSP</span><span id="headerstrong"><strong>MYSQL</strong></span><span id="headerlight">USER</span><span id="headerstrong"><strong>MANAGER</strong></span> - UPGRADE</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="span6 offset2">
					<div class="login">
						<form class="form-horizontal">
							<div class="control-group">
								<div class="controls">
									<h4>Upgrade completed!</h4>
								</div>
								<div class="controls">
									Please remove the upgrade directory to complete the upgrade.
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
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

if(isset($_GET["override"]) && $_GET["override"]=="yes") {
	$currver="3.0.0";
} else {
	$currver=getversion($dbhost,$dbuser,$dbpass,$dbname);
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
						<form class="form-horizontal" name="upgrade" id="upgrade" method="post" action="done.php">
							<input type="hidden" name="cmumver" id="cmumver" value="<?php print($currver); ?>">
							<div class="control-group">
								<?php
									if(CMUM_VERSION>$currver) {
										print("<div class=\"controls\"><h4>CSP MySQL User Manager Upgrade</h4></div>");
										print("<div class=\"controls\">Your CMUM installation needs to be upgraded!<br>Click the button below to start the upgrade.</div>");
										print("&nbsp;<div class=\"controls\"><input type=\"submit\" class=\"btn\" name=\"bupg\" id=\"bupg\" value=\"Start upgrade\"></div>");
									} else {
										print("<div class=\"controls\"><h4>CSP MySQL User Manager Upgrade</h4></div>");
										print("<div class=\"controls\">Your CMUM installation doesn't need to be upgraded!</div>");
									}
								?>
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
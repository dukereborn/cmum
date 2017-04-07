<?php
if(file_exists("config.php")) {
	require("config.php");
} else {
	exit(header("Location: install/"));
}
require("includes/settings.php");
require("functions/cmum.php");
require("functions/login.php");

if(file_exists("install/") && !file_exists("cmum.override")) {
	errorpage("INSTALLATION INCOMPLETE","Please delete the install dir from your www-root after you completed the installation!","utf-8",CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
	exit;
}

if(checkversion(CMUM_VERSION)=="0" && !file_exists("cmum.override")) {
	errorpage("VERSION MISMATCH","Please run the upgrade tool to upgrade your installation!<br><br>Click <a href=\"upgrade/\">here</a> to run the upgrade tool","utf-8",CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
	exit;
}

if(file_exists("upgrade/") && !file_exists("cmum.override")) {
	errorpage("UPGRADE INCOMPLETE","Please delete the upgrade dir from your www-root after you completed the upgrade!","utf-8",CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
	exit;
}

if($dbhost=="" || $dbname=="" || $dbuser=="" || $dbpass=="" || $charset=="" || $secretkey=="") {
	errorpage("CONFIGURATION INCOMPLETE","Please check your configuration file and make sure no values are empty!",$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
	exit;
}

$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	if(mysqli_connect_errno()) {
		errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
		exit;
	}
	$sqls=$mysqli->query("SELECT cleanlogin FROM settings WHERE id='1'");
	$setres=$sqls->fetch_array();
mysqli_close($mysqli);

if(isset($_GET["error"]) && $_GET["error"]=="1") {
	$notice="toastr.error('You dont have access to this page');";
} elseif(isset($_GET["error"]) && $_GET["error"]=="2") {
	$notice="toastr.error('An error occurred, please re-login');";
} elseif(isset($_GET["error"]) && $_GET["error"]=="3") {
	$notice="toastr.error('Session timeout, please re-login');";
}

if(isset($_GET["logout"]) && stripslashes($_GET["logout"])=="1") {
	$notice="toastr.success('You have been successfully logged out');";
}

if(isset($_POST["blogin"]) && $_POST["blogin"]=="Login") {
	$login=login($_POST["user"],$_POST["pass"]);
		if($login=="0") {
			exit(header("Location: dashboard.php"));
		} elseif($login=="1") {
			$notice="toastr.error('Unknown username or bad password');";
		} elseif($login=="2") {
			$notice="toastr.warning('Account disabled');";
		} elseif($login=="3") {
			$notice="toastr.warning('You must enter a username and a password');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php print($charset); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
		<meta name="robots" content="NOODP, NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
		<title><?php if($setres["cleanlogin"]=="1") { print("&nbsp;"); } else { print(CMUM_TITLE); } ?></title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/toastr.min.css">
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="apple-touch-icon" href="favicon.png">
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body onLoad="<?php if(isset($notice)) { print($notice); } ?>">
		<div id="in-nav">
			<div class="container">
				<div class="row">
					<div class="span12">
						<?php
							if($setres["cleanlogin"]=="1") {
								print("<h4></h4>");
							} else {
								print("<h4><span id=\"headerlight\">CSP</span><span id=\"headerstrong\"><strong>MYSQL</strong></span><span id=\"headerlight\">USER</span><span id=\"headerstrong\"><strong>MANAGER</strong></span></h4>");
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="span6 offset2">
					<div class="login">
						<form class="form-horizontal" name="login" id="login" method="post" action="index.php">
							<div class="control-group">
								<div class="controls">
									<h4>Login</h4>
								</div>
							</div>
							<div class="control-group">
								<label for="user" class="control-label">Username </label>
								<div class="controls">
									<input id="user" type="text" name="user" placeholder="Username" maxlength="25" autofocus>
								</div>
							</div>
							<div class="control-group">
								<label for="pass" class="control-label">Password </label>
								<div class="controls">
									<input id="pass" type="password" name="pass" placeholder="Password">
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<input type="submit" class="btn" name="blogin" id="blogin" value="Login">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
			if($setres["cleanlogin"]=="0") {
				require("includes/footer.php");
			}
		?>
		<script src="js/jquery.js"></script>
		<script src="js/cmum.js"></script>
		<script src="js/toastr.min.js"></script>
	</body>
</html>
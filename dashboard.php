<?php
require("functions/logincheck.php");
require("functions/cmum.php");

$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if(mysqli_connect_errno()) {
	errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
	exit;
}

$counters=explode(";",counter());
?>
<html>
	<head>
		<meta charset="<?php print($charset); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
		<meta name="robots" content="NOODP, NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
		<title><?php print(CMUM_TITLE); ?></title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="apple-touch-icon" href="favicon.png">
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<?php
			require("includes/header.php");
		?>
		<div id="in-sub-nav">
			<div class="container">
				<div class="row">
					<div class="span12">
						<ul>
							<li><?php if($_SESSION[$secretkey."fetchcsp"]=="1") { print(dashcheckcspconn($cspconnstatus)); } ?><a href="dashboard.php" class="active"><i class="batch home"></i><br>Dashboard</a></li>
							<li><span class="label label-info pull-right"><?php print($counters[0]); ?></span><a href="users.php"><i class="batch users"></i><br>Users</a></li>
								<?php
									if($_SESSION[$secretkey."admlvl"]=="0") {
										print("<li><span class=\"label label-info pull-right\">".$counters[1]."</span><a href=\"groups.php\"><i class=\"batch database\"></i><br>Groups</a></li>");
										print("<li><span class=\"label label-info pull-right\">".$counters[2]."</span><a href=\"profiles.php\"><i class=\"batch tables\"></i><br>Profiles</a></li>");
										print("<li><span class=\"label label-info pull-right\">".$counters[3]."</span><a href=\"admins.php\"><i class=\"batch star\"></i><br>Admins</a></li>");
										print("<li><a href=\"tools.php\"><i class=\"batch console\"></i><br>Tools</a></li>");
										print("<li><a href=\"settings.php\"><i class=\"batch settings\"></i><br>Settings</a></li>");
									}
								?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="page">
			<div class="page-container">
				<div class="container">
					<div class="row">
						<?php
							if($_SESSION[$secretkey."fetchcsp"]=="1" && $cspconnstatus=="0") {
								print("<div class=\"span12\"><center><div class=\"alert alert-error\"><h4>Warning</h4>Can’t connect to csp server, please check your settings!</div></center></div>");
							}
							if(checksetting("autoupdcheck")==1) {
								$upd=cmumupdcheck(CMUM_VERSION);
								$upd=explode(";",$upd);
									if($upd[0]=="1") {
										print("<div class=\"span12\"><center><div class=\"alert alert-success\"><h4>new version of CMUM available</h4>Installed version: ".CMUM_VERSION." | Latest version: ".$upd[1]."<br>Visit <a href=\"http://github.com/dukereborn/cmum/releases/\" target=\"_blank\">http://github.com/dukereborn/cmum/releases/</a> to download the latest version</center></div>");
									}
							}
						?>
						<div class="span6">
						<h4 class="header header-dash">User Statistics</h4>
							<table class="table table-dash table-striped table-condensed">
								<tr>
									<td>Enabled</td>
									<td><div class="pull-right"><?php print(countusers("1")); ?></div></td>
								</tr>
								<tr>
									<td>Disabled</td>
									<td><div class="pull-right"><?php print(countusers("2")); ?></div></td>
								</tr>
								<tr>
									<td>Expired</td>
									<td><div class="pull-right"><?php print(countusers("3")); ?></div></td>
								</tr>
								<tr>
									<td>Admins</td>
									<td><div class="pull-right"><?php print(countusers("4")); ?></div></td>
								</tr>
								<tr>
									<td>Total</td>
									<td><div class="pull-right"><?php print(countusers("0")); ?></div></td>
								</tr>
							</table>
						
						<?php
							if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
								$nstrusql=$mysqli->query("SELECT id,user,startdate FROM users WHERE startdate>'".date("Y-m-d")."' AND startdate<>'0000-00-00' ORDER BY startdate ".checksetting("notstartusrorder"));
							} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
								$nstrusql=$mysqli->query("SELECT id,user,startdate FROM users WHERE startdate>'".date("Y-m-d")."' AND startdate<>'0000-00-00' AND usrgroup='".$mysqli->real_escape_string($_SESSION[$secretkey."admgrp"])."' ORDER BY startdate ".checksetting("notstartusrorder"));
							} else {
								$nstrusql="";
							}
								if($nstrusql->num_rows<>"0") {
									print("<h4 class=\"header\">Not Started Users</h4>");
									print("<table class=\"table table-dash table-striped table-condensed\">");
										while($nstrres=$nstrusql->fetch_array()) {
											print("<tr><td width=\"50%\">".$nstrres["user"]."</td><td width=\"40%\">".$nstrres["startdate"]."</td><td width=\"10%\"><a href=\"edituser.php?uid=".$nstrres["id"]."\" class=\"btn btn-mini pull-right\">Edit</a></td></tr>");
										}
									print("</table>");
								}
							
							if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
								$expusql=$mysqli->query("SELECT id,user,expiredate FROM users WHERE expiredate<='".date("Y-m-d")."' AND expiredate<>'0000-00-00' ORDER BY expiredate ".checksetting("expusrorder"));
							} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
								$expusql=$mysqli->query("SELECT id,user,expiredate FROM users WHERE expiredate<='".date("Y-m-d")."' AND expiredate<>'0000-00-00' AND usrgroup='".$mysqli->real_escape_string($_SESSION[$secretkey."admgrp"])."' ORDER BY expiredate ".checksetting("expusrorder"));
							} else {
								$expusql="";
							}
								if($expusql->num_rows<>"0") {
									print("<h4 class=\"header\">Expired Users</h4>");
									print("<table class=\"table table-dash table-striped table-condensed\">");
										while($expures=$expusql->fetch_array()) {
											print("<tr><td width=\"50%\">".$expures["user"]."</td><td width=\"40%\">".$expures["expiredate"]."</td><td width=\"10%\"><a href=\"edituser.php?uid=".$expures["id"]."\" class=\"btn btn-mini pull-right\">Edit</a></td></tr>");
										}
									print("</table>");
								}	
							$day30p=date("Y-m-d",strtotime("+30 days"));
							if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
								$expu30sql=$mysqli->query("SELECT id,user,expiredate FROM users WHERE expiredate<='".$day30p."' AND expiredate>'".date("Y-m-d")."' AND expiredate<>'0000-00-00' ORDER BY expiredate ".checksetting("soonexpusrorder"));
							} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
								$expu30sql=$mysqli->query("SELECT id,user,expiredate FROM users WHERE expiredate<='".$day30p."' AND expiredate>'".date("Y-m-d")."' AND expiredate<>'0000-00-00' AND usrgroup='".$mysqli->real_escape_string($_SESSION[$secretkey."admgrp"])."' ORDER BY expiredate ".checksetting("soonexpusrorder"));
							} else {
								$expu30sql="";
							}
								if($expu30sql->num_rows<>"0") {
									print("<h4 class=\"header\">Expired Users Within 30 Days</h4>");
									print("<table class=\"table table-dash table-striped table-condensed\">");
										while($expu30res=$expu30sql->fetch_array()) {
											print("<tr><td width=\"50%\">".$expu30res["user"]."</td><td width=\"40%\">".$expu30res["expiredate"]."</td><td width=\"10%\"><a href=\"edituser.php?uid=".$expu30res["id"]."\" class=\"btn btn-mini pull-right\">Edit</a></td></tr>");
										}
									print("</table>");
								}
						?>
						</div>
						<div class="span6">
						<?php
							if($_SESSION[$secretkey."fetchcsp"]=="1" && $cspconnstatus=="1") {
								$cspsrvdata=explode(";",cspgetsrvinfo());
								print("<h4 class=\"header\">CSP Server Info</h4>");
								print("<table class=\"table table-dash table-striped table-condensed\">");
									print("<tr><td>Name</td><td><span class=\"pull-right\">".$cspsrvdata[0]."</span></td></tr>");
									print("<tr><td>State</td><td><span class=\"pull-right\">".$cspsrvdata[1]."</span></td></tr>");
									print("<tr><td>Version</td><td><span class=\"pull-right\">".$cspsrvdata[2]."</span></td></tr>");
									print("<tr><td>Started</td><td><span class=\"pull-right\">".$cspsrvdata[3]."</span></td></tr>");
									print("<tr><td>Uptime</td><td><span class=\"pull-right\">".$cspsrvdata[4]."</span></td></tr>");
									print("<tr><td>Connectors</td><td><span class=\"pull-right\">".$cspsrvdata[5]."</span></td></tr>");
									print("<tr><td>Sessions</td><td><span class=\"pull-right\">".$cspsrvdata[6]."</span></td></tr>");
									print("<tr><td>Estimated total capacity</td><td><span class=\"pull-right\">".$cspsrvdata[7]."</span></td></tr>");
									print("<tr><td>ECM total</td><td><span class=\"pull-right\">".$cspsrvdata[8]."</span></td></tr>");
									print("<tr><td>ECM forwards</td><td><span class=\"pull-right\">".$cspsrvdata[9]."</span></td></tr>");
									print("<tr><td>ECM cache hits</td><td><span class=\"pull-right\">".$cspsrvdata[10]."</span></td></tr>");
									print("<tr><td>ECM denied</td><td><span class=\"pull-right\">".$cspsrvdata[11]."</span></td></tr>");
									print("<tr><td>ECM filtered</td><td><span class=\"pull-right\">".$cspsrvdata[12]."</span></td></tr>");
									print("<tr><td>ECM failures</td><td><span class=\"pull-right\">".$cspsrvdata[13]."</span></td></tr>");
									print("<tr><td>EMM total</td><td><span class=\"pull-right\">".$cspsrvdata[14]."</span></td></tr>");
									print("<tr><td>JavaVM</td><td><span class=\"pull-right\">".$cspsrvdata[15]."</span></td></tr>");
									print("<tr><td>OS</td><td><span class=\"pull-right\">".$cspsrvdata[16]."</span></td></tr>");
									print("<tr><td>Heap</td><td><span class=\"pull-right\">".$cspsrvdata[17]."</span></td></tr>");
									print("<tr><td>TC</td><td><span class=\"pull-right\">".$cspsrvdata[18]."</span></td></tr>");
									print("<tr><td>FD</td><td><span class=\"pull-right\">".$cspsrvdata[19]."</span></td></tr>");
								print("</table>");
							}	
						?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			require("includes/footer.php");
			mysqli_close($mysqli);
		?>
		<script src="js/jquery.js"></script>
		<script src="js/cmum.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
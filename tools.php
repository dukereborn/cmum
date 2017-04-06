<?php
require("functions/admincheck.php");
require("functions/cmum.php");

if(isset($_POST["baction"]) && $_POST["baction"]=="Enable all users") {
	$status=enallusr($_SESSION[$secretkey."admid"],$_POST["admpasswd"]);
		if($status=="1") {
			$notice="toastr.success('All users enabled');";
		} elseif($status=="2") {
			$notice="toastr.error('Incorrect password');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Disable all users") {
	$status=disallusr($_SESSION[$secretkey."admid"],$_POST["admpasswd"]);
		if($status=="1") {
			$notice="toastr.success('All users disabled');";
		} elseif($status=="2") {
			$notice="toastr.error('Incorrect password');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Delete disabled users") {
	$status=deldisusr($_SESSION[$secretkey."admid"],$_POST["admpasswd"]);
		if($status=="1") {
			$notice="toastr.success('All disabled users deleted');";
		} elseif($status=="2") {
			$notice="toastr.error('Incorrect password');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Delete expired users") {
	$status=delexpusr($_SESSION[$secretkey."admid"],$_POST["admpasswd"],$_POST["expdate"]);
		if($status=="1") {
			$notice="toastr.success('All expired users deleted');";
		} elseif($status=="2") {
			$notice="toastr.error('Incorrect password');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Empty user database") {
	$status=emptyudb($_SESSION[$secretkey."admid"],$_POST["admpasswd"]);
		if($status=="1") {
			$notice="toastr.success('User database emptied');";
		} elseif($status=="2") {
			$notice="toastr.error('Incorrect password');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Empty group database") {
	$status=emptygdb($_SESSION[$secretkey."admid"],$_POST["admpasswd"]);
		if($status=="1") {
			$notice="toastr.success('Group database emptied');";
		} elseif($status=="2") {
			$notice="toastr.error('Incorrect password');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Empty profile database") {
	$status=emptypdb($_SESSION[$secretkey."admid"],$_POST["admpasswd"]);
		if($status=="1") {
			$notice="toastr.success('Profile database emptied');";
		} elseif($status=="2") {
			$notice="toastr.error('Incorrect password');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["bclradmlog"]) && $_POST["bclradmlog"]=="Clear log") {
	$status=clearlog("log_login");
		if($status=="1") {
			$notice="toastr.success('Admin login log cleared');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["bclrgxlog"]) && $_POST["bclrgxlog"]=="Clear log") {
	$status=clearlog("log_genxmlreq");
		if($status=="1") {
			$notice="toastr.success('Genxml request log cleared');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["bclractlog"]) && $_POST["bclractlog"]=="Clear log") {
	$status=clearlog("log_activity");
		if($status=="1") {
			$notice="toastr.success('Activity log cleared');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["bimpxml"]) && $_POST["bimpxml"]=="Import users") {
	if(isset($_POST["createprof"]) && $_POST["createprof"]<>"") {
		$createprof=$_POST["createprof"];
	} else {
		$createprof="0";
	}
	$impstatus=impusrcspxml($_POST["cspxml"],$_POST["usrgrp"],$createprof);
		if(!empty($impstatus["usrimp"]) || !empty($impstatus["usrexi"])) {
			$notice="$('#modalImpUsrRes').modal('show');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["bimpcsv"]) && $_POST["bimpcsv"]=="Import users") {
	if(isset($_POST["creategrp"]) && $_POST["creategrp"]<>"") {
		$creategrp=$_POST["creategrp"];
	} else {
		$creategrp="0";
	}
	if(isset($_POST["createprof"]) && $_POST["createprof"]<>"") {
		$createprof=$_POST["createprof"];
	} else {
		$createprof="0";
	}
	$impstatus=impusrcsv($_POST["csv"],$creategrp,$createprof,$_POST["cmumcsvver"]);
		if(!empty($impstatus["usrimp"]) || !empty($impstatus["usrexi"])) {
			$notice="$('#modalImpUsrRes').modal('show');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Update CSP Users") {
	$status=cspupdateusers();
		if($status=="1") {
			$notice="toastr.success('Updated performed');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Send OSD") {
	$status=cspsendosdtoall($_POST["osdmsg"]);
		$status=explode(";",$status);
		if($status["0"]=="1") {
			$notice="toastr.success('Message sent to ".$status["1"]." active and compatible newcamd sessions');";
		} elseif($status["0"]=="2") {
			$notice="toastr.error('No active/compatible newcamd sessions found');";
		} elseif($status["0"]=="0") {
			$notice="toastr.error('Message not sent, please try again');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Shutdown CSP Server") {
	$status=cspshutdown();
		if($status=="1") {
			$notice="toastr.success('Shutdown initiated');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Check tables") {
	$status=checktables();
		if($status=="1") {
			$notice="toastr.success('Database tables checked successfully');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Analyze tables") {
	$status=analyzetables();
		if($status=="1") {
			$notice="toastr.success('Database tables analyzed successfully');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Repair tables") {
	$status=repairtables();
		if($status=="1") {
			$notice="toastr.success('Database tables repaired successfully');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Optimize tables") {
	$status=optimizetables();
		if($status=="1") {
			$notice="toastr.success('Database tables optimized successfully');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["bexpcsvdown"]) && $_POST["bexpcsvdown"]=="Download") {
	downloadexpusrcsv($_POST["expcsv"]);
}
if(isset($_POST["bexpxmldown"]) && $_POST["bexpxmldown"]=="Download") {
	downloadexpusrxml($_POST["expcspxml"]);
}
if(isset($_POST["bimpprof"]) && $_POST["bimpprof"]=="Import profiles") {
	$impstatus=impcspprofiles($_POST["profvalue"],$_POST["profname"]);
		if(!empty($impstatus["profimp"]) || !empty($impstatus["profexi"])) {
			$notice="$('#modalImpProfRes').modal('show');";
		} else {
			$notice="toastr.error('Something went wrong, try again');";
		}
}
if(isset($_POST["baction"]) && $_POST["baction"]=="Send emails") {
	$status=sendemailtoall($_POST["email_subject"],$_POST["email_body"]);
		if($status=="1") {
			$notice="toastr.error('Emails not sent, please try again or check settings');";
		} else {
			$notice="toastr.success('Emails sent');";
		}
}

$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if(mysqli_connect_errno()) {
	errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
	exit;
}
	$sql=$mysqli->query("SELECT fetchcsp,comptables FROM settings");
	$setres=$sql->fetch_array();
		if($setres["comptables"]=="1") {
			$tblcond=" table-condensed";
		} else {
			$tblcond="";
		}
mysqli_close($mysqli);

$counters=explode(";",counter());
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php print($charset); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
		<meta name="robots" content="NOODP, NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
		<title><?php print(CMUM_TITLE); ?></title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="css/styles.css">
		<link rel="stylesheet" href="css/toastr.min.css">
		<link rel="stylesheet" href="css/datepicker.css">
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="apple-touch-icon" href="favicon.png">
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body onload="<?php if(isset($notice)) { print($notice); } ?>">
		<?php
			require("includes/header.php");
		?>
		<div id="in-sub-nav">
			<div class="container">
				<div class="row">
					<div class="span12">
						<ul>
							<li><?php if($_SESSION[$secretkey."fetchcsp"]=="1") { print(dashcheckcspconn($cspconnstatus)); } ?><a href="dashboard.php"><i class="batch home"></i><br>Dashboard</a></li>
							<li><span class="label label-info pull-right"><?php print($counters[0]); ?></span><a href="users.php"><i class="batch users"></i><br>Users</a></li>
								<?php
									if($_SESSION[$secretkey."admlvl"]=="0") {
										print("<li><span class=\"label label-info pull-right\">".$counters[1]."</span><a href=\"groups.php\"><i class=\"batch database\"></i><br>Groups</a></li>");
										print("<li><span class=\"label label-info pull-right\">".$counters[2]."</span><a href=\"profiles.php\"><i class=\"batch tables\"></i><br>Profiles</a></li>");
										print("<li><span class=\"label label-info pull-right\">".$counters[3]."</span><a href=\"admins.php\"><i class=\"batch star\"></i><br>Admins</a></li>");
										print("<li><a href=\"tools.php\" class=\"active\"><i class=\"batch console\"></i><br>Tools</a></li>");
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
						<div class="span3">
						<h4>Tools</h4>
							<div class="sidebar">
								<ul class="col-nav span3">
									<li class="sidebar-header"><a href="#users" data-toggle="collapse" class="accordion-toggle">Users</a></li>
									<li id="users" <?php if(isset($_GET["menu"]) && $_GET["menu"]=="1") { print("class=\"in collapse\""); } else { print("class=\"collapse\""); }; ?>>
											<ul>
												<li class="sidebar-inner">
													<a href="tools.php?menu=1&tool=101"><span>Enable All Users</span></a>
													<a href="tools.php?menu=1&tool=102"><span>Disable All Users</span></a>
													<a href="tools.php?menu=1&tool=103"><span>Delete Disabled Users</span></a>
													<a href="tools.php?menu=1&tool=104"><span>Delete Expired Users</span></a>
													<a href="tools.php?menu=1&tool=105"><span>Empty User Database</span></a>
												</li>
											</ul>
										</li>
									<li class="sidebar-header"><a href="#impexp" data-toggle="collapse" class="accordion-toggle">Import/Export</a></li>
										<li id="impexp" <?php if(isset($_GET["menu"]) && $_GET["menu"]=="2") { print("class=\"in collapse\""); } else { print("class=\"collapse\""); }; ?>>
											<ul>
												<li class="sidebar-inner">
													<a href="tools.php?menu=2&tool=201"><span>Import Users (CSP XML)</span></a>
													<a href="tools.php?menu=2&tool=202"><span>Import Users (CSV)</span></a>
													<a href="tools.php?menu=2&tool=203"><span>Export Users (CSP XML)</span></a>
													<a href="tools.php?menu=2&tool=204"><span>Export Users (CSV)</span></a>
													<?php
														if($setres["fetchcsp"]=="1" && $cspconnstatus=="1") {
															print("<a href=\"tools.php?menu=2&tool=205\"><span>Import Profiles (CSP Server)</span></a>");
														}	
													?>
												</li>
											</ul>
										</li>
									<?php
										if($setres["fetchcsp"]=="1" && $cspconnstatus=="1") {
											print("<li class=\"sidebar-header\"><a href=\"#csp\" data-toggle=\"collapse\" class=\"accordion-toggle\">CSP Server</a></li>");
												if(isset($_GET["menu"]) && $_GET["menu"]=="3") {
													print("<li id=\"csp\" class=\"in collapse\">");
												} else {
													print("<li id=\"csp\" class=\"collapse\">");
												}
												print("<ul>");
													print("<li class=\"sidebar-inner\">");
														print("<a href=\"tools.php?menu=3&tool=301\"><span>Update CSP Users</span></a>");
														print("<a href=\"tools.php?menu=3&tool=302\"><span>Send OSD to All Users</span></a>");
														print("<a href=\"tools.php?menu=3&tool=303\"><span>Shutdown CSP Server</span></a>");
													print("</li>");
												print("</ul>");
											print("</li>");
										}
									?>
									<li class="sidebar-header"><a href="#logs" data-toggle="collapse" class="accordion-toggle">Logs</a></li>
										<li id="logs" <?php if(isset($_GET["menu"]) && $_GET["menu"]=="4") { print("class=\"in collapse\""); } else { print("class=\"collapse\""); }; ?>>
											<ul>
												<li class="sidebar-inner">
													<a href="tools.php?menu=4&tool=401"><span>Admin Login</span></a>
													<a href="tools.php?menu=4&tool=402"><span>Genxml Request</span></a>
													<a href="tools.php?menu=4&tool=403"><span>Manager Activity</span></a>
												</li>
											</ul>
										</li>
									<li class="sidebar-header"><a href="#misc" data-toggle="collapse" class="accordion-toggle">Miscellaneous</a></li>
									<li id="misc" <?php if(isset($_GET["menu"]) && $_GET["menu"]=="5") { print("class=\"in collapse\""); } else { print("class=\"collapse\""); }; ?>>
											<ul>
												<li class="sidebar-inner">
													<a href="tools.php?menu=5&tool=501"><span>Empty Group Database</span></a>
													<a href="tools.php?menu=5&tool=502"><span>Empty Profile Database</span></a>
													<?php
														if(checkemailsettings()=="0") {
															print("<a href=\"tools.php?menu=5&tool=503\"><span>Send Email to All Users</span></a>");
														}
													?>
												</li>
											</ul>
										</li>
									<li class="sidebar-header"><a href="#db" data-toggle="collapse" class="accordion-toggle">Database</a></li>
									<li id="db" <?php if(isset($_GET["menu"]) && $_GET["menu"]=="6") { print("class=\"in collapse\""); } else { print("class=\"collapse\""); }; ?>>
											<ul>
												<li class="sidebar-inner">
													<a href="tools.php?menu=6&tool=601"><span>Maintenance</span></a>
												</li>
											</ul>
										</li>
									<li class="sidebar-header"><a href="tools.php?menu=9&tool=901"><span>Update Check</span></a></li>
								</ul>
							</div>
						</div>
						<div class="span9">
							<?php
								if(isset($_GET["menu"]) && isset($_GET["tool"]) && !empty($_GET["tool"])) {
									include(includetool($_GET["tool"]));
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			require("includes/modal-impusrres.php");
			require("includes/modal-impprofres.php");
			require("includes/modal-logout.php");
			require("includes/footer.php");
		?>
		<script src="js/jquery.js"></script>
		<script src="js/cmum.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/toastr.min.js"></script>
		<script src="js/tablesorter.min.js"></script>
		<script src="js/datepicker.js"></script>
		<script src="js/ajaxcalls.js"></script>
		<script>
			$('#expdate').datepicker({
				format: 'yyyy-mm-dd',
				weekStart: 1
			});
			$(".sortable").tablesorter();
		</script>
	</body>
</html>
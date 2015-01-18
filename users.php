<!DOCTYPE html>
<?php
require("functions/logincheck.php");
require("functions/cmum.php");

if(isset($_GET["action"]) && stripslashes($_GET["action"])=="delete" && isset($_GET["uid"]) && $_GET["uid"]<>"") {
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	if(mysqli_connect_errno()) {
		errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
		exit;
	}
		$sql=$mysqli->query("SELECT id,user,usrgroup FROM users WHERE id='".$mysqli->real_escape_string($_GET["uid"])."'");
		$eu_res=$sql->fetch_array();
			if($_SESSION[$secretkey."userlvl"]=="2" && $_SESSION[$secretkey."usergrp"]<>$eu_res["usrgroup"]) {
				$notice="toastr.error('This user does not belong to you');";
			} else {
				$eu_id=$eu_res["id"];
				$eu_user=$eu_res["user"];
				$notice="$('#modalDelUser').modal({ show: true });";
			}
	mysqli_close($mysqli);	
}
if(isset($_POST["bdelusr"]) && $_POST["bdelusr"]=="Delete") {
	$status=deleteuser($_POST["uid"]);
		if($status=="0") {
			$notice="toastr.success('User successfully deleted');";
		}
	$counters=explode(";",counter());	
}

$counters=explode(";",counter());

$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if(mysqli_connect_errno()) {
	errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
	exit;
}
	$sql=$mysqli->query("SELECT comptables,extrausrtbl FROM settings WHERE id='1'");
	$setres=$sql->fetch_array();
		if($setres["comptables"]=="1") {
			$tblcond=" table-condensed";
		} else {
			$tblcond="";
		}
mysqli_close($mysqli);

if(isset($_GET["error"]) && $_GET["error"]=="1") {
	$notice="toastr.error('This user does not belong to you');";
}
if(isset($_GET["edit"]) && $_GET["edit"]=="1") {
	$notice="toastr.success('Changes saved');";
}
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
		<link rel="stylesheet" href="css/toastr.min.css">
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
							<li><span class="label label-info pull-right"><?php print($counters[0]); ?></span><a href="users.php" class="active"><i class="batch users"></i><br>Users</a></li>
								<?php
									if($_SESSION[$secretkey."userlvl"]=="0") {
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
						<div class="span12">
							<span class="pull-right form-inline">
							<form name="newsearch" id="newsearch" class="form-inline" method="post" action="users.php">
								<input type="text" name="searchfor" id="searchfor" class="input-medium" placeholder="Search" autocomplete="off" onkeypress="checksearch(event);" value="<?php if(isset($_POST["searchfor"]) && $_POST["searchfor"]<>"") { print($_POST["searchfor"]); } ?>" <?php if(isset($_POST["searchfor"]) && $_POST["searchfor"]<>"") { print("autofocus"); } ?>>
								<input type="text" name="filterfor" id="filterfor" class="input-medium light-table-filter" data-table="order-table" placeholder="Filter" autocomplete="off">
									&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="newuser.php" class="btn">New User</a>
							</form>
							</span>
							<h4 class="header">Users</h4>
								<table class="table table-striped table-hover sortable order-table <?php print($tblcond); ?>">
									<thead>
										<tr>
											<th style="cursor:ns-resize;">Username</th>
											<th style="cursor:ns-resize;">Displayname</th>
											<?php
												if($setres["extrausrtbl"]=="1") {
													print("<th style=\"cursor:ns-resize;\">Password</th>");
												} elseif($setres["extrausrtbl"]=="2") {
													print("<th style=\"cursor:ns-resize;\">Start date</th>");
												} elseif($setres["extrausrtbl"]=="3") {
													print("<th style=\"cursor:ns-resize;\">Expire date</th>");
												} elseif($setres["extrausrtbl"]=="4") {
													print("<th style=\"cursor:ns-resize;\">Added by</th>");
												}
											?>
											<th style="cursor:ns-resize;">Group</th>
											<th style="cursor:ns-resize;">Status</th>
											<?php
												if($_SESSION[$secretkey."fetchcsp"]=="1" && $cspconnstatus=="1") {
													print("<th style=\"cursor:ns-resize;\">CSP Activity</th>");
												}
											?>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
											if($_SESSION[$secretkey."fetchcsp"]=="1" && $cspconnstatus=="1") {
												$cspuserlist=cspgetuserlist();
											}
											
											$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
											if(mysqli_connect_errno()) {
												errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
												exit;
											}
											if($_SESSION[$secretkey."userlvl"]=="0" || $_SESSION[$secretkey."userlvl"]=="1") {
												if(isset($_POST["searchfor"]) && $_POST["searchfor"]<>"") {
													$searchstring=$mysqli->real_escape_string(trim($_POST["searchfor"]));
													$sql=$mysqli->query("SELECT * FROM users WHERE (user LIKE '%".$searchstring."%' OR password LIKE '%".$searchstring."%' OR displayname LIKE '%".$searchstring."%' OR ipmask LIKE '%".$searchstring."%' OR mapexclude LIKE '%".$searchstring."%' OR comment LIKE '%".$searchstring."%' OR email LIKE '%".$searchstring."%' OR boxtype LIKE '%".$searchstring."%' OR macaddress LIKE '%".$searchstring."%' OR serialnumber LIKE '%".$searchstring."%') ORDER BY user");
												} else {
													$sql=$mysqli->query("SELECT id,user,password,displayname,usrgroup,admin,enabled,startdate,expiredate,addedby FROM users ORDER BY user");
												}	
											} elseif($_SESSION[$secretkey."userlvl"]=="2" && $_SESSION[$secretkey."usergrp"]<>"0") {
												if(isset($_POST["searchfor"]) && $_POST["searchfor"]<>"") {
													$searchstring=$mysqli->real_escape_string(trim($_POST["searchfor"]));
													$sql=$mysqli->query("SELECT * FROM users WHERE (user LIKE '%".$searchstring."%' OR password LIKE '%".$searchstring."%' OR displayname LIKE '%".$searchstring."%' OR ipmask LIKE '%".$searchstring."%' OR mapexclude LIKE '%".$searchstring."%' OR comment LIKE '%".$searchstring."%' OR email LIKE '%".$searchstring."%' OR boxtype LIKE '%".$searchstring."%' OR macaddress LIKE '%".$searchstring."%' OR serialnumber LIKE '%".$searchstring."%') AND usrgroup='".$_SESSION[$secretkey."usergrp"]."' ORDER BY user");
												} else {
													$sql=$mysqli->query("SELECT id,user,password,displayname,usrgroup,admin,enabled,startdate,expiredate,addedby FROM users WHERE usrgroup='".$mysqli->real_escape_string($_SESSION[$secretkey."usergrp"])."' ORDER BY user");	
												}
											} else {
												$sql="";
											}
												while($res=$sql->fetch_array()) {
													if($res["startdate"]<>"0000-00-00" && time()<=strtotime($res["startdate"])) {
														$usrexp="2";
													} elseif($res["expiredate"]<>"0000-00-00" && time()>=strtotime($res["expiredate"])) {
														$usrexp="1";
													} else {
														$usrexp="0";
													}
													print("<tr>");
														if($res["admin"]=="1") {
															print("<td>".$res["user"]." <span class=\"label label-warning\">A</span></td>");	
														} else {
															print("<td>".$res["user"]."</td>");
														}
														print("<td>".$res["displayname"]."</td>");
														if($setres["extrausrtbl"]=="1") {
															print("<td>".$res["password"]."</td>");
														} elseif($setres["extrausrtbl"]=="2") {
															print("<td>".printdate($res["startdate"])."</td>");
														} elseif($setres["extrausrtbl"]=="3") {
															print("<td>".printdate($res["expiredate"])."</td>");
														} elseif($setres["extrausrtbl"]=="4") {
															print("<td>".idtoadmin($res["addedby"])."</td>");
														}
														print("<td>".idtogrp($res["usrgroup"])."</td>");
															if($usrexp=="1") {
																print("<td><a id=\"usrlnkenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"enableuser('".$res["id"]."','".$_SESSION[$secretkey."userlvl"]."','".$_SESSION[$secretkey."usergrp"]."','".$_SESSION[$secretkey."userid"]."');\"><div id=\"usrenabled-".$res["id"]."\"><span class=\"label label-warning\">Expired</span></div></a></td>");
															} elseif($usrexp=="2") {
																print("<td><a id=\"usrlnkenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"enableuser('".$res["id"]."','".$_SESSION[$secretkey."userlvl"]."','".$_SESSION[$secretkey."usergrp"]."','".$_SESSION[$secretkey."userid"]."');\"><div id=\"usrenabled-".$res["id"]."\"><span class=\"label label-warning\">Disabled</span></div></a></td>");
															} elseif($res["enabled"]=="1" && $usrexp=="0" || $res["enabled"]=="" && $usrexp=="0") {
																print("<td><a id=\"usrlnkenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"disableuser('".$res["id"]."','".$_SESSION[$secretkey."userlvl"]."','".$_SESSION[$secretkey."usergrp"]."','".$_SESSION[$secretkey."userid"]."');\"><div id=\"usrenabled-".$res["id"]."\"><span class=\"label label-success\">Enabled</span></div></a></td>");
															} elseif($res["enabled"]=="0" && $usrexp=="0") {
																print("<td><a id=\"usrlnkenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"enableuser('".$res["id"]."','".$_SESSION[$secretkey."userlvl"]."','".$_SESSION[$secretkey."usergrp"]."','".$_SESSION[$secretkey."userid"]."');\"><div id=\"usrenabled-".$res["id"]."\"><span class=\"label label-important\">Disabled</span></div></a></td>");
															} else {
																print("<td></td>");
															}
															if($_SESSION[$secretkey."fetchcsp"]=="1" && $cspconnstatus=="1") {
																if(isset($cspuserlist[$res["user"]])) {
																	if($cspuserlist[$res["user"]]=="1") {
																		print("<td><a href=\"javascript:void(0);\" onclick=\"cspkickuser('".$res["user"]."');\"><div id=\"cspstate-".$res["user"]."\"><span class=\"label label-success\">Active</span></div></a></td>");
																	} elseif($cspuserlist[$res["user"]]=="0") {
																		print("<td><a href=\"javascript:void(0);\" onclick=\"cspkickuser('".$res["user"]."');\"><div id=\"cspstate-".$res["user"]."\"><span class=\"label\">Idle</span></div></a></td>");
																	}
																} else {
																	print("<td></td>");
																}
															}
														print("<td>");
															print("<div class=\"btn-group pull-right\">");
																print("<button data-toggle=\"dropdown\" class=\"btn btn-small\">Actions <span class=\"caret\"></span></button>");
																print("<ul class=\"dropdown-menu\">");
																	if($_SESSION[$secretkey."fetchcsp"]=="1" && $cspconnstatus=="1" && isset($cspuserlist[$res["user"]])) {
																		$cspmenu="<a href=\"javascript:void(0);\" onclick=\"cspgetuserinfo('".$res["user"]."');\">User info</a><a href=\"javascript:void(0);\" onclick=\"cspgetuseripinfo('".$res["user"]."');\">User ip info</a><a href=\"javascript:void(0);\" onclick=\"csploadsendosd('".$res["user"]."');\">Send message</a><a href=\"javascript:void(0);\" onclick=\"cspkickuser('".$res["user"]."');\">Kick</a>";
																	} else {
																		$cspmenu="";
																	}
																	if($res["enabled"]=="1" && $usrexp=="0" || $res["enabled"]=="" && $usrexp=="0") {
																		print("<li class=\"ausrenabled-".$res["id"]."\"><a href=\"edituser.php?uid=".$res["id"]."\">Edit</a><a id=\"ausrenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"disableuser('".$res["id"]."','".$_SESSION[$secretkey."userlvl"]."','".$_SESSION[$secretkey."usergrp"]."','".$_SESSION[$secretkey."userid"]."');\">Disable</a>".$cspmenu."<a href=\"users.php?action=delete&uid=".$res["id"]."\">Delete</a></li>");
																	} else {
																		print("<li><a href=\"edituser.php?uid=".$res["id"]."\">Edit</a><a id=\"ausrenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"enableuser('".$res["id"]."','".$_SESSION[$secretkey."userlvl"]."','".$_SESSION[$secretkey."usergrp"]."','".$_SESSION[$secretkey."userid"]."');\">Enable</a>".$cspmenu."<a href=\"users.php?action=delete&uid=".$res["id"]."\">Delete</a></li>");
																	}
																print("</ul>");
															print("</div>");
														print("</td>");
													print("</tr>");
													$usrexp="";
												}
											mysqli_close($mysqli);
										?>
									</tbody>
								</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			require("includes/modal-deluser.php");
			require("includes/modal-cspsendosd.php");
			require("includes/modal-cspuserinfo.php");
			require("includes/modal-cspuseripinfo.php");
			require("includes/footer.php");
		?>
		<script src="js/jquery.js"></script>
		<script src="js/cmum.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/toastr.min.js"></script>
		<script src="js/ajaxcalls.js"></script>
		<script src="js/tablesorter.min.js"></script>
		<script src="js/modal.js"></script>
		<script src="js/tablefilter.js"></script>
		<script language="javascript" type="text/javascript">
			$(".sortable").tablesorter();
		</script>
	</body>
</html>
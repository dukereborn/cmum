<!DOCTYPE html>
<?php
require("functions/admincheck.php");
require("functions/cmum.php");

$counters=explode(";",counter());

if(isset($_POST["value"]) && $_POST["value"]=="baddadm") {
	$status=addadmin($_POST["user"],$_POST["pass"],$_POST["name"],$_POST["admlvl"],$_POST["ugroup"]);
		if($status=="0") {
			$counters=explode(";",counter());
			$notice="toastr.success('Admin successfully created');";
		} elseif($status=="1") {
			$notice="toastr.error('You must enter a username and a password'); $('#modalNewAdmin').modal({ show: true });";
		} elseif($status=="2") {
			$notice="toastr.error('Admin already exists'); $('#modalNewAdmin').modal({ show: true });";
		} elseif($status=="3") {
			$notice="toastr.error('You must select a group'); $('#modalNewAdmin').modal({ show: true });";
		}
}

if(isset($_GET["action"]) && stripslashes($_GET["action"])=="edit" && isset($_GET["aid"]) && $_GET["aid"]<>"") {
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	if(mysqli_connect_errno()) {
		errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
		exit;
	}
		$sql=$mysqli->query("SELECT id,user,name,admlvl,ugroup FROM admins WHERE id='".$mysqli->real_escape_string($_GET["aid"])."'");
		$ea_res=$sql->fetch_array();
			$ea_id=$ea_res["id"];
			$ea_user=$ea_res["user"];
			$ea_name=$ea_res["name"];
			$ea_admlvl=$ea_res["admlvl"];
			$ea_ugroup=$ea_res["ugroup"];
	mysqli_close($mysqli);
	$notice="$('#modalEditAdmin').modal({ show: true });";
}
if(isset($_POST["value"]) && $_POST["value"]=="beditadm") {
	$status=editadmin($_POST["aid"],$_POST["user"],$_POST["name"],$_POST["admlvl"],$_POST["ugroup"]);
		if($status=="0") {
			$notice="toastr.success('Changes successfully saved');";
		} elseif($status=="1") {
			$ea_id=$_POST["aid"];
			$ea_user=$_POST["user"];
			$ea_name=$_POST["name"];
			$ea_admlvl=$_POST["admlvl"];
			$ea_ugroup=$_POST["ugroup"];
			$notice="toastr.error('You must select a group'); $('#modalEditAdmin').modal({ show: true });";
		}
}

if(isset($_GET["action"]) && stripslashes($_GET["action"])=="chpass" && isset($_GET["aid"]) && $_GET["aid"]<>"") {
	$ea_id=$_GET["aid"];
	$notice="$('#modalChpassAdmin').modal({ show: true });";
}
if(isset($_POST["value"]) && $_POST["value"]=="bchpassadm") {
	$status=chpassadmin($_POST["aid"],$_POST["pass1"],$_POST["pass2"]);
		if($status=="0") {
			$notice="toastr.success('Password successfully changed');";
		} elseif($status=="1") {
			$ea_id=$_POST["aid"];
			$ea_pass1=$_POST["pass1"];
			$ea_pass2=$_POST["pass2"];
			$notice="toastr.error('You must fill in both fields'); $('#modalChpassAdmin').modal({ show: true });";
		} elseif($status=="2") {
			$ea_id=$_POST["aid"];
			$ea_pass1=$_POST["pass1"];
			$ea_pass2=$_POST["pass2"];
			$notice="toastr.error('Passwords dont match'); $('#modalChpassAdmin').modal({ show: true });";
		}
}

$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if(mysqli_connect_errno()) {
	errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
	exit;
}
	$sql=$mysqli->query("SELECT comptables FROM settings");
	$setres=$sql->fetch_array();
		if($setres["comptables"]=="1") {
			$tblcond=" table-condensed";
		} else {
			$tblcond="";
		}
mysqli_close($mysqli);
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
							<li><span class="label label-info pull-right"><?php print($counters[0]); ?></span><a href="users.php"><i class="batch users"></i><br>Users</a></li>
								<?php
									if($_SESSION[$secretkey."admlvl"]=="0") {
										print("<li><span class=\"label label-info pull-right\">".$counters[1]."</span><a href=\"groups.php\"><i class=\"batch database\"></i><br>Groups</a></li>");
										print("<li><span class=\"label label-info pull-right\">".$counters[2]."</span><a href=\"profiles.php\"><i class=\"batch tables\"></i><br>Profiles</a></li>");
										print("<li><span class=\"label label-info pull-right\" id=\"numadmins\">".$counters[3]."</span><a href=\"admins.php\" class=\"active\"><i class=\"batch star\"></i><br>Admins</a></li>");
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
							<a href="#modalNewAdmin" data-toggle="modal" class="btn pull-right">New Admin</a>
							<h4 class="header">Admins</h4>
								<table class="table table-striped table-hover sortable <?php print($tblcond); ?>">
									<thead>
										<tr>
											<th style="cursor:ns-resize;">Username</th>
											<th style="cursor:ns-resize;">Name</th>
											<th style="cursor:ns-resize;">Type</th>
											<th style="cursor:ns-resize;">Group</th>
											<th style="cursor:ns-resize;">Status</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
											$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
											if(mysqli_connect_errno()) {
												errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
												exit;
											}
												$sql=$mysqli->query("SELECT * FROM admins ORDER BY user");
												while($res=$sql->fetch_array()) {
													print("<tr id=admin-".$res["id"].">");
														print("<td>".$res["user"]."</td>");
														print("<td>".$res["name"]."</td>");
															if($res["admlvl"]=="0") {
																print("<td>Administrator</td>");
															} elseif($res["admlvl"]=="1") {
																print("<td>Manager</td>");
															}
															elseif($res["admlvl"]=="2") {
																print("<td>Group manager</td>");
															}
														print("<td>".idtogrp($res["ugroup"])."</td>");
															if($res["enabled"]=="1") {
																print("<td><a id=\"admlnkenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"disableadmin('".$res["id"]."');\"><div id=\"admenabled-".$res["id"]."\"><span class=\"label label-success\">Enabled</span></div></a></td>");
															} else {
																print("<td><a id=\"admlnkenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"enableadmin('".$res["id"]."');\"><div id=\"admenabled-".$res["id"]."\"><span class=\"label label-important\">Disabled</span></div></a></td>");
															}
														print("<td>");
															print("<div class=\"btn-group pull-right\">");
																print("<button data-toggle=\"dropdown\" class=\"btn btn-small\">Actions <span class=\"caret\"></span></button>");
																print("<ul class=\"dropdown-menu\">");
																	if($res["enabled"]=="1") {
																		print("<li><a href=\"admins.php?action=edit&aid=".$res["id"]."\">Edit</a><a href=\"admins.php?action=chpass&aid=".$res["id"]."\">Change password</a><a id=\"aadmenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"disableadmin('".$res["id"]."');\">Disable</a><a href=\"javascript:void(0);\" onclick=\"getdeleteadmin('".$res["id"]."','".$res["user"]."');\">Delete</a></li>");
																	} else {
																		print("<li><a href=\"admins.php?action=edit&aid=".$res["id"]."\">Edit</a><a href=\"admins.php?action=chpass&aid=".$res["id"]."\">Change password</a><a id=\"aadmenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"enableadmin('".$res["id"]."');\">Enable</a><a href=\"javascript:void(0);\" onclick=\"getdeleteadmin('".$res["id"]."','".$res["user"]."');\">Delete</a></li>");
																	}
																print("</ul>");
															print("</div>");
														print("</td>");
													print("</tr>");
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
			require("includes/modal-newadmin.php");
			require("includes/modal-editadmin.php");
			require("includes/modal-deladmin.php");
			require("includes/modal-chpassadmin.php");
			require("includes/footer.php");
		?>
		<script src="js/jquery.js"></script>
		<script src="js/cmum.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/toastr.min.js"></script>
		<script src="js/ajaxcalls.js"></script>
		<script src="js/tablesorter.min.js"></script>
		<script src="js/modal.js"></script>
		<script language="javascript" type="text/javascript">
			$(".sortable").tablesorter();
			
			$('#modalNewAdmin').on('hidden', function () {
				cleanmodalNewAdmin();
			});
		</script>
	</body>
</html>
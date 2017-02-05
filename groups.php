<?php
require("functions/admincheck.php");
require("functions/cmum.php");

if(isset($_POST["value"]) && $_POST["value"]=="baddgrp") {
	$status=addgroup($_POST["name"],$_POST["comment"]);
		if($status=="0") {
			$notice="toastr.success('Group successfully created');";
		} else {
			$notice="toastr.error('Something went wrong, please try again')";
		}
}

if(isset($_GET["action"]) && stripslashes($_GET["action"])=="edit" && isset($_GET["gid"]) && $_GET["gid"]<>"") {
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	if(mysqli_connect_errno()) {
		errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
		exit;
	}
		$sql=$mysqli->query("SELECT id,name,comment FROM groups WHERE id='".$mysqli->real_escape_string($_GET["gid"])."'");
		$eg_res=$sql->fetch_array();
			$eg_id=$eg_res["id"];
			$eg_name=$eg_res["name"];
			$eg_comment=$eg_res["comment"];
	mysqli_close($mysqli);
	$notice="$('#modalEditGroup').modal('show');";
}
if(isset($_POST["value"]) && $_POST["value"]=="beditgrp") {
	$status=editgroup($_POST["gid"],$_POST["name"],$_POST["comment"]);
		if($status=="0") {
			$notice="toastr.success('Changes successfully saved');";
		} elseif($status=="1") {
			$eg_id=$_POST["gid"];
			$eg_name=$_POST["name"];
			$eg_comment=$_POST["comment"];
			$notice="toastr.error('You must enter a group name'); $('#modalEditGroup').modal('show');";
		} elseif($status=="2") {
			$eg_id=$_POST["gid"];
			$eg_name=$_POST["name"];
			$eg_comment=$_POST["comment"];
			$notice="toastr.error('Group already exists'); $('#modalEditGroup').modal('show');";
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
										print("<li><span class=\"label label-info pull-right\" id=\"numgroups\">".$counters[1]."</span><a href=\"groups.php\" class=\"active\"><i class=\"batch database\"></i><br>Groups</a></li>");
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
						<a href="#modalNewGroup" data-toggle="modal" class="btn pull-right">New Group</a>
						<h4 class="header">Groups</h4>
							<table class="table table-striped table-hover sortable <?php print($tblcond); ?>">
									<thead>
										<tr>
											<th style="cursor:ns-resize;">Name</th>
											<th style="cursor:ns-resize;">Users</th>
											<th style="cursor:ns-resize;">Comment</th>
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
												$sql=$mysqli->query("SELECT id,name,comment,enabled FROM groups ORDER BY name");
												while($res=$sql->fetch_array()) {
													print("<tr id=group-".$res["id"].">");
														print("<td>".$res["name"]."</td>");
														print("<td>".usersingroup($res["id"])."</td>");
														print("<td>".$res["comment"]."</td>");
															if($res["enabled"]=="1") {
																print("<td><a id=\"grplnkenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"disablegroup('".$res["id"]."');\"><div id=\"grpenabled-".$res["id"]."\"><span class=\"label label-success\">Enabled</span></div></a></td>");
															} else {
																print("<td><a id=\"grplnkenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"enablegroup('".$res["id"]."');\"><div id=\"grpenabled-".$res["id"]."\"><span class=\"label label-important\">Disabled</span></div></a></td>");
															}
														print("<td>");
															print("<div class=\"btn-group pull-right\">");
																print("<button data-toggle=\"dropdown\" class=\"btn btn-small\">Actions <span class=\"caret\"></span></button>");
																print("<ul class=\"dropdown-menu\">");
																	if($res["enabled"]=="1") {
																		print("<li><a href=\"groups.php?action=edit&gid=".$res["id"]."\">Edit</a><a id=\"agrpenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"disablegroup('".$res["id"]."');\">Disable</a><a href=\"javascript:void(0);\" onclick=\"getdeletegroup('".$res["id"]."','".$res["name"]."');\">Delete</a></li>");
																	} else {
																		print("<li><a href=\"groups.php?action=edit&gid=".$res["id"]."\">Edit</a><a id=\"agrpenabled-".$res["id"]."\" href=\"javascript:void(0);\" onclick=\"enablegroup('".$res["id"]."');\">Enable</a><a href=\"javascript:void(0);\" onclick=\"getdeletegroup('".$res["id"]."','".$res["name"]."');\">Delete</a></li>");
																	}
																print("</ul>");
															print("</div>");
														print("</td>");
													print("</tr>");
												}
												print("<tr>");
													print("<td><i>Ungrouped</i></td>");
													print("<td><i>".usersingroup("")."</i></td>");
													print("<td>&nbsp;</td>");
													print("<td>&nbsp;</td>");
													print("<td>&nbsp;</td>");
												print("</tr>");
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
			require("includes/modal-newgroup.php");
			require("includes/modal-editgroup.php");
			require("includes/modal-delgroup.php");
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
			
			$('#modalNewGroup').on('hidden', function () {
				cleanmodalNewGroup();
			});
		</script>
	</body>
</html>
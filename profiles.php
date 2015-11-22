<!DOCTYPE html>
<?php
require("functions/admincheck.php");
require("functions/cmum.php");

if(isset($_POST["value"]) && $_POST["value"]=="baddprf") {
	$status=addprofile($_POST["name"],$_POST["cspvalue"],$_POST["comment"]);
		if($status=="0") {
			$counters=explode(";",counter());
			$notice="toastr.success('Profile successfully created');";
		} elseif($status=="1") {
			$notice="toastr.error('You must enter a profile name'); $('#modalNewProfile').modal({ show: true });";
		} elseif($status=="2") {
			$notice="toastr.error('Profile already exists'); $('#modalNewProfile').modal({ show: true });";
		}
}

if(isset($_GET["action"]) && stripslashes($_GET["action"])=="edit" && isset($_GET["pid"]) && $_GET["pid"]<>"") {
	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	if(mysqli_connect_errno()) {
		errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
		exit;
	}
		$sql=$mysqli->query("SELECT id,name,cspvalue,comment FROM profiles WHERE id='".$mysqli->real_escape_string($_GET["pid"])."'");
		$ep_res=$sql->fetch_array();
			$ep_id=$ep_res["id"];
			$ep_name=$ep_res["name"];
			$ep_cspvalue=$ep_res["cspvalue"];
			$ep_comment=$ep_res["comment"];
	mysqli_close($mysqli);
	$notice="$('#modalEditProfile').modal({ show: true });";
}
if(isset($_POST["value"]) && $_POST["value"]=="beditprf") {
	$status=editprofile($_POST["pid"],$_POST["name"],$_POST["cspvalue"],$_POST["comment"]);
		if($status=="0") {
			$notice="toastr.success('Changes successfully saved');";
		} elseif($status=="1") {
			$ep_id=$_POST["pid"];
			$ep_name=$_POST["name"];
			$ep_cspvalue=$_POST["cspvalue"];
			$ep_comment=$_POST["comment"];
			$notice="toastr.error('You must enter a profile name'); $('#modalEditProfile').modal({ show: true });";
		} elseif($status=="2") {
			$ep_id=$_POST["pid"];
			$ep_name=$_POST["name"];
			$ep_cspvalue=$_POST["cspvalue"];
			$ep_comment=$_POST["comment"];
			$notice="toastr.error('Profile already exists'); $('#modalEditProfile').modal({ show: true });";
		}
}

$counters=explode(";",counter());

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
										print("<li><span class=\"label label-info pull-right\" id=\"numprofs\">".$counters[2]."</span><a href=\"profiles.php\" class=\"active\"><i class=\"batch tables\"></i><br>Profiles</a></li>");
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
						<a href="#modalNewProfile" data-toggle="modal" class="btn pull-right">New Profile</a>
						<h4 class="header">Profiles</h4>
							<table class="table table-striped table-hover sortable <?php print($tblcond); ?>">
									<thead>
										<tr>
											<th style="cursor:ns-resize;">Name</th>
											<th style="cursor:ns-resize;">CSP Value</th>
											<th style="cursor:ns-resize;">Comment</th>
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
												$sql=$mysqli->query("SELECT id,name,cspvalue,comment FROM profiles ORDER BY name");
												while($res=$sql->fetch_array()) {
													print("<tr id=profile-".$res["id"].">");
														print("<td>".$res["name"]."</td>");
														print("<td>".$res["cspvalue"]."</td>");
														print("<td>".$res["comment"]."</td>");
														print("<td>");
															print("<div class=\"btn-group pull-right\">");
																print("<button data-toggle=\"dropdown\" class=\"btn btn-small\">Actions <span class=\"caret\"></span></button>");
																print("<ul class=\"dropdown-menu\">");
																	print("<li><a href=\"profiles.php?action=edit&pid=".$res["id"]."\">Edit</a><a href=\"javascript:void(0);\" onclick=\"getdeleteprofile('".$res["id"]."','".$res["name"]."','".$res["cspvalue"]."');\">Delete</a></li>");
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
			require("includes/modal-newprofile.php");
			require("includes/modal-editprofile.php");
			require("includes/modal-delprofile.php");
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
			
			$('#modalNewProfile').on('hidden', function () {
				cleanmodalNewProfile();
			});
		</script>
	</body>
</html>
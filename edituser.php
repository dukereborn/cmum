<?php
require("functions/logincheck.php");
require("functions/cmum.php");

if(isset($_POST["uid"]) && $_POST["uid"]<>"") {
	if(!isset($_POST["profiles"])) {
		$profiles="";
	} else {
		$profiles=$_POST["profiles"];
	}
	$status=edituser($_POST["uid"],$_POST["user"],$_POST["password"],$_POST["displayname"],$_POST["email"],$_POST["ipmask"],$_POST["maxconn"],$_POST["ecmrate"],$_POST["customvalues"],$_POST["usrgroup"],$_POST["admin"],$_POST["enabled"],$_POST["mapexclude"],$_POST["debug"],$_POST["startdate"],$_POST["expiredate"],$profiles,$_POST["boxtype"],$_POST["macaddress"],$_POST["serialnumber"],$_POST["comment"]);
		if($status=="0") {
			exit(header("Location: /users.php?edit=1"));
		} elseif($status=="1") {
			$notice="toastr.error('You must enter a username and a password');";
		} elseif($status=="2") {
			$notice="toastr.error('Username already exists');";
		}
}

if(!isset($_GET["uid"]) || $_GET["uid"]=="") {
	exit(header("Location: /users.php"));
}

$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if(mysqli_connect_errno()) {
	errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
	exit;
}
	if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
		$grpsql=$mysqli->query("SELECT id,name FROM groups");	
	} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
		$grpsql=$mysqli->query("SELECT id,name FROM groups WHERE id='".$mysqli->real_escape_string($_SESSION[$secretkey."admgrp"])."'");
	} else {
		$grpsql="";
	}
	$usrsql=$mysqli->query("SELECT * FROM users WHERE id='".$mysqli->real_escape_string($_GET["uid"])."'");
		$usrres=$usrsql->fetch_array();
	$profsql=$mysqli->query("SELECT id,name FROM profiles ORDER BY name ASC");
	$defprofsql=$mysqli->query("SELECT id,name FROM profiles ORDER BY name ASC");
	$setsql=$mysqli->query("SELECT rndstring,rndstringlength,def_ipmask,def_profiles,def_maxconn,def_admin,def_enabled,def_mapexc,def_debug,def_custcspval,def_ecmrate FROM settings WHERE id='1'");
		$setres=$setsql->fetch_array();
mysqli_close($mysqli);

if($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>$usrres["usrgroup"]) {
	exit(header("Location: /users.php?error=1"));
}

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
		<link rel="stylesheet" href="css/datepicker.css">
		<link rel="stylesheet" href="css/toastr.min.css">
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="apple-touch-icon" href="favicon.png">
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
		<script language="javascript" type="text/javascript">
			function autouser(id) {
				var length='<?php print($setres["rndstringlength"]); ?>';
				chars="<?php print($setres["rndstring"]); ?>";
				pass="";
					for(x=0;x<length;x++) {
						i=Math.floor(Math.random() * 62);
						pass+=chars.charAt(i);
					}
				oFormObject=document.forms[0];    
				oFormObject.elements[id].value=pass;                           
			}
			
			function loaddef() {
				oFormObject = document.forms[0];
				oFormObject.elements['ipmask'].value="<?php print($setres["def_ipmask"]); ?>";
				oFormObject.elements['maxconn'].value="<?php print($setres["def_maxconn"]); ?>";
				oFormObject.elements['admin'].value="<?php print($setres["def_admin"]); ?>";
				oFormObject.elements['enabled'].value="<?php print($setres["def_enabled"]); ?>";
				oFormObject.elements['mapexclude'].value="<?php print($setres["def_mapexc"]); ?>";
				oFormObject.elements['debug'].value="<?php print($setres["def_debug"]); ?>";
				oFormObject.elements['ipmask'].value="<?php print($setres["def_ipmask"]); ?>";
				oFormObject.elements['customvalues'].value=rhtmlspecialchars('<?php print($setres["def_custcspval"]); ?>');
				oFormObject.elements['ecmrate'].value="<?php print($setres["def_ecmrate"]); ?>";
				<?php
					while($defprofres=$defprofsql->fetch_array()) {
						$defprof=unserialize($setres["def_profiles"]);
							if($defprof<>"") {
								$checked="false";
									foreach($defprof as $defprofid) {
										if($defprofid==$defprofres["id"]) {
											$checked="true";
										}
									}
							} else {
								$checked="false";
							}
						print("document.getElementById('".$defprofres["id"]."').checked=".$checked.";");
					}
				?>
			}
		</script>
	</head>
	<body onload="edituser.user.focus(); <?php if(isset($notice)) { print($notice); } ?> ">
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
						<div class="span12">
						<button class="btn pull-right" onclick="loaddef();">Load Defaults</button>
						<form name="edituser" id="edituser" action="edituser.php?uid=<?php print($usrres["id"]); ?>" method="post" class="form-horizontal">
							<input type="hidden" name="uid" value="<?php print($usrres["id"]); ?>">
							<input type="hidden" name="ruser" value="<?php print($usrres["user"]); ?>">
						<h4 class="header">User Info</h4>
							<div class="row">
								<div class="span5">
									<div class="control-group">
										<label class="control-label" for="user">Username</label>
										<div class="controls">
											<input type="text" name="user" id="user" autocapitalize="off" autocomplete="off" autocorrect="off" spellcheck="false" ondblclick="autouser('user');" value="<?php print($usrres["user"]); ?>" maxlength="30">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="password">Password</label>
										<div class="controls">
											<input type="text" name="password" id="password" autocapitalize="off" autocomplete="off" autocorrect="off" spellcheck="false" ondblclick="autouser('password');" value="<?php print($usrres["password"]); ?>" maxlength="30">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="displayname">Displayname</label>
										<div class="controls">
											<input type="text" name="displayname" id="displayname" value="<?php print($usrres["displayname"]); ?>" maxlength="50">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="email">Email</label>
										<div class="controls">
											<input type="text" name="email" id="email" value="<?php print($usrres["email"]); ?>" maxlength="254">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="ipmask">IP mask</label>
										<div class="controls">
											<input type="text" name="ipmask" id="ipmask" value="<?php print($usrres["ipmask"]); ?>" maxlength="15">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="maxconn">Max connections</label>
										<div class="controls">
											<input type="text" name="maxconn" id="maxconn" onkeypress="return onlynumbers(event);" value="<?php print($usrres["maxconn"]); ?>" maxlength="4">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="ecmrate">Ecm rate</label>
										<div class="controls">
											<input type="text" name="ecmrate" id="ecmrate" value="<?php print($usrres["ecmrate"]); ?>" maxlength="4">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="customvalues">Custom CSP values</label>
										<div class="controls">
											<input type="text" name="customvalues" id="customvalues" value="<?php print(htmlspecialchars($usrres["customvalues"])); ?>" maxlength="255">
										</div>
									</div>
								</div>
								<div class="span5">
									<div class="control-group">
										<label class="control-label" for="usrgroup">Group</label>
										<div class="controls">
											<select name="usrgroup" id="usrgroup">
												<?php
													if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
														print("<option value=\"\"></option>");
													}
													while($grpres=$grpsql->fetch_array()) {
														if($grpres["id"]==$usrres["usrgroup"]) {
															print("<option value=\"".$grpres["id"]."\" selected>".$grpres["name"]."</option>");
														} else {
															print("<option value=\"".$grpres["id"]."\">".$grpres["name"]."</option>");
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="control-group">
											<label class="control-label" for="admin">Admin</label>
											<div class="controls">
												<select name="admin" id="admin">
													<option value="" <?php if($usrres["admin"]=="") { print("selected"); } ?>></option>
													<option value="1" <?php if($usrres["admin"]=="1") { print("selected"); } ?>>True</option>
													<option value="0" <?php if($usrres["admin"]=="0") { print("selected"); } ?>>False</option>
												</select>
											</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="enabled">Enabled</label>
										<div class="controls">
											<select name="enabled" id="enabled">
												<option value="" <?php if($usrres["enabled"]=="") { print("selected"); } ?>></option>
												<option value="1" <?php if($usrres["enabled"]=="1") { print("selected"); } ?>>True</option>
												<option value="0" <?php if($usrres["enabled"]=="0") { print("selected"); } ?>>False</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="mapexclude">Map exclude</label>
										<div class="controls">
											<select name="mapexclude" id="mapexclude">
												<option value="" <?php if($usrres["mapexclude"]=="") { print("selected"); } ?>></option>
												<option value="1" <?php if($usrres["mapexclude"]=="1") { print("selected"); } ?>>True</option>
												<option value="0" <?php if($usrres["mapexclude"]=="0") { print("selected"); } ?>>False</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="debug">Debug</label>
										<div class="controls">
											<select name="debug" id="debug">
												<option value="" <?php if($usrres["debug"]=="") { print("selected"); } ?>></option>
												<option value="1" <?php if($usrres["debug"]=="1") { print("selected"); } ?>>True</option>
												<option value="0" <?php if($usrres["debug"]=="0") { print("selected"); } ?>>False</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="startdate">Start date</label>
										<div class="controls">
											<input type="text" name="startdate" id="startdate" data-date-format="yyyy-mm-dd" value="<?php if(!is_null($usrres["startdate"])) { print($usrres["startdate"]); } ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="expiredate">Expire date</label>
										<div class="controls">
											<input type="text" name="expiredate" id="expiredate" data-date-format="yyyy-mm-dd" value="<?php if(!is_null($usrres["expiredate"])) { print($usrres["expiredate"]); } ?>">
										</div>
									</div>
								</div>
							</div>
						<h4 class="header">Profiles</h4>
							<div class="row">
								<div class="span8">
									<div class="control-group">
										<label class="control-label" for="profiles">Profile(s)</label>
											<div class="controls">
												<table class="table table-dash" width="100%">
													<?php
														$numprof=$profsql->num_rows;
														$row=0;
															while($profres=$profsql->fetch_array()) {
																$eprof=unserialize($usrres["profiles"]);
																	if($eprof<>"") {
																		$checked="";
																			foreach($eprof as $eprintprof) {
																				if($eprintprof==$profres["id"]) {
																					$checked="checked=\"checked\"";
																				}
																			}
																	} else {
																		$checked="";
																	}
																	if($row==0) {
																		print("<tr>");
																		print("<td width=\"35%\"><label class=\"checkbox\"><input type=\"checkbox\" name=\"profiles[]\" value=\"".$profres["id"]."\" id=\"".$profres["id"]."\" ".$checked."> ".$profres["name"]."</label></td>");
																	}
																	if($row==1) {
																		print("<td width=\"35%\"><label class=\"checkbox\"><input type=\"checkbox\" name=\"profiles[]\" value=\"".$profres["id"]."\" id=\"".$profres["id"]."\" ".$checked."> ".$profres["name"]."</label></td>");
																	}
																	if($row==2) {
																		print("<td width=\"30%\"><label class=\"checkbox\"><input type=\"checkbox\" name=\"profiles[]\" value=\"".$profres["id"]."\" id=\"".$profres["id"]."\" ".$checked."> ".$profres["name"]."</label></td>");
																		print("</tr>");
																	}
																$row++;
																	if($row==3) {
																		$row=0;
																	}
																}
													?>
												</table>
												<?php
													if($numprof==0) {
														print("No profiles added!");
													} else {
														print("<label class=\"checkbox\"><input type=\"checkbox\" name=\"allprof\" onclick=\"checkedallprof('edituser')\"> All profiles</all>");
													}
												?>
												</div>
										</div>
								</div>
								<div class="span5">
									&nbsp;
								</div>
							</div>
						<h4 class="header">Extra Info</h4>
							<div class="row">
								<div class="span5">
									<div class="control-group">
										<label class="control-label" for="boxtype">Boxtype</label>
										<div class="controls">
											<input type="text" name="boxtype" id="boxtype" value="<?php print($usrres["boxtype"]); ?>" maxlength="30">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="macaddress">MAC address</label>
										<div class="controls">
											<input type="text" name="macaddress" id="macaddress" value="<?php print($usrres["macaddress"]); ?>" maxlength="23">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="serialnumber">Serialnumber</label>
										<div class="controls">
											<input type="text" name="serialnumber" id="serialnumber" value="<?php print($usrres["serialnumber"]); ?>" maxlength="128">
										</div>
									</div>
								</div>
								<div class="span5">
									<div class="control-group">
										<label class="control-label" for="comment">Comment</label>
										<div class="controls">
											<textarea name="comment" id="comment" rows="4" style="resize: none;" maxlength="255"><?php print($usrres["comment"]); ?></textarea>
										</div>
									</div>
								</div>
							</div>
						<h4 class="header">Edit Info</h4>
							<div class="row">
								<div class="span5">
									<div class="control-group">
										<div class="controls">
											Added: <?php if(!is_null($usrres["added"])) { print($usrres["added"]); } ?>
												<br>
											Added by: <?php if($usrres["addedby"]<>"") { print(idtoadmin($usrres["addedby"])); } ?>
										</div>
									</div>
								</div>
								<div class="span5">
									<div class="control-group">
										<div class="controls">
											Last changed: <?php if(!is_null($usrres["changed"])) { print($usrres["changed"]); } ?>
												<br>
											Last changed by: <?php if($usrres["changedby"]<>"") { print(idtoadmin($usrres["changedby"])); } ?>
										</div>
									</div>
								</div>
							</div>
						</form>
							<button class="btn" name="bedit" value="Save" onclick="checkeditusername();">Save</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			require("includes/modal-logout.php");
			require("includes/footer.php");
		?>
		<script src="js/jquery.js"></script>
		<script src="js/cmum.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/datepicker.js"></script>
		<script src="js/toastr.min.js"></script>
		<script src="js/ajaxcalls.js"></script>
		<script>
			$('#startdate').datepicker({
				format: 'yyyy-mm-dd',
				weekStart: 1
			});
			$('#expiredate').datepicker({
				format: 'yyyy-mm-dd',
				weekStart: 1
			});
		</script>
	</body>
</html>
<?php
require("functions/logincheck.php");
require("functions/cmum.php");

if(isset($_POST["user"]) && $_POST["user"]<>"") {
	if(!isset($_POST["profiles"])) {
		$profiles="";
	} else {
		$profiles=$_POST["profiles"];
	}
	$status=adduser($_POST["user"],$_POST["password"],$_POST["displayname"],$_POST["email"],$_POST["ipmask"],$_POST["maxconn"],$_POST["ecmrate"],$_POST["customvalues"],$_POST["usrgroup"],$_POST["admin"],$_POST["enabled"],$_POST["mapexclude"],$_POST["debug"],$_POST["startdate"],$_POST["expiredate"],$profiles,$_POST["boxtype"],$_POST["macaddress"],$_POST["serialnumber"],$_POST["comment"]);
		if($status=="0") {
			$notice="toastr.success('User successfully created');";
		} elseif($status=="1") {
			$notice="toastr.error('You must enter a username and a password');";
		} elseif($status=="2") {
			$notice="toastr.error('Username already exists');";
		}
}

$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
if(mysqli_connect_errno()) {
	errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
	exit;
}
	if($_SESSION[$secretkey."admlvl"]=="0" ||  $_SESSION[$secretkey."admlvl"]=="1") {
		$grpsql=$mysqli->query("SELECT id,name FROM groups ORDER BY name ASC");	
	} elseif($_SESSION[$secretkey."admlvl"]=="2" && $_SESSION[$secretkey."admgrp"]<>"0") {
		$grpsql=$mysqli->query("SELECT id,name FROM groups WHERE id='".$mysqli->real_escape_string($_SESSION[$secretkey."admgrp"])."' ORDER BY name ASC");
	} else {
		$grpsql="";
	}
	$profsql=$mysqli->query("SELECT id,name FROM profiles ORDER BY name ASC");
	$defprofsql=$mysqli->query("SELECT id,name FROM profiles ORDER BY name ASC");
	$setsql=$mysqli->query("SELECT rndstring,rndstringlength,def_autoload,def_ipmask,def_profiles,def_maxconn,def_admin,def_enabled,def_mapexc,def_debug,def_custcspval,def_ecmrate FROM settings WHERE id='1'");
		$setres=$setsql->fetch_array();
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
				toastr.success('Defaults loaded');
			}
		</script>
	</head>
	<body onload="<?php if(isset($notice)) { print($notice); } if($setres["def_autoload"]=="1") { print(" loaddef();"); }?> ">
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
						<form name="newuser" id="newuser" action="newuser.php" method="post" class="form-horizontal">
						<h4 class="header">User Info</h4>
							<div class="row">
								<div class="span5">
									<div class="control-group">
										<label class="control-label" for="user" ondblclick="autouser('user');">Username</label>
										<div class="controls">
											<input type="text" name="user" id="user" autocapitalize="off" autocomplete="off" autocorrect="off" spellcheck="false" ondblclick="autouser('user');" maxlength="30" autofocus>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="password" ondblclick="autouser('password');">Password</label>
										<div class="controls">
											<input type="text" name="password" id="password" autocapitalize="off" autocomplete="off" autocorrect="off" spellcheck="false" ondblclick="autouser('password');" maxlength="30">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="displayname">Displayname</label>
										<div class="controls">
											<input type="text" name="displayname" id="displayname" maxlength="50">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="email">Email</label>
										<div class="controls">
											<input type="text" name="email" id="email" maxlength="254">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="ipmask">IP mask</label>
										<div class="controls">
											<input type="text" name="ipmask" id="ipmask" maxlength="15">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="maxconn">Max connections</label>
										<div class="controls">
											<input type="text" name="maxconn" id="maxconn" onkeypress="return onlynumbers(event);" maxlength="4">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="ecmrate">Ecm rate</label>
										<div class="controls">
											<input type="text" name="ecmrate" id="ecmrate" maxlength="4">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="customvalues">Custom CSP values</label>
										<div class="controls">
											<input type="text" name="customvalues" id="customvalues" maxlength="255">
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
														print("<option value=\"".$grpres["id"]."\">".$grpres["name"]."</option>");
													}
												?>
											</select>
										</div>
									</div>
									<div class="control-group">
											<label class="control-label" for="admin">Admin</label>
											<div class="controls">
												<select name="admin" id="admin">
													<option value=""></option>
													<option value="1">True</option>
													<option value="0">False</option>
												</select>
											</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="enabled">Enabled</label>
										<div class="controls">
											<select name="enabled" id="enabled">
												<option value=""></option>
												<option value="1">True</option>
												<option value="0">False</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="mapexclude">Map exclude</label>
										<div class="controls">
											<select name="mapexclude" id="mapexclude">
												<option value=""></option>
												<option value="1">True</option>
												<option value="0">False</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="debug">Debug</label>
										<div class="controls">
											<select name="debug" id="debug">
												<option value=""></option>
												<option value="1">True</option>
												<option value="0">False</option>
											</select>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="startdate">Start date</label>
										<div class="controls">
											<input type="text" name="startdate" id="startdate" data-date-format="yyyy-mm-dd">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="expiredate">Expire date</label>
										<div class="controls">
											<input type="text" name="expiredate" id="expiredate" data-date-format="yyyy-mm-dd">
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
																if($row==0) {
																	print("<tr>");
																	print("<td width=\"35%\"><label class=\"checkbox\"><input type=\"checkbox\" name=\"profiles[]\" value=\"".$profres["id"]."\" id=\"".$profres["id"]."\"> ".$profres["name"]."</label></td>");
																}
																if($row==1) {
																	print("<td width=\"35%\"><label class=\"checkbox\"><input type=\"checkbox\" name=\"profiles[]\" value=\"".$profres["id"]."\" id=\"".$profres["id"]."\"> ".$profres["name"]."</label></td>");
																}
																if($row==2) {
																	print("<td width=\"30%\"><label class=\"checkbox\"><input type=\"checkbox\" name=\"profiles[]\" value=\"".$profres["id"]."\" id=\"".$profres["id"]."\"> ".$profres["name"]."</label></td>");
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
														print("<label class=\"checkbox\"><input type=\"checkbox\" name=\"allprof\" onclick=\"checkedallprof('newuser')\"> All profiles</all>");
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
											<input type="text" name="boxtype" id="boxtype" maxlength="30">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="macaddress">MAC address</label>
										<div class="controls">
											<input type="text" name="macaddress" id="macaddress" maxlength="23">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="serialnumber">Serialnumber</label>
										<div class="controls">
											<input type="text" name="serialnumber" id="serialnumber" maxlength="128">
										</div>
									</div>
								</div>
								<div class="span5">
									<div class="control-group">
										<label class="control-label" for="comment">Comment</label>
										<div class="controls">
											<textarea name="comment" id="comment" rows="4" style="resize: none;" maxlength="255"></textarea>
										</div>
									</div>
								</div>
							</div>
						</form>
							<button class="btn" name="badd" value="Add User" onclick="checkusername();">Add User</button>	
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
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
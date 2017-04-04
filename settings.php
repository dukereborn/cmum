<?php
require("functions/admincheck.php");
require("functions/cmum.php");

if(isset($_POST["bsave"]) && $_POST["bsave"]=="Save Changes") {
	if(!isset($_POST["def_profiles"])) {
		$def_profiles="";
	} else {
		$def_profiles=$_POST["def_profiles"];
	}
	$updnotice=updatesettings($_POST["servername"],$_POST["timeout"],$_POST["rndstring"],$_POST["rndstringlength"],$_POST["invalidcharcheck"],$_POST["loglogins"],$_POST["logactivity"],$_POST["cleanlogin"],$_POST["genxmlkey"],$_POST["genxmllogreq"],$_POST["genxmlusrgrp"],$_POST["genxmldateformat"],$_POST["genxmlintstrexp"],$_POST["def_autoload"],$_POST["def_ipmask"],$def_profiles,$_POST["def_maxconn"],$_POST["def_admin"],$_POST["def_enabled"],$_POST["def_mapexc"],$_POST["def_debug"],$_POST["def_custcspval"],$_POST["def_ecmrate"],$_POST["fetchcsp"],$_POST["cspsrv_ip"],$_POST["cspsrv_port"],$_POST["cspsrv_user"],$_POST["cspsrv_pass"],$_POST["cspsrv_protocol"],$_POST["comptables"],$_POST["extrausrtbl"],$_POST["notstartusrorder"],$_POST["expusrorder"],$_POST["soonexpusrorder"],$_POST["autoupdcheck"],$_POST["usrorderby"],$_POST["usrorder"],$_POST["email_host"],$_POST["email_port"],$_POST["email_secure"],$_POST["email_auth"],$_POST["email_authuser"],$_POST["email_authpass"],$_POST["email_fromname"],$_POST["email_fromaddr"]);
		if($updnotice=="0") {
			$notice="toastr.success('Settings saved successfully');";
		}
}

$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
	if(mysqli_connect_errno()) {
		errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
		exit;
	}
$sqls=$mysqli->query("SELECT * FROM settings WHERE id='1'");
$setres=$sqls->fetch_array();
	
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
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="apple-touch-icon" href="favicon.png">
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
		<script language="javascript" type="text/javascript">
			function autovalue(id) {
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
		</script>
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
										print("<li><a href=\"tools.php\"><i class=\"batch console\"></i><br>Tools</a></li>");
										print("<li><a href=\"settings.php\" class=\"active\"><i class=\"batch settings\"></i><br>Settings</a></li>");
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
						<form name="settings" action="settings.php" method="post" class="form-horizontal">
							<h4 class="header">General</h4>
								<div class="row">
									<div class="span5">
										<div class="control-group">
											<label class="control-label" for="servername">Servername</label>
												<div class="controls">
													<input type="text" name="servername" id="servername" value="<?php print($setres["servername"]); ?>" maxlength="25">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="timeout">Timeout (in seconds)</label>
												<div class="controls">
													<input type="text" name="timeout" id="timeout" value="<?php print($setres["timeout"]); ?>" maxlength="4">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="rndstring">Random String Chars</label>
												<div class="controls">
													<input type="text" name="rndstring" id="rndstring" value="<?php print($setres["rndstring"]); ?>" maxlength="75">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="rndstringlength">Random String Length</label>
												<div class="controls">
													<input type="text" name="rndstringlength" id="rndstringlength" value="<?php print($setres["rndstringlength"]); ?>" maxlength="2">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="invalidcharcheck">Invalid Character Check</label>
												<div class="controls">
													<select name="invalidcharcheck" id="invalidcharcheck">
														<option value="1" <?php if($setres["invalidcharcheck"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="0" <?php if($setres["invalidcharcheck"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<div class="controls">
												<input type="submit" name="bsave" value="Save Changes" class="btn">
											</div>
										</div>
									</div>
									<div class="span6">
										<p>
											&nbsp;
										</p>								
									</div>
								</div>
								<div class="row">
									<div class="span9">&nbsp;</div>
								</div>
							<h4 class="header">Dashboard</h4>
								<div class="row">
									<div class="span5">
										<div class="control-group">
											<label class="control-label" for="autoupdcheck">Auto Update Check</label>
												<div class="controls">
													<select name="autoupdcheck" id="autoupdcheck">
														<option value="1" <?php if($setres["autoupdcheck"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="0" <?php if($setres["autoupdcheck"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="notstartusrorder">Not Started User Order</label>
												<div class="controls">
													<select name="notstartusrorder" id="notstartusrorder">
														<option value="asc" <?php if($setres["notstartusrorder"]=="asc") { print("selected"); } ?>>Ascending</option>
														<option value="desc" <?php if($setres["notstartusrorder"]=="desc") { print("selected"); } ?>>Descending</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="expusrorder">Expired User Order</label>
												<div class="controls">
													<select name="expusrorder" id="expusrorder">
														<option value="asc" <?php if($setres["expusrorder"]=="asc") { print("selected"); } ?>>Ascending</option>
														<option value="desc" <?php if($setres["expusrorder"]=="desc") { print("selected"); } ?>>Descending</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="soonexpusrorder">Soon Expired User Order</label>
												<div class="controls">
													<select name="soonexpusrorder" id="soonexpusrorder">
														<option value="asc" <?php if($setres["soonexpusrorder"]=="asc") { print("selected"); } ?>>Ascending</option>
														<option value="desc" <?php if($setres["soonexpusrorder"]=="desc") { print("selected"); } ?>>Descending</option>
													</select>
												</div>
										</div>										
										<div class="control-group">
											<div class="controls">
												<input type="submit" name="bsave" value="Save Changes" class="btn">
											</div>
										</div>
									</div>
									<div class="span6">
										<p>
											&nbsp;
										</p>								
									</div>
								</div>
								<div class="row">
									<div class="span9">&nbsp;</div>
								</div>
							<h4 class="header">Layout</h4>
								<div class="row">
									<div class="span5">
										<div class="control-group">
											<label class="control-label" for="comptables">Compact Tables</label>
												<div class="controls">
													<select name="comptables" id="comptables">
														<option value="1" <?php if($setres["comptables"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="0" <?php if($setres["comptables"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="extrausrtbl">Extra User Table</label>
												<div class="controls">
													<select name="extrausrtbl" id="extrausrtbl">
														<option value="0" <?php if($setres["extrausrtbl"]=="0") { print("selected"); } ?>>None</option>
														<option value="1" <?php if($setres["extrausrtbl"]=="1") { print("selected"); } ?>>Password</option>
														<option value="2" <?php if($setres["extrausrtbl"]=="2") { print("selected"); } ?>>Start date</option>
														<option value="3" <?php if($setres["extrausrtbl"]=="3") { print("selected"); } ?>>Expire date</option>
														<option value="4" <?php if($setres["extrausrtbl"]=="4") { print("selected"); } ?>>Added by</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="usrorderby">User Order By</label>
												<div class="controls">
													<select name="usrorderby" id="usrorderby">
														<option value="user" <?php if($setres["usrorderby"]=="user") { print("selected"); } ?>>Username</option>
														<option value="password" <?php if($setres["usrorderby"]=="password") { print("selected"); } ?>>Password</option>
														<option value="displayname" <?php if($setres["usrorderby"]=="displayname") { print("selected"); } ?>>Displayname</option>
														<option value="usrgroup" <?php if($setres["usrorderby"]=="usrgroup") { print("selected"); } ?>>Group</option>
														<option value="admin" <?php if($setres["usrorderby"]=="admin") { print("selected"); } ?>>Admin</option>
														<option value="enabled" <?php if($setres["usrorderby"]=="enabled") { print("selected"); } ?>>Enabled</option>
														<option value="startdate" <?php if($setres["usrorderby"]=="startdate") { print("selected"); } ?>>Start date</option>
														<option value="expiredate" <?php if($setres["usrorderby"]=="expiredate") { print("selected"); } ?>>Expire date</option>
														<option value="addedby" <?php if($setres["usrorderby"]=="addedby") { print("selected"); } ?>>Added by</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="usrorder">User Order</label>
												<div class="controls">
													<select name="usrorder" id="usrorder">
														<option value="asc" <?php if($setres["usrorder"]=="asc") { print("selected"); } ?>>Ascending</option>
														<option value="desc" <?php if($setres["usrorder"]=="desc") { print("selected"); } ?>>Descending</option>
													</select>
												</div>
										</div>							
										<div class="control-group">
											<div class="controls">
												<input type="submit" name="bsave" value="Save Changes" class="btn">
											</div>
										</div>
									</div>
									<div class="span6">
										<p>
											&nbsp;
										</p>								
									</div>
								</div>
								<div class="row">
									<div class="span9">&nbsp;</div>
								</div>
							<h4 class="header">Security</h4>
								<div class="row">
									<div class="span5">
										<div class="control-group">
											<label class="control-label" for="loglogins">Log Logins</label>
												<div class="controls">
													<select name="loglogins" id="loglogins">
														<option value="1" <?php if($setres["loglogins"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="2" <?php if($setres["loglogins"]=="2") { print("selected"); } ?>>Only failed</option>
														<option value="0" <?php if($setres["loglogins"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="logactivity">Log Manager Activity</label>
												<div class="controls">
													<select name="logactivity" id="logactivity">
														<option value="1" <?php if($setres["logactivity"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="0" <?php if($setres["logactivity"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="cleanlogin">Clean Loginpage</label>
												<div class="controls">
													<select name="cleanlogin" id="cleanlogin">
														<option value="1" <?php if($setres["cleanlogin"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="0" <?php if($setres["cleanlogin"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<div class="controls">
												<input type="submit" name="bsave" value="Save Changes" class="btn">
											</div>
										</div>
									</div>
									<div class="span6">
										<p>
											&nbsp;
										</p>
									</div>
								</div>
								<div class="row">
									<div class="span9">&nbsp;</div>
								</div>
							<h4 class="header">Genxml</h4>
								<div class="row">
									<div class="span5">
										<div class="control-group">
											<label class="control-label" for="genxmlkey" ondblclick="autovalue('genxmlkey');">Genxml Key</label>
												<div class="controls">
													<input type="text" name="genxmlkey" id="genxmlkey" value="<?php print($setres["genxmlkey"]); ?>" maxlength="50" ondblclick="autovalue('genxmlkey');">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="genxmllogreq">Log Requests</label>
												<div class="controls">
													<select name="genxmllogreq" id="genxmllogreq">
														<option value="1" <?php if($setres["genxmllogreq"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="2" <?php if($setres["genxmllogreq"]=="2") { print("selected"); } ?>>Only failed</option>
														<option value="0" <?php if($setres["genxmllogreq"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="genxmlusrgrp">Include Usergroups</label>
												<div class="controls">
													<select name="genxmlusrgrp" id="genxmlusrgrp">
														<option value="1" <?php if($setres["genxmlusrgrp"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="0" <?php if($setres["genxmlusrgrp"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="genxmldateformat">Start-/Expiredate Format</label>
												<div class="controls">
													<select name="genxmldateformat" id="genxmldateformat">
														<option value="d-m-Y" <?php if($setres["genxmldateformat"]=="d-m-Y") { print("selected"); } ?>>dd-mm-yyyy</option>
														<option value="d/m/Y" <?php if($setres["genxmldateformat"]=="d/m/Y") { print("selected"); } ?>>dd/mm/yyyy</option>
														<option value="Y-m-d" <?php if($setres["genxmldateformat"]=="Y-m-d") { print("selected"); } ?>>yyyy-mm-dd</option>
														<option value="Y/m/d" <?php if($setres["genxmldateformat"]=="Y/m/d") { print("selected"); } ?>>yyyy/mm/dd</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="genxmlintstrexp">Internal Start-/Expiredate</label>
												<div class="controls">
													<select name="genxmlintstrexp" id="genxmlintstrexp">
														<option value="1" <?php if($setres["genxmlintstrexp"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="0" <?php if($setres["genxmlintstrexp"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<div class="controls">
												<input type="submit" name="bsave" value="Save Changes" class="btn">
											</div>
										</div>
									</div>
									<div class="span6">
										<p>
											&nbsp;
										</p>
									</div>
								</div>
								<div class="row">
									<div class="span9">&nbsp;</div>
								</div>
							<h4 class="header">Default User Values</h4>
								<div class="row">
									<div class="span5">
										<div class="control-group">
											<label class="control-label" for="def_autoload">Auto Load Default Values</label>
												<div class="controls">
													<select name="def_autoload" id="def_autoload">
														<option value="1" <?php if($setres["def_autoload"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="0" <?php if($setres["def_autoload"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="def_ipmask">IP Mask</label>
												<div class="controls">
													<input type="text" name="def_ipmask" id="def_ipmask" value="<?php print($setres["def_ipmask"]); ?>" maxlength="15">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="def_profiles">Profile(s)</label>
												<div class="controls">
													<?php
														$sqldp=$mysqli->query("SELECT id,name FROM profiles ORDER BY name ASC");
														$numprof=$sqldp->num_rows;
															while($linedp=$sqldp->fetch_array()) {
																$dbprof=unserialize($setres["def_profiles"]);
																	if($dbprof<>"") {
																		$checked="";
																			foreach($dbprof as $printprof) {
																				if($printprof==$linedp["id"]) {
																					$checked="checked=\"checked\"";
																				}
																			}
																	} else {
																		$checked="";
																	}
																print("<label class=\"checkbox\"><input type=\"checkbox\" name=\"def_profiles[]\" value=".$linedp["id"]." ".$checked."> ".$linedp["name"]."<br></label>");
															}
														if($numprof==0) {
															print("No profiles added!");
														}
													?>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="def_maxconn">Max Connections</label>
												<div class="controls">
													<input type="text" name="def_maxconn" id="def_maxconn" onkeypress="return onlynumbers(event);" value="<?php print($setres["def_maxconn"]); ?>" maxlength="4">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="def_admin">Admin</label>
												<div class="controls">
													<select name="def_admin" id="def_admin">
														<option value=""></option>
														<option value="1" <?php if($setres["def_admin"]=="1") { print("selected"); } ?>>True</option>
														<option value="0" <?php if($setres["def_admin"]=="0") { print("selected"); } ?>>False</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="def_enabled">Enabled</label>
												<div class="controls">
													<select name="def_enabled" id="def_enabled">
														<option value=""></option>
														<option value="1" <?php if($setres["def_enabled"]=="1") { print("selected"); } ?>>True</option>
														<option value="0" <?php if($setres["def_enabled"]=="0") { print("selected"); } ?>>False</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="def_mapexc">Map Exclude</label>
												<div class="controls">
													<select name="def_mapexc" id="def_mapexc">
														<option value=""></option>
														<option value="1" <?php if($setres["def_mapexc"]=="1") { print("selected"); } ?>>True</option>
														<option value="0" <?php if($setres["def_mapexc"]=="0") { print("selected"); } ?>>False</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="def_debug">Debug</label>
												<div class="controls">
													<select name="def_debug" id="def_debug">
														<option value=""></option>
														<option value="1" <?php if($setres["def_debug"]=="1") { print("selected"); } ?>>True</option>
														<option value="0" <?php if($setres["def_debug"]=="0") { print("selected"); } ?>>False</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="def_custcspval">Custom CSP Values</label>
												<div class="controls">
													<input type="text" name="def_custcspval" id="def_custcspval" value="<?php print(htmlspecialchars(($setres["def_custcspval"]))); ?>" maxlength="255">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="def_ecmrate">ECM Rate</label>
												<div class="controls">
													<input type="text" name="def_ecmrate" id="def_ecmrate" value="<?php print($setres["def_ecmrate"]); ?>" maxlength="4">
												</div>
										</div>
										<div class="control-group">
											<div class="controls">
												<input type="submit" name="bsave" value="Save Changes" class="btn">
											</div>
										</div>
									</div>
									<div class="span6">
										<p>
											&nbsp;
										</p>
									</div>
								</div>
								<div class="row">
									<div class="span9">&nbsp;</div>
								</div>
							<h4 class="header">CSP Server</h4>
								<div class="row">
									<div class="span5">
										<div class="control-group">
											<label class="control-label" for="fetchcsp">Fetch Data from CSP</label>
												<div class="controls">
													<select name="fetchcsp" id="fetchcsp">
														<option value="1" <?php if($setres["fetchcsp"]=="1") { print("selected"); } ?>>Yes</option>
														<option value="0" <?php if($setres["fetchcsp"]=="0") { print("selected"); } ?>>No</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="cspsrv_ip">IP/Hostname</label>
												<div class="controls">
													<input type="text" name="cspsrv_ip" id="cspsrv_ip" value="<?php print($setres["cspsrv_ip"]); ?>" maxlength="255">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="cspsrv_port">Port</label>
												<div class="controls">
													<input type="text" name="cspsrv_port" id="cspsrv_port" value="<?php print($setres["cspsrv_port"]); ?>" maxlength="6">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="cspsrv_user">Username</label>
												<div class="controls">
													<input type="text" name="cspsrv_user" id="cspsrv_user" value="<?php print($setres["cspsrv_user"]); ?>" maxlength="30">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="cspsrv_pass">Password</label>
												<div class="controls">
													<input type="text" name="cspsrv_pass" id="cspsrv_pass" value="<?php print($setres["cspsrv_pass"]); ?>" maxlength="30">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="cspsrv_protocol">Protocol</label>
												<div class="controls">
													<select name="cspsrv_protocol" id="cspsrv_protocol">
														<option value="0" <?php if($setres["cspsrv_protocol"]=="0") { print("selected"); } ?>>HTTP</option>
														<option value="1" <?php if($setres["cspsrv_protocol"]=="1") { print("selected"); } ?>>HTTPS</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<div class="controls">
												<input type="submit" name="bsave" value="Save Changes" class="btn">
											</div>
										</div>
									</div>
									<div class="span6">
										<p>
											&nbsp;
										</p>
									</div>
								</div>
								<div class="row">
									<div class="span9">&nbsp;</div>
								</div>
							<h4 class="header">Email SMTP Server</h4>
								<div class="row">
									<div class="span5">
										<div class="control-group">
											<label class="control-label" for="email_host">SMTP Host</label>
												<div class="controls">
													<input type="text" name="email_host" id="email_host" value="<?php print($setres["email_host"]); ?>" maxlength="255">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="email_port">SMTP Port</label>
												<div class="controls">
													<input type="text" name="email_port" id="email_port" value="<?php print($setres["email_port"]); ?>" maxlength="6">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="email_secure">Encryption</label>
												<div class="controls">
													<select name="email_secure" id="email_secure">
														<option value="0" <?php if($setres["email_secure"]=="0") { print("selected"); } ?>>None</option>
														<option value="1" <?php if($setres["email_secure"]=="1") { print("selected"); } ?>>SSL</option>
														<option value="2" <?php if($setres["email_secure"]=="2") { print("selected"); } ?>>TLS</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="email_auth">Authentication</label>
												<div class="controls">
													<select name="email_auth" id="email_auth">
														<option value="0" <?php if($setres["email_auth"]=="0") { print("selected"); } ?>>No</option>
														<option value="1" <?php if($setres["email_auth"]=="1") { print("selected"); } ?>>Yes</option>
													</select>
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="email_authuser">Username</label>
												<div class="controls">
													<input type="text" name="email_authuser" id="email_authuser" value="<?php print($setres["email_authuser"]); ?>" maxlength="254">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="email_authpass">Password</label>
												<div class="controls">
													<input type="text" name="email_authpass" id="email_authpass" value="<?php print($setres["email_authpass"]); ?>" maxlength="50">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="email_fromname">From Name</label>
												<div class="controls">
													<input type="text" name="email_fromname" id="email_fromname" value="<?php print($setres["email_fromname"]); ?>" maxlength="50">
												</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="email_fromaddr">From Email</label>
												<div class="controls">
													<input type="text" name="email_fromaddr" id="email_fromaddr" value="<?php print($setres["email_fromaddr"]); ?>" maxlength="254">
												</div>
										</div>
										<div class="control-group">
											<div class="controls">
												<input type="submit" name="bsave" value="Save Changes" class="btn">
											</div>
										</div>
									</div>
									<div class="span6">
										<p>
											&nbsp;
										</p>
									</div>
								</div>
								<div class="row">
									<div class="span9">&nbsp;</div>
								</div>
						</form>						
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
			require("includes/modal-logout.php");
			require("includes/footer.php");
			mysqli_close($mysqli);
		?>
		<script src="js/jquery.js"></script>
		<script src="js/cmum.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/toastr.min.js"></script>
	</body>
</html>
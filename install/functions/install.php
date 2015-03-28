<?php
//
// install functions
//
function checkmysql($sqlhost,$sqluser,$sqlpass) {
	$mysqli=new mysqli($sqlhost,$sqluser,$sqlpass);
		if(mysqli_connect_errno()) {
			$status="0";
		} else {
			$status="1";
		}
	mysqli_close($mysqli);
return($status);
}

function checktables($sqlhost,$sqluser,$sqlpass,$sqlname) {
	$mysqli=new mysqli($sqlhost,$sqluser,$sqlpass);
		if($mysqli->select_db($sqlname)) {
			$tbl_admins=$mysqli->query("SHOW TABLES LIKE 'admins'");
			$tbl_groups=$mysqli->query("SHOW TABLES LIKE 'groups'");
			$tbl_logactivity=$mysqli->query("SHOW TABLES LIKE 'log_activity'");
			$tbl_loggenxmlreq=$mysqli->query("SHOW TABLES LIKE 'log_genxmlreq'");
			$tbl_loglogin=$mysqli->query("SHOW TABLES LIKE 'log_login'");
			$tbl_profiles=$mysqli->query("SHOW TABLES LIKE 'profiles'");
			$tbl_settings=$mysqli->query("SHOW TABLES LIKE 'settings'");
			$tbl_users=$mysqli->query("SHOW TABLES LIKE 'users'");
				if($tbl_admins->num_rows<>0 || $tbl_groups->num_rows<>0 || $tbl_logactivity->num_rows<>0 || $tbl_loggenxmlreq->num_rows<>0 || $tbl_loglogin->num_rows<>0 || $tbl_profiles->num_rows<>0 || $tbl_settings->num_rows<>0 || $tbl_users->num_rows<>0) {
					$status="0";
				} else {
					$status="1";
				}
		} else {
			$status="1";
		}
	mysqli_close($mysqli);
return($status);
}

function downloadconfig($sqlhost,$sqlname,$sqluser,$sqlpass,$charset,$timezone,$seckey) {
	$config="<?php\n\$dbhost=\"".$sqlhost."\";\n\$dbname=\"".$sqlname."\";\n\$dbuser=\"".$sqluser."\";\n\$dbpass=\"".$sqlpass."\";\n\$charset=\"".$charset."\";\n\$secretkey=\"".$seckey."\";\ndate_default_timezone_set(\"".$timezone."\");\n?>";
	ob_end_clean();
		header('Content-type: text/plain; charset='.$charset);
		header('Content-Disposition: attachment; filename="config.php"');
		echo $config;
	exit();
}

function installcmumdb($sqlhost,$sqlname,$sqluser,$sqlpass,$charset,$admin_name,$admin_pass,$seckey) {
	$mysqli=new mysqli($sqlhost,$sqluser,$sqlpass);
		if(mysqli_connect_errno()) {
			$status="0";
		} else {
			$fh=fopen("../includes/cmumdb.sql","r");
				$data=fread($fh,filesize("../includes/cmumdb.sql"));
				$charset=str_replace("-","",$charset);
				$data=str_replace("%cmumdbname%",$sqlname,$data);
				$data=str_replace("%charset%",$charset,$data);
				$data=str_replace("%aname%",$admin_name,$data);
				$data=str_replace("%apass%",hash("sha256",$admin_pass.$seckey),$data);
			fclose($fh);
			$datalines=preg_split("/\r\n|[\r\n]/",$data);
				foreach($datalines as $i => $value) {
					$mysqli->query($datalines[$i]);	
				}
			$status="1";
		}
	mysqli_close($mysqli);
return($status);
}

//
// install ajax helpers 
//
if(isset($_POST["function"]) && $_POST["function"]=="dbcheck" && $_POST["host"]<>"" && $_POST["user"]<>"" && $_POST["pass"]<>"") {	
	$status=checkmysql($_POST["host"],$_POST["user"],$_POST["pass"]);
echo $status;
}

if(isset($_POST["function"]) && $_POST["function"]=="tblcheck" && $_POST["host"]<>"" && $_POST["user"]<>"" && $_POST["pass"]<>"" && $_POST["name"]<>"") {	
	$status=checktables($_POST["host"],$_POST["user"],$_POST["pass"],$_POST["name"]);
echo $status;
}

if(isset($_POST["function"]) && $_POST["function"]=="dbinstall" && $_POST["host"]<>"" && $_POST["name"]<>""  && $_POST["user"]<>"" && $_POST["pass"]<>"" && $_POST["charset"]<>"" && $_POST["aname"]<>"" && $_POST["apass"]<>"" && $_POST["skey"]<>"") {	
	$status=installcmumdb($_POST["host"],$_POST["name"],$_POST["user"],$_POST["pass"],$_POST["charset"],$_POST["aname"],$_POST["apass"],$_POST["skey"]);
echo $status;
}
?>
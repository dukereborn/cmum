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

if(isset($_POST["function"]) && $_POST["function"]=="dbinstall" && $_POST["host"]<>"" && $_POST["name"]<>""  && $_POST["user"]<>"" && $_POST["pass"]<>"" && $_POST["charset"]<>"" && $_POST["aname"]<>"" && $_POST["apass"]<>"" && $_POST["skey"]<>"") {	
	$status=installcmumdb($_POST["host"],$_POST["name"],$_POST["user"],$_POST["pass"],$_POST["charset"],$_POST["aname"],$_POST["apass"],$_POST["skey"]);
echo $status;
}
?>
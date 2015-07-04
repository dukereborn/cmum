<?php
function getversion($sqlhost,$sqluser,$sqlpass,$sqlname) {
	$mysqli=new mysqli($sqlhost,$sqluser,$sqlpass,$sqlname);
		$versql=$mysqli->query("SELECT cmumversion FROM settings WHERE id='1'");
		$verres=$versql->fetch_array();
	mysqli_close($mysqli);
return($verres["cmumversion"]);
}

function upgradecmumdb($sqlhost,$sqluser,$sqlpass,$sqlname,$cmumversion,$charset) {
	$mysqli=new mysqli($sqlhost,$sqluser,$sqlpass,$sqlname);
		if(mysqli_connect_errno()) {
			$status="0";
		} else {
			if($cmumversion=="3.0.0") {
				$fh=fopen("includes/300to310.sql","r");
					$data=fread($fh,filesize("includes/300to310.sql"));
					$charset=str_replace("-","",$charset);
					$data=str_replace("%charset%",$charset,$data);
				fclose($fh);
				$datalines=preg_split("/\r\n|[\r\n]/",$data);
					foreach($datalines as $i => $value) {
						$mysqli->query($datalines[$i]);	
					}
				$fh=fopen("includes/310to311.sql","r");
					$data=fread($fh,filesize("includes/310to311.sql"));
					$charset=str_replace("-","",$charset);
					$data=str_replace("%charset%",$charset,$data);
				fclose($fh);
				$datalines=preg_split("/\r\n|[\r\n]/",$data);
					foreach($datalines as $i => $value) {
						$mysqli->query($datalines[$i]);	
					}
				$fh=fopen("includes/311to320.sql","r");
					$data=fread($fh,filesize("includes/311to320.sql"));
					$charset=str_replace("-","",$charset);
					$data=str_replace("%charset%",$charset,$data);
				fclose($fh);
				$datalines=preg_split("/\r\n|[\r\n]/",$data);
					foreach($datalines as $i => $value) {
						$mysqli->query($datalines[$i]);	
					}
				$fh=fopen("includes/320to330.sql","r");
					$data=fread($fh,filesize("includes/311to320.sql"));
					$charset=str_replace("-","",$charset);
					$data=str_replace("%charset%",$charset,$data);
				fclose($fh);
				$datalines=preg_split("/\r\n|[\r\n]/",$data);
					foreach($datalines as $i => $value) {
						$mysqli->query($datalines[$i]);	
					}
				$status="1";
			}
			if($cmumversion=="3.1.0") {
				$fh=fopen("includes/310to311.sql","r");
					$data=fread($fh,filesize("includes/310to311.sql"));
					$charset=str_replace("-","",$charset);
					$data=str_replace("%charset%",$charset,$data);
				fclose($fh);
				$datalines=preg_split("/\r\n|[\r\n]/",$data);
					foreach($datalines as $i => $value) {
						$mysqli->query($datalines[$i]);	
					}
				$fh=fopen("includes/311to320.sql","r");
					$data=fread($fh,filesize("includes/311to320.sql"));
					$charset=str_replace("-","",$charset);
					$data=str_replace("%charset%",$charset,$data);
				fclose($fh);
				$datalines=preg_split("/\r\n|[\r\n]/",$data);
					foreach($datalines as $i => $value) {
						$mysqli->query($datalines[$i]);	
					}
				$fh=fopen("includes/320to330.sql","r");
					$data=fread($fh,filesize("includes/311to320.sql"));
					$charset=str_replace("-","",$charset);
					$data=str_replace("%charset%",$charset,$data);
				fclose($fh);
				$datalines=preg_split("/\r\n|[\r\n]/",$data);
					foreach($datalines as $i => $value) {
						$mysqli->query($datalines[$i]);	
					}
				$status="1";
			}
			if($cmumversion=="3.1.1") {
				$fh=fopen("includes/311to320.sql","r");
					$data=fread($fh,filesize("includes/311to320.sql"));
					$charset=str_replace("-","",$charset);
					$data=str_replace("%charset%",$charset,$data);
				fclose($fh);
				$datalines=preg_split("/\r\n|[\r\n]/",$data);
					foreach($datalines as $i => $value) {
						$mysqli->query($datalines[$i]);	
					}
				$fh=fopen("includes/320to330.sql","r");
					$data=fread($fh,filesize("includes/311to320.sql"));
					$charset=str_replace("-","",$charset);
					$data=str_replace("%charset%",$charset,$data);
				fclose($fh);
				$datalines=preg_split("/\r\n|[\r\n]/",$data);
					foreach($datalines as $i => $value) {
						$mysqli->query($datalines[$i]);	
					}
				$status="1";
			}
			if($cmumversion=="3.2.0") {
				$fh=fopen("includes/320to330.sql","r");
					$data=fread($fh,filesize("includes/311to320.sql"));
					$charset=str_replace("-","",$charset);
					$data=str_replace("%charset%",$charset,$data);
				fclose($fh);
				$datalines=preg_split("/\r\n|[\r\n]/",$data);
					foreach($datalines as $i => $value) {
						$mysqli->query($datalines[$i]);	
					}
				$status="1";
			}
		}
	mysqli_close($mysqli);
return($status);
}
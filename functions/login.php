<?php
function login($user,$pass) {
	if(file_exists("config.php")) {
		require("config.php");
	} else {
		require("../config.php");
	}
		$loginuser=stripslashes($user);
		$loginpass=stripslashes($pass);
			if(!isset($loginuser) || !isset($loginpass)) { 
				$status="3";
			} elseif(empty($loginuser) || empty($loginpass)) { 
				$status="3"; 
			} else {
				$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
					$loginuser=$mysqli->real_escape_string($user);
					$loginpass=$mysqli->real_escape_string($pass);
				$sql=$mysqli->query("SELECT timeout,loglogins,servername,fetchcsp FROM settings WHERE id='1'");
					$sline=$sql->fetch_array();
				$sql=$mysqli->query("SELECT id,enabled,admlvl,ugroup FROM admins WHERE user='".$loginuser."' AND pass='".hash("sha256",$loginpass.$secretkey)."'");
					$rowcheck=$sql->num_rows;
					$line=$sql->fetch_array();
				if($rowcheck==1) {
					if($line["enabled"]=="0") {
						if($sline["loglogins"]=="1" || $sline["loglogins"]=="2") {
							$mysqli->query("INSERT INTO log_login (status,ip,user,pass) VALUES ('2','".$_SERVER["REMOTE_ADDR"]."','".$loginuser."','')");
						}
						mysqli_close($mysqli);
						$status="2";
					} else {
						if($sline["loglogins"]=="1") {
							$mysqli->query("INSERT INTO log_login (status,ip,user,pass) VALUES ('0','".$_SERVER["REMOTE_ADDR"]."','".$loginuser."','')");
						}
						mysqli_close($mysqli);
							@session_start();
								session_regenerate_id(true);
								$_SESSION[$secretkey."sessid"]=session_id();
								$_SESSION[$secretkey."admin"]=$loginuser;
								$_SESSION[$secretkey."admid"]=$line["id"];
								$_SESSION[$secretkey."admlvl"]=$line["admlvl"];
								$_SESSION[$secretkey."admgrp"]=$line["ugroup"];
								$_SESSION[$secretkey."timeout"]=$sline["timeout"];
								$_SESSION[$secretkey."servername"]=$sline["servername"];
								$_SESSION[$secretkey."fetchcsp"]=$sline["fetchcsp"];
						$status="0";	
					}
				} else {
					if($sline["loglogins"]=="1" || $sline["loglogins"]=="2") {
						$mysqli->query("INSERT INTO log_login (status,ip,user,pass) VALUES ('1','".$_SERVER["REMOTE_ADDR"]."','".$loginuser."','".$loginpass."')");
					}
					mysqli_close($mysqli);
					$status="1";
				}
			}
return($status);
}

function logout() {
	require("functions/cmum.php");
		if(checksetting("fetchcsp")=="1") {
			if(checkcspconn()=="1") {
				cspupdateusers();
			}
		}
		@session_start();
			session_unset(); 
			session_destroy();
		header("Location:index.php?logout=1");
		exit;
}
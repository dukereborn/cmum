<?php
@session_start();
	require("config.php");
	require("includes/settings.php");
	require("functions/login.php");
		if($_SESSION[$secretkey."sessid"]<>session_id() || $_SESSION[$secretkey."admin"]=="" || $_SESSION[$secretkey."admid"]=="" || $_SESSION[$secretkey."admlvl"]=="") {
			session_unset ();
			session_destroy ();
			exit(header("Location: index.php?error=1"));
		}
		
		if(isset($_SESSION[$secretkey."sys_timeout"]) ) {
			$sesslife=time()-$_SESSION[$secretkey."sys_timeout"];
				if($sesslife>$_SESSION[$secretkey."timeout"]) {
					session_unset ();
					session_destroy ();
					exit(header("Location: index.php?error=3"));
				}
		}
		$_SESSION[$secretkey."sys_timeout"]=time();
		
		if(isset($_GET["dologout"]) && stripslashes($_GET["dologout"])=="1") {
			logout();
		}
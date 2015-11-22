<?php
@session_start();
	require("config.php");
	require("includes/settings.php");
	require("functions/login.php");
		if($_SESSION[$secretkey."sessid"]<>session_id() || $_SESSION[$secretkey."admin"]=="" || $_SESSION[$secretkey."admid"]=="" || $_SESSION[$secretkey."admlvl"]=="") {
			session_unset ();
			session_destroy ();
			header("Location:index.php?error=1");
			exit;
		}
		
		if(isset($_SESSION[$secretkey."sys_timeout"]) ) {
			$sesslife=time()-$_SESSION[$secretkey."sys_timeout"];
				if($sesslife>$_SESSION[$secretkey."timeout"]) {
					session_unset ();
					session_destroy ();
					header("Location:index.php?error=3");
					exit;
				}
		}
		$_SESSION[$secretkey."sys_timeout"]=time();
		
		// Logout function
		print("<div id=\"modalLogout\" class=\"modal hide fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">");
			print("<div class=\"modal-header\">");
				print("<h3 id=\"myModalLabel\">Logout</h3>");
			print("</div>");
			print("<div class=\"modal-body\">");
				print("Are you sure you want to log out?");
			print("</div>");
			print("<div class=\"modal-footer\">");
				print("<button class=\"btn\" data-dismiss=\"modal\" aria-hidden=\"true\">Cancel</button>");
				print("&nbsp;");
				print("<a class=\"btn btn-primary\" aria-hidden=\"true\" name=\"blogout\" value=\"logout\" href=\"?dologout=1\">Logout</a>");
			print("</div>");
		print("</div>");
		
		if(isset($_GET["dologout"]) && stripslashes($_GET["dologout"])=="1") {
			logout();
		}
<?php
	require("config.php");
	require("functions/cmum.php");
		if(isset($_GET["option"]) && $_GET["option"]<>"") {
			$option=stripslashes($_GET["option"]);
		} else {
			$option="";
		}
		if(isset($_GET["key"]) && $_GET["key"]<>"") {
			$xmldata=genxml($_GET["key"],$_SERVER["REMOTE_ADDR"],$option);
		} else {
			$xmldata=genxml("",$_SERVER["REMOTE_ADDR"],$option);
		}
		if($xmldata<>"") {
			header("Content-type: text/xml; charset=".$charset);
				print($xmldata);
			exit;
		} else {
			header('HTTP/1.0 404 Not Found');
				print("<h1>404 Not Found</h1>");
				print("The page that you have requested could not be found.");
			exit();
		}			
?>
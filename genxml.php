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
			header("Content-type: text/xml; charset=".$charset);
				print($xmldata);
?>
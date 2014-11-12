<h4 class="header">Check For Updates</h4>
	<form name="tools" action="tools.php?menu=9&tool=901" method="post" class="form">
		<div class="control-group">
			<div class="controls">
				<input type="submit" name="baction" value="Check now" class="btn">
			</div>
		</div>
			&nbsp;
		<div class="control-group">
			<div class="controls">
				<?php
					if(isset($_POST["baction"]) && $_POST["baction"]=="Check now") {
						$upd=cmumupdcheck(CMUM_VERSION);
						$upd=explode(";",$upd);
						if($upd[0]=="0") {
							$updnotice="<div class=\"alert alert-success\">CMUM ".CMUM_VERSION." is currently the newest version available</div>";
						} elseif($upd[0]=="1") {
							$updnotice="<div class=\"alert alert-info\">There is a new version of CMUM available<br><br>Installed version: ".CMUM_VERSION."<br>Latest version: ".$upd[1]."<br><br>Visit <a href=\"http://github.com/dukereborn/cmum/\" target=\"_blank\">http://github.com/dukereborn/cmum/</a> to download the latest version</div>";
						} elseif($upd[0]=="2") {
							$updnotice="<div class=\"alert alert-error\">Can't connect to the update server</div>";
						}
						print($updnotice);
					}	
				?>
			</div>
		</div>
	</form>
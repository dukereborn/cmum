<h4 class="header">Import CSP Profiles</h4>
	<form name="tools" action="tools.php?menu=2&tool=205" method="post" class="form">
		<?php
			$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
				$setsql=$mysqli->query("SELECT cspsrv_ip,cspsrv_port,cspsrv_user,cspsrv_pass,cspsrv_protocol FROM settings WHERE id='1'");
				$setdata=$setsql->fetch_array();
			mysqli_close($mysqli);
				$caprofiles="ca-profiles";
				$xmldata=simplexml_load_file(valuetohttpprotocol($setdata["cspsrv_protocol"])."://".$setdata["cspsrv_user"].":".$setdata["cspsrv_pass"]."@".$setdata["cspsrv_ip"].":".$setdata["cspsrv_port"]."/xmlHandler?command=ca-profiles");
		?>
		<div class="row">
			<div class="span3">
				<div class="control-group">
					<label class="control-label" for="profvalue">CSP Value</label>
					<?php
						$i=0;
					    foreach($xmldata->$caprofiles->profile as $profile) {
							if((string)$xmldata->$caprofiles->profile[$i]->attributes()->name<>"*") {
								print("<div class=\"controls\">");
									print("<input type=\"text\" name=\"profvalue[]\" id=\"profvalue\" value=\"".(string)$xmldata->$caprofiles->profile[$i]->attributes()->name."\" readonly>");
								print("</div>");
							}
							$i++;
						}
					?>
				</div>
				<div class="control-group">
					<div class="controls">
						<input type="submit" name="bimpprof" value="Import profiles" class="btn">
					</div>
				</div>
			</div>
			<div class="span4">
				<div class="control-group">
					<label class="control-label" for="profname">Name</label>
					<?php
						$i=0;
					    foreach($xmldata->$caprofiles->profile as $profile) {
							if((string)$xmldata->$caprofiles->profile[$i]->attributes()->name<>"*") {
								print("<div class=\"controls\">");
									print("<input type=\"text\" name=\"profname[]\" id=\"profname\" value=\"".(string)$xmldata->$caprofiles->profile[$i]->attributes()->name."\">");
								print("</div>");
							}
							$i++;
						}
					?>
				</div>
			</div>
		</div>
	</form>
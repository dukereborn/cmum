<h4 class="header">Genxml Request Log</h4>
	<form name="tools" action="tools.php?menu=4&tool=402" method="post" class="form">
		<div class="control-group form-inline">
			<label class="control-label" for="logfilter">Filter</label>
				<div class="controls form-inline">
					<select name="logfilter" id="logfilter" onchange="this.form.submit();">
						<option value="" selected></option>
						<option value="0" <?php if(isset($_POST["logfilter"]) && $_POST["logfilter"]=="0") { print("selected"); } ?>>Succeeded</option>
						<option value="1" <?php if(isset($_POST["logfilter"]) && $_POST["logfilter"]=="1") { print("selected"); } ?>>Failed</option>
					</select>
						&nbsp;&nbsp;
					<input type="submit" name="bclrgxlog" value="Clear log" class="btn">
				</div>
		</div>
		<div class="control-group">
			<table class="table table-striped table-hover sortable <?php print($tblcond); ?>">
				<thead>
					<tr>
						<th>Time</th>
						<th>IP Address</th>
						<th>Genxml Key</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
						if(mysqli_connect_errno()) {
							errorpage("MYSQL DATABASE ERROR",mysqli_connect_error(),$charset,CMUM_TITLE,$_SERVER["REQUEST_URI"],CMUM_VERSION,CMUM_BUILD,CMUM_MOD);
							exit;
						}
						if(isset($_POST["logfilter"]) && $_POST["logfilter"]<>"") {
							$sql=$mysqli->query("SELECT status,timestamp,ip,genxmlkey FROM log_genxmlreq WHERE status='".$_POST["logfilter"]."' ORDER BY id DESC");
						} else {
							$sql=$mysqli->query("SELECT status,timestamp,ip,genxmlkey FROM log_genxmlreq ORDER BY id DESC");
						}
							while($logdata=$sql->fetch_array()) {
								if($logdata["status"]=="0") {
									print("<tr class=\"success\">");
								} elseif($logdata["status"]=="1") {
									print("<tr class=\"error\">");
								} else {
									print("<tr>");
								}
									print("<td>".$logdata["timestamp"]."</td>");
									print("<td>".$logdata["ip"]."</td>");
									print("<td>".$logdata["genxmlkey"]."</td>");
									print("</tr>");
							}
						mysqli_close($mysqli);
					?>
				</tbody>
			</table>
		</div>
		<div class="control-group">
			<div class="controls">
			</div>
		</div>
	</form>
<h4 class="header">Manager Activity Log</h4>
	<form name="tools" action="tools.php?menu=4&tool=403" method="post" class="form">
		<div class="control-group form-inline">
			<label class="control-label" for="logfilter">Manager</label>
				<div class="controls form-inline">
					<select name="logfilter" id="logfilter" onchange="this.form.submit();">
						<option value="" selected></option>
						<?php
							$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
								$msql=$mysqli->query("SELECT id,user FROM admins WHERE admlvl<>0 ORDER BY user");
									while($mdata=$msql->fetch_array()) {
										if(isset($_POST["logfilter"]) && $_POST["logfilter"]==$mdata["id"]) {
											print("<option value=\"".$mdata["id"]."\" selected>".$mdata["user"]."</option>");
										} else {
											print("<option value=\"".$mdata["id"]."\">".$mdata["user"]."</option>");
										}	
									}
							mysqli_close($mysqli);
						?>
					</select>
						&nbsp;&nbsp;
					<input type="submit" name="bclractlog" value="Clear log" class="btn">
				</div>
		</div>
		<div class="control-group">
			<table class="table table-striped table-hover sortable <?php print($tblcond); ?>">
				<thead>
					<tr>
						<th>Time</th>
						<th>Activity</th>
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
							$sql=$mysqli->query("SELECT activity,adminid,userid,data,timestamp FROM log_activity WHERE adminid='".$_POST["logfilter"]."' ORDER BY id DESC");
						} else {
							$sql=$mysqli->query("SELECT activity,adminid,userid,data,timestamp FROM log_activity ORDER BY id DESC");
						}
							while($logdata=$sql->fetch_array()) {
								print("<tr>");
									print("<td>".$logdata["timestamp"]."</td>");
									if($logdata["activity"]=="1") {
										print("<td>".idtoadmin($logdata["adminid"])." added ".$logdata["data"]." (userid: ".$logdata["userid"].")</td>");
									}
									if($logdata["activity"]=="2") {
										print("<td>".idtoadmin($logdata["adminid"])." edited ".$logdata["data"]." (userid: ".$logdata["userid"].")</td>");

									}
									if($logdata["activity"]=="3") {
										print("<td>".idtoadmin($logdata["adminid"])." deleted ".$logdata["data"]." (userid: ".$logdata["userid"].")</td>");

									}
									if($logdata["activity"]=="4") {
										print("<td>".idtoadmin($logdata["adminid"])." enabled ".$logdata["data"]." (userid: ".$logdata["userid"].")</td>");

									}
									if($logdata["activity"]=="5") {
										print("<td><".idtoadmin($logdata["adminid"])." disabled ".$logdata["data"]." (userid: ".$logdata["userid"].")</td>");

									}
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
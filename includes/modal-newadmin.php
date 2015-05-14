<div id="modalNewAdmin" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">New Admin</h3>
	</div>
	<form name="newadmin" id="newadmin" action="admins.php" method="post">
		<div class="modal-body">
			<div class="row">
				<div class="span3">
					<input type="hidden" name="value" id="value" value="baddadm">
					<label>Username</label>
						<input type="text" name="user" id="user" value="<?php if(isset($_POST["user"]) && isset($status) && $status<>"0") { print($_POST["user"]); } ?>" autocomplete="off" onkeypress="submitNewAdmin(event);" maxlength="25">
					<label>Password</label>
						<input type="text" name="pass" id="pass" value="<?php if(isset($_POST["pass"]) && isset($status) && $status<>"0") { print($_POST["pass"]); } ?>" autocomplete="off" onkeypress="submitNewAdmin(event);">
					<label>Name</label>
						<input type="text" name="name" id="name" value="<?php if(isset($_POST["name"]) && isset($status) && $status<>"0") { print($_POST["name"]); } ?>" autocomplete="off" onkeypress="submitNewAdmin(event);" maxlength="40">
				</div>
				<div class="span2">
					<label>Type</label>
						<select name="admlvl" id="admlvl" onkeypress="submitNewAdmin(event);">
							<option value="0" <?php if(isset($_POST["admlvl"]) && $_POST["admlvl"]=="0" && isset($status) && $status<>"0") { print("selected"); } ?>>Administrator</option>
							<option value="1" <?php if(isset($_POST["admlvl"]) && $_POST["admlvl"]=="1" && isset($status) && $status<>"0") { print("selected"); } ?>>Manager</option>
							<option value="2" <?php if(isset($_POST["admlvl"]) && $_POST["admlvl"]=="2" && isset($status) && $status<>"0") { print("selected"); } ?>>Group manager</option>
						</select>
					<label>Group</label>
						<select name="ugroup" id="ugroup" onkeypress="submitNewAdmin(event);">
							<option value="0"></option>
								<?php
									$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
									$sql=$mysqli->query("SELECT id,name FROM groups ORDER BY name");
										while($res=$sql->fetch_array()) {
											if(isset($_POST["group"]) && $_POST["group"]==$res["id"] && isset($status) && $status<>"0") {
												print("<option value=\"".$res["id"]."\" selected>".$res["name"]."</option>");
											} else {
												print("<option value=\"".$res["id"]."\">".$res["name"]."</option>");
											}
										}
									mysqli_close($mysqli);
								?>
						</select>
				</div>
			</div>
		</div>
	</form>
		<div class="modal-footer">
			<button class="btn btn-success pull-right" name="baddadm" value="Add" onclick="checkadminname();">Add</button>
			<button class="btn" data-dismiss="modal" aria-hidden="true" onclick="cleanmodalNewAdmin();">Cancel</button>&nbsp;&nbsp;
		</div>
</div>
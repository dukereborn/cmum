<div id="modalEditAdmin" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Edit Admin</h3>
	</div>
	<form name="editadmin" id="editadmin" action="admins.php" method="post">
		<div class="modal-body">
			<div class="row">
				<div class="span3">
					<input type="hidden" name="value" id="value" value="beditadm">
					<input type="hidden" name="ruser" id="ruser" value="<?php if(isset($ea_user)) { print($ea_user); } ?>">
					<input type="hidden" name="aid" id="aid" value="<?php if(isset($ea_id)) { print($ea_id); } ?>">
					<label>Username</label>
						<input type="text" name="user" id="user" value="<?php if(isset($ea_user)) { print($ea_user); } ?>" autocomplete="off" onkeypress="submitEditAdmin(event);" maxlength="25">
					<label>Name</label>
						<input type="text" name="name" id="name" value="<?php if(isset($ea_name)) { print($ea_name); } ?>" autocomplete="off" onkeypress="submitEditAdmin(event);" maxlength="40">
				</div>
				<div class="span2">
					<label>Type</label>
						<select name="admlvl" id="admlvl" onkeypress="submitEditAdmin(event);">
							<option value="0" <?php if(isset($ea_admlvl)) { if($ea_admlvl=="0") { print("selected"); } } ?>>Administrator</option>
							<option value="1" <?php if(isset($ea_admlvl)) { if($ea_admlvl=="1") { print("selected"); } } ?>>Manager</option>
							<option value="2" <?php if(isset($ea_admlvl)) { if($ea_admlvl=="2") { print("selected"); } } ?>>Group manager</option>
						</select>
					<label>Group</label>
						<select name="ugroup" id="ugroup" onkeypress="submitEditAdmin(event);">
							<option value="0" <?php if(isset($ea_ugroup)) { if($ea_ugroup=="0") { print("selected"); } } ?>></option>
							 <?php
							 	$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
								$sql=$mysqli->query("SELECT id,name FROM groups ORDER BY name");
									while($res=$sql->fetch_array()) {
										if(isset($ea_ugroup) && $res["id"]==$ea_ugroup) {
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
			<button class="btn btn-success pull-right" name="beditadm" value="Save" onclick="checkeditadminname();">Save</button>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
		</div>
</div>
<div id="modalChpassAdmin" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Change Password</h3>
	</div>
	<form name="chpassadmin" id="chpassadmin" action="admins.php" method="post">
		<div class="modal-body">
			<div class="row">
				<div class="span3">
					<input type="hidden" name="value" id="value" value="bchpassadm">
					<input type="hidden" name="aid" id="aid" value="<?php if(isset($ea_id)) { print($ea_id); } ?>">
					<label>New password</label>
						<input type="text" name="pass1" id="pass1" value="<?php if(isset($ea_pass1)) { print($ea_pass1); } ?>" autocomplete="off" onkeypress="submitChpassAdmin(event);">
					<label>Retype new password</label>
						<input type="text" name="pass2" id="pass2" value="<?php if(isset($ea_pass2)) { print($ea_pass2); } ?>" autocomplete="off" onkeypress="submitChpassAdmin(event);">
				</div>
				<div class="span2">
					&nbsp;
				</div>
			</div>
		</div>
	</form>
		<div class="modal-footer">
			<button class="btn btn-success pull-right" name="bchpassadm" value="Save" onclick="checkchpassadminname();">Save</button>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
		</div>
</div>
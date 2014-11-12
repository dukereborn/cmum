<div id="modalEditProfile" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Edit Profile</h3>
	</div>
	<form name="editprofile" id="editprofile" action="profiles.php" method="post">
		<div class="modal-body">
			<div class="row">
				<div class="span3">
					<input type="hidden" name="value" id="value" value="beditprf">
					<input type="hidden" name="rname" id="rname" value="<?php if(isset($ep_name)) { print($ep_name); } ?>">
					<input type="hidden" name="rcspvalue" id="rcspvalue" value="<?php if(isset($ep_cspvalue)) { print($ep_cspvalue); } ?>">
					<input type="hidden" name="pid" id="pid" value="<?php if(isset($ep_id)) { print($ep_id); } ?>">
					<label>Name</label>
						<input type="text" name="name" id="name" value="<?php if(isset($ep_name)) { print($ep_name); } ?>" autocomplete="off" onkeypress="submitEditProfile(event);" maxlength="25">
					<label>CSP Value</label>
						<input type="text" name="cspvalue" id="cspvalue" value="<?php if(isset($ep_cspvalue)) { print($ep_cspvalue); } ?>" autocomplete="off" onkeypress="submitEditProfile(event);" maxlength="25">
					<label>Comment</label>
						<input type="text" name="comment" id="comment" value="<?php if(isset($ep_comment)) { print($ep_comment); } ?>" autocomplete="off" onkeypress="submitEditProfile(event);" maxlength="50">
				</div>
				<div class="span2">
					&nbsp;
				</div>
			</div>
		</div>
	</form>
		<div class="modal-footer">
			<button class="btn btn-success pull-right" name="beditprf" value="Save" onclick="checkeditprofilename();">Save</button>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
		</div>
</div>
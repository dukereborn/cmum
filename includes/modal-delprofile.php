<div id="modalDelProfile" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Delete Profile</h3>
	</div>
	<form name="delprofile" action="profiles.php" method="post" id="delprofile">
		<div class="modal-body">
			<input type="hidden" name="pid" id="pid" value="<?php if(isset($ep_id)) { print($ep_id); } ?>">
			Are your sure you want to delete profile <strong><?php if(isset($ep_name)) { print($ep_name); } ?> (<?php if(isset($ep_cspvalue)) { print($ep_cspvalue); } ?>)</strong>?
		</div>
		<div class="modal-footer">
			<input type="submit" class="btn btn-danger pull-right" aria-hidden="true" name="bdelprf" value="Delete">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
		</div>
	</form>
</div>
<div id="modalDelUser" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Delete User</h3>
	</div>
	<form name="deluser" id="deluser" action="users.php" method="post">
		<div class="modal-body">
			<input type="hidden" name="uid" id="uid" value="<?php if(isset($eu_id)) { print($eu_id); } ?>">
			Are your sure you want to delete user <strong><?php if(isset($eu_user)) { print($eu_user); } ?></strong>?
		</div>
		<div class="modal-footer">
			<input type="submit" class="btn btn-danger pull-right" aria-hidden="true" name="bdelusr" value="Delete">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
		</div>
	</form>
</div>
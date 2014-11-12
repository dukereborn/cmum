<div id="modalDelAdmin" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Delete Admin</h3>
	</div>
	<form name="deladmin" action="admins.php" method="post" id="deladmin">
		<div class="modal-body">
			<input type="hidden" name="uid" id="uid" value="<?php if(isset($ea_id)) { print($ea_id); } ?>">
			Are your sure you want to delete admin <strong><?php if(isset($ea_user)) { print($ea_user); } ?></strong>?
		</div>
		<div class="modal-footer">
			<input type="submit" class="btn btn-danger pull-right" aria-hidden="true" name="bdeladm" value="Delete">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
		</div>
	</form>
</div>
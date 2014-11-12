<div id="modalDelGroup" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Delete Group</h3>
	</div>
	<form name="delgroup" action="groups.php" method="post" id="delgroup">
		<div class="modal-body">
			<input type="hidden" name="gid" id="gid" value="<?php if(isset($eg_id)) { print($eg_id); } ?>">
			Are your sure you want to delete group <strong><?php if(isset($eg_name)) { print($eg_name); } ?></strong>?<br><br>
			<strong>WARNING!</strong><br>
			All users in this group	will also be deleted and group managers for this group will be disabled.
		</div>
		<div class="modal-footer">
			<input type="submit" class="btn btn-danger pull-right" aria-hidden="true" name="bdelgrp" value="Delete">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
		</div>
	</form>
</div>
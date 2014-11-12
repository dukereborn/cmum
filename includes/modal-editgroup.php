<div id="modalEditGroup" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Edit Group</h3>
	</div>
	<form name="editgroup" id="editgroup" action="groups.php" method="post">
		<div class="modal-body">
			<div class="row">
				<div class="span3">
					<input type="hidden" name="value" id="value" value="beditgrp">
					<input type="hidden" name="rname" id="rname" value="<?php if(isset($eg_name)) { print($eg_name); } ?>">
					<input type="hidden" name="gid" id="gid" value="<?php if(isset($eg_id)) { print($eg_id); } ?>">
					<label>Name</label>
						<input type="text" name="name" id="name" value="<?php if(isset($eg_name)) { print($eg_name); } ?>" autocomplete="off" onkeypress="submitEditGroup(event);" maxlength="25">
					<label>Comment</label>
						<input type="text" name="comment" id="comment" value="<?php if(isset($eg_comment)) { print($eg_comment); } ?>" autocomplete="off" onkeypress="submitEditGroup(event);" maxlength="50">
				</div>
				<div class="span2">
					&nbsp;
				</div>
			</div>
		</div>
	</form>
		<div class="modal-footer">
			<button class="btn btn-success pull-right" name="beditgrp" value="Save" onclick="checkeditgroupname();">Save</button>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
		</div>
</div>
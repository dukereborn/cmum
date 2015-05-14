<div id="modalNewGroup" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">New Group</h3>
	</div>
	<form name="newgroup" id="newgroup" action="groups.php" method="post">
		<div class="modal-body">
			<div class="row">
				<div class="span3">
					<input type="hidden" name="value" id="value" value="baddgrp">
					<label>Name</label>
						<input type="text" name="name" id="name" value="<?php if(isset($_POST["name"]) && isset($status) && $status<>"0") { print($_POST["name"]); } ?>" autocomplete="off" onkeypress="submitNewGroup(event);" maxlength="25">
					<label>Comment</label>
						<input type="text" name="comment" id="comment" value="<?php if(isset($_POST["comment"]) && isset($status) && $status<>"0") { print($_POST["comment"]); } ?>" autocomplete="off" onkeypress="submitNewGroup(event);" maxlength="50">
				</div>
				<div class="span2">
					&nbsp;
				</div>
			</div>
		</div>
	</form>
		<div class="modal-footer">
			<button class="btn btn-success pull-right" name="baddgrp" value="Add" onclick="checkgroupname();">Add</button>
			<button class="btn" data-dismiss="modal" aria-hidden="true" onclick="cleanmodalNewGroup();">Cancel</button>&nbsp;&nbsp;
		</div>
</div>
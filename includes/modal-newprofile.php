<div id="modalNewProfile" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">New Profile</h3>
	</div>
	<form name="newprofile" id="newprofile" action="profiles.php" method="post">
		<div class="modal-body">
			<div class="row">
				<div class="span3">
					<input type="hidden" name="value" id="value" value="baddprf">
					<label>Name</label>
						<input type="text" name="name" id="name" value="<?php if(isset($_POST["name"]) && isset($status) && $status<>"0") { print($_POST["name"]); } ?>" autocomplete="off" onkeypress="submitNewProfile(event);" maxlength="25">
					<label>CSP Value</label>
						<input type="text" name="cspvalue" id="cspvalue" value="<?php if(isset($_POST["cspvalue"]) && isset($status) && $status<>"0") { print($_POST["cspvalue"]); } ?>" autocomplete="off" onkeypress="submitNewProfile(event);" maxlength="25">
					<label>Comment</label>
						<input type="text" name="comment" id="comment" value="<?php if(isset($_POST["comment"]) && isset($status) && $status<>"0") { print($_POST["comment"]); } ?>" autocomplete="off" onkeypress="submitNewProfile(event);" maxlength="50">
				</div>
				<div class="span2">
					&nbsp;
				</div>
			</div>
		</div>
	</form>
		<div class="modal-footer">
			<button class="btn btn-success pull-right" name="baddprf" value="Add" onclick="checkprofilename();">Add</button>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
		</div>
</div>
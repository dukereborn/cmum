<h4 class="header">Delete Expired Users</h4>
	<form name="tools" action="tools.php?menu=1&tool=104" method="post" class="form">
		<div class="control-group">
			<label class="control-label" for="expdate">Expire date (Users with expire date older than given date will be deleted)</label>
				<div class="controls">
					<input type="text" name="expdate" id="expdate" data-date-format="yyyy-mm-dd" value="<?php if(isset($_POST["expdate"]) && $_POST["expdate"]<>"") { print($_POST["expdate"]); } else { print(date("Y-m-d")); } ?>">
				</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="admpasswd">Enter your password to confirm</label>
				<div class="controls">
					<input type="password" name="admpasswd" id="admpasswd" value="" autofocus>
				</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<input type="submit" name="baction" value="Delete expired users" class="btn">
			</div>
		</div>
	</form>
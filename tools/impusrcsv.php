<h4 class="header">Import Users (CSV)</h4>
	<form name="tools" action="tools.php?menu=2&tool=202" method="post" class="form">
		<div class="control-group">
			<label class="control-label" for="cmumcsvver">CMUM CSV Format</label>
				<div class="controls">
					<select name="cmumcsvver" id="cmumcsvver">
						<option value="2">Version 2.x</option>
						<option value="3" selected>Version 3.x</option>
					</select>
				</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="csv">CSV</label>
				<div class="controls">
					<textarea name="csv" id="csv" rows="15" style="width: 90%; resize: none;" wrap="off" autofocus></textarea>
				</div>
		</div>
		<div class="control-group">
			<label class="checkbox">
				<input type="checkbox" name="createprof" value="1"> Create profiles if not already exist
	  		</label>
		</div>
		<div class="control-group">
			<label class="checkbox">
				<input type="checkbox" name="creategrp" value="1"> Create user groups if not already exist
	  		</label>
		</div>
		<div class="control-group">
			<div class="controls">
				<input type="submit" name="bimpcsv" value="Import users" class="btn">
			</div>
		</div>
	</form>
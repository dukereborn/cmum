<h4 class="header">Import Users (CSP XML)</h4>
	<form name="tools" action="tools.php?menu=2&tool=201" method="post" class="form">
		<div class="control-group">
			<label class="control-label" for="cspxml">CSP XML</label>
				<div class="controls">
					<textarea name="cspxml" id="cspxml" rows="15" style="width: 90%; resize: none;" wrap="off" style="resize: none;" autofocus></textarea>
				</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="usrgrp">Import users into group</label>
				<div class="controls">
					<select name="usrgrp" id="usrgrp">
						<option value="" selected></option>
						<?php
							$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
							$grpsql=$mysqli->query("SELECT id,name FROM groups ORDER BY name ASC");
								while($grpres=$grpsql->fetch_array()) {
									print("<option value=\"".$grpres["id"]."\">".$grpres["name"]."</option>");
								}
							mysqli_close($mysqli);
						?>
					</select>
				</div>
		</div>
		<div class="control-group">
			<label class="checkbox">
				<input type="checkbox" name="createprof" value="1"> Create profiles if not already exist
	  		</label>
		</div>
		<div class="control-group">
			<div class="controls">
				<input type="submit" name="bimpxml" value="Import users" class="btn">
			</div>
		</div>
	</form>
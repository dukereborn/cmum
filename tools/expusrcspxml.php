<h4 class="header">Export Users (CSP XML)</h4>
	<form name="tools" action="tools.php?menu=2&tool=203" method="post" class="form">
		<div class="control-group">
			<label class="control-label" for="usrgrp">Export users from group</label>
				<div class="controls">
					<select name="usrgrp" id="usrgrp" onchange="this.form.submit();">
						<option value="" selected></option>
						<?php
							$mysqli=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
							$grpsql=$mysqli->query("SELECT id,name FROM groups ORDER BY name ASC");
								while($grpres=$grpsql->fetch_array()) {
									if(isset($_POST["usrgrp"]) && $_POST["usrgrp"]<>"") {
										if($grpres["id"]==$_POST["usrgrp"]) {
											print("<option value=\"".$grpres["id"]."\" selected>".$grpres["name"]."</option>");
										} else {
											print("<option value=\"".$grpres["id"]."\">".$grpres["name"]."</option>");
										}
									} else {
										print("<option value=\"".$grpres["id"]."\">".$grpres["name"]."</option>");
									}
								}
							mysqli_close($mysqli);
						?>
					</select>
				</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="expcspxml">CSP XML</label>
				<div class="controls">
					<textarea name="expcspxml" id="expcspxml" rows="15" style="width: 90%; resize: none;" wrap="off" autofocus><?php if(isset($_POST["usrgrp"]) && $_POST["usrgrp"]<>"") { print(expusrcspxml($_POST["usrgrp"])); } else { print(expusrcspxml("")); } ?></textarea>
				</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<input type="submit" name="bexpxmldown" value="Download" class="btn">&nbsp;&nbsp;<input type="button" name="bexpcopy" value="Select all" class="btn" onclick="expcspxml.select()">
			</div>
		</div>
	</form>
<div id="modalImpUsrRes" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Import Results</h3>
	</div>
	<form>
		<div class="modal-body">
			<?php
				if(!empty($impstatus["usrimp"]) && $impstatus["usrimp"]<>"0") {
					print("<strong>Imported Users (".$impstatus["usrimp"].")</strong><br><p class=\"text-success\">".$impstatus["implist"]."</p>");
				}
				if(!empty($impstatus["usrexi"]) && $impstatus["usrexi"]<>"0") {
					print("<strong>Duplicates/Not Imported (".$impstatus["usrexi"].")</strong><br><p class=\"text-warning\">".$impstatus["exilist"]."</p>");
				}
				if(!empty($impstatus["profimp"]) && $impstatus["profimp"]<>"0") {
					print("<strong>Auto Created Profiles (".$impstatus["profimp"].")</strong><br><p class=\"text-success\">".$impstatus["proflist"]."</p>");
				}
				if(!empty($impstatus["grpimp"]) && $impstatus["grpimp"]<>"0") {
					print("<strong>Auto Created Groups (".$impstatus["grpimp"].")</strong><br><p class=\"text-success\">".$impstatus["grplist"]."</p>");
				}
			?>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>&nbsp;&nbsp;
		</div>
	</form>
</div>
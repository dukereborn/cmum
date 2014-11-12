<div id="modalImpProfRes" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Import Results</h3>
	</div>
	<form>
		<div class="modal-body">
			<?php
				if(!empty($impstatus["profimp"]) && $impstatus["profimp"]<>"0") {
					print("<strong>Imported Profiles (".$impstatus["profimp"].")</strong><br><p class=\"text-success\">".$impstatus["proflist"]."</p>");
				}
				if(!empty($impstatus["profexi"]) && $impstatus["profexi"]<>"0") {
					print("<strong>Duplicates/Not Imported (".$impstatus["profexi"].")</strong><br><p class=\"text-warning\">".$impstatus["exilist"]."</p>");
				}
			?>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>&nbsp;&nbsp;
		</div>
	</form>
</div>
<div id="modalCspSendOsd" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Send OSD Message</h3>
	</div>
	<div class="modal-body">
		<div id="osdusrlabel"><label>Message to <strong>%user%</strong></label></div>
			<input type="hidden" class="span5" name="osdusr" id="osdusr" value="">
			<input type="text" class="span5" name="osdmsg" id="osdmsg" value="" onkeypress="submitCspSendOsd(event);">
	</div>
	<div class="modal-footer">
		<button class="btn btn-success pull-right" name="bsendosd" id="bsendosd" value="Send" onclick="checkcspsendosd();">Send</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
	</div>
</div>
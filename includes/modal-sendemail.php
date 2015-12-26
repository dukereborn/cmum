<div id="modalSendEmail" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel">Send Email</h3>
	</div>
	<div class="modal-body">
		<div id="emailusrlabel"></div>
			<input type="hidden" class="span5" name="email_to" id="email_to" value="">
			<label for="email_subject">Subject</label>
				<input type="text" class="form-control" style="min-width: 98%" name="email_subject" id="email_subject" value=""><br>
			<label for="email_body">Message</label>
				<textarea class="form-control" style="min-width: 98%" name="email_body" id="email_body" rows="10"></textarea>
	</div>
	<div class="modal-footer">
		<button class="btn btn-success pull-right" name="bsendemail" id="bsendemail" value="Send" onclick="checksendemail();">Send</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>&nbsp;&nbsp;
	</div>
</div>
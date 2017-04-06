<h4 class="header">Send Email To All Users</h4>
	<form name="tools" action="tools.php?menu=5&tool=503" method="post" class="form" id="sendemailtoall">
		<div class="control-group">
			<label class="control-label" for="email_subject">Subject</label>
				<div class="controls">
					<input class="input-xxlarge" type="text" name="email_subject" id="email_subject" value="" autofocus>
				</div>
			<label class="control-label" for="email_body">Message</label>
				<div class="controls">
					<textarea class="input-xxlarge" name="email_body" id="email_body" rows="10"></textarea>
				</div>
			<input type="hidden" name="baction" id="baction" value="Send emails">
		</div>
	</form>
		<div class="control-group">
			<div class="controls">
				<button class="btn" name="bsendemails" id="bsendemails" onclick="checkemailfields();">Send Emails</button>
			</div>
		</div>
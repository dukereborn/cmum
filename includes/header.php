<?php
if($_SESSION[$secretkey."fetchcsp"]=="1") {
	$cspconnstatus=checkcspconn();
} else {
	$cspconnstatus="0";
}	
?>
<div id="in-nav">
	<div class="container">
		<div class="row">
			<div class="span12">
				<ul class="pull-right">
					<p><?php if($_SESSION[$secretkey."servername"]<>"") { print($_SESSION[$secretkey."servername"]." | "); } ?>Logged on as <strong><?php print($_SESSION[$secretkey."admin"]); ?></strong> - <a href="#modalLogout" data-toggle="modal">Logout</a></p>
				</ul>
				<h4><span id="headerlight">CSP</span><span id="headerstrong"><strong>MYSQL</strong></span><span id="headerlight">USER</span><span id="headerstrong"><strong>MANAGER</strong></span></h4>
			</div>
		</div>
	</div>
</div>
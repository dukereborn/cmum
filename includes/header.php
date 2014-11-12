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
					<p><?php if($_SESSION[$secretkey."servername"]<>"") { print($_SESSION[$secretkey."servername"]." | "); } ?>Logged on as <strong><?php print($_SESSION[$secretkey."user"]); ?></strong> - <a href="#modalLogout" data-toggle="modal">Logout</a></p>
				</ul>
				<h4>CSP<strong id="headerstrong">MYSQL</strong>USER<strong id="headerstrong">MANAGER</strong></h4>
			</div>
		</div>
	</div>
</div>
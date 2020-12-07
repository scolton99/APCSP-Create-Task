<?php require_once("var.php"); ?>
<nav class="navbar navbar-default navbar-fixed-top" id="top-navbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="<?php echo getHome(); ?>" class="navbar-brand">Collaborative Health</a>
		</div>
		<div class="collapse navbar-collapse" id="topnav-collapse">
			<ul class="nav navbar-nav">
				<li>
					<a href="<?php echo getHome(); ?>/team">Team</a>
				</li>
				<li>
					<a href="<?php echo getHome(); ?>/goals">Goals</a>
				</li>
				<li>
					<a href="<?php echo getHome(); ?>/account">Account</a>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="javascript:toggleNotifications();" data-toggle="popover" title="Notifications" data-placement="auto bottom" data-content="XXX"><i class="fa fa-bell-o"></i></a>
				</li>
				<li>
					<?php if (userLoggedIn()): ?>
					<a id="logout" href="javascript:void(0);">Log out <i class="fa fa-sign-out"></i></a>
					<?php else: ?>
					<a href="<?php echo getHome(); ?>/login">Sign In <i class="fa fa-sign-in"></i></a>
					<?php endif;?>
				</li>
			</ul>
		</div>
	</div>
</nav>
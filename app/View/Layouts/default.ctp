<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>
		<?php echo $title_for_layout; ?>
		- Vermont Legislation
	</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Le styles -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/bootstrap.timeline.css">
	<link rel="stylesheet" href="/css/zocial/zocial.css">
	
	<link href='http://fonts.googleapis.com/css?family=Poiret+One|Kreon' rel='stylesheet' type='text/css'>
	
	<link rel="stylesheet" href="/css/custom.css">
	
	<style>
	body {
		padding-top: 70px; /* 70px to make the container go all the way to the bottom of the topbar */
	}
	.affix {
		position: fixed;
		top: 60px;
		width: 220px;
	}
	</style>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	?>
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>

<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			
			<div class="pull-right mobile-hide" >
				<ul class="nav navbar-nav">
					<li>
						<?php
						if($this->Session->read('Logged_In'))
						{ ?>
							<a href="" onclick="$('#modal_login').modal('show'); return false;" ><?php echo $this->Session->read('User.name'); ?></a>
						<?php 
						}
						else
						{?>
							<a href="" onclick="$('#modal_login').modal('show'); return false;" >Login</a>
						<?php
						} ?>
					</li>
				</ul>
			</div>
			
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href='/' class='navbar-brand' >Vermont Legislation</a>
			</div>
			
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">
					<li><?php echo $this->Html->link('Bills', '/bills'); ?></li>
					<li><?php echo $this->Html->link('Legislators', '/legislators'); ?></li>
					<li><?php echo $this->Html->link('Committees', '/committees'); ?></li>
					<li><?php echo $this->Html->link('Bootstrap 3 Info', '/boostCake' ); ?></li>
				</ul>
			</div>
			
			
		</div>
	</nav>

	<div class="container">

		<?php echo $this->fetch('content'); ?>
	
	<!-- Modal -->
	<div class="modal fade" id="modal_login" tabindex="-1" role="dialog" aria-labelledby="modal_login_label" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<?php
					if($this->Session->read('Logged_In'))
					{ 
						echo '<h4 class="modal-title" id="modal_login_label">Hi, '.$this->Session->read('User.first_name').'</h4>';
					}
					else
					{?>
						<h4 class="modal-title" id="modal_login_label">Login Below</h4>
					<?php
					} ?>
				</div>
				<div class="modal-body">
					<?php
					if($this->Session->read('Logged_In'))
					{ 
						if($this->Session->read('User.image') != NULL)
						{
							echo '<img src="'.$this->Session->read('User.image').'" title="Your Profilie Image" />';
						}
						debug($this->Session->read('User'));
					}
					else
					{?>
						<a href="/auth/google" class="zocial gmail" >Login With GMail/Google</a>
					<?php
					} ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<!--
					<button type="button" class="btn btn-primary">Save changes</button>
					-->
				</div>
			</div>
		</div>
	</div>

	
	</div><!-- /container -->

	<!-- Le javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	
	<?php echo $this->fetch('script'); ?>

</body>
</html>

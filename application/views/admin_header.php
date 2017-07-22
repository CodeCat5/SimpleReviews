<!DOCTYPE html>
<html lang="en">
<head>
	<title>Boosterthon Fundraiser Reviews</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<base href="<?= base_url(); ?>admin/" />

	<link rel="stylesheet" href="../assets/bootstrap-3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="../assets/bootstrap-3.3.7/css/bootstrap-theme.min.css" />
	<link rel="stylesheet" type="text/css" href="../css/admin.css" />

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<?= (isset($header_extra) ? $header_extra : ''); ?>
</head>
<body>

<nav class="navbar navbar-inverse">
	<div class="container-fluid">
		<div class="navbar-header">
			<?php if ($template_name != 'admin_login'): ?>
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<?php endif; ?>
			<span class="navbar-brand">Booster Reviews</span>
		</div>
		<?php if ($template_name != 'admin_login'): ?>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">
				<li<?= ($template_name == 'admin_home') ? ' class="active"' : ''; ?>><a href="#">Home</a></li>
				<li<?= ($template_name == 'admin_fundraisers') ? ' class="active"' : ''; ?>><a href="fundraisers">Manage Fundraisers</a></li>
				<li<?= ($template_name == 'admin_reviews' AND !isset($approve)) ? ' class="active"' : ''; ?>><a href="reviews">Manage Reviews</a></li>
				<li<?= (($template_name == 'admin_reviews' AND isset($approve))) ? ' class="active"' : ''; ?>><a href="approve">Approve Reviews</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</nav>
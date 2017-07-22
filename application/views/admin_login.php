<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container" id="page-admin-fundraisers">
	<h1>Admin Login</h1>
	<hr />

	<div id="body">
		<?php if ($this->session->flashdata('error')): ?>
			<div class="alert alert-danger alert-dismissable fade in">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?= $this->session->flashdata('error'); ?>
			</div>
		<?php endif ?>
		<?php if ($this->session->flashdata('warning')): ?>
			<div class="alert alert-warning alert-dismissable fade in">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?= $this->session->flashdata('warning'); ?>
			</div>
		<?php endif ?>
		<?php if ($this->session->flashdata('success')): ?>
			<div class="alert alert-success alert-dismissable fade in">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?= $this->session->flashdata('success'); ?>
			</div>
		<?php endif ?>
	</div>

	<div class="clearfix"></div>

	<?= form_open('', array('id' => 'admin-login', 'class' => 'form-horizontal')); ?>
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-primary">
			<div class="panel-heading">Login</div>
			<div class="panel-body">

				<div class="col-md-10 col-md-offset-1">
					<div class="form-group">
						<label for="email">Email address:</label>
						<input type="email" name="email" class="form-control" id="login-email">
					</div>
					<div class="form-group">
						<label for="pwd">Password:</label>
						<input type="password" name="password" class="form-control" id="login-password">
					</div>
				</div>
			</div>
		</div>

		<div class="clearfix"></div>
		<input type="submit" class="btn btn-primary btn-lg" value="Login" />
	</div>
</div>

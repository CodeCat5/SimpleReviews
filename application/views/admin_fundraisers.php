<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container" id="page-admin-fundraisers">
	<h1>Manage Fundraisers</h1>
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

		<div class="form-group">
			<button id="add-fundraiser" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#submit-fundraiser-modal">Add New Fundraiser</button>
		</div>

		<table id="fundraisers-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th>Fundraisers</th>
			</tr>
			</thead>
			<?php foreach ($fundraisers AS $fund): ?>
				<tr>
					<td>
						<a href="#" id="fund-edit-<?= $fund->id ?>" class="fundraiser-edit" title="Edit Fundraiser" data-toggle="modal" data-target="#submit-fundraiser-modal" data-id="<?= $fund->id; ?>" data-title="<?= $fund->title; ?>"><span class="glyphicon glyphicon-edit"></a>
						<a href="#" id="fund-delete-<?= $fund->id ?>" class="fundraiser-delete" title="Delete Fundraiser" data-toggle="modal" data-target="#delete-fundraiser-modal" data-id="<?= $fund->id; ?>" data-title="<?= $fund->title; ?>"><span class="glyphicon glyphicon-trash"></a>
						<h4 id="fund-<?= $fund->id ?>"><?= $fund->title; ?></h4>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>

	<div class="clearfix"></div>
</div>

<!-- Add/Edit Modal -->
<?= form_open('admin/save_fundraiser', array('id' => 'submit-fundraiser', 'class' => 'form-horizontal')); ?>
<input type="hidden" name="edit-fundraiserid" id="edit-fundraiserid" value="0" />
<div id="submit-fundraiser-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Add New Fundraiser</h4>
			</div>
			<div class="modal-body">
				<div id="modal-error" class="alert"></div>

				<div class="form-group">
					<label class="control-label col-sm-4" for="submit-title">Fundraiser Name:</label>
					<div class="col-sm-8">
						<input type="text" id="submit-title" class="form-control" required="required" placeholder="Name of Fundraiser">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-primary" value="Save" />
			</div>
		</div>

	</div>
</div>
</form>

<!-- Delete Modal -->
<?= form_open('admin/delete_fundraiser', array('id' => 'submit-fundraiser', 'class' => 'form-horizontal')); ?>
<input type="hidden" name="delete-fundraiserid" id="delete-fundraiserid" value="0" />
<div id="delete-fundraiser-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Delete Fundraiser?</h4>
			</div>
			<div class="modal-body">
				<div id="modal-error" class="alert"></div>

				<div class="form-group">
					<h3 id="fund-title"></h3>
					<h3>Are you sure you want to delete this fundraiser?<br />This action cannot be undone!</h3>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-lg" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-danger btn-lg" value="Delete" />
			</div>
		</div>

	</div>
</div>
</form>
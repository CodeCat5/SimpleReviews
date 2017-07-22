<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
	<h1><?= (isset($approve)) ? 'Approve New Reviews' : 'Manage Reviews'; ?></h1>
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

		<?php if (!isset($approve)): ?>
		<form action="reviews" method="get" id="reviews" class="form-horizontal">
		<div class="col-md-8 col-md-offset 2">
			<div class="form-group">
				<select name="fundraiserid" id="reviews-fundraiserid" class="form-control">
					<option value="0" class="">Please Choose A Fundraiser...</option>
					<?php foreach ($fundraisers_select AS $fund): ?>
						<option value="<?= $fund->id; ?>"<?= ($fund->id == $fundraiserid) ? ' selected="selected"' : ''; ?>><?= $fund->title; ?> (<?= $fund->rating_votes; ?> reviews)</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		</form>
		<?php endif; ?>

		<?= form_open('admin/save_approved', array('id' => 'save-approved', 'class' => 'form-horizontal')); ?>
		<input type="hidden" id="save-fundraiserid" name="fundraiserid" value="<?= (isset($fundraiserid) ? $fundraiserid : 0); ?>" />
		<table id="approve-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
			<td align="center"><input type="checkbox" class="check-all" data-formid="save-approved" data-toggle="tooltip" title="Select All Reviews" /></td>
			<td>Name</td>
			<td>Email</td>
			<td>Rating</td>
			<td class="col-md-6">Details</td>
			</thead>
			<?php foreach ($reviews AS $review): ?>
				<tr>
					<td align="center"><input type="checkbox" name="reviewids[<?= $review->id; ?>]" value="<?= $review->fundraiserid; ?>" /></td>
					<td><?= $review->name ?></td>
					<td><?= $review->email ?></td>
					<td><?= $review->rating ?></td>
					<td><div><strong><?= date('m-d-Y h:i a', $review->time_added) ?> - <?= $review->fundraiser_title ?></strong></div><?= $review->details ?></td>
				</tr>
			<?php endforeach; ?>
		</table>

		<?= (isset($approve) ? '<button id="approve-submit" class="btn btn-info btn-large" name="action" value="submit">Approve Selected Reviews</button>' : ''); ?>
		<button id="approve-delete" name="action" class="btn btn-info btn-danger" value="delete">Delete Selected Reviews</button>

		</form>
	</div>
</div>
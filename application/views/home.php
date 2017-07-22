<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container" id="page-home">
	<h1>Recent Booster Reviews</h1>
	<hr />
	<div id="body">
		<div class="col-sm-8">
			<div class="form-group">
				<a class="btn btn-primary btn-lg review-href" href="#" data-toggle="modal" data-target="#review-modal" data-id="0">Write a review</a>
			</div>
		</div>
		<div class="clearfix"></div>

		<?php foreach ($fundraisers AS $fund): ?>
			<div class="col-md-6 fundraisers">
				<h2><a href="home/fundraiser/<?= $fund->id ?>/<?= $fund->slug; ?>"><?= trim(substr($fund->title, 0, $title_length + 1)) . (strlen($fund->title) > $title_length + 1 ? '...' : ''); ?></a></h2>
				<div class="desktop-inline">
					<div class="stars" data-toggle="tooltip" title="<?= $fund->rating_avg . ' average, ' . $fund->rating_votes . ' votes'; ?>">
						<label for="st1"<?= ($fund->rating_avg >= 0.5 ? ' class="checked"' : '') ?>></label>
						<label for="st2"<?= ($fund->rating_avg >= 1.5 ? ' class="checked"' : '') ?>></label>
						<label for="st3"<?= ($fund->rating_avg >= 2.5 ? ' class="checked"' : '') ?>></label>
						<label for="st4"<?= ($fund->rating_avg >= 3.5 ? ' class="checked"' : '') ?>></label>
						<label for="st5"<?= ($fund->rating_avg >= 4.5 ? ' class="checked"' : '') ?>></label>
					</div>
				</div>
				<div class="desktop-inline"><?= $fund->rating_votes ?> review<?= $fund->rating_votes == 1 ? '' : 's' ?>, <?= number_format($fund->rating_avg, 1); ?> average</div>
				<div class="desktop-inline"><a class="review-href" href="#" data-toggle="modal" data-target="#review-modal" title="<?= $fund->title; ?>" data-id="<?= $fund->id; ?>">Write a review</a></div>

				<?php if ($fund->details): ?>
					<div class="recent-review content-separator">
						<div>Most recent by <?= $fund->name; ?> on <?= date('M jS, Y', $fund->time_added); ?></div>
						<div class="show-read-more"><?= $fund->details; ?></div>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>

		<div class="clearfix"></div>
		<?= $pages ?>
	</div>
</div>
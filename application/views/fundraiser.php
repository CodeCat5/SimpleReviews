<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container" id="page-fundraiser">
	<h1><a href="/"><span class="glyphicon glyphicon-home"></a>Reviews for <?= $fundraiser->title; ?></h1>
	<hr />

	<div id="body">

		<div class="col-md-8 col-sm-8 col-xs-12" id="above-stars">
			<a class="btn btn-primary btn-lg review-href" href="#" data-toggle="modal" data-target="#review-modal" data-id="<?= $fundraiser->id; ?>">Write a review</a>

			<h3>Rated <?= number_format($fundraiser->rating_avg, 2); ?> / 5 with <?= $fundraiser->rating_votes; ?> total vote<?= ($fundraiser->rating_votes == 1 ? '' : 's') ?>. </h3>
		</div>

		<div class="col-md-4 col-sm-4 col-xs-12">
			<div class="rating-bars">
				<?php
				foreach (range(5, 1) AS $numb):
					$vote_count = (isset($details[$numb])) ? $details[$numb] : 0;
					$vote_avg = ($vote_count) ? number_format($details[$numb] / $totalvotes * 100, 2) : 0;
					?>
					<div class="bar-wrapper">
						<span><a href="home/fundraiser/<?= $fundraiser->id . '/' . $fundraiser->slug . '/' . $numb; ?>"><?= $numb . ($numb == 1 ? ' Star' : ' Stars'); ?></a></span>
						<a href="home/fundraiser/<?= $fundraiser->id . '/' . $fundraiser->slug . '/' . $numb; ?>"><div class="bars" data-toggle="tooltip" title="<?= $vote_avg; ?>% - <?= ($vote_count == 1) ? '1 rating' : $vote_count . ' ratings'; ?>">
							<div class="bars-filled" style="width: <?= $vote_avg; ?>%"></div>
						</div></a>
					</div>
				<?php endforeach; ?>
				<?php if ($rating): ?>
				<div class="bar-wrapper"><a href="home/fundraiser/<?= $fundraiser->id . '/' . $fundraiser->slug ?>" class="label label-primary">Show All</a></div>
				<?php endif; ?>

			</div>
		</div>

		<div class="clearfix"></div>

		<div class="col-lg-12">
			<?php foreach ($reviews AS $review): ?>
				<div class="well">
					<div class="stars" data-toggle="tooltip" title="<?= $review->rating . ' / 5'; ?>">
						<label for="st1"<?= ($review->rating >= 0.5 ? ' class="checked"' : '') ?>></label>
						<label for="st2"<?= ($review->rating >= 1.5 ? ' class="checked"' : '') ?>></label>
						<label for="st3"<?= ($review->rating >= 2.5 ? ' class="checked"' : '') ?>></label>
						<label for="st4"<?= ($review->rating >= 3.5 ? ' class="checked"' : '') ?>></label>
						<label for="st5"<?= ($review->rating >= 4.5 ? ' class="checked"' : '') ?>></label>
					</div>
					<h4><?= date('M jS, Y', $review->time_added); ?> <span>by <?= $review->name; ?></span></h4>
					<div class="clearfix"></div>
					<div class="content-separator"><?= $review->details; ?></div>
				</div>
			<?php endforeach; ?>
			<div class="clearfix"></div>
			<?= $pages ?>

			<div class="form-group pull-right">
				<a class="btn btn-primary btn-lg review-href" href="#" data-toggle="modal" data-target="#review-modal" data-id="<?= $fundraiser->id; ?>">Write a review</a>
			</div>
		</div>
	</div>


</div>
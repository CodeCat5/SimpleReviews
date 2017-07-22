<div class="container" id="page-admin-home">

	<h1>Welcome to the Booster Reviews Admin Panel!</h1>

	<div id="body">
		<h3><?= $pending_phrase; ?></h3>

		<?php if ($pending_count): ?>
			<a class="btn btn-primary" href="approve">View Pending Reviews</a>
		<?php endif; ?>

	</div>

</div>
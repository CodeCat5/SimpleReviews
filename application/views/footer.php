<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>

<!-- Modal -->
<?= form_open('home/save_review', array('id' => 'save-review', 'class' => 'form-horizontal')); ?>
<div id="review-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close modal-dismiss" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Post Your Review</h4>
			</div>
			<div class="modal-body">
				<div id="modal-error" class="alert"></div>

				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<h3>Tell us about your experience with:</h3>
						<div id="review-fundraiser-select">
							<select name="fundraiserid" id="review-fundraiserid" class="form-control input-lg">
								<option value="0">Please choose a fundraiser...</option>
								<option value="-1">+ Add A New Fundraiser</option>
								<optgroup label="Fundraisers">
									<?php foreach ($fundraisers_select AS $fund): ?>
										<option value="<?= $fund->id; ?>"><?= $fund->title; ?></option>
									<?php endforeach; ?>
								</optgroup>
							</select>
						</div>
						<div id="review-fundraiser-input">
							<input type="text" name="fundraisertitle" id="reveiw-fundraisertitle" class="form-control" placeholder="Name of fundraiser" value="" />
						</div>
					</div>
				</div>
				<div class="form-group text-center">
					<div class="col-sm-8 col-sm-offset-2">
						<h3>Your Rating:</h3>
						<div class="stars">
							<input name="rating" type="radio" id="st5" value="5" />
							<label for="st5"></label>
							<input name="rating" type="radio" id="st4" value="4" />
							<label for="st4"></label>
							<input name="rating" type="radio" id="st3" value="3" />
							<label for="st3"></label>
							<input name="rating" type="radio" id="st2" value="2" />
							<label for="st2"></label>
							<input name="rating" type="radio" id="st1" value="1" />
							<label for="st1"></label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<hr />
					<label class="control-label col-sm-4" for="review-name">Your Name:</label>
					<div class="col-sm-8">
						<input type="text" id="review-name" class="form-control" name="name" required="1" placeholder="Name"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" for="review-email">Your Email Address:</label>
					<div class="col-sm-8">
						<input type="text" id="review-email" class="form-control" name="email" required="1" placeholder="Email Address"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" for="review-details">Your Review:</label>
					<div class="col-sm-8">
						<textarea id="review-details" class="form-control" rows="6" cols="50" required="1" name="details" placeholder="Your review"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" for="review-details">Help Fight The Bots:</label>
					<div class="col-sm-8">
						<div class="g-recaptcha" data-sitekey="<?= $this->config->item('google_key'); ?>"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-lg modal-dismiss" data-dismiss="modal">Cancel</button>
				<input type="submit" class="btn btn-primary btn-lg" value="Submit Review" />
			</div>
		</div>

	</div>
</div>
</form>

<script src="assets/jquery-3.2.1.min.js"></script>
<script src="assets/bootstrap-3.3.7/js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async></script>
</body>
</html>
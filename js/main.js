$(document).ready(function () {

	$("[data-toggle=tooltip]").tooltip();

	$(".review-href").on("click", function(e) {
		e.preventDefault();
		$("#review-fundraiserid").val($(this).data("id"));
	});

	$("#review-fundraiserid").on("change", function() {
		if (this.value == "-1") {
			$("#review-fundraiser-select").hide();
			$("#review-fundraiser-input").show();
			$("#reveiw-fundraisertitle").focus();
		}
	});

	var maxLength = 260;
	$(".show-read-more").each(function(){
		var myStr = $(this).text();
		if($.trim(myStr).length > maxLength){
			var newStr = myStr.substring(0, maxLength);
			var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
			$(this).empty().html(newStr);
			$(this).append('<span class="read-more ">... <a href="javascript:void(0);">read more</a></span>');
			$(this).append('<span class="more-text">' + removedStr + '</span>');
		}
	});

	$(".read-more").click(function(){
		$(this).siblings(".more-text").contents().unwrap();
		$(this).remove();
	});

	$(".modal-dismiss").on("click", function() {
		modalReset();
	});

	$("#save-review").submit(function(e) {
		e.preventDefault();

		var name = $("#review-name").val();
		var email = $("#review-email").val();
		var details = $("#review-details").val();
		var rating = $("#save-review input[name='rating']:checked").val();
		var fundraiserid = $("#review-fundraiserid").val();
		var fundraisertitle = $("#reveiw-fundraisertitle").val();
		var token = $("input[name=ratingtoken]").val();
		var gtoken = $("#g-recaptcha-response").val();
		var errors = "";

		// Check for errors
		if (name == "") {
			errors += "<div>Please enter your name.</div>";
		}
		if (email == "" || !isEmail(email)) {
			errors += "<div>Please enter a valid email address.</div>";
		}
		if (details == "") {
			errors += "<div>Please enter your review for this fundraiser.</div>";
		}
		if (rating == "") {
			errors += "<div>Please select a rating for this fundraiser.</div>";
		}
		if (fundraiserid == "" || (fundraiserid == -1 && fundraisertitle == "")) {
			errors += "<div>Please select a fundraiser.</div>";
		}

		if (errors != "") {
			modalError(errors);
		} else {
			$(".btn-default").prop("disabled", true);
			$.ajax({
				type: "POST",
				url: "home/save_review",
				data: {
					details: details,
					email: email,
					fundraiserid: fundraiserid,
					fundraisertitle: fundraisertitle,
					gtoken: gtoken,
					name: name,
					rating: rating,
					ratingtoken: token
				},
				success: function(data) {
					var json = $.parseJSON(data);

					$("input[name=ratingtoken]").val(json.token);

					if (json.hasOwnProperty("errors")) {
						modalError(json.errors);
						grecaptcha.reset();
					} else {
						// Success!!
						$("#modal-error").removeClass("alert-danger");
						$("#modal-error").addClass("alert-success");
						$("#modal-error").html("Thank you, your review has been submitted.");
						$("#modal-error").show();
						$("#review-modal .form-group").hide();
						$(".modal-footer").hide();
						$("#save-review")[0].reset();

						// Reset the modal and elements
						setTimeout(function () {
							modalReset();
						}, 3000);
					}
				},
				error: function() {
					modalError("There was a problem saving your review. Please try again.");
				}
			});
		}
	});

	function modalReset() {
		$('#review-modal').modal('hide');

		setTimeout(function () {
			$("#modal-error").hide();
			$("#review-modal .form-group").show();
			$(".modal-footer").show();
			$(".btn-default").prop("disabled", false);
			$("#review-fundraiser-input").hide();
			$("#review-fundraiser-select").show();
			$("#save-review")[0].reset();
			grecaptcha.reset();
		}, 300);
	}

	function modalError(msg) {
		$("#modal-error").removeClass("alert-success");
		$("#modal-error").addClass("alert-danger");
		$("#modal-error").html(msg);
		$(".btn-default").prop("disabled", false);
		$("#modal-error").show();
	}

	function isEmail(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}
});

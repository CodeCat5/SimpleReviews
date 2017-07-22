$(document).ready(function () {

	$("[data-toggle=tooltip]").tooltip();

	$(".check-all").on("click", function(){
		var formid = $(this).attr("data-formid");
		$("#" + formid + " input:checkbox").not(this).prop("checked", this.checked);
	});

	$("#reviews-fundraiserid").on("change", function(event, ui) {
		$("#save-fundraiserid").val($(this).val());
		$("#reviews").submit();
	});

	if ($("#approve-table").length) {
		$("#approve-table").DataTable({
			responsive: {
				details: {
					display: $.fn.dataTable.Responsive.display.childRowImmediate,
					type: ''
				}
			},
			"order": [],
			"columnDefs": [{
				"targets": [0],
				"orderable": false
			}],
			"lengthMenu": [[25, 50, 100, 250], [25, 50, 100, 250]]
		});
	}

	if ($("#fundraisers-table").length) {
		$("#fundraisers-table").DataTable({
			responsive: {
				details: {
					display: $.fn.dataTable.Responsive.display.childRowImmediate,
					type: ''
				}
			},
			"order": [],
			"columnDefs": [{
				"targets": [0],
				"orderable": false
			}],
			"lengthMenu": [[25, 50, 100, 250], [25, 50, 100, 250]]
		});
	}

	$("#add-fundraiser").on("click", function() {
		$("#edit-fundraiserid").val(0);
		$("#submit-title").val("");
		$("#submit-title").focus();
		$("#submit-fundraiser .modal-title").html("Add New Fundraiser");
	});

	$(".fundraiser-edit").on("click", function(e) {
		e.preventDefault();
		$("#edit-fundraiserid").val($(this).data("id"));
		$("#submit-title").val($(this).attr("data-title")); // data() caches, so attr() is needed here
		$("#submit-fundraiser .modal-title").html("Edit Fundraiser: " + $(this).data("title"));
	});

	$(".fundraiser-delete").on("click", function(e) {
		e.preventDefault();
		$("#fund-title").html($(this).data("title"));
		$("#delete-fundraiserid").val($(this).data("id"));
	});

	$("#submit-fundraiser").submit(function(e) {
		var ftitle = $("#submit-title").val();
		fundraiserid = $("#edit-fundraiserid").val();

		if (ftitle == "") {
			modalError("Please enter a name for this fundraiser.");
		} else {
			$(".btn-default").prop("disabled", true);

			$.ajax({
				type: "POST",
				url: "save_fundraiser",
				data: {
					title: ftitle,
					ratingtoken: $("input[name=ratingtoken]").val(),
					fundraiserid: fundraiserid
				},
				success: function(data) {
					var json = $.parseJSON(data);

					$("input[name=ratingtoken]").val(json.token);
					if (json.errors) {
						$("#modal-error").removeClass("alert-success");
						$("#modal-error").addClass("alert-danger");
						$("#modal-error").html(json.errors);
						$(".btn-default").prop("disabled", false);
						$("#modal-error").show();
					} else {
						$('#submit-fundraiser-modal').modal('hide');
						$("#modal-error").hide();
						$("#submit-fundraiser")[0].reset();
						$(".btn-default").prop("disabled", false);

						if (fundraiserid > 0) {
							console.log('fund??' + fundraiserid);
							$("#fund-" + fundraiserid).html(ftitle);
							$("#fund-edit-" + fundraiserid).attr("data-title", ftitle);
							$("#fund-delete-" + fundraiserid).attr("data-title", ftitle);
						} else {
							window.location.reload(false);
						}
					}
				},
				error: function() {
					modalError("There was a problem saving this fundraiser. Please try again.");
				}
			});

		}

		e.preventDefault();
	});

	function modalError(msg) {
		$("#modal-error").removeClass("alert-success");
		$("#modal-error").addClass("alert-danger");
		$(".btn-default").prop("disabled", false);
		$("#modal-error").html(msg);
		$("#modal-error").show();
	}
});

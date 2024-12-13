var base_url = $("input[name='base_url']").val();
$(document).ready(function () {
	$("button[id='send-memo']").click(function (e) {
		e.preventDefault();
		document.querySelector("[name=isi_memo").value = instance.getData();
		var url = $('form[id="form-memo"]').attr("action");
		var formData = new FormData($("#form-memo")[0]);
		Swal.fire({
			title: "Are you sure?",
			text: "You want to submit the form?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes",
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: url,
					method: "POST",
					data: formData,
					processData: false,
					contentType: false,
					dataType: "JSON",
					beforeSend: () => {
						Swal.fire({
							title: "Loading....",
							timerProgressBar: true,
							allowOutsideClick: false,
							didOpen: () => {
								Swal.showLoading();
							},
						});
					},
					success: function (res) {
						if (res.success) {
							Swal.fire({
								icon: "success",
								title: `${res.msg}`,
								showConfirmButton: false,
								timer: 1500,
							}).then(function () {
								Swal.close();
								location.href = base_url + "app/inbox";
							});
						} else {
							Swal.fire({
								icon: "error",
								title: `${res.msg}`,
								showConfirmButton: false,
								timer: 1500,
							}).then(function () {
								Swal.close();
							});
						}
					},
					error: function (xhr, status, error) {
						Swal.fire({
							icon: "error",
							title: `${error}`,
							showConfirmButton: false,
							timer: 1500,
						});
					},
				});
			}
		});
	});

	$("a[id='btn-logout']").click(function (e) {
		e.preventDefault();
		var url = $("a[id='btn-logout']").attr("href");
		console.log(`${url}`);
		Swal.fire({
			title: "Are you sure?",
			text: "You want to logout?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes",
		}).then((result) => {
			if (result.isConfirmed) {
				location.href = $("a[id='btn-logout']").attr("href");
			}
		});
	});

	$("button[id='btn-submit-task']").click(function (e) {
		e.preventDefault();
		Swal.fire({
			title: "Are you sure?",
			text: "You want submit this form?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes",
		}).then((result) => {
			if (result.isConfirmed) {
				$("form[id='form-create-task']").submit();
			}
		});
	});

	$("button[id='btn-submit-card']").click(function (e) {
		e.preventDefault();
		Swal.fire({
			title: "Are you sure?",
			text: "You want submit this form?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes",
		}).then((result) => {
			if (result.isConfirmed) {
				$("form[id='form-create-card']").submit();
			}
		});
	});

	$("a#btn-close-task").click(function (e) {
		e.preventDefault();
		var href = $(this).attr("href");
		Swal.fire({
			title: "Are you sure?",
			text: "You won't to closed this task?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes",
		}).then((result) => {
			if (result.isConfirmed) {
				location.href = href;
			}
		});
	});

	$(window).on("beforeunload", function () {
		Swal.fire({
			title: "Loading....",
			timerProgressBar: true,
			allowOutsideClick: false,
			didOpen: () => {
				Swal.showLoading();
			},
		});
	});
});

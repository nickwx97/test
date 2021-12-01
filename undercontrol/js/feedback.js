function validateFeedbackSubject(form) {
	var subjectRegex = /^.{1,200}$/;
	var errorSubject = document.getElementById("errorSubject");

	if (form.subject.value == "" || form.subject.value == null || subjectRegex.test(form.subject.value) == false) {
		form.subject.style.borderColor = "Red";
		errorSubject.innerHTML = "* Please enter a valid feedback subject";
		errorSubject.style.color = "Red";
		return false;
	}
	else {
		form.subject.style.borderColor = "Green";
		errorSubject.innerHTML = "";
		return true;
	}
}

function validateFeedbackFullname(form) {
	var nameRegex = /^[a-zA-Z ,.'-]{1,200}$/;
	var errorFullName = document.getElementById("errorFullName");
	if (form.fullname.value == "" || form.fullname.value == null || nameRegex.test(form.fullname.value) == false) {
		form.fullname.style.borderColor = "Red";
		errorFullName.innerHTML = "* Please enter a valid name";
		errorFullName.style.color = "Red";
		return false;
	}
	else {
		form.fullname.style.borderColor = "Green";
		errorFullName.innerHTML = "";
		return true;
	}
}

function validateFeedbackMobile(form) {
	var moblieRegex = /^[0-9]{8,10}$/;
	var errorMobileNo = document.getElementById("errorMobileNo");
	if (form.mobile_no.value == "" || form.mobile_no.value == null || moblieRegex.test(form.mobile_no.value) == false) {
		form.mobile_no.style.borderColor = "Red";
		errorMobileNo.innerHTML = "* Please enter a valid mobile number";
		errorMobileNo.style.color = "Red";
		return false;
	}
	else {
		form.mobile_no.style.borderColor = "Green";
		errorMobileNo.innerHTML = "";
		return true;
	}
}

function validateFeedbackEmail(form) {
	var emailRegex = /^\w+@\w+([\.-]?\w+)*(\.\w{3})+$/;
	var errorEmail = document.getElementById("errorEmail");
	if (form.email.value == "" || form.email.value == null || emailRegex.test(form.email.value) == false) {
		form.email.style.borderColor = "Red";
		errorEmail.innerHTML = "* Please enter a valid email address";
		errorEmail.style.color = "Red";
		return false;
	}
	else {
		form.email.style.borderColor = "Green";
		errorEmail.innerHTML = "";
		return true;
	}
}

function validateFeedbackMessage(form) {
	var messageRegex = /^.{1,2000}$/;
	var errorMessage = document.getElementById("errorMessage");
	if (form.message.value == "" || form.message.value == null || messageRegex.test(form.message.value) == false) {
		form.message.style.borderColor = "Red";
		errorMessage.innerHTML = "* Please enter a valid feedback content";
		errorMessage.style.color = "Red";
		return false;
	}
	else {
		form.message.style.borderColor = "Green";
		errorMessage.innerHTML = "";
		return true;
	}
}

function feedbackInsert() {
	var subject = $("#subject").val();
	var fullname = $("#fullname").val();
	var ddCountryCode = $("#ddCountryCode").val();
	var mobile_no = $("#mobile_no").val();
	var email = $("#email").val();
	var ddFeedbackType = $("#ddFeedbackType").val();
	var message = $("#message").val();
	var form = document.forms['contact-form'];
	console.log("WHAT IS THIS FEEDBACK");
	var isSubjectValid = validateFeedbackSubject(form);
	var isNameValid = validateFeedbackFullname(form);
	var isMobileNoValid = validateFeedbackMobile(form);
	var isEmailValid = validateFeedbackEmail(form);
	var isMessageValid = validateFeedbackMessage(form);

	// check validation here if accept move on
	if (!isSubjectValid || !isNameValid || !isMobileNoValid || !isEmailValid || !isMessageValid) {
		return false;
	} else {
		// pass js validation use ajax to insert the data
		$.ajax({ // ajax from php validation
			url: 'feedback_process.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				action: 'submitFeedback', subject: subject, fullname: fullname, ddCountryCode: ddCountryCode,
				mobile_no: mobile_no, email: email, ddFeedbackType: ddFeedbackType, message: message
			},
			success: function (data) {
				if (data.success == true) {
					alert("You have successfully submitted your feedback");
					location.reload();
				}else if (data.count >= 20){
					alert("Exceeded submitting feedback, Come back tomorrow!");
				}else {
					alert("Failed to submit feedback, please try again!");
				}
			},
			error: function (request, status, error) {
				console.log(request);
				alert(status);
			},
			async: false
		});
	}
}

function feedbackDelete(id) {
	var deleteID = id;
	$.ajax({
		// ajax from PHP validation
		url: 'feedback_process.php',
		type: 'POST',
		dataType: 'JSON',
		data: {
			action: 'deleteFeedback', deleteID: deleteID,
		},
		success: function (data) {

			if (data.success == true) {
				alert("Successfully deleted feedback entry!");
				location.reload();
				$("#table2").show();
			} else {
				alert("Failed to delete feedback, please try again!");
			}
		},
		error: function (request, status, error) {
			console.log(request);
			alert(status);
		},
		async: false
	});

}

//page load information
function loadInformation() {
	$('[data-filter=".fireInfo"]').click();
}

function validateSenderName(id) {
	var nameRegex = /^[a-zA-Z ,.'-]{1,200}$/;
	var senderName = document.getElementById("senderName" + id);
	var errorMsg = document.getElementById("errorSenderName" + id);

	if (senderName.value == "" || senderName.value == null || nameRegex.test(senderName.value) == false) {
		errorMsg.innerHTML = "* Please enter a valid sender name";
		errorMsg.style.color = "Red";
		return false;
	}
	else {
		errorMsg.innerHTML = "";
		return true;
	}
}

function validateEmailSender(id) {
	var emailRegex = /^\w+@\w+([\.-]?\w+)*(\.\w{3})+$/;
	var senderEmail = document.getElementById("senderEmail" + id);
	var errorMsg = document.getElementById("errorSenderEmail" + id);

	if (senderEmail.value == "" || senderEmail.value == null || emailRegex.test(senderEmail.value) == false) {
		errorMsg.innerHTML = "* Please enter a valid email address";
		errorMsg.style.color = "Red";
		return false;
	}
	else {
		errorMsg.innerHTML = "";
		return true;
	}
}

function validateRecipientName(id) {
	var nameRegex = /^[a-zA-Z ,.'-]{1,200}$/;
	var recipientName = document.getElementById("recipientName" + id);
	var errorMsg = document.getElementById("errorRecipientName" + id);

	if (recipientName.value == "" || recipientName.value == null || nameRegex.test(recipientName.value) == false) {
		errorMsg.innerHTML = "* Please enter a valid recipient name";
		errorMsg.style.color = "Red";
		return false;
	}
	else {
		errorMsg.innerHTML = "";
		return true;
	}
}

function validateEmailRecipient(id) {
	var emailRegex = /^\w+@\w+([\.-]?\w+)*(\.\w{3})+$/;
	var recipientEmail = document.getElementById("recipientEmail" + id);
	var errorMsg = document.getElementById("errorRecipientEmail" + id);

	if (recipientEmail.value == "" || recipientEmail.value == null || emailRegex.test(recipientEmail.value) == false) {
		errorMsg.innerHTML = "* Please enter a valid email address";
		errorMsg.style.color = "Red";
		return false;
	}
	else {
		errorMsg.innerHTML = "";
		return true;
	}
}

function validateSubject(id) {
	var subjectRegex = /^.{1,200}$/;
	var subjectSender = document.getElementById("subjectSender" + id);
	var errorMsg = document.getElementById("errorSubjectSender" + id);

	if (subjectSender.value == "" || subjectSender.value == null  || subjectRegex.test(subjectSender.value) == false) {
		errorMsg.innerHTML = "* Please enter subject";
		errorMsg.style.color = "Red";
		return false;
	}
	else {
		errorMsg.innerHTML = "";
		return true;
	}
}

function validateMessage(id) {
	var messageRegex = /^.{1,2000}$/;
	var feedbackMessage = document.getElementById("feedbackMessage" + id);
	var errorMsg = document.getElementById("errorFeedbackMessage" + id);

	if (feedbackMessage.value == "" || feedbackMessage.value == null || messageRegex.test(feedbackMessage.value) == false) {
		errorMsg.innerHTML = "* Please enter feedback message";
		errorMsg.style.color = "Red";
		return false;
	}
	else {
		errorMsg.innerHTML = "";
		return true;
	}
}

function feedbackRespond(id) {
	// here to inside ajax or send email from php
	var senderName = $("#senderName" + id).val();
	var senderEmail = $("#senderEmail" + id).val();
	var recipientName = $("#recipientName" + id).val();
	var recipientEmail = $("#recipientEmail" + id).val();
	var subjectSender = $("#subjectSender" + id).val();
	var feedbackMessage = $("#feedbackMessage" + id).val();

	var isSenderNameValid = validateSenderName(id);
	var isSenderEmailValid = validateEmailSender(id);
	var isRecipientNameValid = validateRecipientName(id);
	var isRecipientEmailValid = validateEmailRecipient(id);
	var isSubjectValid = validateSubject(id);
	var isMessageValid = validateMessage(id);

	if (!isSenderNameValid || !isSenderEmailValid || !isRecipientNameValid || !isRecipientEmailValid || !isSubjectValid || !isMessageValid) {
		return false;
	} else {
		$.ajax({
			// ajax from PHP validation
			url: 'smtp_process.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				action: 'submitRespond', senderName: senderName, senderEmail: senderEmail, recipientName: recipientName, recipientEmail: recipientEmail, subjectSender: subjectSender, feedbackMessage: feedbackMessage
			},
			success: function (data) {
				if (data.success == true) {
					alert("Successfully submitted feedback response email!");
					$('#respondFeedbackModal' + id).modal('hide');
					location.reload();
				}else {
					alert("Failed to submit feedback response email, please try again!");
				}
			},
			error: function (request, status, error) {
				console.log(request);
				alert(status);
			},
			async: false
		});
	}
}

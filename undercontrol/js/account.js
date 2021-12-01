function validateRegister() {
    var nameRegex = /^[a-zA-Z ,.'-]+$/;
    var usernameRegex = /^[a-zA-Z0-9_]{6,20}$/;
    var emailRegex = /^\w+@\w+([\.-]?\w+)*(\.\w{3})+$/;
    var passRegex = /^[a-zA-Z0-9_]{8,16}$/;

    var newFullName = document.getElementById("newFullName");
    var newUsername = document.getElementById("newUsername");
    var newEmail = document.getElementById("newEmail");
    var newPassword = document.getElementById("newPassword");
	var newEmployeeType = $("#newEmployeeType").val();

    var nameValid = false;
    var usernameValid = false;
    var emailValid = false;
    var passwordValid = false;

    var errorMsgName = document.getElementById("errorNewName");
    var errorMsgUsename = document.getElementById("errorNewUsername");
    var errorMsgEmail = document.getElementById("errorNewEmail");
    var errorMsgPassword = document.getElementById("errorNewPassword");

    if (newFullName.value == "" || newFullName.value == null || nameRegex.test(newFullName.value) == false)
    {
        newFullName.style.borderColor = "Red";
        errorMsgName.innerHTML = "* Please enter a valid name";
        errorMsgName.style.color = "Red";
        nameValid = false;
    }
    else {
        newFullName.style.borderColor = "Green";
        errorMsgName.innerHTML = "";
        nameValid = true;
    }

    if (newUsername.value == "" || newUsername.value == null || usernameRegex.test(newUsername.value) == false)
    {
        newUsername.style.border = "1px solid #ff0000";
        errorMsgUsename.innerHTML = "* Please enter a valid username";
        errorMsgUsename.style.color = "Red";
        usernameValid = false;
    }
    else {
        newUsername.style.borderColor = "Green";
        errorMsgUsename.innerHTML = "";
        usernameValid = true;
    }

    if (newEmail.value == "" || newEmail.value == null || emailRegex.test(newEmail.value) == false)
    {
        newEmail.style.borderColor = "Red";
        errorMsgEmail.innerHTML = "* Please enter a valid email address";
        errorMsgEmail.style.color = "Red";
        emailValid = false;
    }
    else {
        newEmail.style.borderColor = "Green";
        errorMsgEmail.innerHTML = "";
        emailValid = true;
    }

    if (newPassword.value == "" || newPassword.value == null || passRegex.test(newPassword.value) == false)
    {
        newPassword.style.borderColor = "Red";
        errorMsgPassword.innerHTML = "* Please enter a valid password";
        errorMsgPassword.style.color = "Red";
        passwordValid = false;
    }
    else{
        newPassword.style.borderColor = "Green";
        errorMsgPassword.innerHTML = "";
        passwordValid = true;
    }

    if (nameValid && usernameValid && emailValid && passwordValid == false || nameValid == false || usernameValid ==false || emailValid == false || passwordValid == false) {
        return false
    }
    else {
		$.ajax({ // ajax from php validation
			url: 'account_process.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				action: 'register_account', fullname: newFullName.value, username: newUsername.value, email: newEmail.value, password: newPassword.value, user_privilege: newEmployeeType
			},
			success: function (data) {
	
				if (data.success == true) {
					alert("You have successfully registered an account");
				} else {
					alert("Failed to create account, please try again!");
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

function employeeDelete(id) {
	var deleteID = id;
	$.ajax({
		// ajax from PHP validation
		url: 'account_process.php',
		type: 'POST',
		dataType: 'JSON',
		data: {
			action: 'deleteAccount', deleteID: deleteID,
		},
		success: function (data) {
			if (data.success == true) {
                alert("Successfully deleted an employee account!");
				location.reload();
			} else {
				alert("Failed to delete Employee Account, please try again!");
			}
            $('#deleteEmployeeConfirmationModal' + deleteID).modal('hide');
		},
		error: function (request, status, error) {
			console.log(request);
			alert(status);
            alert(error);
		},
		async: false
	});

}
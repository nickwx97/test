function validateForgetEmail() {
    var emailRegex = /^\w+@\w+([\.-]?\w+)*(\.\w{3})+$/;
    var getEmailToken = document.getElementById("emailToken");
    var errorMsg = document.getElementById("errorMsg");

    if (getEmailToken.value == "" || getEmailToken.value == null || emailRegex.test(getEmailToken.value) == false)
    {
        getEmailToken.style.borderColor = "Red";
        errorMsg.innerHTML = "* Please enter a valid email address";
        errorMsg.style.color = "Red";
        return false;
    }
    else{
        $.ajax({ // ajax from php validation
			url: 'token_process.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				action: 'checkEmailExists', getEmailToken: getEmailToken.value
			},
			success: function (data) {
				if (data.success == true) {
					alert("Password reset link has been send!");
                    window.location.href = "login_account";
				} else {
					alert(data.emailMsg);
				}
			},
			error: function (request, status, error) {
				console.log(request);
				alert(status);
			},
			async: false
		});
    }
    return false;
}

function validateForgetPassword(token) {

    var passRegex = /^[a-zA-Z0-9_]{8,16}$/;
    var resetPass1 = document.getElementById("resetPassword1");
    var resetPass1Valid = false;
    var resetPass2 = document.getElementById("resetPassword2");
    var resetPass2Valid = false;
    var errormsgpass1 = document.getElementById("errorforgetPass1");
    var errormsgpass2 = document.getElementById("errorforgetPass2");
    var validatePassword = false;

    if (resetPass1.value == "" || resetPass1.value == null || passRegex.test(resetPass1.value) == false)
    {
        resetPass1.style.borderColor = "Red";
        errormsgpass1.innerHTML = "* Please enter a valid password";
        errormsgpass1.style.color = "Red";
        resetPass1Valid = false;
    }
    else {
        resetPass1.style.borderColor = "Green";
        errormsgpass1.innerHTML = "";
        resetPass1Valid = true;
    }

    if (resetPass2.value == "" || resetPass2.value == null || passRegex.test(resetPass2.value) == false)
    {
        resetPass2.style.borderColor = "Red";
        errormsgpass2.innerHTML = "* Please enter a valid password";
        errormsgpass2.style.color = "Red";
        resetPass2Valid = false;
    }
    else {
        resetPass2.style.borderColor = "Green";
        errormsgpass2.innerHTML = "";
        resetPass2Valid = true;
    }

    if (resetPass1Valid && resetPass2Valid == true)
    {
        if (resetPass1.value != resetPass2.value)
        {
            resetPass1.style.borderColor = "Red";
            errormsgpass1.innerHTML = "* Please ensure the passwords are the same";
            errormsgpass1.style.color = "Red";

            resetPass2.style.borderColor = "Red";
            errormsgpass2.innerHTML = "* Please ensure the passwords are the same";
            errormsgpass2.style.color = "Red";

            validatePassword = false;
        }
        else {
            resetPass1.style.borderColor = "Green";
            resetPass2.style.borderColor = "Green";
            validatePassword = true;
        }
    }

    if (resetPass1Valid && resetPass2Valid == false || resetPass1Valid == false || resetPass2Valid == false || validatePassword == false)
    {
        return false;
    }
    else {
        $.ajax({ // ajax from php validation
			url: 'update_password_token.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				action: 'submitNewPassword', resetPass1: resetPass1.value, resetPass2: resetPass2.value, token: token
			},
			success: function (data) {
				if (data.success == true) {
					alert("You have successfully reset your password!");
                    window.location = "login_account";
				} else {
					alert("Failed to reset account password due to " + data.failPassMsg);
				}
			},
			error: function (request, status, error) {
				console.log(request);
                alert(request.responseText);
			},
			async: false
		});
    }
    return false;
}

function validateUpdateOwnInfo(accountID) {
    var nameRegex = /^[a-zA-Z ,.'-]+$/;
    var usernameRegex = /^[a-zA-Z0-9_]{6,20}$/;
    var emailRegex = /^\w+@\w+([\.-]?\w+)*(\.\w{3})+$/;

    var updateFullName = document.getElementById("updateFullName");
    var updateUserName = document.getElementById("updateUsername");
    var updateEmail = document.getElementById("updateEmail");

    var updNameValid = false;
    var updUsernameValid = false;
    var updEmailValid = false;

    var errorMsgUpdName = document.getElementById("errorUpdName");
    var errorMsgUpdUsename = document.getElementById("errorUpdUsername");
    var errorMsgUpdEmail = document.getElementById("errorUpdEmail");

    if (updateFullName.value == "" || updateFullName.value == null || nameRegex.test(updateFullName.value) == false)
    {
        updateFullName.style.borderColor = "Red";
        errorMsgUpdName.innerHTML = "* Please enter a valid name";
        errorMsgUpdName.style.color = 'Red';
        updNameValid = false;
    }
    else {
        updateFullName.style.borderColor = "Green";
        errorMsgUpdName.innerHTML = "";
        updNameValid = true;
    }

    if (updateUserName.value == "" || updateUserName.value == null || usernameRegex.test(updateUserName.value) == false)
    {
        updateUserName.style.borderColor = "Red";
        errorMsgUpdUsename.innerHTML = "* Please enter a valid username";
        errorMsgUpdUsename.style.color = 'Red';
        updUsernameValid = false;
    }
    else {
        updateUserName.style.borderColor = "Green";
        errorMsgUpdUsename.innerHTML = "";
        updUsernameValid = true;
    }

    if (updateEmail.value == "" || updateEmail.value == null || emailRegex.test(updateEmail.value) == false)
    {
        updateEmail.style.borderColor = "Red";
        errorMsgUpdEmail.innerHTML = "* Please enter a valid email";
        errorMsgUpdEmail.style.color = 'Red';
        updEmailValid = false;
    }
    else {
        updateEmail.style.borderColor = "Green";
        errorMsgUpdEmail.innerHTML = "";
        updEmailValid = true;
    }

    if (updNameValid && updUsernameValid && updEmailValid == false || updNameValid == false || updUsernameValid ==false || updEmailValid == false)
    {
        return false;
    }
    else{
        // pass js validation use ajax to insert the data
		$.ajax({ // ajax from php validation
			url: 'update_account_process.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				action: 'submitOwnInfo', accountID: accountID, updateFullname: updateFullName.value, updateUserName: updateUserName.value, updateEmail: updateEmail.value
			},
			success: function (data) {
				if (data.success == true) {
					alert("You have successfully updated your account information");
                    location.reload();
				} else {
					alert("Failed to update account information due to" + data.duplicatemsg);
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

function validateUpdateInfo(accountID) {
    var nameRegex = /^[a-zA-Z ,.'-]+$/;
    var usernameRegex = /^[a-zA-Z0-9_]{6,20}$/;
    var emailRegex = /^\w+@\w+([\.-]?\w+)*(\.\w{3})+$/;

    var updateFullname = $("#updateFullName" + accountID).val();
    var updateUserName = $("#updateUsername" + accountID).val();
    var updateEmail = $("#updateEmail" + accountID).val();
    var updateType = $("#ddEmployeeType" + accountID).val();

    var updNameValid = false;
    var updUsernameValid = false;
    var updEmailValid = false;

    var errorMsgUpdName = $("#errorUpdName" + accountID);
    var errorMsgUpdUsename = $("#errorUpdUsername" + accountID);
    var errorMsgUpdEmail = $("#errorUpdEmail" + accountID);

    if (updateFullname == "" || updateFullname == null || nameRegex.test(updateFullname) == false)
    {
        $("#updateFullName" + accountID).css('border-color', "Red");
        errorMsgUpdName.html("* Please enter a valid name");
        errorMsgUpdName.css('color', "Red");
        updNameValid = false;
    }
    else {
        $("#updateFullName" + accountID).css('border-color', "Green");
        errorMsgUpdName.html("");
        updNameValid = true;
    }

    if (updateUserName == "" || updateUserName == null || usernameRegex.test(updateUserName) == false)
    {
        $("#updateUsername" + accountID).css('border-color', "Red");
        errorMsgUpdUsename.html("* Please enter a valid username");
        errorMsgUpdUsename.css('color', "Red");
        updUsernameValid = false;
    }
    else {
        $("#updateUsername" + accountID).css('border-color', "Green");
        errorMsgUpdUsename.html("");
        updUsernameValid = true;
    }

    if (updateEmail == "" || updateEmail == null || emailRegex.test(updateEmail ) == false)
    {
        $("#updateEmail" + accountID).css('border-color', "Red");
        errorMsgUpdEmail.html("* Please enter a valid email address");
        errorMsgUpdEmail.css('color', "Red");
        updEmailValid = false;
    }
    else {
        $("#updateEmail" + accountID).css('border-color', "Green");
        errorMsgUpdEmail.html("");
        updEmailValid = true;
    }

    if (updNameValid && updUsernameValid && updEmailValid == false || updNameValid == false || updUsernameValid ==false || updEmailValid == false)
    {
        return false
    }
    else{
        // pass js validation use ajax to insert the data
		$.ajax({ // ajax from php validation
			url: 'update_account_process.php',
			type: 'POST',
			dataType: 'JSON',
			data: {
				action: 'submitUpdateInfo', accountID: accountID, updateFullname: updateFullname, updateUserName: updateUserName, updateEmail: updateEmail, updateType: updateType
			},
			success: function (data) {
				if (data.success == true) {
					alert("You have successfully updated the account information!");
                    location.reload();
				} else {
					alert("Failed to update account information due to" + data.duplicatemsg);
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

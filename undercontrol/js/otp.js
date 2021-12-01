$(document).ready(function () {
    // Attach onClickListener for the back button from OTP form
    $("#btnBack").click(function(){
        // Goes back to login page
        window.location = 'login_account';
   });
});

function validateOTP() {
    otpRegex = /^[a-zA-Z0-9]{6}$/;
    var otp = document.getElementById("sixDigitOTP");
    var errorOTP = document.getElementById("errorOTP");
    var isOTPValid = true;
    if (otp.value == "" || otp.value == null || otpRegex.test(otp.value) == false)
    {
        otp.style.borderColor = "Red";
        errorOTP.innerHTML = "* Please enter a valid 6-character OTP";
        errorOTP.style.color = "Red";
        isOTPValid = false;
    }
    else {
        otp.style.borderColor = "Green";
        errorOTP.innerHTML = "";
        isOTPValid = true;
    }

    if (!isOTPValid) {
        //return false;
    } else {
        // Successful then move on
        $.ajax({ // ajax from php validation
            url: 'login_process.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'submitOTP', OTP: otp.value,
            },
            success: function (data) {
                if (data.success == true) {
                    alert(data.message);
                    window.location.href = "dashboard";
                } else {
                    alert(data.message);
                }
            },
            error: function (request, status, error) {
                console.log(request);
                alert(request.responseText);
            },
        });
    }
    return false;
}

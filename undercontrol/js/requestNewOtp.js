function checkOTPGenNumber(count) {
    if (count > 5) {
        alert("Sorry, you have exceed the number of OTP generations!");
        document.getElementById("regenOTP").disabled = true;
        $('#regenOTP').css('background-color', '#D1D1D1');
    }
}

function newOTP(id){
    $.ajax({ // ajax from php validation
        url: 'login_process.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
            action: 'genNewOTP', newgenID: id
        },
        success: function (data) {
            if (data.success == false) {
                alert(data.message);
            } else {
                if (data.number_of_otp > 5) {
                    alert("Sorry, you have exceed the number of OTP generations!");
                    document.getElementById("regenOTP").disabled = true;
                    $('#regenOTP').css('background-color', '#D1D1D1');
                } else {
                    alert(data.message);
                }
            }
        },
        error: function (request, status, error) {
            console.log(request);
            alert(request.responseText);
            alert(status);
        },
        async: false
    });
    return false;
}
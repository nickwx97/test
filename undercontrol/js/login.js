function validateLogin() {
    var loginSRP = {
        srp: new srp(),

        loginPhase1: function(email, password){
            var me = this;

            var a = this.srp.getRandomSeed();
            var A = this.srp.generateA(a);
            var data = {phase: 1, I: email, A: A};
            $.ajax({
                url: "server.php",
                method: 'POST',
                dataType: 'json',
                data: data,
                success: function(res){
                    if(!res.success){
                        alert("User Not Found");
                        return;
                    }
                    var s = res.s;
                    var x = me.srp.generateX(s, email, password);
                    loginSRP.loginPhase2(a, A, res.B, x, email, password);
                }
            });
        },

        loginPhase2: function(a, A, B, x, email, password){
            var me = this;

            var S = me.srp.generateS_Client(A, B, a, x);
            var M1  = me.srp.generateM1(A, B, S);
            var data = {phase: 2, M1: M1};
             $.ajax({
                url: "server.php",
                method: 'POST',
                dataType: 'json',
                data: data,
                success: function(res){

                    if(!res.success){
                        alert("Password Wrong");
                        return;
                    }
                    var M2 = res.M2;

                    var M2_check = me.srp.generateM2(A, M1, S);

                    if(M2 == M2_check){
                        alert("Server Verification Complete");
                        $.ajax({ // ajax from php validation
                            url: 'login_process.php',
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                action: 'login_account', email: email, password: password
                            },
                            success: function (data) {
                                if (data.success == true) {
                                    window.location.href = "login_otp";
                                } else {
                                    alert(data.message);
                                }
                            },
                            error: function (request, status, error) {
                                console.log(request);
                                alert(status);
                            },
                            async: false
                        });
                    } else {
                        alert("Server Not Verficated");
                    }
                }
            })
        }

    };

    var emailRegex = /^\w+@\w+([\.-]?\w+)*(\.\w{3})+$/;
    var passRegex = /^[a-zA-Z0-9_]{8,16}$/;
    var loginEmail = document.getElementById("loginEmail");
    var loginEmailValid = false;
    var loginPassword = document.getElementById("loginPassword");
    var loginPassValid = false;
    var errorMsgEmail = document.getElementById("errorLogEmail");
    var errorMsgPassword = document.getElementById("errorLogPass");

    if (loginEmail.value == "" || loginEmail.value == null || emailRegex.test(loginEmail.value) == false) {
        loginEmail.style.borderColor = "Red";
        errorMsgEmail.innerHTML = "* Please enter a valid email address";
        errorMsgEmail.style.color = "Red";
        loginEmailValid = false;
    }
    else {
        loginEmail.style.borderColor = "Green";
        errorMsgEmail.innerHTML = "";
        loginEmailValid = true;
    }

    if (loginPassword.value == "" || loginPassword.value == null || passRegex.test(loginPassword.value) == false) {
        loginPassword.style.borderColor = "Red";
        errorMsgPassword.innerHTML = "* Please enter a valid password";
        errorMsgPassword.style.color = "Red";
        loginPassValid = false;
    }
    else {
        loginPassword.style.borderColor = "Green";
        errorMsgPassword.innerHTML = "";
        loginPassValid = true;
    }

    if (loginEmailValid && loginPassValid == false || loginEmailValid == false || loginPassValid == false) {
        return false;
    }
    else {
        loginSRP.loginPhase1(loginEmail.value, loginPassword.value);
        return false;
    }
}

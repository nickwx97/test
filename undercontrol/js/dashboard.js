$(document).ready(function(){
    $("#information").click(function(){
        $("#table1").show();
        $('#information').css('background-color', '#D1D1D1');
        $('#information').css('font-weight', 'bold');
        $("#table2").hide();
        $('#feedback').css('background-color', '');
        $('#feedback').css('font-weight', 'normal');
    });

    $("#feedback").click(function(){
        $("#table1").hide();
        $('#information').css('background-color', '');
        $('#information').css('font-weight', 'normal');
        $("#table2").show();
        $('#feedback').css('background-color', '#D1D1D1');
        $('#feedback').css('font-weight', 'bold');
    });

    // Attach OnClickListener for Add Employee Modal
    $("#btnAddEmployee").click(function(){
        // Display modal popup
        $('#addEmployeeModal').modal('show');
    });

    // Attach OnClickListener for Update Employee Modal
    $("#btnUpdateEmployee").click(function(){
        // Display modal popup
        $('#updateEmployeeModal').modal('show');
    });

});

function validateUserPrivilege() {
    minimal_user_privilege = 1;
    $.ajax({
        // ajax from PHP validation
        url: 'user_privilege_process.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
            action: 'retrieveUserPrivilege'
        },
        success: function (data) {
            if (data.success == true) {
                // Programatically retrieve user privilege back from session and edit page accordingly

                // Limit the minimal user privilege to display feedback table only
                if (data.user_privilege == minimal_user_privilege) {
                    $('#information').hide();
                    $('#btnAddEmployee').hide();
                    $('#feedback').css('background-color', '#D1D1D1');
                    $('#feedback').css('font-weight', 'bold');
                    $('#table1').hide();
                } else {
                    $('#information').css('background-color', '#D1D1D1');
                    $('#information').css('font-weight', 'bold');
                    $('#table2').hide();
                }
            } else {
                window.location.href = "login_account";
            }
        },
        error: function (request, status, error) {
            console.log(request);
            alert(status);
        },
        async: false
    });

}

function updateAccount(id){
    $('#updateEmployeeModal'+id).modal('show');
}

function delAccount(id){
    $('#deleteEmployeeConfirmationModal'+id).modal('show');
}

function delFeedback(id){
    $('#deleteFeedbackConfirmationModal'+id).modal('show');
}
function respondFeedback(id){
    $('#respondFeedbackModal'+id).modal('show');
}

$("#btnUpdateMyAccount").click(function(){
    $('#updateMyAccountModal').modal('show');
});
function login()
{
    window.location = "login_account";
    return false;
}
function dashboard()
{
    window.location = "dashboard";
    return false;
}
function logout()
{
    window.location = "logout.php";
    return false;
}

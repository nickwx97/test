<?php 
use PHPUnit\Framework\TestCase;
include_once('./login_process.php');
include_once('./token_process.php');
include_once('./account_process.php');
include_once('./update_account_process.php');

// Testing in accordance to WSTG-BUSL-02/WSTG-ATHZ-03 & WSTG-BUSL-03
// Dependant on the database values inside
class BusinessLogicTest extends TestCase {
    // Testing in accordance to WSTG-ATHZ-03
    // Test if OTP is generated to valid user
    public function testGenerateTOTPIfAccountExists() {
        $this->assertEquals(true, generateSecretKeyForTest(59));
    }

    // Test if OTP is not generated to valid user
    public function testGenerateTOTPIfAccountDoesNotExists() {
        $this->assertEquals(false, generateSecretKeyForTest(-1));
    }

    // Test if the unique Token is generated to the authorised user for them to reset password 
    public function testGenerateTokenIfAccountExists() {
        $this->assertEquals(true, generateTokenForTest(59));
    }

    // Test if the unique Token is generated to the unauthorised user for them to reset password 
    public function testGenerateTokenIfAccountDoesNotExists() {
        $this->assertEquals(false, generateTokenForTest(-1));
    }

    // WSTG-BUSL-02/WSTG-ATHZ-03
    // Test if users are logged in as Manager, they can add employee
    public function testLoggedinAsManagerAddEmployee()
    {
        // Simulate if user logged in as Manager
        $_SESSION['user_privilege'] = 2;
        $add_account_info = new Account("Test New Employee", "testEmployee", "testEmployee@gmail.com", 1);
        $this->assertEquals(true, test_add_employee(1, $add_account_info));
    }

    // Test if users are logged in as employee, they cannot add employee
    public function testLoggedinAsEmployeeAddEmployee()
    {
        // Simulate if user logged in as Employee
        $_SESSION['user_privilege'] = 1;
        $add_account_info = new Account("Test New Employee", "testEmployee", "testEmployee@gmail.com", 1);
        $this->assertEquals(false, test_add_employee(1, $add_account_info));
    }

    // Test if users are logged in as Manager, they can update employee
    public function testLoggedinAsManagerUpdateEmployee()
    {
        // Simulate if user logged in as Manager
        $_SESSION['user_privilege'] = 2;
        $update_account_info = new Account("Update New Employee", "updEmployee", "updateEmployee@gmail.com", 1);
        $this->assertEquals(true, test_update_employee(1, $update_account_info));
    }

    // Test if users are logged in as Employee, they cannot update employee
    public function testLoggedinAsEmployeeUpdateEmployee()
    {
        // Simulate if user logged in as Employee
        $_SESSION['user_privilege'] = 1;
        $update_account_info = new Account("Update New Employee", "updEmployee", "updateEmployee@gmail.com", 1);
        $this->assertEquals(false, test_update_employee(1, $update_account_info));
    }
    
    // Test if users are logged in as Manager they can delete employee
    public function testLoggedinAsManagerDeleteEmployee()
    {
        // Simulate if user logged in as Manager
        $_SESSION['user_privilege'] = 2;
        $this->assertEquals(true, test_delete_employee(1));
    }

    // Test if users are logged in as Employee they cannot delete employee
    public function testLoggedinAsEmployeeDeleteEmployee()
    {
        // Simulate if user logged in as Employee
        $_SESSION['user_privilege'] = 1;
        $this->assertEquals(false, test_delete_employee(1));
    }
    
    // Test if users are logged in their own account, they can update
    public function testCanUpdateOwnAccount()
    {
        // Simulate if user that is logged in as user_id of 1
        $_SESSION['user_id'] = 1;
        $update_account_info = new Account("Update Own Employee", "updOwnEmployee", "updateownEmployee@gmail.com", 1);
        $this->assertEquals(true, test_update_own_info(1, $update_account_info));
    }

    // Test if users are logged in to other people's account, they cannot update other people account
    public function testCannotUpdateOwnAccount()
    {
        // Simulate if user that is logged in as user_id of 2
        $_SESSION['user_id'] = 2;
        $update_account_info = new Account("Update Own Employee", "updOwnEmployee", "updateownEmployee@gmail.com", 1);
        $this->assertEquals(false, test_update_own_info(1, $update_account_info));
    }

    // WSTG-ATHZ-03
    // Test if users are logged in, they cannot change their own user types to gain more privilege from their account
    public function testCannotUpdateOwnRole()
    {
        // Simulate if user that is logged in as user_id of 1
        $_SESSION['user_id'] = 1;
        $_SESSION['user_privilege'] = 1;
        $update_account_info = new Account("Update Own Employee", "updOwnEmployee", "updateownEmployee@gmail.com", 2);
        $this->assertEquals(false, test_update_own_info(1, $update_account_info));
    }
}
?>
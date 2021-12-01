<?php 
use PHPUnit\Framework\TestCase;
include_once('./validation_input.php');

// Testing in accordance to WSTG-ATHN-07
class WeakPasswordTest extends TestCase {

    // Test valid password
    public function testPasswordValid() {
        $this->assertEquals(true,  is_password_valid("nerifyer12"));
    }
    
    // Test password less than 8 characters
    public function testPasswordLessThan8Char() {
        $this->assertEquals(false, is_password_valid("abc123")); 
    }

    // Test password containing at least 1 special character such as '@'
    public function testPasswordOneSpecialChar() {
        $this->assertEquals(false, is_password_valid("abcd@123")); 

    }

    // Test commonly used password 
    public function testPasswordCommonlyUsed() {
        $this->assertEquals(false, is_password_valid("abcd1234")); 
    }

    // Test password more than 16 characters
    public function testPasswordMoreThan16Char() {
        $this->assertEquals(false, is_password_valid("fireawayabcd12345")); 
    }
}
?>
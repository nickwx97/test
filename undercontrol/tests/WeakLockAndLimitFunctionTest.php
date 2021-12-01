<?php

use PHPUnit\Framework\TestCase;

include_once('./validation_input.php');
include_once('./login_process.php');

// Testing in accordance to WSTG-ATHN-03 & WSTG-BUSL-05 
class WeakLockAndLimitFunctionTest extends TestCase
{
	// Test if users are eligible to generate OTP if they have not generated OTP yet
	public function testGenerateOTPIfZeroOTPGenerated()
	{
		$_SESSION['regenOTPcount'] = 0;
        $this->assertEquals(true, checkIfEligibleToGenerateOTP($_SESSION['regenOTPcount']));
	}

    // Test if users are eligible to generate OTP if they have generated an OTP yet
	public function testGenerateOTPIfOneOTPGenerated()
	{
		$_SESSION['regenOTPcount'] = 1;
        $this->assertEquals(true, checkIfEligibleToGenerateOTP($_SESSION['regenOTPcount']));
	}

    // Test if users are eligible to generate OTP if they have generated four OTPs already
	public function testGenerateOTPIfFourOTPGenerated()
	{
		$_SESSION['regenOTPcount'] = 4;
        $this->assertEquals(true, checkIfEligibleToGenerateOTP($_SESSION['regenOTPcount']));
	}

    // Test if users are ineligible to generate any new OTP if they have generated five OTPs already
	public function testGenerateOTPIfFiveOTPGenerated()
	{
		$_SESSION['regenOTPcount'] = 5;
        $this->assertEquals(false, checkIfEligibleToGenerateOTP($_SESSION['regenOTPcount']));
	}
}
?>
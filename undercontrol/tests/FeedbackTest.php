<?php

use PHPUnit\Framework\TestCase;

include_once('./validation_input.php');
include_once('./feedback_process.php');

// Testing in accordance to WSTG-BUSL-01 & WSTG-BUSL-05
class FeedbackTest extends TestCase
{

	// Test valid feedback entered is within 200 characters and feedback content is within 2000 characters)
	public function testFeedbackAndSubjectValid()
	{
		$feedback = generateRandomString(2000);
		$subject = generateRandomString(200);
		$this->assertEquals(true, (is_message_valid($feedback) && is_subject_valid($subject)));
	}

	// Test feedback subject entered is 201 characters and feedback content is within 2000 characters
	public function testFeedback2000AndSubject201Char()
	{
		$feedback = generateRandomString(2000);
		$subject = generateRandomString(201);
		$this->assertEquals(false, (is_message_valid($feedback) && is_subject_valid($subject)));
	}

	// feedback subject entered is 201 characters and feedback content is within 2000 characters
	public function testFeedback2001AndSubject200Char()
	{
		$feedback = generateRandomString(2001);
		$subject = generateRandomString(200);
		$this->assertEquals(false, (is_message_valid($feedback) && is_subject_valid($subject)));
	}
	
	// feedback subject entered is 201 characters and feedback content is within 2000 characters
	public function testFeedback2001AndSubject2001Char()
	{
		$feedback = generateRandomString(2001);
		$subject = generateRandomString(201);
		$this->assertEquals(false, (is_message_valid($feedback) && is_subject_valid($subject)));
	}

	// Test if users ar currently sends less than 20 feedback entries on a particular day
	public function testFeedbackBelow20TimesDaily()
	{
		$this->assertEquals(true, (check_feedback_eligiblity(1)));
	}

	// Test if users ar currently sends more than 20 feedback entries on a particular day
	public function testFeedbackAbove20TimesDaily()
	{
		$feedback_length = 0;
		// Simulate users enter feedback 20 times already
		for ($i = 0; $i <= 20; $i++) {
			$feedback_length = $feedback_length + 1;
		}
		$this->assertEquals(false, (check_feedback_eligiblity($feedback_length)));
	}
}
?>
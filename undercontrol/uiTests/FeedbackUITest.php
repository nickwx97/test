<?php
require 'vendor/autoload.php';
require_once './feedback_process.php';
require_once './validation_input.php';


use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverSelect;
use Facebook\WebDriver\WebDriverBy;

// Testing in accordance to WSTG-CONF-07
class FeedbackUITest extends TestCase
{
    protected $webDriver;

    public function build_chrome_capabilities()
    {
        $options = new ChromeOptions();
        $options->addArguments(["--headless", "--no-sandbox", "--disable-gpu"]);
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        $capabilities->setPlatform("Linux");
        return $capabilities;
    }

    public function setUp(): void
    {
        $capabilities = $this->build_chrome_capabilities();
        /* Download the Selenium Server 3.141.59 from
    	https://selenium-release.storage.googleapis.com/3.141/selenium-server-standalone-3.141.59.jar
    	*/
        $this->webDriver = RemoteWebDriver::create('http://127.0.0.1:4444/wd/hub', $capabilities);
    }

    public function tearDown(): void
    {
        $this->webDriver->quit();
    }
    /*
  	* @test
  	*/
    public function testFeedbackForm()
    {
        $this->webDriver->get("https://undercontrol.sitict.net/index.php");
        $this->webDriver->manage()->window()->maximize();

        $dbMapperFactory = new DBMapperFactory();

        sleep(10);

        /* Initialising form widgets */
        $subject_textbox = $this->webDriver->findElement(WebDriverBy::id("subject"));
        $fullname_textbox = $this->webDriver->findElement(WebDriverBy::id("fullname"));
        $countryCode_dropdown = $this->webDriver->findElement(WebDriverBy::id("ddCountryCode"));
        $moblieno_textbox = $this->webDriver->findElement(WebDriverBy::id("mobile_no"));
        $email_textbox = $this->webDriver->findElement(WebDriverBy::id("email"));
        $feedbackType_dropdown = $this->webDriver->findElement(WebDriverBy::id("ddFeedbackType"));
        $msg_textbox = $this->webDriver->findElement(WebDriverBy::id("message"));

        // https://stackoverflow.com/questions/69734802/how-to-type-some-text-in-hidden-field-in-selenium-webdriver-using-php
        $subject_input = 'Testing';
        $this->webDriver->executeScript("document.querySelector('input[id=\"subject\"]').value='".$subject_input."';");

        $fullname_input = 'David Beckham';
        $this->webDriver->executeScript("document.querySelector('input[id=\"fullname\"]').value='".$fullname_input."';");

        $countryCode_select = new WebDriverSelect($countryCode_dropdown);
        $countryCode_select->selectByValue('+65');

        $mobile_no_input = '98765432';
        $this->webDriver->executeScript("document.querySelector('input[id=\"mobile_no\"]').value='".$mobile_no_input."';");

        $email_input = 'david_beckham@gmail.com';
        $this->webDriver->executeScript("document.querySelector('input[id=\"email\"]').value='".$email_input."';");

        $feedbacktype_select = new WebDriverSelect($feedbackType_dropdown);
        $feedbacktype_select->selectByValue('Feedback');

        $message_input = 'This is David Beckham, would like to feedback...';
        $this->webDriver->executeScript("document.querySelector('textarea[id=\"message\"]').value='".$message_input."';");

        /* Retrieve the values */
        $subject = $subject_textbox->getAttribute('value');
        $fullname = $fullname_textbox->getAttribute('value');
        $ddCountryCode = $countryCode_dropdown->getAttribute('value');
        $mobile_no = $moblieno_textbox->getAttribute('value');
        $email = $email_textbox->getAttribute('value');
        $ddFeedbackType = $feedbackType_dropdown->getAttribute('value');
        $message = $msg_textbox->getAttribute('value');

        $feedback_mapper_instance = $dbMapperFactory->createMapperInstance("Feedback");
        $conn = $feedback_mapper_instance->readFile("./config.txt");

        // Set default timezone to Singapore (GMT +8)
        date_default_timezone_set('Asia/Singapore');
        $current_datetime = date('Y-m-d');
        $limitCountEmail = $feedback_mapper_instance->readCountLimitFromDB($conn, "Feedback",$email,$current_datetime); // limit the insert to 20

        $new_feedback = new Feedback(sanitize_input($subject), sanitize_input($fullname), sanitize_input($ddCountryCode), sanitize_input($mobile_no), sanitize_input($email), sanitize_input($ddFeedbackType), sanitize_input($message));

        if (is_subject_valid($subject) and is_fullname_valid($fullname) and is_mobile_valid($mobile_no) and is_email_valid($email) and is_message_valid($message) and $limitCountEmail <= 20) {
            // Returns boolean success depending on whether query is successful
            $feedback_mapper_instance = $dbMapperFactory->createMapperInstance("Feedback");
            $conn = $feedback_mapper_instance->readFile("./config.txt");
            $success = $feedback_mapper_instance->insertRowToDB($conn, "Feedback", $new_feedback);
            // If users have added successfully for a feedback
            $this->assertEquals(true, $success);
        } else {
            // Always returns false should PHP validation fails
            $success = false;
            // If users have added successfully for a feedback
            $this->assertEquals(false, $success);
        }

        // Test for fail to add feedback due to any one of the fields having invalid syntax
        $mobile_no_input = 'xxxxxxxx';
        $this->webDriver->executeScript("document.querySelector('input[id=\"mobile_no\"]').value='".$mobile_no_input."';");

        /* Retrieve the values */
        $message = $msg_textbox->getAttribute('value');
        $mobile_no = $moblieno_textbox->getAttribute('value');
 
        $new_feedback = new Feedback(sanitize_input($subject), sanitize_input($fullname), sanitize_input($ddCountryCode), sanitize_input($mobile_no), sanitize_input($email), sanitize_input($ddFeedbackType), sanitize_input($message));
 
        if (is_subject_valid($subject) and is_fullname_valid($fullname) and is_mobile_valid($mobile_no) and is_email_valid($email) and is_message_valid($message) and $limitCountEmail <= 20) {
            // Returns boolean success depending on whether query is successful
            $feedback_mapper_instance = $dbMapperFactory->createMapperInstance("Feedback");
            $conn = $feedback_mapper_instance->readFile("./config.txt");
            $success = $feedback_mapper_instance->insertRowToDB($conn, "Feedback", $new_feedback);
        } else {
            // Always returns false should PHP validation fails
            $success = false;
        }
        // If users have not added successfully for a feedback
        $this->assertEquals(false, $success);
    }
}
?>

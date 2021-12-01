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
use Facebook\WebDriver\WebDriverKeys;

// Testing in accordance to WSTG-CONF-07
class FeedbackUIXSSTest extends TestCase
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

        sleep(10);

        /* Initialising form widgets */
        $countryCode_dropdown = $this->webDriver->findElement(WebDriverBy::id("ddCountryCode"));
        $feedbackType_dropdown = $this->webDriver->findElement(WebDriverBy::id("ddFeedbackType"));

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

        $message_input = '<script>alert(\"This is a test on the XSS alert triggering\")</script>';
        $this->webDriver->executeScript("document.querySelector('textarea[id=\"message\"]').value='".$message_input."';");

        $this->webDriver->executeScript("document.querySelector('input[id=\"submitFB\"]').click()");

        try {
            if ($this->webDriver->switchTo()->alert()->getText() == "This is a test on the XSS alert triggering") {
                $xss_success = true;
            } else {
                // If users do not detect the alert
                $xss_success = false;
            }
        } catch (Exception\NoSuchElementException $e) {
            return false;
        }
        // If users do not detect the alert
        $this->assertEquals(false, $xss_success);
    }
}
?>

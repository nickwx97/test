<?php
require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Firefox\FirefoxDriver;
use Facebook\WebDriver\Firefox\FirefoxProfile;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

// Testing in accordance to WSTG-CONF-07
class HTTPStrictTransportTest extends TestCase
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
	public function testStrictTransport()
	{
		$this->webDriver->get("http://undercontrol.sitict.net/");
		$this->assertEquals(true, substr($this->webDriver->getCurrentURL(), 0, 5) == "https");
	}
}
?>
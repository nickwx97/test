<?php
use PHPUnit\Framework\TestCase;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class GoogleAuthenticatorTest extends TestCase
{
    /**
     * @var \Sonata\GoogleAuthenticator\GoogleAuthenticator
     */
    protected $helper;

    protected function setUp(): void
    {
        $this->helper = new GoogleAuthenticator();
    }

    public function testGenerateSecret(): void
    {
        static::assertSame(
            16,
            \strlen($this->helper->generateSecret())
        );
    }

    /**
     * @group legacy
     * @expectedDeprecation Passing anything other than null or a DateTimeInterface to $time is deprecated as of 2.0 and will not be possible as of 3.0.
     * @dataProvider testCheckCodeData
     */
    public function testCheckCodeWithLegacyArguments($expectation, $inputDate): void
    {
        $authenticator = new GoogleAuthenticator(6, 10, new \DateTime('2012-03-17 22:17:00'), 30);
        static::assertSame(
            $expectation,
            $authenticator->checkCode('3DHTQX4GCRKHGS55CJ', $authenticator->getCode('3DHTQX4GCRKHGS55CJ', strtotime($inputDate) / 30))
        );
    }

    /**
     * @dataProvider testCheckCodeData
     */
    public function testCheckCode($expectation, $inputDate): void
    {
        $authenticator = new GoogleAuthenticator(6, 10, new \DateTime('2012-03-17 22:17:00'), 30);

        $datetime = new \DateTime($inputDate);
        static::assertSame(
            $expectation,
            $authenticator->checkCode('3DHTQX4GCRKHGS55CJ', $authenticator->getCode('3DHTQX4GCRKHGS55CJ', $datetime))
        );
    }

    /**
     * @dataProvider testCheckCodeDiscrepancyData
     */
    public function testCheckCodeDiscrepancy($expectation, $inputDate): void
    {
        $authenticator = new GoogleAuthenticator(6, 10, new \DateTime('2012-03-17 22:17:00'), 30);

        $datetime = new \DateTime($inputDate);
        static::assertSame(
            $expectation,
            $authenticator->checkCode('3DHTQX4GCRKHGS55CJ', $authenticator->getCode('3DHTQX4GCRKHGS55CJ', $datetime), 0)
        );
    }

    /**
     * @dataProvider testCheckCodeCustomPeriodData
     */
    public function testCheckCodeCustomPeriod($expectation, $inputDate): void
    {
        $authenticator = new GoogleAuthenticator(6, 10, new \DateTime('2012-03-17 22:17:00'), 300);

        $datetime = new \DateTime($inputDate);
        static::assertSame(
            $expectation,
            $authenticator->checkCode('3DHTQX4GCRKHGS55CJ', $authenticator->getCode('3DHTQX4GCRKHGS55CJ', $datetime))
        );
    }

    /**
     * @dataProvider testCheckCodeCustomPeriodDiscrepancyData
     */
    public function testCheckCodeCustomPeriodDiscrepancy($expectation, $inputDate): void
    {
        $authenticator = new GoogleAuthenticator(6, 10, new \DateTime('2012-03-17 22:17:00'), 300);

        $datetime = new \DateTime($inputDate);
        static::assertSame(
            $expectation,
            $authenticator->checkCode('3DHTQX4GCRKHGS55CJ', $authenticator->getCode('3DHTQX4GCRKHGS55CJ', $datetime), 0)
        );
    }

    /**
     * all dates compare to the same date + or - the several seconds compared
     * to 22:17:00 to verify if the code was perhaps the previous or next 30
     * seconds. This ensures that slow entries or time delays are not causing
     * problems.
     */
    public static function testCheckCodeData(): array
    {
        return [
            '1 second before valid interval' => [false, '2012-03-17 22:16:29'],
            'beginning of interval' => [true, '2012-03-17 22:16:30'],
            'same as code create time' => [true, '2012-03-17 22:17:00'],
            'end of interval' => [true, '2012-03-17 22:17:59'],
            '1 second after valid interval' => [false, '2012-03-17 22:18:00'],
        ];
    }

    public static function testCheckCodeDiscrepancyData(): array
    {
        return [
            '1 second before valid interval' => [false, '2012-03-17 22:16:59'],
            'beginning of interval' => [true, '2012-03-17 22:17:00'],
            'end of interval' => [true, '2012-03-17 22:17:29'],
            '1 second after valid interval' => [false, '2012-03-17 22:17:30'],
        ];
    }

    public static function testCheckCodeCustomPeriodData(): array
    {
        return [
            '1 second before valid interval' => [false, '2012-03-17 22:11:59'],
            'beginning of interval' => [true, '2012-03-17 22:12:00'],
            'same as code create time' => [true, '2012-03-17 22:17:00'],
            'end of interval' => [true, '2012-03-17 22:17:59'],
            '1 second after valid interval' => [false, '2012-03-17 22:18:00'],
        ];
    }

    public static function testCheckCodeCustomPeriodDiscrepancyData(): array
    {
        return [
            '1 second before valid interval' => [false, '2012-03-17 22:12:29'],
            'beginning of interval' => [true, '2012-03-17 22:12:30'],
            'end of interval' => [true, '2012-03-17 22:17:29'],
            '1 second after valid interval' => [false, '2012-03-17 22:17:30'],
        ];
    }

    public function testGetCodeReturnsDefinedLength(): void
    {
        $authenticator = new GoogleAuthenticator(8, 10, new \DateTime('2012-03-17 22:17:00'), 30);

        for ($a = 0; $a < 1000; ++$a) {
            static::assertSame(8, \strlen($authenticator->getCode($authenticator->generateSecret())));
        }
    }

    /**
     * @group legacy
     * @expectedDeprecation Using Sonata\GoogleAuthenticator\GoogleAuthenticator::getUrl() is deprecated as of 2.1 and will be removed in 3.0. Use Sonata\GoogleAuthenticator\GoogleQrUrl::generate() instead.
     */
    public function testGetUrlIssuer(): void
    {
        static::assertSame(
            'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=otpauth%3A%2F%2Ftotp%2Ffoo%40foobar.org%3Fsecret%3D3DHTQX4GCRKHGS55CJ&ecc=M%26issuer%3DFooBar',
            $this->helper->getUrl('foo', 'foobar.org', '3DHTQX4GCRKHGS55CJ', 'FooBar')
        );
    }

    /**
     * @group legacy
     * @expectedDeprecation Using Sonata\GoogleAuthenticator\GoogleAuthenticator::getUrl() is deprecated as of 2.1 and will be removed in 3.0. Use Sonata\GoogleAuthenticator\GoogleQrUrl::generate() instead.
     */
    public function testGetUrlNoIssuer(): void
    {
        static::assertSame(
            'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=otpauth%3A%2F%2Ftotp%2Ffoo%40foobar.org%3Fsecret%3D3DHTQX4GCRKHGS55CJ&ecc=M',
            $this->helper->getUrl('foo', 'foobar.org', '3DHTQX4GCRKHGS55CJ')
        );
    }
}
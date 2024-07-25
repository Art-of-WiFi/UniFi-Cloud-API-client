<?php

namespace UniFiCloudApiClient\Tests;

use PHPUnit\Framework\TestCase;
use UniFiCloudApiClient\Client\UniFiClient;

/**
 * initial PHPUnit test class for the UniFi API client
 *
 * @todo add more tests to mock API requests using Guzzle's MockHandler
 * @see https://unifi.ui.com/api
 * @see https://developer.ui.com/unifi-api/
 *
 * @package UniFi_Cloud_API_Client_Class
 * @author  Art of WiFi, info@artofwifi.net
 * @version see VERSION in UniFiClient.php or use the UniFiClient::getVersion() method
 * @license This class is subject to the MIT license bundled with this package in the file LICENSE.md
 */
class UniFiClientTest extends TestCase
{
    /**
     * update this value when the version of the UniFi API client changes
     */
    const VERSION = '1.0.1';

    /**
     * constants for the API key and URI
     */
    const API_KEY = '1234567890';
    const URI = 'https://mock_api.com';

    private ?UniFiClient $sut;

    protected function setUp(): void
    {
        $this->sut = UniFiClient::getInstance(self::API_KEY, self::URI);
    }

    public function test_get_instance()
    {
        $this->assertInstanceOf(
            UniFiClient::class,
            $this->sut,
            'Test failed asserting the correct instance of UniFiCloudApiClient\Client\UniFiClient'
        );
    }

    public function test_version()
    {
        $this->assertEquals(self::VERSION, $this->sut->getVersion());
    }

    public function test_debug_mode()
    {
        $this->sut->setDebug(true);
        $this->assertTrue($this->sut->getDebug());

        $this->sut->setDebug(false);
        $this->assertFalse($this->sut->getDebug());
    }

    public function test_timeout()
    {
        $this->sut->setTimeout(20);
        $this->assertEquals(20, $this->sut->getTimeout());
    }
}
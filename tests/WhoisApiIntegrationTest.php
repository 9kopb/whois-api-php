<?php

namespace whoisServerList;

/**
 * A Whois API integration test.
 *
 * Please provide the environment variable API_KEY.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link https://market.mashape.com/malkusch/whois
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class WhoisApiIntegrationTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var WhoisApi the SUT
     */
    private $api;
    
    protected function setUp()
    {
        parent::setUp();
        
        $key = getenv("API_KEY");
        if (empty($key)) {
            $this->markTestIncomplete("Please provide the API key with the environment variable API_KEY.");
            return;
        }
        
        $this->api = new WhoisApi($key);
    }
    
    /**
     * @test
     */
    public function isAvailableShouldReturnTrue()
    {
        $this->assertFalse($this->api->isAvailable("example.net"));
    }
    
    /**
     * @test
     */
    public function isAvailableShouldReturnFalse()
    {
        $this->assertTrue($this->api->isAvailable("dsfsdfsdfsdfdsfsdfdsfsdfdsfdssdfse.net"));
    }
    
    /**
     * @test
     */
    public function isAvailableShouldFailForUnknownTLD()
    {
        $this->expectException(WhoisApiException::class);
        $this->api->isAvailable("invalid");
    }
    
    /**
     * @test
     */
    public function whoisShouldReturnString()
    {
        $response = $this->api->whois("example.net");
        $this->assertNotEmpty($response);
    }
    
    /**
     * @test
     */
    public function whoisShouldFailForUnknownTLD()
    {
        $this->expectException(WhoisApiException::class);
        $this->api->whois("invalid");
    }
    
    /**
     * @test
     */
    public function queryShouldReturnString()
    {
        $response = $this->api->query("whois.nic.de", "example.net");
        $this->assertNotEmpty($response);
    }
    
    /**
     * @test
     */
    public function queryShouldFailForWrongHost()
    {
        $this->expectException(WhoisApiException::class);
        $this->api->query("invalid", "example.net");
    }
    
    /**
     * @test
     */
    public function domainsShouldIncludKnownTLDs()
    {
        $domains = $this->api->domains();
        $this->assertTrue(in_array("de", $domains));
        $this->assertTrue(in_array("com", $domains));
        $this->assertTrue(in_array("net", $domains));
    }
    
    /**
     * @test
     */
    public function areAvailableShouldReturnMap()
    {
        $result = $this->api->areAvailable(
            ["example.net", "dsfsdfsdfsdfdsfsdfdsfsdfdsfdssdfse.net", "invalid", "example.com"]
        );
        
        $this->assertEquals(
            [
                "example.net" => false,
                "dsfsdfsdfsdfdsfsdfdsfsdfdsfdssdfse.net" => true,
                "invalid" => null,
                "example.com" => false
            ],
            $result
        );
    }
}

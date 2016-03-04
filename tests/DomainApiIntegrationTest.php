<?php

namespace whoisServerList;

/**
 * A Domain API integration test.
 *
 * Please provide the environment variable API_KEY and API_PASSWORD.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link https://alpha.domaininformation.de/
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class DomainApiIntegrationTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @var DomainApi the SUT
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
        $password = getenv("API_PASSWORD");
        if (empty($password)) {
            $this->markTestIncomplete("Please provide the API key with the environment variable API_PASSWORD.");
            return;
        }
        
        $this->api = new DomainApi($key, $password);
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
        $this->expectException(DomainApiException::class);
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
        $this->expectException(DomainApiException::class);
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
        $this->expectException(DomainApiException::class);
        $this->api->query("invalid", "example.net");
    }
}

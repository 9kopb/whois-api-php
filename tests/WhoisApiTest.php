<?php

namespace whoisServerList;

/**
 * A Whois API test.
 *
 * @author  Markus Malkusch <markus@malkusch.de>
 * @link    http://whois-api.domaininformation.de/
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class WhoisApiTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @dataProvider provideBuildingShouldFailWithInvalidParameters
     * @test
     */
    public function buildingShouldFailWithInvalidParameters($apiKey, $endpoint)
    {
        $this->expectException(\InvalidArgumentException::class);
        new WhoisApi($apiKey, $endpoint);
    }
    
    public function provideBuildingShouldFailWithInvalidParameters()
    {
        return [
            [null, "http://example.org"],
            ["", "http://example.org"],
            ["valid", null],
            ["valid", ""],
        ];
    }
    
    /**
     * @dataProvider provideInvalidDomain
     * @test
     */
    public function isAvailableShoudFailWithInvalidParameters($domain)
    {
        $this->expectException(\InvalidArgumentException::class);
        $api = new WhoisApi("key");
        $api->isAvailable($domain);
    }
    
    /**
     * @dataProvider provideInvalidDomain
     * @test
     */
    public function whoisShoudFailWithInvalidParameters($domain)
    {
        $this->expectException(\InvalidArgumentException::class);
        $api = new WhoisApi("key");
        $api->whois($domain);
    }
    
    public function provideInvalidDomain()
    {
        return [
            [null],
            [""],
        ];
    }
    
    /**
     * @dataProvider provideQueryShoudFailWithInvalidParameters
     * @test
     */
    public function queryShoudFailWithInvalidParameters($host, $query)
    {
        $this->expectException(\InvalidArgumentException::class);
        $api = new WhoisApi("key");
        $api->query($host, $query);
    }
    
    public function provideQueryShoudFailWithInvalidParameters()
    {
        return [
            [null, "example.net"],
            ["", "example.net"],
            ["whois.example.net", null],
            ["whois.example.net", ""],
        ];
    }
}

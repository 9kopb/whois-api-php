<?php

namespace whoisServerList;

/**
 * A Domain API test.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link https://alpha.domaininformation.de/
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class DomainApiTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * @dataProvider provideBuildingShouldFailWithInvalidParameters
     * @test
     */
    public function buildingShouldFailWithInvalidParameters($apiKey, $password, $endpoint)
    {
        $this->expectException(\InvalidArgumentException::class);
        new DomainApi($apiKey, $password, $endpoint);
    }
    
    public function provideBuildingShouldFailWithInvalidParameters()
    {
        return [
            ["valid", null, "http://example.org"],
            ["valid", "", "http://example.org"],
            [null, "valid", "http://example.org"],
            ["", "valid", "http://example.org"],
            ["valid", "valid", null],
            ["valid", "valid", ""],
        ];
    }
    
    /**
     * @dataProvider provideInvalidDomain
     * @test
     */
    public function isAvailableShoudFailWithInvalidParameters($domain)
    {
        $this->expectException(\InvalidArgumentException::class);
        $api = new DomainApi("key", "pwd");
        $api->isAvailable($domain);
    }
    
    /**
     * @dataProvider provideInvalidDomain
     * @test
     */
    public function whoisShoudFailWithInvalidParameters($domain)
    {
        $this->expectException(\InvalidArgumentException::class);
        $api = new DomainApi("key", "pwd");
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
        $api = new DomainApi("key", "pwd");
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

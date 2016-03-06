<?php

namespace whoisServerList;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;

/**
 * A Whois API.
 *
 * This is a client library for the service of https://market.mashape.com/malkusch/whois.
 * Register there to get an API key.
 *
 * With this API you can check if a domain name is available, get its
 * whois data or query an arbitrary whois server. The service is using
 * the whois list from https://github.com/whois-server-list/whois-server-list.
 * Also it avoids hitting any rate limits on the whois servers.
 *
 * Example:
 * <code>
 * use whoisServerList\WhoisApi;
 *
 * $whoisApi = new WhoisApi("apiKey");
 * echo $whoisApi->isAvailable("example.net") ? "available" : "registered";
 * </code>
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link https://market.mashape.com/malkusch/whois
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class WhoisApi
{

    /**
     * @var ClientInterface HTTP client
     */
    private $client;

    /**
     * Builds a Domain API.
     *
     * Register at https://market.mashape.com/malkusch/whois to get an API key.
     *
     * @param string $apiKey API key
     * @param string $endpoint optional endpoint, default is "https://whois-v0.p.mashape.com/".
     */
    public function __construct($apiKey, $endpoint = "https://whois-v0.p.mashape.com/")
    {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException("API key is empty.");
        }
        if (empty($endpoint)) {
            throw new \InvalidArgumentException("Endpoint is empty.");
        }

        $this->client = new Client([
            "base_uri" => $endpoint,
            "headers" => ["X-Mashape-Key" => $apiKey],
            "http_errors" => false,
        ]);
    }

    /**
     * Checks if a domain is available.
     *
     * If a domain is available (i.e. not registered) this method
     * will return true.
     *
     * @param string $domain domain name, e.g. "example.net"
     * @return bool true if the domain is available, false otherwise.
     *
     * @throws RecoverableWhoisApiException API failed, but you can try again.
     *      This can happen if the upstream whois server did not respond in time.
     * @throws WhoisApiException API failed
     */
    public function isAvailable($domain)
    {
        if (empty($domain)) {
            throw new \InvalidArgumentException("The domain is empty.");
        }
        
        $body = $this->sendRequest("check", ["domain" => $domain]);
        $result = json_decode($body);
        return $result->available;
    }

    /**
     * Returns the whois data for a domain.
     *
     * @param string $domain domain name, e.g. "example.net"
     * @return string response of the respective whois server
     *
     * @throws RecoverableWhoisApiException API failed, but you can try again.
     *      This can happen if the upstream whois server did not respond in time.
     * @throws WhoisApiException API failed
     */
    public function whois($domain)
    {
        if (empty($domain)) {
            throw new \InvalidArgumentException("The domain is empty.");
        }
        
        $body = $this->sendRequest("check", ["domain" => $domain]);
        $result = json_decode($body);
        return $result->whoisResponse;
    }
    
    /**
     * Queries a whois server.
     *
     * @param string $host hostname of the whois server, e.g. "whois.verisign-grs.com"
     * @param string $query query, e.g. "example.net"
     *
     * @return string response from the whois server
     *
     * @throws RecoverableWhoisApiException API failed, but you can try again.
     *      This can happen if the upstream whois server did not respond in time.
     * @throws WhoisApiException API failed
     */
    public function query($host, $query)
    {
        if (empty($host)) {
            throw new \InvalidArgumentException("The host is empty.");
        }
        if (empty($query)) {
            throw new \InvalidArgumentException("The query is empty.");
        }
        
        return $this->sendRequest("whois", ["host" => $host, "query" => $query]);
    }

    /**
     * Returns a list of all top and second level domains, which are
     * known to the Whois API.
     *
     * @return string[] all available top and second level domains.
     */
    public function domains()
    {
        $body = $this->sendRequest("domains");
        $result = json_decode($body);
        return $result;
    }
    
    /**
     * Sends a request and return the reponse body.
     *
     * @param string $path request path
     * @param string[] $parameters request paramters
     *
     * @return string response body
     *
     * @throws RecoverableWhoisApiException API failed, but you can try again.
     * @throws WhoisApiException API failed
     */
    private function sendRequest($path, array $parameters = [])
    {
        try {
            $response = $this->client->request("GET", $path, ["query" => $parameters]);

        } catch (\Exception $e) {
            throw new WhoisApiException("Transport failed.", 0, $e);
        }
            
        switch ($response->getStatusCode()) {

            case 200:
                return $response->getBody()->__toString();

            case 502:
            case 504:
                throw new RecoverableWhoisApiException(
                    "Try again: {$response->getBody()}",
                    $response->getStatusCode()
                );

            default:
                throw new WhoisApiException("API failed: {$response->getBody()}", $response->getStatusCode());
        }
    }
}

<?php

namespace whoisServerList;

/**
 * A recoverable Whois API exception.
 *
 * The cause of this exception is temporary. You should try the
 * causing call again.
 *
 * @author  Markus Malkusch <markus@malkusch.de>
 * @link    http://whois-api.domaininformation.de/ Whois API
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class RecoverableWhoisApiException extends WhoisApiException
{
    
}

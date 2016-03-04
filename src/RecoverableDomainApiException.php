<?php

namespace whoisServerList;

/**
 * A recoverable domain API exception.
 *
 * The cause of this exception is temporary. You should try the
 * causing call again.
 *
 * @author Markus Malkusch <markus@malkusch.de>
 * @link https://alpha.domaininformation.de/
 * @license http://www.wtfpl.net/txt/copying/ WTFPL
 */
class RecoverableDomainApiException extends DomainApiException
{
    
}

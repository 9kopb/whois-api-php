# Domain API

This is a client library for the [Domain API service](https://alpha.domaininformation.de/).
With this API you can

- Check if a domain name is available
- Get its whois data or query an arbitrary whois server
- Don't worry about rate limits on the respective whois server

The service supports all domains of the
[Whois Server list](https://github.com/whois-server-list/whois-server-list),
which is more than 500 top level domains.

# Installation

Use [Composer](https://getcomposer.org/):

```sh
composer require whois-server-list/domain-api
```

# Usage

You'll need an API key to use this library. Register at the
[Domain API service](https://alpha.domaininformation.de/) to get
an API key.

```php
$domainApi = new whoisServerList\DomainApi("apiKey", "apiPassword");
```

- [`DomainApi::isAvailable()`](http://whois-server-list.github.io/domain-api-php/api/class-whoisServerList.DomainApi.html#_isAvailable)
  checks if a domain name is available.
- [`DomainApi::whois()`](http://whois-server-list.github.io/domain-api-php/api/class-whoisServerList.DomainApi.html#_whois)
  returns the whois data of a domain.
- [`DomainApi::query()`](http://whois-server-list.github.io/domain-api-php/api/class-whoisServerList.DomainApi.html#_whois)
  queries an arbitrary whois server.

## Example

```php
$domainApi = new whoisServerList\DomainApi("apiKey", "apiPassword");
echo $domainApi->isAvailable("example.net") ? "available" : "registered";
```

# License and authors

This project is free and under the WTFPL.
Responsable for this project is Markus Malkusch markus@malkusch.de.

[![Build Status](https://travis-ci.org/whois-server-list/domain-api-php.svg?branch=master)](https://travis-ci.org/whois-server-list/domain-api-php)

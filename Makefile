# @configure_input@

doc: composer-update
	./vendor/bin/apigen generate

composer-clean:
	rm -rf vendor/whois-server-list/domain-api composer.lock

composer-update: composer-clean
	composer.phar update


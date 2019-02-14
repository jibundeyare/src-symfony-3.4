#!/bin/bash
# bin/tests.sh

php bin/console doctrine:database:drop --force --env=test
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --no-interaction --env=test
php bin/console doctrine:fixture:load --env=test --no-interaction
bin/phpunit


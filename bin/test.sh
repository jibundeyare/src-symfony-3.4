#!/bin/bash

# bin/tests.sh

php bin/console doctrine:fixture:load --env=test --purge-with-truncate --no-interaction
bin/phpunit


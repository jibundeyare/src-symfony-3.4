# Symfony 3.4

## Install

Clone and install required packages :

    git clone https://github.com/jibundeyare/src_symfony_3.4
    cd src_symfony_3.4
    composer install

Configure database access (change db_user and db_password to your needs) :

    echo "APP_ENV=dev" > .env.local
    echo "# APP_DEBUG=0" > .env.local
    echo "APP_SECRET=secret" >> .env.local
    echo "DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/src_symfony_3_4" >> .env.local

Create database :

    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate

Load required fixtures :

    php bin/console doctrine:fixtures:load --group=required

## Test fixtures

Load test fixtures :

    php bin/console doctrine:fixtures:load --group=test

## Deploy

Edit deploy.php and configure project repository and hosts.

Deploy to prod (change foo to your needs) :

    ssh_user=foo
    dep deploy:prepare # only the first time
    dep deploy:env # only the first time
    dep deploy
    dep deploy fixtures:load # only the first time

Deploy to test (change foo to your needs) :

    ssh_user=foo
    dep deploy:prepare test # only the first time
    dep deploy:env test # only the first time
    dep deploy test
    dep deploy fixtures:load # only the first time


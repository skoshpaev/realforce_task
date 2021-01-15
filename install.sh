cp docker/docker-compose-test.yml.example docker/docker-compose-test.yml
cp docker/docker-compose.yml.example docker/docker-compose.yml
cp docker/var.env.example docker/var.env
cp docker/var.env.example docker/var_test.env
cp realforce/.env realforce/.enc.local

docker network create realforce
alias dd='docker-compose -f docker/docker-compose.yml'
alias console='docker-compose -f docker/docker-compose.yml exec fpm realforce/bin/console'
dd up -d

dd exec fpm composer install --working-dir=realforce
dd exec fpm composer require doctrine/doctrine-fixtures-bundle symfony/phpunit-bridge --dev --working-dir=realforce

alias dd_test='docker-compose -f docker/docker-compose-test.yml'
dd_test up -d

dd_test exec fpm_test realforce/bin/phpunit realforce/tests --bootstrap=realforce/tests/bootstrap.php

dd_test down

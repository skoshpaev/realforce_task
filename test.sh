docker network create realforce_test
cp docker/docker-compose-test.yml.example docker/docker-compose-test.yml
cp docker/var.env.example docker/var_test.env
cp realforce/.env.test realforce/.env.test.local

alias dd_test='docker-compose -f docker/docker-compose-test.yml'
dd_test up -d

dd_test exec fpm_test realforce/bin/phpunit realforce/tests --bootstrap=realforce/tests/bootstrap.php

dd_test down

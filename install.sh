cp docker/docker-compose-test.yml.example docker/docker-compose-test.yml
cp docker/docker-compose.yml.example docker/docker-compose.yml
cp docker/var.env.example docker/var.env
cp docker/var.env.example docker/var_test.env
cp realforce/.env realforce/.enc.local

docker network create realforce
alias dd='docker-compose -f docker/docker-compose.yml'
alias console='docker-compose -f docker/docker-compose.yml exec fpm realforce/bin/console'
dd up -d

sleep 20

dd exec fpm composer install --working-dir=realforce
dd exec fpm composer require doctrine/doctrine-fixtures-bundle symfony/phpunit-bridge --dev --working-dir=realforce

console doctrine:migrations:migrate -n
console doctrine:fixtures:load --append

alias dd_test='docker-compose -f docker/docker-compose-test.yml'
dd_test up -d

sleep 20

console doctrine:migrations:migrate -n --env=test
console doctrine:fixtures:load --env=test --append

dd exec fpm realforce/bin/phpunit

console --env=test doctrine:schema:drop --full-database --force -n
dd_test down

console about

# les commandes necessaires:
composer require jms/serializer
composer require friendsofsymfony/rest-bundle
symfony console make:user
php bin/console doctrine:schema:update --force
composer require lexik/jwt-authentication-bundle
php bin/console lexik:jwt:generate-keypair

php bin/console make:controller --no-template ApiRegistration
php bin/console make:controller --no-template User-Dashboard








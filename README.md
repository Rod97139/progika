Changer le mailer dsn dans .env

composer require symfonycasts/verify-email-bundle

composer require symfonycasts/reset-password-bundle

composer require symfony/google-mailer

faire un mailer Service

test email ==> php bin/console mailer:test fayaflame@gmail.com

symfony console make:auth

symfony console make:registration-form

symfony console make:reset-password

fixtures ==> ```composer require --dev orm-fixtures`
            ```php bin/console doctrine:fixtures:load --append`
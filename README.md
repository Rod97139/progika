##Installation : 

- `composer install` `yarn install` `yarn watch`

- `symfony console d:d:c` `symfony console make migration` `php bin/console doctrine:migrations:migrate`

- Mettre les table region et departement dans l'ordre avec en premier l'id puis region_code et ensuite "code, name, slug" et importer le fichier sql, qui se trouve dans le dossier sql, dans phpmyadmin ou autre

- importer les fixtures avec la commande `php bin/console doctrine:fixtures:load --append`

- enjoy




Changer le mailer dsn dans .env

composer require symfonycasts/verify-email-bundle

composer require symfonycasts/reset-password-bundle

composer require symfony/google-mailer

faire un mailer Service

test email ==> php bin/console mailer:test adressemail@gmail.com

symfony console make:auth

symfony console make:registration-form

symfony console make:reset-password

fixtures ==> `composer require --dev orm-fixtures`

 --append evite la purge ==> `php bin/console doctrine:fixtures:load --append`

 selectionner un group seulement `php bin/console doctrine:fixtures:load --group=LodgingFixtures --append`

 Recherche Map, prix en fonction des semaines de l'année
 #[isgranted],
 
 
  Services et Event
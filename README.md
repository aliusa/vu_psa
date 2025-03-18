# Inerneto tiekėjo informacinė sistema ITIS

Symfony 7.2  
Easyadmin Admin bundle 4

### Reikalavimai:  
* PHP 8.2  
* MariaDB 10.10 - 11.8

## Scripts
```shell
chmod -R 0755 config/ src/ var/ public/
chown -R $(pc_user) bin/ migrations/ config/ src/ var/ public/
chmod -R 0777 assets/ uploads/ vendor/ var/ public/ importmap.php

composer update
composer validate
php bin/console cache:clear --env=dev && php bin/console cache:clear --env=prod
php bin/console assets:install
php bin/console importmap:install
php bin/console asset-map:compile
php bin/console doctrine:schema:update --force
```

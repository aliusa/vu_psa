# Interneto tiekėjo informacinė sistema ITIS

Projektas VU "Programų sistemų architektūra" dalykui.

Sistema skirta vadybininkams valdyti klientus, jų paslaugas, peržiūrėti mokėjimus. Klientai gali matyti savo objektus, paslaugas tuose objektuose, sąskaitas, apmokėti už sąskaitas testiniu būdu arba per Paysera.  
Per web'ą galima užduot klausimus, vadybininkai gali atsakyti (jei registruotas - matys savo klausimus, atsakymus. Bet nėra, kad siūstų el.laiškų).  
Yra nuolaidos (pvz. pirmiems 3 mėnesiai X % nuolaida), jų skaičiavimas sąskaitose.  
Jeigu paslauga veikia ne nuo pirmos mėnesio dienos, o pvz nuo 15-os - sąskaitoje paskaičiuoja atitinkamai mažiau.  

Integracijos:
* Leaflet žemėlapiui
* Paysera

Symfony 7.2  
Easyadmin Admin bundle 4

### Reikalavimai:  
* PHP 8.2  
* MariaDB 10.10 - 11.8

### Problemos
Pirma prisijungiama per frontend'ą ir tik tada į TVS. Atvirkščiai bug'ovai.

## Scripts
### Fix permissions
```shell
chmod -R 0755 config/ src/ var/ public/
chown -R $(pc_user) bin/ migrations/ config/ src/ var/ public/
chmod -R 0777 assets/ uploads/ vendor/ var/ public/ importmap.php
```

### ...
```shell
composer update
composer validate
php bin/console cache:clear --env=dev && php bin/console cache:clear --env=prod
php bin/console assets:install
php bin/console importmap:install
php bin/console asset-map:compile
php bin/console doctrine:schema:update --force
```

### Unit testai
```shell
php bin/phpunit
```

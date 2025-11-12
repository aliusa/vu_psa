# 2. A
Points: 0.25
## Reikalavimai _(angl. Requirements)_
* Think of a business case and of a large enough IT system which will help solve business case
* Define stakeholders of IT system
* Create architectural description with context view of the system

# 2. B
Points: 1.5  
Bonus Points : Document all 7 views 0.25
## Reikalavimai _(angl. Requirements)_
* Document 5 chosen views in architectural description
* Choose views from view catalog
* Use viewpoints to document views

# 2. C
Points: 1  
Bonus Points: Use all 4 perspectives 0.25
## Reikalavimai _(angl. Requirements)_
* Adjust architectural description by using 2 different perspectives
* Choose perspectives from Security, Performance and scalability, Availability and resilience, Evolution perspectives


---
Architektūrinis aprašymas _(angl. Architectural description)_

# 1. Dokumento kontrolė ir įvadas _(angl. Document control (versioning))_
**Versija:** 1.0  
**Data:** 2025-11-09  
**Sistemos pavadinimas:** Interneto tiekėjo informacinė sistema (ITIS)

## 1.1. Santrauka vadovybei _(angl. Introduction to management summary (Executive summary))_
Interneto tiekėjo informacinė sistema (ITIS) skirta automatizuoti klientų duomenų, jiems priskirtų paslaugų, sąskaitų išrašymo ir apmokėjimo procesus. Ši sistema padeda interneto paslaugų tiekėjui centralizuotai valdyti klientus ir paslaugas, mažina administracinę naštą bei užtikrina duomenų saugumą.

**Pagrindiniai ITIS tikslai _(angl. Objectives of AD)_:**
* Automatizuoti sąskaitų išrašymo ir apmokėjimo procesą.
* Suteikti klientams prieigą prie jų duomenų per patogią naudotojo sąsają.
* Užtikrinti aukštą sistemos prieinamumą ir saugumą.
* Centralizuoti klientų ir paslaugų valdymą.
* Užtikrinti duomenų saugumą ir prieinamumą.
* Užtikrinti patogų klientų ir paslaugų valdymą administratoriams.

**Nauda:**
* Greitesnis atsiskaitymas ir mažiau rankinio darbo.
* Sumažintos klaidų rizikos.
* Geresnė klientų patirtis.
* Lengvai plečiama architektūra.


## 1.2. Architektūros principai ir sprendimai _(angl. General architectural principles)_
### Bendrieji architektūriniai principai
| Nr    | Principas _(angl. Principles)_                             | Pagrindimas _(angl. Rationale)_                                                                                                                                             | Pasekmės _(angl. Implications)_                                                                       |
|:------|:-----------------------------------------------------------|:----------------------------------------------------------------------------------------------------------------------------------------------------------------------------|:------------------------------------------------------------------------------------------------------|
| **1** | **Modulinė architektūra _(angl. Separation of Concerns)_** | Sistemoje yra skirtingos paskirties funkcionalai – klientų valdymas, sąskaitos, paslaugos, klausimai – todėl juos reikia atskirti į modulius, kad būtų aiškesnė atsakomybė. | Lengvesnis testavimas ir priežiūra; modulius galima tobulinti nepriklausomai; mažesnė klaidų sklaida. |
| **2** | **Model–View–Controller (MVC) šablonas**                   | Symfony karkasas natūraliai palaiko MVC struktūrą, leidžiančią atskirti duomenis, logiką ir sąsają.                                                                         | Geresnis kodo tvarkingumas; aiškus sluoksnių padalijimas; palengvina naujų programuotojų įtraukimą.   |
| **3** | **Atvirojo kodo technologijos**                            | Naudojant Symfony, MariaDB ir EasyAdmin sumažinamos licencinės išlaidos, o bendruomenės palaikymas užtikrina stabilumą.                                                     | Mažesni kaštai; greitesnis vystymas; priklausomybė nuo bendruomenės atnaujinimų.                      |
| **4** | **Saugumo prioritetas**                                    | Sistema tvarko klientų ir mokėjimų duomenis, todėl būtina užtikrinti aukštą saugumo lygį (prisijungimas, CSRF, IP filtrai).                                                 | Papildomas kodo ir infrastruktūros sudėtingumas; reikia nuolatinio testavimo ir auditų.               |
| **5** | **Automatizavimas _(angl.Automation First)_**              | Sąskaitų generavimas, atsarginių kopijų kūrimas, testavimas – turi vykti automatiškai.                                                                                      | Mažiau žmogiškų klaidų; reikia patikimų „cron“ procesų ir logavimo.                                   |
| **6** | **Palaikymas ir plėtra _(angl. Evolvability)_**            | Sistema turi būti pritaikoma naujoms paslaugoms ar akcijų sistemai ateityje.                                                                                                | Kodas turi būti rašomas moduliškai; reikia dokumentacijos ir testų.                                   |
| **7** | **Dviejų aplinkų principas (Test + Production)**           | Skirtingos aplinkos užtikrina, kad pakeitimai būtų testuojami prieš diegimą.                                                                                                | Reikia atskiros infrastruktūros; papildomi ištekliai, bet mažesnė klaidų rizika.                      |
| **8** | **Kokybės stebėsena ir logavimas**                         | Visi įvykiai (prisijungimai, sąskaitų generavimas) turi būti registruojami Monolog įrankiu.                                                                                 | Sukuriamas audito pėdsakas; padidėja saugojimo poreikis.                                              |

## 1.3 Architektūrinius principus įtvirtinantys spendimai _(angl. Architectural design decision)_

| Nr.   | Sprendimas                                                          | Pagrindimas _(angl. Rationale)_                                                                              | Alternatyvos                                 | Kodėl atmestos                                                                      | Pasekmės _(angl. Implications)_                                                               |
|-------|---------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------|----------------------------------------------|-------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------|
| **1** | **Naudoti Symfony 7 karkasą (MVC)**                                 | Symfony leidžia greitai kurti modulinius sprendimus, turi integruotą saugumą ir gerą bendruomenės palaikymą. | Laravel, Django                              | Laravel mažiau pritaikytas TVS tipo sprendimams; Django – kita kalba (Python).      | Aiškus MVC sluoksnių atskyrimas, greitesnis vystymas, geresnis testuojamumas.                 |
| **2** | **MariaDB duomenų bazė**                                            | Suderinama su PHP ir atviro kodo, greita ir patikima.                                                        | PostgreSQL, MySQL                            | PostgreSQL – per sudėtinga šiam mastui; MySQL – licencinė priklausomybė nuo Oracle. | Lengva integracija su Symfony Doctrine ORM, greitas diegimas, mažesnės išlaidos.              |
| **3** | **Front-end integruotas į tą pačią sistemą (monolitinė struktūra)** | Nedidelis projektas, todėl vieningas kodas palengvina priežiūrą.                                             | Mikroservisai                                | Mikroservisai per sudėtingi mažam projektui; reikalauja papildomos infrastruktūros. | Paprastesnė architektūra, mažesni resursai, tačiau ribotas mastelio didinimas.                |
| **4** | **Naudoti Paysera mokėjimų integraciją**                            | Paysera palaiko vietinius EUR mokėjimus, turi API, atitinka BDAR.                                            | Stripe, PayPal, Neopay                       | Stripe/PayPal mažiau lokalizuoti, didesni komisiniai.                               | Patogus lokalus apmokėjimas, mažesni mokesčiai, priklausomybė nuo trečios šalies.             |
| **5** | **Automatinis sąskaitų generavimas per „crontab“**                  | Leidžia generuoti sąskaitas be žmogaus įsikišimo.                                                            | Rankinis generavimas, atskiras mikroservisas | Rankinis – klaidų rizika; mikroservisas – per sudėtingas šiam projektui.            | Padidėja efektyvumas, tačiau reikia stebėti „cron“ procesus dėl klaidų.                       |
| **6** | **Symfony Security + CSRF apsauga**                                 | Užtikrina sesijų ir formų saugumą be papildomo kodo.                                                         | Custom autentifikacija                       | Daugiau klaidų, mažiau testuota.                                                    | Saugus prisijungimas ir sesijų valdymas, mažesnė kodo priežiūros našta.                       |
| **7** | **Naudoti EasyAdmin 3 TVS moduliui**                                | Pritaikytas Symfony, greitai kuriami CRUD valdikliai.                                                        | WordPress, custom admin panel                | WordPress per sunkus integruoti; custom – brangu kurti nuo nulio.                   | Greitas administracinės dalies kūrimas, nuosekli sąsaja, priklausomybė nuo EasyAdmin versijų. |
| **8** | **Dviejų aplinkų diegimas (testinė ir produkcinė)**                 | Užtikrina saugų kodo išbandymą prieš diegimą.                                                                | Viena aplinka                                | Didelė rizika klaidų produkcijoje.                                                  | Saugus testavimas, aiškus diegimo procesas, papildomi infrastruktūros kaštai.                 |


# 2. Suinteresuotosios šalys ir rūpesčiai _(angl. Stakeholders and concerns)_
## 2.1 Suinteresuotos šalys _(angl. Stakeholders)_

| Suinteresuota šalis                              | Aprašymas                                                                | Interesas / poreikis                                     |
|:-------------------------------------------------|:-------------------------------------------------------------------------|:---------------------------------------------------------|
| **Klientas (naudotojas)**                        | Naudojasi interneto tiekėjo paslaugomis.                                 | Nori matyti paslaugas, sąskaitas ir atlikti apmokėjimus. |
| **Vadybininkas / administratorius**              | Atsakingas už duomenų, paslaugų ir klientų administravimą TVS sistemoje. | Nori efektyviai valdyti duomenis ir generuoti sąskaitas. |
| **Sistemos savininkas (tiekėjas)**               | Projekto vykdytojas.                                                     | Siekia turėti patikimą, saugią ir prižiūrimą sistemą.    |
| **Techninis administratorius (IT specialistas)** | Atsakingas už sistemos palaikymą ir serverius.                           | Nori užtikrinti sistemos prieinamumą ir našumą.          |
| **Apmokėjimų sistema (Paysera)**                 | Trečiosios šalies integracija.                                           | Teikia saugius mokėjimus klientams.                      |


# 3. Viepoints
Pagal ISO/IEC 42010 standartą, pasirinkti šie architektūriniai požiūriai _(angl. viewpoints)_, kurie padėjo sukurti ir struktūruoti ITIS architektūros vaizdus.  
Kiekvienas viewpoint apibrėžia savo tikslą, suinteresuotuosius asmenis, rūpesčius ir naudojamus modelius.

| Viewpoint                 | Aprašymas                                                                                                 | Suinteresuotosios šalys                                                           | Sprendžiami rūpesčiai                                                                                                                                                                                | Naudojami modeliai / diagramos                                                                                                          |
|---------------------------|-----------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------|
| **Context Viewpoint**     | Apibrėžia sistemos ribas, jos sąveiką su išorinėmis sistemomis ir naudotojais.                            | Klientai, administratoriai, sistemos savininkas (tiekėjas), Paysera API tiekėjas. | - Kaip sistema integruojama su išorinėmis paslaugomis (mokėjimais, el. paštu).<br/>- Kaip klientai ir administratoriai sąveikauja su sistema.<br/>- Kokie duomenys perduodami tarp išorinių sistemų. | - UML konteksto diagrama                                                                                                                |
| **Functional Viewpoint**  | Parodo pagrindinius funkcinius modulius, jų atsakomybę ir sąveiką.                                        | Administratoriai, projektuotojai, tiekėjas                                        | - Kokias funkcijas vykdo sistema.<br/>- Kaip šios funkcijos tarpusavyje susijusios.<br/>- Kaip skirtingi naudotojai (rolės) jas naudoja.                                                             | - UML panaudos atvejų diagramos<br/>- UML veiklos diagramos<br/>- Verslo procesų aprašai                                                |
| **Information Viewpoint** | Aprašo pagrindines duomenų esybes ir ryšius tarp jų.                                                      | Duomenų bazės administratorius, programuotojai                                    | - Kaip saugoma ir valdoma informacija.<br/>- Kokie yra esybių ryšiai (klientai, paslaugos, sąskaitos, akcijos).<br/>- Kaip užtikrinamas duomenų vientisumas ir saugumas.                             | - UML klasių diagrama<br/>- UML duomenų esybių (ERD) diagrama                                                                           |
| **Concurrency Viewpoint** | Nustatyti, kaip sistema elgiasi, kai keli naudotojai ar procesai veikia vienu metu.                       | Architektas, programuotojai, sistemų administratoriai.                            | - Kaip tvarkomos vienalaikės užklausos į duomenų bazę.<br/>- Kaip valdomi sesijų konfliktai.<br/>- Kaip užtikrinamas stabilus veikimas esant dideliam srautui.                                       | - Sekos diagramos (Concurrency scenarijai)<br/>- Apkrovos testų modeliai (Apache JMeter)<br/>- Užraktų (locks) ir sesijų valdymo logika |
| **Development Viewpoint** | Apibrėžti sistemos loginę struktūrą kūrimo požiūriu – kaip organizuojamas kodas, komponentai ir moduliai. | Kūrėjai, architektas, kokybės užtikrinimo (QA) komanda.                           | - Kaip kodas organizuotas (MVC struktūra, modulių išdėstymas).<br/>- Kaip naudojamos priklausomybės ir bibliotekos.<br/>- Kaip valdomos versijos ir testai.                                          | - Kodo struktūros schema (Symfony projektas)<br/>- Priklausomybių (Dependency Injection) modelis<br/>- Unit testų planas                |
| **Deployment Viewpoint**  | Aprašyti, kaip programinė įranga diegiama į infrastruktūrą.                                               | Sistemų administratoriai, tiekėjas, diegimo komanda.                              | - Kaip programinė įranga įdiegta (serveriai, DB, aplinkos).<br/>- Kaip užtikrinamas pasiekiamumas, atsarginės kopijos ir saugumas.<br/>- Koks ryšys tarp testinės ir produkcinės aplinkos.           | - UML Deployment diagrama                                                                                                               |
| **Operational Viewpoint** | Aprašyti, kaip sistema veikia, stebima ir palaikoma eksploatacijos metu.                                  | Sistemos savininkas, administratoriai, techninė priežiūra.                        | - Kaip sistema prižiūrima ir stebima realiu laiku.<br/>- Kaip tvarkomi žurnalai (logai) ir klaidų ataskaitos.<br/>- Kaip atliekamos atsarginės kopijos ir atnaujinimai.                              | - Procesų priežiūros diagrama<br/>- Logų srautų modelis _(Symfony Monolog)_<br/>- Backup & restore politika                             |

Šie septyni viewpoint’ai užtikrina, kad ITIS architektūra apžvelgiama iš visų esminių kampų –  
nuo verslo konteksto iki techninio diegimo ir eksploatacijos.  
Kiekvienas požiūris turi savo paskirtį ir suinteresuotąją auditoriją, todėl kartu jie sudaro visapusišką architektūros aprašymą pagal ISO/IEC 42010 standartą.


# 4. Views
Šiame skyriuje pateikiami konkretūs ITIS architektūros vaizdai _(angl. views)_, sukurti pagal ankstesniame skyriuje aprašytus **7 Viewpoints**.  
Kiekvienas vaizdas pateikia tam tikrą sistemos architektūros aspektą, atspindintį atitinkamų suinteresuotųjų šalių rūpesčius.

## 4.1. Context View
**Tikslas:**  
Parodyti ITIS sistemos ribas, išorines sąsajas ir naudotojus, kurie su ja sąveikauja.

**Aprašymas:**  
Sistema susideda iš dviejų pagrindinių sričių:
- **Frontend (naudotojo sąsaja):** skirta klientams prisijungti, peržiūrėti sąskaitas, apmokėti per Paysera.
- **TVS (Turinio valdymo sistema):** skirta administratoriams valdyti klientus, paslaugas ir sąskaitas.

**Išorinės sąsajos:**
- **Paysera API** – mokėjimų integracija.
- **El. pašto paslauga (SMTP)** – pranešimų siuntimas klientams.
- **MariaDB** – duomenų saugojimas.

**Suinteresuotosios šalys _(angl. Stakeholders)_:** klientai, administratoriai, tiekėjas.

**Modelis:**
- UML konteksto diagrama

**Rūpesčiai _(angl. Concerns)_:**
- Kaip sistema sąveikauja su išoriniais veikėjais.
- Kur baigiasi sistemos atsakomybė.

![context_view.png](context_view.png)

## 4.2. Functional View

**Tikslas:**  
Pavaizduoti sistemos funkcionalumą, modulinius komponentus ir jų tarpusavio sąveiką.

**Aprašymas:**  
Sistema padalinta į funkcinius modulius, atspindinčius verslo procesus:

- **Klientų modulis** – kuria ir tvarko klientų įrašus.
- **Paslaugų modulis** – tvarko paslaugų sąrašą ir jų paketus.
- **Sąskaitų modulis** – generuoja ir saugo sąskaitas.
- **Klausimų modulis** – leidžia klientams pateikti klausimus.
- **Akcijų modulis** – taiko nuolaidas paslaugoms.
- **Nustatymų modulis** – apima sistemos konfigūraciją (PVM, rodymo nustatymai).

**Suinteresuotosios šalys _(angl. Stakeholders)_:** administratoriai, architektas, projektuotojas.

**Modeliai:** UML panaudos atvejų diagramos ir veiklos diagramos.

**Rūpesčiai _(angl. Concerns)_:**
- Ką sistema daro (funkcijos).
- Kaip šios funkcijos paskirstytos tarp modulių.

![functional_view.png](functional_view.png)

## 4.3. Information View
**Tikslas:**  
Pavaizduoti duomenų modelį, pagrindines esybes ir jų ryšius.

**Aprašymas:**  
Duomenų modelis paremtas **Entity–Relationship (ER)** struktūra. Pagrindinės esybės:

- **Klientas** – turi kelis **Objektus**.
- **Objektas** – turi kelis **Paslaugų paketus**.
- **Paslaugos paketas** – apima vieną ar kelias **Paslaugas**.
- **Sąskaita** – generuojama pagal paslaugas, turi **Sąskaitos eilutes**.
- **Akcija** – taikoma paslaugoms ar paketams.
- **Nustatymai** – saugo sistemos konfigūraciją.

**Suinteresuotosios šalys _(angl. Stakeholders)_:**
- Programuotojai
- DB administratoriai.

**Modeliai:**
- UML duomenų esybių diagrama (ERD)
- UML klasių diagrama.

**Rūpesčiai _(angl. Concerns)_:**
- Kaip organizuoti duomenų struktūrą.
- Kaip užtikrinti duomenų vientisumą ir ryšius.

UML klasių diagrama  
![intormation_view.png](intormation_view_classdiagram.png)

UML Esybių ryšių diagrama (Baronas (Chen) notation)  
![intormation_view.png](intormation_view_erdiagram.png)



## 4.4. Concurrency  View
**Tikslas:**  
Aprašyti, kaip sistema tvarko vienalaikes užklausas ir procesų sinchronizaciją.

**Aprašymas:**  
ITIS sistema palaiko vienalaikį kelių naudotojų prisijungimą:
- Naudojamas **Symfony sesijų valdymas** – atskira sesija kiekvienam naudotojui.
- **Doctrine ORM** užtikrina duomenų vientisumą užrakinant įrašus (transactional locks).
- **crontab** procesas generuoja sąskaitas fone (asinchroninis vykdymas).
- Testuota su **Apache JMeter**, iki 1000 užklausų per 3 s.

**Suinteresuotosios šalys _(angl. Stakeholders)_:**
- TODO

**Modeliai:**
- Sekos diagramos (duomenų užrakinimas).
- Apkrovos testų rezultatai.

**Rūpesčiai _(angl. Concerns)_:**
- TODO


## 4.5. Development View
**Tikslas:**  
Pateikti sistemos loginę struktūrą iš kūrimo perspektyvos – kodą, modulius ir priklausomybes.

**Aprašymas:**  
Kodas organizuotas pagal **MVC (Model–View–Controller)** šabloną:
- **Model:** Doctrine ORM entitetai.
- **Controller:** Symfony kontroleriai, valdantys logiką.
- **View:** Twig šablonai.

Papildomai:
- Naudojamas **EasyAdmin 3** TVS moduliui.
- Priklausomybės valdomos per **Composer**.
- Kodo kokybė tikrinama per **PHPUnit** testus.

**Modeliai / diagramos:**
- Kodo struktūros schema.
- Priklausomybių diagrama.

**Rezultatas:**  
Development View užtikrina, kad programinė architektūra būtų tvarkinga, modulinė ir lengvai plečiama.


## 4.6. Deployment View
**Tikslas:**  
Parodyti, kaip sistema yra diegiama į techninę infrastruktūrą.

**Aprašymas:**
Sistemos komponentai diegiami į dvi aplinkas:
- **Testinę** (staging) – naujų funkcijų bandymams.
- **Produkcijos** (production) – veikianti versija vartotojams.

**Serverių konfigūracija:**
- OS: Ubuntu 24 LTS
- Web serveris: Apache
- DBVS: MariaDB 11
- PHP 8.2, JavaScript
- Automatizuoti darbai: crontab (sąskaitų generavimas)
- Logavimas: Symfony Monolog

**Suinteresuotosios šalys _(angl. Stakeholders)_:**
- Sistemų administratorius
- tiekėjas

**Modeliai:** Deployment diagrama

**Rūpesčiai _(angl. Concerns)_:**
- Kaip sistema pasiekiama ir prižiūrima.
- Kaip užtikrinamas pasiekiamumas, atsarginės kopijos ir našumas.


## 4.7. Operational View

**Tikslas:**  
Pavaizduoti, kaip sistema veikia, stebima ir palaikoma eksploatacijos metu.

**Aprašymas:**
- **Monolog** fiksuoja veiksmus ir klaidas („.log“ failai saugomi 90 dienų).
- **Automatinės atsarginės kopijos** vykdomos periodiškai.
- **Serverio stebėsena** atliekama per OS įrankius (pvz., `top`, `journalctl`).
- **Klaidos** siunčiamos administratoriui el. paštu.
- **Atnaujinimai** vykdomi per „composer update“ ir cache valymą.

**Modeliai / diagramos:**
- Log'ų srautų schema (Symfony Monolog)
- Backup procesų diagrama

**Suinteresuotosios šalys _(angl. Stakeholders)_:**
- Sistemų administratorius
- Programuotojai
- Testuotojai
- Support

**Rezultatas:**  
Operational View parodo, kaip sistema veikia realiame gyvenimo cikle — kaip ji stebima, palaikoma ir atstatoma gedimo atveju.


## 4.8 Santrauka
Šie vaizdai bendrai aprašo **ITIS architektūrą iš kelių perspektyvų**, kad kiekviena suinteresuotųjų šalis galėtų suprasti jai svarbius aspektus:
- naudotojai – kontekstą ir funkcijas;
- kūrėjai – loginę ir duomenų struktūrą;
- administratoriai – diegimą, saugumą ir našumą.

Kartu jie sudaro išsamų, subalansuotą architektūros aprašymą, atitinkantį ISO/IEC 42010 reikalavimus.


# 5. Perspectives
## 5.1. Security
TODO

## 5.2. Performance and scalability
TODO

## 5.3. Availability and resilience
TODO

## 5.4. Evolution perspectives
TODO

## 5.5. Consistency and correspondences
TODO

# Appendix A. Architecture decisions and rationale
TODO

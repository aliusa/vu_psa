# lab 2.A
Points: 0.25
## Reikalavimai _(angl. Requirements)_
* Think of a business case and of a large enough IT system which will help solve business case
* Define stakeholders of IT system
* Create architectural description with context view of the system

#### Į ką atkreipia dėmesį
- Funkciniai, nefunkciniai reikalavimai
- Suinteresuotos šalys
- Sistemos apimtis (Scope)
- Context view diagrama
- Context view scenarijai
- Neturi būti konkrečios technologijos - reikia rašyti iš verslo pusės

# lab 2.B
Points: 1.5  
Bonus Points : Document all 7 views 0.25
## Reikalavimai _(angl. Requirements)_
* Document 5 chosen views in architectural description
* Choose views from view catalog
* Use viewpoints to document views

# lab 2.C
Points: 1  
Bonus Points: Use all 4 perspectives 0.25
## Reikalavimai _(angl. Requirements)_
* Adjust architectural description by using 2 different perspectives
* Choose perspectives from Security, Performance and scalability, Availability and resilience, Evolution perspectives


---
<h1 style="text-align:center;">Architektūrinis aprašymas <i>(angl. Architectural description)</i></h1>


# 1. Dokumento kontrolė ir įvadas _(angl. Document control (versioning))_
**Versija:** 1.0  
**Data:** 2025-11-09  
**Sistemos pavadinimas:** Interneto tiekėjo informacinė sistema (ITIS)

## 1.1. Santrauka vadovybei _(angl. Introduction to management summary (Executive summary))_
Interneto tiekėjo informacinė sistema (ITIS) skirta automatizuoti klientų duomenų, jiems priskirtų paslaugų, sąskaitų išrašymo ir apmokėjimo procesus. Ši sistema padeda interneto paslaugų tiekėjui centralizuotai valdyti klientus ir paslaugas, mažina administracinę naštą bei užtikrina duomenų saugumą.

**Pagrindiniai ITIS tikslai _(angl. Objectives of AD)_:**
- Automatizuoti sąskaitų išrašymo ir apmokėjimo procesą.
- Suteikti klientams prieigą prie jų duomenų per patogią naudotojo sąsają.
- Užtikrinti aukštą sistemos prieinamumą ir duomenų saugumą.
- Centralizuoti klientų ir paslaugų valdymą.
- Užtikrinti patogų klientų ir paslaugų valdymą administratoriams.

**Nauda:**
- Greitesnis atsiskaitymas ir mažiau rankinio darbo.
- Sumažintos klaidų rizikos.
- Geresnė klientų patirtis.
- Lengvai plečiama architektūra.
- Analitika.

**Sistemos tikslas _(angl. Purpose)_:**
- Sistema gali:
  - Klientų savitarna:
    - Gali peržiūrėti savo paslaugas, sąskaitas, mokėjimus, objektus
    - Užduoti klausimą administracijai (per klausimų modulį)
  - Vadibininkų funkcijos:
    - Gali kurti klientus
    - Gali koreguoti visus klientų, paslaugų duomenis, akcijas
  - Informaciniai ir veikimo mechanizmai:
    - Log'inti visas klaidas ir sistemos veiksmus
    - Kaupti veiklos žurnalus 90 dienų
    - Veikti dvejose aplinkose — testavimo (staging) ir production'o.
- Sistema neturi, negali:
  - Klientų ir paslaugų valdymas:
    - Klientas pats negali užsiregistruoti į sistemą - jį gali priregistruoti tik vadybininkas
    - Klientas pats negali užsakyti paslaugų - tik per vadybininką
    - Klientas pats negali keisti sutarties
  - Finansai ir apmokėjimai:
    - Sistema nepriima mokėjimų tiesiogiai – visi apmokėjimai vykdomi tik per integruotą e-mokėjimų sistemą (Paysera API)
    - Sistema neapdoroja grąžinimų (refunds) automatiškai – tai daro finansų skyrius
    - Sistema nesaugo pilnų mokėjimo kortelių duomenų (tik tokenizuotus ID iš e-mokėjimo sistemos, jei reikia)
  - Finansai
    - Sistema neprognozuoja pajamų ar paslaugų vartojimo tendencijų – analitika ribota iki bendros ataskaitų peržiūra.

## 1.2. Architektūros principai ir sprendimai _(angl. General architectural principles)_
### Bendrieji architektūriniai principai
| Nr    | Principas _(angl. Principles)_                             | Pagrindimas _(angl. Rationale)_                                                                                                                                             | Pasekmės _(angl. Implications)_                                                                       |
|:------|:-----------------------------------------------------------|:----------------------------------------------------------------------------------------------------------------------------------------------------------------------------|:------------------------------------------------------------------------------------------------------|
| **1** | **Modulinė architektūra _(angl. Separation of Concerns)_** | Sistemoje yra skirtingos paskirties funkcionalai – klientų valdymas, sąskaitos, paslaugos, klausimai – todėl juos reikia atskirti į modulius, kad būtų aiškesnė atsakomybė. | Lengvesnis testavimas ir priežiūra; modulius galima tobulinti nepriklausomai; mažesnė klaidų sklaida. |
| **2** | **Model–View–Controller (MVC) šablonas**                   | Symfony karkasas natūraliai palaiko MVC struktūrą, leidžiančią atskirti duomenis, logiką ir sąsają.                                                                         | Geresnis kodo tvarkingumas; aiškus sluoksnių padalijimas; palengvina naujų programuotojų įtraukimą.   |
| **3** | **Atvirojo kodo technologijos**                            | Naudojant Symfony, MariaDB ir EasyAdmin sumažinamos licencinės išlaidos, o bendruomenės palaikymas užtikrina stabilumą.                                                     | Mažesni kaštai; greitesnis vystymas; priklausomybė nuo bendruomenės atnaujinimų.                      |
| **4** | **Saugumo prioritetas**                                    | Sistema tvarko klientų ir mokėjimų duomenis, todėl būtina užtikrinti aukštą saugumo lygį (prisijungimas, CSRF, IP filtrai).                                                 | Papildomas kodo ir infrastruktūros sudėtingumas; reikia nuolatinio testavimo ir auditų.               |
| **5** | **Automatizavimas _(angl.Automation First)_**              | Sąskaitų generavimas, atsarginių kopijų kūrimas, testavimas – turi vykti automatiškai.                                                                                      | Mažiau žmogiškų klaidų; reikia patikimų „cron“ procesų ir log'inimo.                                  |
| **6** | **Palaikymas ir plėtra _(angl. Evolvability)_**            | Sistema turi būti pritaikoma naujoms paslaugoms ar akcijų sistemai ateityje.                                                                                                | Kodas turi būti rašomas moduliškai; reikia dokumentacijos ir testų.                                   |
| **7** | **Dviejų aplinkų principas (Test + Production)**           | Skirtingos aplinkos užtikrina, kad pakeitimai būtų testuojami prieš diegimą.                                                                                                | Reikia atskiros infrastruktūros; papildomi ištekliai, bet mažesnė klaidų rizika.                      |
| **8** | **Kokybės stebėsena ir log'inimas**                        | Visi įvykiai (prisijungimai, sąskaitų generavimas) turi būti registruojami Monolog įrankiu.                                                                                 | Sukuriamas audito pėdsakas; padidėja saugojimo poreikis.                                              |

## 1.3. Architektūrinius principus įtvirtinantys spendimai _(angl. Architectural design decision)_

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

## 1.4. Sistemos reikalavimai _(angl. System requirements)_
### 1.4.1. Funkciniai reikalavimai _(angl. Functional requirements)_

| Nr.     | Reikalavimas                           | Aprašymas                                                                                                                |
|---------|----------------------------------------|--------------------------------------------------------------------------------------------------------------------------|
| **F1**  | **Kliento prisijungimas**              | Sistema turi leisti klientui prisijungti prie savitarnos naudodama el. paštą ir slaptažodį.                              |
| **F2**  | **Kliento duomenų peržiūra**           | Klientas turi galėti peržiūrėti savo sutartis, paslaugas, sąskaitas ir mokėjimų istoriją.                                |
| **F3**  | **Sąskaitų generavimas**               | Sistema turi automatiškai generuoti sąskaitas klientams pagal aktyvias paslaugas (naudojant „crontab“).                  |
| **F4**  | **Mokėjimų integracija su Paysera**    | Sistema turi palaikyti e-mokėjimus per Paysera API, registruoti apmokėjimo būseną ir pranešti apie nesėkmingus bandymus. |
| **F5**  | **Vadybininko prisijungimas prie TVS** | Vadybininkas turi galėti prisijungti prie TVS administracinės dalies per EasyAdmin valdymo panelę.                       |
| **F6**  | **Klientų administravimas**            | Vadybininkas gali kurti, redaguoti ir ištrinti klientų įrašus.                                                           |
| **F7**  | **Paslaugų administravimas**           | Vadybininkas gali kurti, redaguoti ir ištrinti paslaugas bei jų paketus.                                                 |
| **F8**  | **Akcijų ir nuolaidų valdymas**        | Rinkodaros specialistas gali pridėti akcijas, kurios taikomos konkrečioms paslaugoms.                                    |
| **F9**  | **Pranešimų siuntimas**                | Sistema turi siųsti el. laiškus klientams apie sąskaitas, mokėjimus ar sistemos pakeitimus.                              |
| **F10** | **Logų ir įvykių registravimas**       | Sistema turi fiksuoti visus svarbius įvykius (prisijungimai, sąskaitų generavimas, klaidos) Monolog įrankiu.             |
| **F11** | **Atsarginės kopijos**                 | Sistema turi generuoti duomenų bazės atsargines kopijas nustatytu periodiškumu.                                          |
| **F12** | **Rolės ir leidimai**                  | Sistema turi palaikyti skirtingas roles (klientas, vadybininkas, rinkodaros specialistas, administratorius).             |
| **F13** | **Veiklos ataskaitos**                 | Sistema turi pateikti bendrą klientų, paslaugų ir apyvartos statistiką vadybininkui ar savininkui.                       |

### 1.4.2. Nefunkciniai reikalavimai _(angl. Non-functional requirements)_

| Nr.      | Reikalavimas                            | Aprašymas                                                                                                                             |
|----------|-----------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------|
| **NF1**  | **Našumas (Performance)**               | Sistema turi apdoroti bent 10000 užklausų per 1 sekundę esant apkrovai.                                                               |
| **NF2**  | **Prieinamumas (Availability)**         | Sistema turi būti pasiekiama bent 99,99 % laiko per mėnesį.                                                                           |
| **NF3**  | **Atsparumas (Resilience)**             | Gedimo atveju sistema turi būti atstatoma ne vėliau kaip per 2 valandas (MTTR ≤ 2 h).                                                 |
| **NF4**  | **Saugumas (Security)**                 | Visi duomenys perduodami HTTPS protokolu; slaptažodžiai saugomi su bcrypt / Argon2; naudojami CSRF token'ai.                          |
| **NF5**  | **Duomenų apsauga (Privacy)**           | Sistema turi atitikti BDAR _(angl. GDPR)_ reikalavimus — klientas turi teisę peržiūrėti, ištaisyti ir prašyti ištrinti savo duomenis. |
| **NF6**  | **Pritaikomumas (Usability)**           | Kliento savitarna turi būti aiški ir pasiekiama per 3 paspaudimus iki pagrindinės informacijos (sąskaitos ar paslaugos).              |
| **NF7**  | **Patikimumas (Reliability)**           | Sistema turi išlaikyti stabilų veikimą be klaidų esant keliems šimtams aktyvių vartotojų.                                             |
| **NF8**  | **Palaikymas (Maintainability)**        | Architektūra turi būti modulinė (MVC), kad kiekvienas modulis galėtų būti atnaujinamas nepriklausomai.                                |
| **NF9**  | **Išplečiamumas (Scalability)**         | Sistema turi būti parengta pridėti papildomus modulinius komponentus (pvz., naujus mokėjimo tiekėjus).                                |
| **NF10** | **Perkeliamumas (Portability)**         | Sistema turi būti paleidžiama tiek Linux, tiek Windows serveriuose be kodo keitimo.                                                   |
| **NF11** | **Testuojamumas (Testability)**         | Visi verslo moduliai turi būti padengti bent 70 % vienetinių testų (unit tests).                                                      |
| **NF12** | **Naudojamumo stebėjimas (Monitoring)** | Sistema turi fiksuoti klaidas, įvykius ir siųsti pranešimus el. paštu (Monolog + Sentry).                                             |

# 2. Suinteresuotosios šalys ir rūpesčiai _(angl. Stakeholders and concerns)_
## 2.1. Suinteresuotos šalys _(angl. Stakeholders)_

| Suinteresuota šalis _(angl. [Stakeholder](https://www.viewpoints-and-perspectives.info/home/stakeholders/))_  | Aprašymas                                                              | Interesas / poreikis                                            |
|:--------------------------------------------------------------------------------------------------------------|:-----------------------------------------------------------------------|:----------------------------------------------------------------|
| **Klientas (naudotojas)**                                                                                     | Naudojasi interneto tiekėjo paslaugomis                                | Nori matyti paslaugas, sąskaitas ir atlikti apmokėjimus         |
| **Vadybininkai**                                                                                              | Atsakingi už duomenų, paslaugų ir klientų administravimą TVS sistemoje | Nori efektyviai valdyti klientų, paslaugų duomenis              |
| **Rinkodaros specialistai**                                                                                   | Atsakingi už paslaugų siūlymą esamiems klientams, naujų įvedimą        | Nori pritraukti daugiau pinigų į įmonę                          |
| **Sistemos savininkas (tiekėjas)**                                                                            | Projekto vykdytojas                                                    | Siekia turėti patikimą, saugią ir prižiūrimą sistemą            |
| **Programuotojai**                                                                                            | Programuoja sistemą                                                    | Siekia sukurti kitoms suinteresuotoms šalims tinkamą sistemą    |
| **Testuotojai**                                                                                               | Testuoja sistemą                                                       | Siekia užtikrinti sistemos veiklą be trūkių                     |
| **E-mokėjimų sistema (Paysera)**                                                                              | Trečiosios šalies integracija                                          | Teikia saugius mokėjimus klientams                              |
| **Žemėlapio integracija (Leaflet)**                                                                           | Trečiosios šalies integracija                                          | Teikia žemėlapį vadybininkams ir rinkodaros specialistams TVS'e |

# 3. Viepoints
Pagal ISO/IEC 42010:2022 standartą, pasirinkti visi šie architektūriniai požiūriai _(angl. viewpoints)_, kurie padėjo sukurti ir struktūruoti ITIS architektūros vaizdus.  
Kiekvienas viewpoint apibrėžia savo aprašymą, tikslą, sprendžiamus rūpesčius, suinteresuotuosius asmenis ir naudojamus modelius.

| Požiūris _(angl. [Viewpoint](https://www.viewpoints-and-perspectives.info/home/viewpoints/))_                  | Tikslas _(angl. Purpose)_                                                                                | Sprendžiami rūpesčiai _(angl. Concerns)_                                                                                                                                                             | Suinteresuotosios šalys _(angl. Stakeholders)_                                                               | Naudojami modeliai / diagramos                                                                                                                             |
|----------------------------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **[Context Viewpoint](https://www.viewpoints-and-perspectives.info/home/viewpoints/context/)**                 | Apibrėžia sistemos ribas, jos sąveiką su išorinėmis sistemomis ir naudotojais                            | - Kaip sistema integruojama su išorinėmis paslaugomis (mokėjimais, el. paštu).<br/>- Kaip klientai ir administratoriai sąveikauja su sistema.<br/>- Kokie duomenys perduodami tarp išorinių sistemų. | Visos suinteresuotos šalys, bet labiausiai:<br/>- klientai (naudotojai)<br/>- Sistemos savininkas (tiekėjas) | - UML konteksto diagrama                                                                                                                                   |
| **[Functional Viewpoint](https://www.viewpoints-and-perspectives.info/home/viewpoints/functional-viewpoint/)** | Apibrėžia pagrindinius funkcinius modulius, jų atsakomybę ir sąveiką                                     | - Ką sistema daro (funkcijos).<br/>- Kaip šios funkcijos tarpusavyje susijusios.<br/>- Kaip skirtingi naudotojai (rolės) jas naudoja.                                                                | Visos suinteresuotos šalys                                                                                   | - UML panaudos atvejų diagramos<br/>- UML veiklos diagramos<br/>- Verslo procesų aprašai                                                                   |
| **[Information Viewpoint](https://www.viewpoints-and-perspectives.info/home/viewpoints/information/)**         | Apibrėžia pagrindines duomenų esybes ir ryšius tarp jų                                                   | - Kaip saugoma ir valdoma informacija.<br/>- Kokie yra esybių ryšiai (klientai, paslaugos, sąskaitos, akcijos).<br/>- Kaip užtikrinamas duomenų vientisumas ir saugumas.                             | - Programuotojai<br/>- Sistemos savininkas (tiekėjas)                                                        | - UML klasių _(angl. class)_ diagrama<br/>- UML esybių ryšių (ER) diagrama<br/>- UML esybių ryšių (ER) žvaigždinė _(angl. star)_ diagrama                  |
| **[Concurrency Viewpoint](https://www.viewpoints-and-perspectives.info/home/viewpoints/concurrency/)**         | Apibrėžia, kaip sistema elgiasi, kai keli naudotojai ar procesai veikia vienu metu                       | - Kaip tvarkomos vienalaikės užklausos į duomenų bazę.<br/>- Kaip valdomi sesijų konfliktai.<br/>- Kaip užtikrinamas stabilus veikimas esant dideliam srautui.                                       | - Programuotojai<br/>- Testuotojai                                                                                             | - Sekos diagramos (Concurrency scenarijai)<br/>- Užraktų _(angl. locks)_ ir sesijų valdymo logika                                                          |
| **[Development Viewpoint](https://www.viewpoints-and-perspectives.info/home/viewpoints/development/)**         | Apibrėžia sistemos loginę struktūrą kūrimo požiūriu – kaip organizuojamas kodas, komponentai ir moduliai | - Kaip kodas organizuotas (MVC struktūra, modulių išdėstymas).<br/>- Kaip naudojamos priklausomybės ir bibliotekos.<br/>- Kaip valdomos versijos ir testai.                                          | - Testuotojai<br/>- Sistemos savininkas (tiekėjas)                                                                | - Kodo struktūros schema<br/>- Priklausomybių _(angl. Dependency Injection)_ modelis                                                                       |
| **[Deployment Viewpoint](https://www.viewpoints-and-perspectives.info/home/viewpoints/deployment/)**           | Apibrėžia, kaip sistema diegiama į kliento infrastruktūrą                                                | - Kaip programinė įranga įdiegta (serveriai, DB, aplinkos).<br/>- Kaip užtikrinamas pasiekiamumas, atsarginės kopijos ir saugumas.<br/>- Koks ryšys tarp testinės ir produkcinės aplinkos.           | - Programuotojai<br/>- Sistemos savininkas (tiekėjas)                                                        | - Serverių topologijos schema                                                                                                                              |
| **[Operational Viewpoint](https://www.viewpoints-and-perspectives.info/home/viewpoints/operational/)**         | Apibrėžia, kaip sistema veikia, stebima ir palaikoma eksploatacijos metu                                 | - Kaip sistema prižiūrima ir stebima realiu laiku.<br/>- Kaip tvarkomi žurnalai (logai) ir klaidų ataskaitos.<br/>- Kaip atliekamos atsarginės kopijos ir atnaujinimai.                              | - Programuotojai<br/>- Testuotojai<br/>- Sistemos savininkas (tiekėjas)                                      | - Procesų priežiūros diagrama<br/>- Logų srautų modelis _(Symfony Monolog)_<br/>- UML veiklos _(angl. activity)_ diagrama _(angl. Backup restore diagram)_ |

Šie septyni viewpoint’ai užtikrina, kad ITIS architektūra apžvelgiama iš visų esminių kampų – nuo verslo konteksto iki techninio diegimo ir eksploatacijos.  
Kiekvienas požiūris turi savo paskirtį ir suinteresuotąją auditoriją, todėl kartu jie sudaro visapusišką architektūros aprašymą pagal ISO/IEC 42010:2022 standartą.

# 4. Architektūros vaizdai _(angl. Views)_
Šiame skyriuje pateikiami konkretūs ITIS architektūros vaizdai _(angl. views)_, sukurti pagal ankstesniame skyriuje aprašytus **7 Viewpoints**.  
Kiekvienas vaizdas pateikia tam tikrą sistemos architektūros aspektą, atspindintį atitinkamų suinteresuotųjų šalių rūpesčius.

## 4.1. Konteksto vaizdas _(angl. Context View)_
**Aprašymas:**
Sistema susideda iš dviejų pagrindinių sričių:
- **Frontend (naudotojo sąsaja):** skirta klientams prisijungti, peržiūrėti sąskaitas, apmokėti per e-mokėjimų sistemą Paysera.
- **TVS (Turinio valdymo sistema):** skirta administratoriams, rinkodaroms specialistams valdyti klientus, paslaugas ir sąskaitas.

![context_view.png](context_view.png)

### Konteksto scenarijai

**Scenarijus 1 — Klientas prisijungia ir peržiūri paslaugas**
1. Klientas atidaro sistemos savitarnos puslapį.
2. Įveda el. paštą ir slaptažodį.
3. Sistema autentifikuoja klientą.
4. Klientas mato savo paslaugų sąrašą, objektus.
5. Sistema duomenis gauna iš duomenų bazės.

**Scenarijus 2 — Klientas apmoka sąskaitą per el.mokėjimų sistemą (Paysera)**
1. Klientas pasirenka neapmokėtą sąskaitą ir paspaudžia „Apmokėti“.
2. ITIS perduoda mokėjimo informaciją į Paysera API.
3. Paysera nukreipia klientą į mokėjimo langą.
4. Po sėkmingo mokėjimo Paysera grąžina „callback“ į ITIS.
5. ITIS atnaujina sąskaitos statusą į „Apmokėta“.

**Scenarijus 3 — Vadybininkas administruoja klientų duomenis**
1. Vadybininkas prisijungia prie TVS.
2. Pasirenka klientų modulį.
3. Redaguoja kliento kontaktinius duomenis, sutartį ar prideda naują paslaugų paketą.
4. Sistema atnaujina duomenis duomenų bazėje.

**Scenarijus 4 — Sistema automatiškai sugeneruoja sąskaitas**
1. Kasdien 02:00 val. nakties crontab paleidžia sąskaitų generavimo procesą.
2. Sistema tikrina visus aktyvius klientų paslaugų paketus.
3. Sugeneruojamos naujos sąskaitos už mėnesio paslaugas.
4. Kiekvienas generavimas registruojamas log'uose.
5. Klientams jų savitarnoje atsiranda nauja sąskaita.

**Scenarijus 5 — Sistemos gedimas ir automatinis atstatymas**
1. Duomenų bazė trumpam tampa nepasiekiama dėl resursų perkrovos.
2. ITIS rodo vartotojui draugišką klaidos pranešimą.
3. Monitoring modulyje užregistruojamas įvykis.
4. Sistema automatiškai atsistato, kai DB vėl pradeda veikti.
5. Administratorius gauna pranešimą el. paštu arba Sentry.


## 4.2. Funkcinis vaizdas _(angl. Functional View)_
**Aprašymas:**  
Sistema padalinta į funkcinius modulius, atspindinčius verslo procesus:
- **Klientų modulis** – kuria ir tvarko klientų įrašus.
- **Paslaugų modulis** – tvarko paslaugų sąrašą ir jų paketus.
- **Sąskaitų modulis** – generuoja ir saugo sąskaitas.
- **Klausimų modulis** – leidžia klientams pateikti klausimus.
- **Akcijų modulis** – taiko nuolaidas paslaugoms.
- **Nustatymų modulis** – apima sistemos konfigūraciją (PVM, rodymo nustatymai).
- **Struktūros modulis** – atvaizduoja tekstinius puslapius.
- **Administratorių modulis** – valdo sistemos administratorius - vadybininkus, rinkodaros specialistus.

![functional_view.png](functional_view.png)

### 4.2.1. Rūpesčiai _(angl. Concerns)_
Šis funkcinis požiūris apibrėžia sistemos funkcines galimybes — **ką sistema privalo daryti ir ko sistema nedaro**, atsižvelgiant į verslo poreikius ir proceso ribas.

#### Ką sistema privalo daryti _(angl. In scope)_
Sistemai keliami šie funkciniai reikalavimai:
1. **Klientų savitarna**
   - Leisti klientui prisijungti.
   - Leisti peržiūrėti paslaugas, objektus, sąskaitas ir mokėjimus.
   - Leisti pateikti klausimą administracijai.
2. **Klientų ir paslaugų administravimas**
   - Leisti vadybininkui kurti ir redaguoti klientus.
   - Leisti valdyti kliento objektus (adresus).
   - Leisti pridėti ir koreguoti paslaugas bei jų paketus klientams.
   - Leisti kurti ir taikyti akcijas paslaugoms.
3. **Sąskaitų valdymas**
   - Automatiškai generuoti sąskaitas.
   - Saugoti sąskaitų istoriją.
   - Leisti peržiūrėti sąskaitų būsenas.
4. **Klausimų-atsakymų valdymas**
   - Leisti vadybininkui kurti ir redaguoti klausimų kategorijas.
4. **Mokėjimai**
   - Inicijuoti mokėjimą per integruotą e-mokėjimų sistemą (Paysera).
   - Priimti mokėjimų būsenos „callback“ signalus.
   - Atnaujinti sąskaitos statusą.
5. **Operacinės funkcijos**
   - Log'inti įvykius ir klaidas.
   - Siųsti pranešimus el. paštu.
   - Daryti atsargines DB kopijas.
   - Laikyti dvi atskiras aplinkas (staging & production).

#### Ko sistema nedaro _(angl. Out of Scope)_
Šios funkcijos nėra sistemos galimybės ir nepatenka į apimtį _(angl. Scope)_:
1. Saviregistracija
   - Klientas negali pats susikurti paskyros.
2. Savarankiškas paslaugų užsakymas
   - Klientas negali pats keisti ar užsisakyti paslaugų.
3. Finansinės funkcijos
   - Sistema nepriima mokėjimų tiesiogiai (tik per e-mokėjimų sistemą).
   - Sistema neapdoroja grąžinimų (refunds).
   - Sistema nesaugo mokėjimo kortelių duomenų.
   - Sistema nevykdo automatinių nuskaitytų iš mokėjimo kortelės.
4. Išplėstinė analitika
   - Sistema neprognozuoja pajamų.
   - Sistema neatlieka paslaugų vartojimo analizės.
5. Automatinis sutarties valdymas
   - Sistema nekeičia klientų sutarčių be vadybininko įsikišimo.

UML panaudos atvejų _(angl. Use Case)_ diagrama.  
Išeities kodas pateiktas priede 1.  
![function_view_use_case_diagram.png](function_view_use_case_diagram.png)

### 4.2.2. Išorinės sąsajos _(angl. External Interfaces)_
Šiame skyriuje aprašomos visos ITIS sistemos funkcinės sąveikos su išoriniais aktoriais ir trečiųjų šalių sistemomis. Tai leidžia identifikuoti, kokie duomenys, įvykiai ir valdymo srautai būtini sistemai atlikti funkcijas, aprašytas Funkciniame vaizde.

| Išorinis aktorius / sistema             | Sąveikos tipas                                      | Funkcinė paskirtis                                                    |
|-----------------------------------------|-----------------------------------------------------|-----------------------------------------------------------------------|
| **Klientas**                            | UI sąveika, duomenų užklausos, inicijuojami įvykiai | Peržiūri paslaugas, sąskaitas, inicijuoja mokėjimą, teikia klausimus. |
| **Vadybininkas / administratorius**     | Valdymo veiksmai, duomenų keitimas                  | Kuria klientus, keičia objektus, paslaugas, sąskaitas.                |
| **E-mokėjimų sistema (Paysera)**        | Duomenų mainai, įvykiai (callback)                  | Apdoroja mokėjimus ir grąžina jų būsenas.                             |
| **El. pašto sistema (SMTP)**            | Įvykiai, pranešimų siuntimas                        | Siunčia sąskaitas, pranešimus, slaptažodžio atkūrimo laiškus.         |
| **Žemėlapis (Leaflet / OpenStreetMap)** | Duomenų užklausos (tiles)                           | Atvaizduoja klientų objektus žemėlapyje administracinė dalyje.        |

### 4.2.3. Duomenų mainų srautai _(angl. Data Flows)_
**ITIS → Paysera**
Siunčiama:
- mokėjimo inicijavimo užklausa:  
`invoice_id`, `customer_id`, `amount`, `currency`, `redirect_url`, `callback_url`

Gaunama:
- mokėjimo būsena (`paid`, `canceled`, `failed`)
- Paysera parašas (`sign`) duomenų patikrai

**ITIS → El. pašto sistema**
- sąskaitos PDF failas
- kliento el. pašto adresas
- laiško tema ir turinys
- slaptažodžio keitimo nuoroda

**ITIS → Leaflet/OpenStreetMap**
- HTTP GET užklausos žemėlapio sluoksniams
- Kliento objektų koordinatės

**ITIS ↔ Klientas**
- Peržiūros funkcijos (sąskaitos, paslaugos)
- Mokėjimo inicijavimas
- Klausimų pateikimas

**ITIS ↔ Administratorius**
- Klientų, paslaugų, objektų, sąskaitų keitimas
- Sistemos nustatymų valdymas
- Prieigos valdymo operacijos

### 4.2.4. Įvykių srautai _(angl. Events)_
**Išorinių sistemų inicijuojami įvykiai**

| Įvykis               | Pasekmė sistemoje                                              |
|----------------------|----------------------------------------------------------------|
| **Paysera callback** | Atnaujinama sąskaitos būsena, siunčiamas patvirtinimo laiškas. |

**Sistemos inicijuojami įvykiai**

| Įvykis                             | Paskirtis                                              |
|------------------------------------|--------------------------------------------------------|
| **Cron job: sąskaitų generavimas** | Sukuriamos naujos sąskaitos pagal paslaugas ir planus. |
| **Sesijos inicijavimas**           | Klientui ar administratoriui suteikiama prieiga.       |
| **Klaidų log'inimas**              | Užfiksuoti sistemines klaidas.                         |

## 4.3. Informacinis vaizdas _(angl. Information View)_
**Aprašymas:**  
Duomenų modelis paremtas **Entity–Relationship (ER)** struktūra. Pagrindinės esybės:
- **Klientas** – turi kelis **Objektus**.
- **Objektas** – turi kelis **Paslaugų paketus**.
- **Paslaugos paketas** – apima vieną ar kelias **Paslaugas**.
- **Sąskaita** – generuojama pagal paslaugas, turi **Sąskaitos eilutes**.
- **Akcija** – taikoma paslaugoms ar paketams.
- **Nustatymai** – saugo sistemos konfigūraciją.

UML klasių diagrama  
![intormation_view.png](intormation_view_classdiagram.png)

UML Klasių diagrama (žvaigždės schema)  
![intormation_view.png](intormation_view_erdiagram_star.png)

UML Esybių ryšių diagrama (Baronas (Chen) notation)  
![intormation_view.png](intormation_view_erdiagram.png)


## 4.4. Lygiagretumo vaizdas _(angl. Concurrency  View)_
**Aprašymas:**  
ITIS sistema palaiko vienalaikį kelių naudotojų prisijungimą:
- Naudojamas **Symfony sesijų valdymas** – atskira sesija kiekvienam naudotojui.
- **Doctrine ORM** užtikrina duomenų vientisumą užrakinant įrašus (transactional locks).
- **crontab** procesas generuoja sąskaitas fone (asinchroninis vykdymas).
- Testuota su **Apache JMeter**, iki 10000 užklausų per 1 s.

**TODO diagram**


## 4.5. Vystymo vaizdas _(angl. Development View)_
**Aprašymas:**  
Kodas organizuotas pagal **MVC (Model–View–Controller)** šabloną:
- **Model:** Doctrine ORM modeliai.
- **Controller:** Symfony kontrolerio klasės, valdantys logiką.
- **View:** Twig šablonai.

Papildomai:
- Naudojamas **EasyAdmin 3** TVS daliai.
- PHP bibliotekų priklausomybės valdomos per **Composer**.
- Kodo kokybė tikrinama per **PHPUnit** testus.

**Rezultatas:**  
Development View užtikrina, kad programinė architektūra būtų tvarkinga, modulinė ir lengvai plečiama.

**TODO diagram**


## 4.6. Diegimo vaizdas _(angl. Deployment View)_
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
- Log'inimas: Symfony Monolog

**TODO diagram**


## 4.7. Operacinis vaizdas _(angl. Operational View)_
**Aprašymas:**
- **Monolog** fiksuoja veiksmus ir klaidas („.log“ failai saugomi 90 dienų).
- **Automatinės atsarginės kopijos** vykdomos periodiškai.
- **Serverio stebėsena** atliekama per OS įrankius (pvz., `top`, `journalctl`).
- **Klaidos** siunčiamos administratoriui el. paštu, Sentry įrankiu.
- **Atnaujinimai** vykdomi per „composer update“ ir cache valymą.

**Rezultatas:**  
Operational View parodo, kaip sistema veikia realiame gyvenimo cikle — kaip ji stebima, palaikoma ir atstatoma gedimo atveju.

**TODO diagram**


## 4.8. Santrauka
Šie vaizdai bendrai aprašo **ITIS architektūrą iš kelių perspektyvų**, kad kiekviena suinteresuotųjų šalis galėtų suprasti jai svarbius aspektus:
- klientai – kontekstą ir funkcijas;
- vadybininkai – terpę valdyti klientus;
- rinkodaros specialistai – terpę valdyti klientus;
- programuotojai – loginę ir duomenų struktūrą;
- testuotojai – duomenų patikimumą;
- sistemos savininkas (tiekėjas) – diegimą, saugumą ir našumą.

Kartu jie sudaro išsamų, subalansuotą architektūros aprašymą, atitinkantį ISO/IEC 42010 reikalavimus.

# 5. Perspektyvos _(angl. [Perspectives](https://www.viewpoints-and-perspectives.info/home/perspectives/))_
## 5.1. Prieinamumas neįgaliems _(angl. [Accessibility](https://www.viewpoints-and-perspectives.info/home/perspectives/accessibility/))_
Nereikia.

## 5.2. Prieinamumas ir Atsparumas _(angl. [Availability and Resilience](https://www.viewpoints-and-perspectives.info/home/perspectives/availability-and-resilience/))_

| Aspektas                                     | Taikymas                                                                                                                                                                                                                 |
|----------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Taikymas _(angl. Applicability)_**         |                                                                                                                                                                                                                          |
| **Sprendžiami rūpesčiai _(angl. Concerns)_** | - Laikas iki atstatymo (time to repair) po gedimo.<br/>- Nelaimės atstatymo (disaster recovery) strategijos.<br/>- Vienos klaidos taško (single point of failure) identifikavimas.                                                                                                      |
| **Veiksmai _(angl. Activities)_**            |                                                                                                                                                                                                                          |
| **Taktikos _(angl. Tactics)_**               | - Sistemos „kūrimo gedimams“ _(angl. „design for failure“)_ principas — manyti, kad komponentas gali sugesti, ir numatyti mechanizmus atstatymui.<br/>- Automatizuotos atsarginių kopijų procesai, atstatymo procedūros. |
| **Spąstai _(angl. Pitfalls)_**               |                                                                                                                                                                                                                          |

## 5.3. Plėtros ištekliai _(angl. [Development Resource](https://www.viewpoints-and-perspectives.info/home/perspectives/development-resource-perspective/))_
Nereikia.

## 5.4. Evoliucija _(angl. [Evolution](https://www.viewpoints-and-perspectives.info/home/perspectives/evolution/))_

| Aspektas                                     | Taikymas                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          |
|----------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Taikymas _(angl. Applicability)_**         | Ši perspektyva taikoma tam, kad ITIS sistema galėtų ilgainiui būti tobulinama ir pritaikoma naujiems verslo bei technologiniams poreikiams. Ji užtikrina, kad architektūra būtų pakankamai lanksti diegiant naujas paslaugas (pvz., papildomi mokėjimų tiekėjai, akcijų moduliai ar mobilioji sąsaja).                                                                                                                                                                                            |
| **Sprendžiami rūpesčiai _(angl. Concerns)_** | - Kaip sistema gali būti plečiama naujais moduliais ir funkcijomis be didelių perrašymų.<br>- Kaip užtikrinti, kad atnaujinimai (framework, DB) būtų suderinami su esamais komponentais.<br>- Kaip išlaikyti duomenų suderinamumą keičiant modelius ar struktūras.<br>- Kaip planuoti versijų atnaujinimus (Symfony, PHP).                                                                                                                                                                        |
| **Veiksmai _(angl. Activities)_**            | - Modulinės architektūros palaikymas (kiekvienas modulis gali būti vystomas nepriklausomai).<br>- Reguliarus priklausomybių atnaujinimas per Composer.<br>- Migracijų valdymas naudojant Doctrine Migration įrankį.<br>- Kodo refaktoringas pagal testų rezultatus.<br>- Naudotojų poreikių analizė naujų funkcijų planavimui.<br>- Dokumentacijos palaikymas ir atnaujinimas.                                                                                                                    |
| **Taktikos _(angl. Tactics)_**               | - Naudoti **MVC** ir **Service-oriented** architektūros principus, kad modulius būtų galima keisti nepriklausomai.<br>- Naudoti **versijų valdymo sistemą (Git)** su aiškiu „branching“ modeliu (pvz., *GitFlow*).<br>- Naudoti **automatinį testavimą (PHPUnit)** prieš kiekvieną atnaujinimą.<br>- Naudoti **Continuous Integration (CI)** ir **Continuous Deployment (CD)** procesus.<br>- Numatyti **API sąsajas** išoriniams moduliniams plėtiniams (pvz., papildomiems mokėjimų tiekėjams). |
| **Spąstai _(angl. Pitfalls)_**               | - Architektūra tampa pernelyg monolitinė, todėl kiekvienas pakeitimas paveikia visą sistemą.<br>- Nepakankamas testų rinkinys – didelė rizika sugadinti esamas funkcijas.<br>- Priklausomybės nuo konkrečios Symfony ar PHP versijos gali apsunkinti atnaujinimus.<br>- Nepakankamas dokumentacijos atnaujinimas lemia žinių praradimą.<br>- Nenumatyta duomenų migracijos strategija sukelia klaidas atnaujinimų metu.                                                                           |

## 5.5. Internacionalizacija _(angl. [Internationalization](https://www.viewpoints-and-perspectives.info/home/perspectives/internationalization/))_
Nereikia.

## 5.6. Vieta _(angl. [Location](https://www.viewpoints-and-perspectives.info/home/perspectives/location/))_
Nereikia.

## 5.7. Našumas ir mastelio keitimas _(angl. [Performance and Scalability](https://www.viewpoints-and-perspectives.info/home/perspectives/performance-and-scalability/))_

| Aspektas                                     | Taikymas |
|----------------------------------------------|----------|
| **Taikymas _(angl. Applicability)_**         |          |
| **Sprendžiami rūpesčiai _(angl. Concerns)_** |          |
| **Veiksmai _(angl. Activities)_**            |          |
| **Taktikos _(angl. Tactics)_**               |          |
| **Spąstai _(angl. Pitfalls)_**               |          |

## 5.8. Teisinis reguliavimas _(angl. [Regulation](https://www.viewpoints-and-perspectives.info/home/perspectives/regulation-perspective/))_
Nereikia.

## 5.9. Saugumas _(angl. [Security](https://www.viewpoints-and-perspectives.info/home/perspectives/security/))_

| Aspektas                                     | Taikymas                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
|----------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Taikymas _(angl. Applicability)_**         | Ši perspektyva taikoma visiems ITIS sistemos komponentams, siekiant apsaugoti klientų duomenis, mokėjimų informaciją ir užtikrinti, kad tik įgalioti naudotojai galėtų pasiekti savo duomenis. Saugumas yra kertinis sistemos aspektas, nes ji tvarko asmeninius ir finansinius duomenis.                                                                                                                                                                                                                                                                                                                                                                            |
| **Sprendžiami rūpesčiai _(angl. Concerns)_** | - Kaip autentifikuojami naudotojai (klientai ir administratoriai).<br>- Kaip užtikrinamas duomenų vientisumas ir konfidencialumas.<br>- Kaip valdomos prieigos teisės (rolės ir leidimai).<br>- Kaip apsisaugoma nuo įsilaužimų, CSRF, XSS, SQL Injection atakų.<br>- Kaip saugomos ir perduodamos jautrios reikšmės (pvz., slaptažodžiai).<br>- Kaip fiksuojami ir stebimi saugumo incidentai.                                                                                                                                                                                                                                                                      |
| **Veiksmai _(angl. Activities)_**            | - Įdiegti **Symfony Security** modulį autentifikacijai ir rolėms valdyti.<br>- Naudoti **CSRF token'us** formoms ir POST užklausoms.<br>- Naudoti **HTTPS (SSL)** visam duomenų srautui tarp naudotojo ir serverio.<br>- Slaptažodžių saugojimui naudoti saugius **bcrypt / Argon2** algoritmus.<br>- Diegti **log'inimą su Monolog**, fiksuojant prisijungimus, nesėkmingus bandymus.<br/>- Diegti klaidų gaudymo įrankį **Sentry**, kuris iškart informuoja administratorius.<br>- Reguliariai testuoti sistemą naudojant **OWASP ZAP / Burp Suite**.<br/>- Pasitelkti išorinius testuotojus.<br>- Stebėti serverių saugumą, OS atnaujinimus, PHP klaidų taisymus. |
| **Taktikos _(angl. Tactics)_**               | - Įgyvendinti **mažiausių privilegijų principą (Least Privilege Principle)** – kiekvienas naudotojas turi tik jam būtinas teises.<br>- **Defence in Depth** – keli apsaugos sluoksniai (serveris, DB, aplikacija, tinklas).<br>- **Input validation** – duomenų įvesties validacija prieš apdorojant.<br>- **Error handling & logging** – saugūs klaidų pranešimai be jautrios informacijos.<br>- **Session management** – ribotas sesijų galiojimo laikas ir automatinis atsijungimas.<br>- **Security by default** – išjungtos nereikalingos paslaugos, aiškūs konfigūracijos failai.                                                                              |
| **Spąstai _(angl. Pitfalls)_**               | - Prasta rolių valdymo sistema leidžia neautorizuotą prieigą prie administracinės dalies.<br>- Neužšifruotas srautas (HTTP vietoje HTTPS) gali leisti duomenų perėmimą.<br>- Netinkamai valdomos sesijos (neuždarius prisijungimų).<br>- Perteklinė klaidų informacija gali atskleisti sistemos struktūrą.<br>- Nepakankamas log'ų stebėjimas lemia saugumo incidentų praleidimą.<br>- Trūksta periodinių saugumo auditų ir testavimo procesų.                                                                                                                                                                                                                       |

## 5.10. Naudojimo patogumas _(angl. [Usability](https://www.viewpoints-and-perspectives.info/home/perspectives/usability-perspective/))_
Nereikia.

# Priedai
## Priedas 1. Funkcinio vaizdo Use Case diagramos kodas
```plantuml
@startuml
left to right direction

actor Klientas as Client
actor "Vadybininkas" as Vadybininkas
actor "E-mokėjimų sistema" as PaymentProvider
actor "El. pašto sistema" as Email

rectangle "ITIS sistema" {

  (Peržiūrėti paslaugas) as UC_101
  (Peržiūrėti sąskaitas) as UC_102
  ' (Atsisiųsti sąskaitą) as UC_103
  (Apmokėti sąskaitą) as UC_104
  (Pateikti klausimą) as UC_105

  (Kurti klientą) as UC_201
  (Redaguoti klientą) as UC_202
  (Tvarkyti objektus) as UC_203
  (Valdyti paslaugas) as UC_204
  (Valdyti paslaugų paketus) as UC_205
  (Koreguoti akcijas) as UC_206
  (Atsakyti į klausimus) as UC_207
  (Tvarkyti klausimų kategorijas) as UC_208

  (Generuoti sąskaitas automatiškai) as UC_301
  (Atnaujinti mokėjimo būseną) as UC_302
  (Išsiųsti pranešimą el. paštu) as UC_303
  (Klaidų log'inimas) as UC_304
}

' Kliento ryšiai
Client --> UC_101
Client --> UC_102
'Client --> UC_103
Client --> UC_104
Client --> UC_105

' Vadybininko ryšiai
Vadybininkas --> UC_201
Vadybininkas --> UC_202
Vadybininkas --> UC_203
Vadybininkas --> UC_204
Vadybininkas --> UC_205
Vadybininkas --> UC_206
Vadybininkas --> UC_207
Vadybininkas --> UC_208

' Sistemos ryšiai
'Vadybininkas --> UC_304

' Sistemos procesai
UC_104 --> PaymentProvider : "Inicijuoti mokėjimą"
PaymentProvider --> UC_302 : "Callback: mokėjimo būsena"

' El. pašto sistema
UC_303 <-- Email

' Automatiniai procesai
UC_301 --> UC_303 : "Siųsti sąskaitas"
UC_301 --> UC_102 : "Kurti sąskaitų įrašus"

@enduml
```

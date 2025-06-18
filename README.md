# VL Woordenboek 

Een open-source woordenboek voor Vlaamse woorden en uitdrukkingen.

## 📖 Over dit project 

Welkom bij **VL Woordenboek!** Dit project is een verzameling van typisch Vlaamse woorden en uitdrukkingen, bedoeld om deze taalrijkdom te documenteren en toegankelijk te maken voor iedereen.
Of je nu een liefhebber bent van taal, een onderzoeker, of gewoon nieuwsgierig naar de rijke Vlaamse woordenschat, je bent hier op de juiste plek. 

VL Woordenboek is volledig open-source en groeit dankzij de bijdragen van enthousiaste taal- en techliefhebbers.
Samen bouwen we een complete en interactieve database waarin Vlaamse taal en cultuur bewaard blijven. 
We nodigen je uit om mee te helpen en dit woordenboek nog beter te maken! 

Dit project is ontwikkeld met **Laravel**, **Laravel Filament** en **Bootstrap**, wat zorgt voor een moderne en gebruiksvriendelijke interface voor zowel bezoekers als bijdragers.

## 🚀 Functionaliteiten

De volgende functionaliteiten hebben we al reeds op de implementatie planning staan of uitgevoerd: 

- 📚 **Zoekfunctie** om snel een woord of uitdrukking te vinden.
- 📝 **Mogelijkheid om nieuwe woorden en definities toe te voegen.**
- 🔍 **Gedetailleerde definities en voorbeeldzinnen** per woord.
- 🌍 **Eenvoudige en toegankelijke webinterface.**
- 🏷 **Categorieën en tags om woorden gemakkelijk te organiseren.**
- 🔄 **Suggestiesysteem** waarmee gebruikers verbeteringen kunnen voorstellen voor bestaande woorden.

## 🧐 Bijdragen

Wil je helpen om dit project beter te maken? Dat zou geweldig zijn! O0f je nu een nieuw record toevoegt, een fout corrigeert oàf een nieuwe functie voorstelt, alle hilp is welkom. 

### Hoe bijdragen? 

1. **Fork deze repository** naar je eigen GitHub-account. 
2. **Maak een nieuwe branch** voor je wijziging:
   ```sh
   git checkout -b feature/nieuwe-functionaliteit
    ```
3. **Doe je aanpassingen en commit ze:**
   ```sh
   git commit -m "Voeg nieuwe woorden en definities toe"
    ```
4. **Push je fork en open een pull request. **

Elke bijdragen, hoe klein ook, helpt om dit project beter te maken. 
Laten we samen bouwen aan een uitgebreiçd en authentiek Vlaams Woordenboek!

## 📜 Gedragscode

We streven naar een vriendelijke, respectvolle en inclusieve community. Door bij te dragen aan dit project, ga je akkoord met onze [Code of Conduct](https://github.com/vl-wbk/.github/blob/main/CODE_OF_CONDUCT.md).
We verwachten van alle bijdragers dat ze anderen met respect behandelen en samenwerken in een positieve sfeer. 

## 📜 Licentie

Dit project is gelicenseerd onder de MIT-licentie. Zie het bestand [LICENSE](/LICENSE) voor meer informlatie.

## 📞 Contact & Community

Wil je meedenken, feedback geven of gewoon je liefde voor de Vlaamse taal delen? We horen graag van je! 

- Open een [een GitHub issue](https://github.com/vl-wbk/vl-woordenboek/issues/new) voor vragen, suggesties of problemen.
- Doe mee aan discussies en help ons om het project verder uit te bouwen.
- Heb je een idee voor een verbetering? Aarzel niet om een pull request in te dienen. 

Samen kunnen we een waardevolle bron creeren voor iedereen die de Vlaamse taal en cultuur wil ontdekken en bewaren. Sluit je aan en draag bij!

## 💻 Lokale ontwikkeling

Wil je het project lokaal draaien voor ontwikkeling? Volg deze stappen om aan de slag te gaan:

### Vereisten

- PHP 8.2 of hoger
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/) (v16 of hoger)
- [npm](https://www.npmjs.com/get-npm) of [yarn](https://yarnpkg.com/getting-started/install)
- Docker (tenzij je zelf een database gaat voorzien)

### Voorbereiding

1. **Clone de repository**
   ```sh
   git clone https://github.com/vl-wbk/vl-woordenboek.git
   cd vl-woordenboek
   ```

2. **Installeer PHP dependencies**
   ```sh
   composer install
   ```

3. **Installeer JavaScript dependencies**
   ```sh
   npm install
   # of
   yarn install
   ```

4. **Maak een kopie van het .env bestand**
   ```sh
   cp .env.example .env
   ```

5. **Genereer een applicatie sleutel**
   ```sh
   php artisan key:generate
   ```

### Installatie mbv sail

1. **Configureer je database verbinding in het .env bestand**

   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=vl_woordenboek
   DB_USERNAME=laravel
   DB_PASSWORD=laravel

2. **Start de database en de applicatie**

    vendor/bin/sail up -d

3**Voer de database migraties uit**
   ```sh
   vendor/bin/sail artisan migrate
   ```

4**Seed de database met testgegevens (optioneel)**
   ```sh
   vendor/bin/sail artisan db:seed
   ```

Je bent nu klaar, de applicatie is beschikbaar op [http://localhost:8000](http://localhost:8000)

### Filament Admin Panel

Het admin panel is beschikbaar op [http://localhost:8000/admin](http://localhost:8000/admin).

### Handige commando's

- **Cache wissen**
  ```sh
  vendor/bin/sail artisan optimize:clear
  ```

- **Tests uitvoeren**
  ```sh
  vendor/bin/sail artisan test
  ```

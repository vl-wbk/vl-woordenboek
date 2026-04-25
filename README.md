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

De volgende functionaliteiten hebben we al reeds op de implementatie-planning staan of uitgevoerd:

- 📚 **Zoekfunctie** om snel een woord of uitdrukking te vinden.
- 📝 **Mogelijkheid om nieuwe woorden en definities toe te voegen.**
- 🔍 **Gedetailleerde definities en voorbeeldzinnen** per woord.
- 🌍 **Eenvoudige en toegankelijke webinterface.**
- 🏷 **Categorieën en tags om woorden gemakkelijk te organiseren.**
- 🔄 **Suggestiesysteem** waarmee gebruikers verbeteringen kunnen voorstellen voor bestaande woorden.

## 🐳 Lokaal ontwikkelen met Docker

### Vereisten

- [Docker](https://docs.docker.com/get-docker/) en [Docker Compose](https://docs.docker.com/compose/)

### Stappen

1. **Kopieer de omgevingsvariabelen en genereer een app-sleutel:**
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```

2. **Start de containers:**
   ```sh
   docker compose up -d
   ```

3. **Voer de migraties en seeders uit:**
   ```sh
   docker compose exec app php artisan migrate --seed
   ```

4. **Open de applicatie** op [http://localhost](http://localhost).

### Handige commando's

| Commando | Beschrijving |
|---|---|
| `docker compose exec app composer test` | Voer de testsuite uit |
| `docker compose exec app php artisan tinker` | Open de Laravel REPL |
| `docker compose logs -f app` | Bekijk de applicatielogs |
| `docker compose down` | Stop alle containers |

## 🚀 Synchronisatie

Omdat we boilerplate code van `laravel/laravel` up-to-date willen houden synchroniseren we regelmatig de code van hun starter-template met ons project.
Zo was de laatste synchronistatie uitgevoerd op 18/04/2026 *(v13.2.0)*

## 🧐 Bijdragen

Wil je helpen om dit project beter te maken? Dat zou geweldig zijn! Of je nu een nieuw record toevoegt, een fout corrigeert of een nieuwe functie voorstelt, alle hulp is welkom.

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
4. **Push je fork en open een pull request.**

Elke bijdrage, hoe klein ook, helpt om dit project beter te maken.
Laten we samen bouwen aan een uitgebreid en authentiek Vlaams Woordenboek!

## 📜 Gedragscode

We streven naar een vriendelijke, respectvolle en inclusieve community. Door bij te dragen aan dit project, ga je akkoord met onze [Code of Conduct](https://github.com/Tjoosten/vl-woordenboek/blob/develop/CODE_OF_CONDUCT.md).
We verwachten van alle bijdragers dat ze anderen met respect behandelen en samenwerken in een positieve sfeer.

## 📜 Licentie

Dit project is gelicenseerd onder de MIT-licentie. Zie het bestand [LICENSE](/LICENSE) voor meer informlatie.

## 📞 Contact & Community

Wil je meedenken, feedback geven of gewoon je liefde voor de Vlaamse taal delen? We horen graag van je!

- Open een [een GitHub issue](https://github.com/Tjoosten/vl-woordenboek/issues/new) voor vragen, suggesties of problemen.
- Doe mee aan discussies en help ons om het project verder uit te bouwen.
- Heb je een idee voor een verbetering? Aarzel niet om een pull request in te dienen.

Samen kunnen we een waardevolle bron creeren voor iedereen die de Vlaamse taal en cultuur wil ontdekken en bewaren. Sluit je aan en draag bij!

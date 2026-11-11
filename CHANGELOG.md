# Changelog

## [1.4.1](https://github.com/vl-wbk/vl-woordenboek/releases/tag/v1.4.1) (2026-02-25)

## [2.0.0](https://github.com/vl-wbk/vl-woordenboek/releases/tag/v2.0.0) (2026-11-11)

### ⚠ BREAKING CHANGES

- switch config variables naar attributes (#624) ([7ae362e7](https://github.com/vl-wbk/vl-woordenboek/commit/7ae362e7b90933b061afff4c0e4797d96f204248))
  - Models gebruiken nu attributes ipv properties.
- switch config variables naar attributes (#624) ([17e46499](https://github.com/vl-wbk/vl-woordenboek/commit/17e464995b715b8e688afd255916e0caecca35d3))
  - Models gebruiken nu attributes ipv properties.

### Features

- toevoegen van een quick reference actie ([d45bba61](https://github.com/vl-wbk/vl-woordenboek/commit/d45bba6101a05917954b558d5f884df41e76d3fd))
- toevoegen van duplicatie tabel in artikelen ([850532ca](https://github.com/vl-wbk/vl-woordenboek/commit/850532ca9cf6a272094aebdacbd19f84170284a7))
- toevoegen info weergave voor woordsoort ([9ebc0d34](https://github.com/vl-wbk/vl-woordenboek/commit/9ebc0d34bdf74a67432840251f750ed159d3e701))
- suggestie wachtrij uitbreiden met tabs (onbehandeld/kladartikelen/afgewezen) (#635) ([9b11157e](https://github.com/vl-wbk/vl-woordenboek/commit/9b11157e0678fa2cab0d4d806a5c44ab0240c4d3))
- Implementatie van een suggestiebeleid met quotumbeperking (#623) ([c22e2efc](https://github.com/vl-wbk/vl-woordenboek/commit/c22e2efc19e72cb8aeab31bd33b24640cad9f3d5))
- **articles:** optimaliseer workflow en opslaan bij sluiten ([38913b11](https://github.com/vl-wbk/vl-woordenboek/commit/38913b113acc942e6233be6e5b97448e34b01529))
- Implementatie van een suggestiebeleid met quotumbeperking (#623) ([52fefe84](https://github.com/vl-wbk/vl-woordenboek/commit/52fefe842a30bd449338f7e6a198cabc31e43688))
- **articles:** optimaliseer workflow en opslaan bij sluiten ([cb5fb82c](https://github.com/vl-wbk/vl-woordenboek/commit/cb5fb82c15d1651c1d0f1e841580e4dc3e71833f))
- voor kaartje toe voor regionale spreiding ([8769a2b6](https://github.com/vl-wbk/vl-woordenboek/commit/8769a2b6c2f359ca01125b8de77191b33cf186ab))
- Toevoegen ID en snelle navigatie voor artikelen ([ec63b749](https://github.com/vl-wbk/vl-woordenboek/commit/ec63b7498428bbf89aba77c343ea1b36549bde49))
- **voorbeeldzinnen:** implementeer dynamisch beheer van voorbeeldzinnen ([fedce7a8](https://github.com/vl-wbk/vl-woordenboek/commit/fedce7a8cf690a63dd35a5a236f13194698dcb0d))
- **editorial:** implementeer duplicatie policy met workflow-specifieke foutmeldingen (#526) ([7f2854b2](https://github.com/vl-wbk/vl-woordenboek/commit/7f2854b24af3a319ab854747d9c6fa1e04b780e1))
- Reputatiesysteem + fast path voor artikelcorrecties (#569) ([b5710afa](https://github.com/vl-wbk/vl-woordenboek/commit/b5710afa260edf358d097b27080cf47d1e17a341))
- implementatie decay systeem voor de reputatie (#570) ([1357d617](https://github.com/vl-wbk/vl-woordenboek/commit/1357d617ddb6d5531bc61c0659f4342d771973ab))
- embed of the correction reason in the audit ([39db0a5a](https://github.com/vl-wbk/vl-woordenboek/commit/39db0a5a61f6e9dc3b5161e4249223205f796626))
- **config:** add magical warnings to rate limiting docs ([78442065](https://github.com/vl-wbk/vl-woordenboek/commit/7844206502063c97358c0a445902dcbae8d557b4))
- nieuwe weergave voor statistieken ([0a141ab1](https://github.com/vl-wbk/vl-woordenboek/commit/0a141ab13c335264f65ff2dd2dad50586aa0a432))
- openbare log voor bestanden. (#559) ([66125b10](https://github.com/vl-wbk/vl-woordenboek/commit/66125b10d3f03e0e2b76b638993f21cadeca0083))
- introductie van een Attribute-gebaseerd TODO-systeem ([cba2c54d](https://github.com/vl-wbk/vl-woordenboek/commit/cba2c54d1debaf003644cce656d195a9475874ac))
- toevoegen van de woordvergelijker ([46a3bd5f](https://github.com/vl-wbk/vl-woordenboek/commit/46a3bd5fc47c136141b33808dbbd5942a17d265e))

### Bug Fixes

- zoeken met 'bevat' geeft consistente resultaten bij meerdere woorden (#655) ([2998a670](https://github.com/vl-wbk/vl-woordenboek/commit/2998a670ed14a8110592eeb0341a3bdbb79ec884))
- **#640:** Bevestigingsbericht blokkeert formulier niet meer (#642) ([71a17c08](https://github.com/vl-wbk/vl-woordenboek/commit/71a17c087e2a9a15ab76d4312812f79d44bd725b))
- **#647:** Prioriteit van zoekresultaten en verbeter type-safety (#648) ([a652e882](https://github.com/vl-wbk/vl-woordenboek/commit/a652e882ba562d32d50b855ffe4a0215a4ff203e))
- **auth:** voorkom agressieve wachtwoord-popup in Firefox ([c418729d](https://github.com/vl-wbk/vl-woordenboek/commit/c418729d5ab08dfbffcf4ae992183cfc6d750d39))
- word schema ([ffc831b3](https://github.com/vl-wbk/vl-woordenboek/commit/ffc831b32653365d39367013b525358db7017b66))
- **#644:** toevoegen van artikel info in de meldingsweergave ([b44414ac](https://github.com/vl-wbk/vl-woordenboek/commit/b44414acee088149075cdac36bd6b38ee81b7014))
- resolve view bug ([9a7af2cb](https://github.com/vl-wbk/vl-woordenboek/commit/9a7af2cb7614aa2ad389c9214fc0100fc0a4ace2))
- **#639:** woord invullen bij suggestieformulier na lege zoekopdracht ([dececae7](https://github.com/vl-wbk/vl-woordenboek/commit/dececae70a752916236f4b064e522d5d7e15d3e6))
- **#641:** zichtbaarheid van de heatmap legende ([25069dd5](https://github.com/vl-wbk/vl-woordenboek/commit/25069dd5cede06f0e132bf7020afb31688d50e6f))
- **data:** los PHP deprecated waarschuwing op in constructor van ExampleSentenceData ([2ca16ef9](https://github.com/vl-wbk/vl-woordenboek/commit/2ca16ef989f84ae21b0e1971523ab431c15a2c97))
- **#637:** Verwijderen van verkeerde return type (#638) ([e1eda11c](https://github.com/vl-wbk/vl-woordenboek/commit/e1eda11c04727f92ee05f48f1a724bb051c666e3))
- dupliceer ook voorbeeldzinnen bij artikel duplicatie ([b8a80d5b](https://github.com/vl-wbk/vl-woordenboek/commit/b8a80d5b71651458fcb025f08e24e989d81dd35a))
- lock filament/filament op v5.7 ([10d29d31](https://github.com/vl-wbk/vl-woordenboek/commit/10d29d319209450c734a0d95bd76f359042b36d8))
- suggesties => Mijn bijdrages ([2c4391d4](https://github.com/vl-wbk/vl-woordenboek/commit/2c4391d41a22461c7cc25b80184fa0d0e3fdc9b2))
- dateInterface in het clean up commando ([851687eb](https://github.com/vl-wbk/vl-woordenboek/commit/851687eb758fc16c449457ee46044169600c777e))
- **#618:** up en downvotes text onder knoppen ([3ab21298](https://github.com/vl-wbk/vl-woordenboek/commit/3ab21298c1a60bfe343d7868f223a318c7cb261a))
- **#600:** Meld een probleem hernoemen naar 'tip de redactie' ([d2fed03f](https://github.com/vl-wbk/vl-woordenboek/commit/d2fed03f75329d4a2a815344ebcaeb7cac8353f5))
- **#568:** Infrastructuur & workflow opzet staging-omgeving ([26f12c5c](https://github.com/vl-wbk/vl-woordenboek/commit/26f12c5c457f8ddccfe0d8d546c06bff72e71620))
- **#614:** voorbeeldzinnen vroegtijdig afbreken in de admin ([9ac97f50](https://github.com/vl-wbk/vl-woordenboek/commit/9ac97f5005894067f61b32d68de1c8a555c6ee92))
- publicatie count in het openbaar profiel ([dadbcab3](https://github.com/vl-wbk/vl-woordenboek/commit/dadbcab3f8ed903e18cc6cc53f54ee12b3dbe612))
- **#606:** Fix wording archief zoeken text ([d73ff676](https://github.com/vl-wbk/vl-woordenboek/commit/d73ff67610a49b763fcdf8787eab36b5caa6b14d))
- **#607:** Kernwoorden hernoemen naar alternatieve zoektermen ([514fea7b](https://github.com/vl-wbk/vl-woordenboek/commit/514fea7b01fa2c9a1ba9ffd923360df6fa945e38))
- **#609:** koppelteken als standaardwaarde bij kenmerken veld ([6c3eaae6](https://github.com/vl-wbk/vl-woordenboek/commit/6c3eaae6eccacf4786c9875eccf9bc0d116ddde1))
- **#612:** Voorbeelden worden niet getoond bij bekijken suggestie ([1e00bda1](https://github.com/vl-wbk/vl-woordenboek/commit/1e00bda19b4fe938bd2f87ab335139834afb3b93))
- **#613:** dt-fout in opmerking bij concept ([89b5d460](https://github.com/vl-wbk/vl-woordenboek/commit/89b5d46008d6bb643c6ec36f1aafae1d18edccc8))
- **#599:** Optie om disclaimer uit te schakelen bij meldingen ([2504b740](https://github.com/vl-wbk/vl-woordenboek/commit/2504b740cc6d94e23259693212a59ddaa8855835))
- **ui:** Herontwerpen van de recente artikelen ([25c2c28d](https://github.com/vl-wbk/vl-woordenboek/commit/25c2c28d41c4333e5a6841dc912064cb12a8286b))
- **article:** sta het dupliceren van suggesties (nieuwe artikelen) toe ([97c14ab3](https://github.com/vl-wbk/vl-woordenboek/commit/97c14ab3058f3ffd4c173137a7ed18a6636fb591))
- **docs:** Documenteren van de correctionProposalPolicy ([0055d01a](https://github.com/vl-wbk/vl-woordenboek/commit/0055d01aa36a37e02dcbb5764da855ee5b672d54))
- lock filament/filament op v5.7 ([131a1b41](https://github.com/vl-wbk/vl-woordenboek/commit/131a1b41c4cb1a806679bb1238e1e3942e62b09c))
- suggesties => Mijn bijdrages ([b72e871a](https://github.com/vl-wbk/vl-woordenboek/commit/b72e871af746f37d900ee79b4402d4b1548a73f2))
- dateInterface in het clean up commando ([9b76c743](https://github.com/vl-wbk/vl-woordenboek/commit/9b76c74304e5d1943d9f135658ff18699ada216f))
- **#618:** up en downvotes text onder knoppen ([1e2c1710](https://github.com/vl-wbk/vl-woordenboek/commit/1e2c1710f90f148795d9b27efa568a4c0007baa1))
- **#600:** Meld een probleem hernoemen naar 'tip de redactie' ([68de71e7](https://github.com/vl-wbk/vl-woordenboek/commit/68de71e76130007720ddd26f4c0ebb84753623f0))
- **#568:** Infrastructuur & workflow opzet staging-omgeving ([d1d0dc87](https://github.com/vl-wbk/vl-woordenboek/commit/d1d0dc87c18b4cdf3aea6d6c078ba5e5c9d18d4b))
- **#614:** voorbeeldzinnen vroegtijdig afbreken in de admin ([974f93d1](https://github.com/vl-wbk/vl-woordenboek/commit/974f93d1cc5ad538f79e70e8107165b4e9115e5d))
- publicatie count in het openbaar profiel ([9148a232](https://github.com/vl-wbk/vl-woordenboek/commit/9148a232229df1f5a62205a769f1554e5980f3ca))
- **#606:** Fix wording archief zoeken text ([e8942f6d](https://github.com/vl-wbk/vl-woordenboek/commit/e8942f6d91a77400908ff656c76916b80be81574))
- **#607:** Kernwoorden hernoemen naar alternatieve zoektermen ([9b359675](https://github.com/vl-wbk/vl-woordenboek/commit/9b3596753a3e98298bc4f72527a58d85157af7e1))
- **#609:** koppelteken als standaardwaarde bij kenmerken veld ([e5d1780e](https://github.com/vl-wbk/vl-woordenboek/commit/e5d1780e2c8f97d3eb4a61234ee14b290915e729))
- **#612:** Voorbeelden worden niet getoond bij bekijken suggestie ([b8f35d25](https://github.com/vl-wbk/vl-woordenboek/commit/b8f35d25c4b35a1eb59730c63e2b330d39c3c08f))
- **#613:** dt-fout in opmerking bij concept ([b0b454ed](https://github.com/vl-wbk/vl-woordenboek/commit/b0b454ed0a0ad97ebaf3beec751502a857c3b7e4))
- **#599:** Optie om disclaimer uit te schakelen bij meldingen ([03800f90](https://github.com/vl-wbk/vl-woordenboek/commit/03800f90526ccca7261cd3f2d6fb7243722013aa))
- **ui:** Herontwerpen van de recente artikelen ([5c8a0912](https://github.com/vl-wbk/vl-woordenboek/commit/5c8a09122ddb805876bfd27e678fc7adcc884d76))
- **article:** sta het dupliceren van suggesties (nieuwe artikelen) toe ([9ec4f579](https://github.com/vl-wbk/vl-woordenboek/commit/9ec4f57942dc8beb84a74da9968b8c345553450d))
- **docs:** Documenteren van de correctionProposalPolicy ([b649a5e4](https://github.com/vl-wbk/vl-woordenboek/commit/b649a5e4f9d9c875da579e2c6627e8fca77fbc25))
- archief zoeken. (#592) ([534c4f59](https://github.com/vl-wbk/vl-woordenboek/commit/534c4f59314d1afbae8a3d1f6f34301fb6c9d9bd))
- Zichtbaarheids bug in the notitie create weergave ([e054d1ef](https://github.com/vl-wbk/vl-woordenboek/commit/e054d1ef8a1a90bf01ce58ff5de37f4ddbe8a76e))
- **audit:** voorkom lege audit records bij ongewijzigde velden ([6e3eb459](https://github.com/vl-wbk/vl-woordenboek/commit/6e3eb459724ebf80d7e8e0b1c3a0ab34bc453e41))
- gast gebruikers moeten een melding kunnen aanmaken ([c20bea9e](https://github.com/vl-wbk/vl-woordenboek/commit/c20bea9e7e50431caa9b45b818345fc9141fefca))
- **wording:** #590 Nieuwe issue voor 'nieuwe toevoeging' in Github ([2ba7b162](https://github.com/vl-wbk/vl-woordenboek/commit/2ba7b16218987394fe10ee42e45744f5641eb731))
- toestaan dat afgewezen suggesties ingezonden worden ([a8435280](https://github.com/vl-wbk/vl-woordenboek/commit/a8435280a40b17e67fc812a21982122c7146d8e9))
- afgewezen publicaties kunnen niet bewerkt worden ([5eaf50de](https://github.com/vl-wbk/vl-woordenboek/commit/5eaf50de2ce5528848f03dee666d626fd273e49c))
- ID's in de zoekfunctie voor gerelateerde artikelen ([87929995](https://github.com/vl-wbk/vl-woordenboek/commit/87929995da3e0fcb4dac5c1364f128a927a4d5b1))
- Empty states voor het gebruikers beheer ([bf2d2a6c](https://github.com/vl-wbk/vl-woordenboek/commit/bf2d2a6cab1fc88d9aa997d45b3b980b6abc5216))
- **search:** koppeltekens in zoekterm breken fulltext zoekopdracht ([b22f1e1f](https://github.com/vl-wbk/vl-woordenboek/commit/b22f1e1fa18f493f7b0f32dff0e516748b7b9680))
- Toevoegen van deny messages -> PartOfSpeechPolicy ([e56f7c68](https://github.com/vl-wbk/vl-woordenboek/commit/e56f7c6833befb5ad1d989f6c1c31ac2a8605826))
- **#578:** Blanco lijnen tussen voorbeeldzinnen ([1b3afcab](https://github.com/vl-wbk/vl-woordenboek/commit/1b3afcab30926d403e8a0d9313e8c0b419d057a9))
- notify_author flag registreerd zich als false na eerste publicatie ([e6cc5565](https://github.com/vl-wbk/vl-woordenboek/commit/e6cc5565358cfe0c8e5b267cd6864c03c532bc98))
- Concepten counter moet enkel maar zichtbaar zijn voor huidige gebruiker ([c5887e45](https://github.com/vl-wbk/vl-woordenboek/commit/c5887e456a8096651a2b69190db2e4a45865ecc1))
- forgotten gate check ([76f5b735](https://github.com/vl-wbk/vl-woordenboek/commit/76f5b7354d0b7c8486ef49a50956617938a70b1a))
- Policy methode om correcties te behandelen. ([17872bbb](https://github.com/vl-wbk/vl-woordenboek/commit/17872bbba6793b7f4906b1e2e9857a355efcebed))
- Toevoegen van factoy voor PartOfSpeech (#563) ([591dbb41](https://github.com/vl-wbk/vl-woordenboek/commit/591dbb41e6c84f36827cf2c6966c881f25b7409c))
- optimaliseren van de suggestie opslag method. ([95437c99](https://github.com/vl-wbk/vl-woordenboek/commit/95437c9977094dbbf584089e267d8ddfa0dd52b8))
- toevoegen van deny message in de category policy ([0d122cd3](https://github.com/vl-wbk/vl-woordenboek/commit/0d122cd3033e52cb7b1e7c3c96c7a606be5b21d6))
- sortering bij de voorbeeldzinnen ([a819a8a0](https://github.com/vl-wbk/vl-woordenboek/commit/a819a8a059bfaa7fea148c62dda1c6fac6e02bd8))
- phpstan max in de database seeders ([23ca06f8](https://github.com/vl-wbk/vl-woordenboek/commit/23ca06f87153baee99d096bd256ee75252f2bb97))
- couple phpstan errors ([1eacfae4](https://github.com/vl-wbk/vl-woordenboek/commit/1eacfae464abee4959bd49e3f8806e21bf21eb94))
- log problemen ([c03e7295](https://github.com/vl-wbk/vl-woordenboek/commit/c03e7295e2d870d4c2e54a7bc56e6f5f8cf29fce))
- random WOTD selecteert ook disclaimer gerelateerde records. ([040e554b](https://github.com/vl-wbk/vl-woordenboek/commit/040e554ba174f420dff427adbdacf9ab7ef97176))
- **#550:** subtitel in GitHub formulier klopt niet. ([f86a4469](https://github.com/vl-wbk/vl-woordenboek/commit/f86a44695e1748777d4e2158a220504825735d51))
- implementatie van een nieuwe favicon + logo ([64ff04a6](https://github.com/vl-wbk/vl-woordenboek/commit/64ff04a637b54b8fdbf31d42d045a725a00491d5))
- compare only published articles ([47638387](https://github.com/vl-wbk/vl-woordenboek/commit/47638387699b8e5aad264410c82f6077781c3959))
- WOTD neemt nu een random woord indien er geen planning is. ([35626494](https://github.com/vl-wbk/vl-woordenboek/commit/356264948f5c6378f2bede76ab444fc2c530c3ae))
- **ui:** Toevoegen van het nieuwe logo in de navigatie balk en favicon. ([adcfc329](https://github.com/vl-wbk/vl-woordenboek/commit/adcfc329d004b1f06be43432f861159da854cca4))
- **#546:** Doortrekken navbar kleur naar de footer. ([04c79fa5](https://github.com/vl-wbk/vl-woordenboek/commit/04c79fa5be87afd8eb4213eb31f8480349d474f1))
- mass-assignment issue on the articles ([71f9be5e](https://github.com/vl-wbk/vl-woordenboek/commit/71f9be5edb08342d1db640a1bf9ae99b83115c3b))
- hernoemen community voorbeeldzinnen naar voorbeeldzinnen ([5d318e1e](https://github.com/vl-wbk/vl-woordenboek/commit/5d318e1ef295e4e11ce872a46579db744a709e8b))
- maak de motivatie verplicht bij registratie vrijwilligers ([13e650db](https://github.com/vl-wbk/vl-woordenboek/commit/13e650dbd1131cf089791d2e22e163966143c604))
- implementatie nieuwe rate limit (#497) ([c23a39c6](https://github.com/vl-wbk/vl-woordenboek/commit/c23a39c6dcbacbdd20eb16bab72f39e001520eeb))
- toevoegen missende docblocks in de ArticlePolicy ([dec2dbc5](https://github.com/vl-wbk/vl-woordenboek/commit/dec2dbc579f4e5aaab0d3ee12db2a20d37ec1096))
- Redacteurs kunnen eigen artikelen aanbieden ter controle zonder permissie vereisten ([1e2eae82](https://github.com/vl-wbk/vl-woordenboek/commit/1e2eae82b58f9195c73e2fffd0f8fc98a3579a46))
- cascade verwijderen van naslagwerken ([91e15ea3](https://github.com/vl-wbk/vl-woordenboek/commit/91e15ea3e117ba081b141fe086ac63bf7f8afd45))

### Code Refactoring

- switch config variables naar attributes (#624) ([7ae362e7](https://github.com/vl-wbk/vl-woordenboek/commit/7ae362e7b90933b061afff4c0e4797d96f204248))
  - Models gebruiken nu attributes ipv properties.
- **filament:** vereenvoudig zoekfunctionaliteit voor gerelateerde woorden ([80833faa](https://github.com/vl-wbk/vl-woordenboek/commit/80833faa403a2539c432d8a7e6231840e970487f))
- switch config variables naar attributes (#624) ([17e46499](https://github.com/vl-wbk/vl-woordenboek/commit/17e464995b715b8e688afd255916e0caecca35d3))
  - Models gebruiken nu attributes ipv properties.
- **filament:** vereenvoudig zoekfunctionaliteit voor gerelateerde woorden ([eeefb92f](https://github.com/vl-wbk/vl-woordenboek/commit/eeefb92f97f6068abb95b03eb773a9e455629125))
- **user-examples:** migreer naar polymorfe relatie 'exampleable' ([b97a097b](https://github.com/vl-wbk/vl-woordenboek/commit/b97a097bde71ec29f6afefebbbcc17661c5c11b9))
- namespae van states in Article.php ([1e4fcc9b](https://github.com/vl-wbk/vl-woordenboek/commit/1e4fcc9b4728d555a62299ca1d82fde4baa7e962))
- make SuggestionKPIWidget strict ([264edc0e](https://github.com/vl-wbk/vl-woordenboek/commit/264edc0e786a1257ed08546682feac1cbaea5f35))
- **messaging:** voeg specifieke foutmeldingen toe aan ThreadPolicy ([7435a44a](https://github.com/vl-wbk/vl-woordenboek/commit/7435a44a9de4e082b389cfb726a2f8f9a75951f7))
- **auth:** voeg specifieke foutmeldingen toe aan UserPolicy authorisatie ([df76fb3e](https://github.com/vl-wbk/vl-woordenboek/commit/df76fb3ecf1e14d6b0e2c01399a7ed7371f926ec))
- update policy method voor een artikel ([822c61bb](https://github.com/vl-wbk/vl-woordenboek/commit/822c61bb8e84b53256ec167e380d2a6ca7f9cadf))
- replace MenuItem with Action in the panel config ([382d7cb0](https://github.com/vl-wbk/vl-woordenboek/commit/382d7cb0c87978778c3b9e6a62a590ac6936d1d8))
- change region badges to links for improved navigation ([c2768f97](https://github.com/vl-wbk/vl-woordenboek/commit/c2768f97a900df772790d00787f10c90c726d4f7))
- **ui:** update paragraaf-class in gebruikersvoorbeelden voor betere spatiëring ([a0dd7e30](https://github.com/vl-wbk/vl-woordenboek/commit/a0dd7e301e6e83a1a1c3f774a35e55394f54a774))
- verbeter ExampleSentence componenten ([67ba312e](https://github.com/vl-wbk/vl-woordenboek/commit/67ba312e433c9ef585c136d8001e5074db1bce1e))
- update datumlabels in ArticleRegistrationChart naar vertaalde opmaak ([c28b5a56](https://github.com/vl-wbk/vl-woordenboek/commit/c28b5a5636bd1d9e06cbc62838cad78eab8d5be4))

### Documentation

- toevoegen van docblocks voor het concept model ([33af5b8a](https://github.com/vl-wbk/vl-woordenboek/commit/33af5b8aa97a1529a66eae5a97563744c4d7c67d))
- documenteer public profile component ([a6ddbe9c](https://github.com/vl-wbk/vl-woordenboek/commit/a6ddbe9c5b58e0793c2a4d448101d9daaa0a9542))
- Toevoegen van docblocks voor de model observers ([22f5ce24](https://github.com/vl-wbk/vl-woordenboek/commit/22f5ce24a020329b5287abd2f5b10620bd3e5f6d))
- toevoegen van docblocks voor het concept model ([236d3f65](https://github.com/vl-wbk/vl-woordenboek/commit/236d3f6502f23e3725b4011270495de3dd2280f2))
- documenteer public profile component ([1e25e54a](https://github.com/vl-wbk/vl-woordenboek/commit/1e25e54a93fe3065ee5f5e142b58d0bece4f4530))
- Toevoegen van docblocks voor de model observers ([b20c4f29](https://github.com/vl-wbk/vl-woordenboek/commit/b20c4f2901f734345d34a0281ac997961b192b01))
- Toevoegen van docblocks ([6b3bc6db](https://github.com/vl-wbk/vl-woordenboek/commit/6b3bc6db9452f44807c41e90f25f31d261c58dca))
- Toevoegen van missende docblocks voor de PartOfSpeech policy ([5ca571be](https://github.com/vl-wbk/vl-woordenboek/commit/5ca571be84a712817d96f4c3645fc9544f074316))
- toevoegen missende docblocks in PasskeyPolicy ([a2c94ace](https://github.com/vl-wbk/vl-woordenboek/commit/a2c94ace3547f7e6e930a2ca1156fc9493bf8b38))
- Toevoegen missende docblocks in FeedbackPolicy ([a3489080](https://github.com/vl-wbk/vl-woordenboek/commit/a348908004c91bd022a8adae56530928590b4935))
- add docblocks to the ManagesReputation trait ([e39b9738](https://github.com/vl-wbk/vl-woordenboek/commit/e39b973871187c13260015ec2f5084e6b2327b72))
- Toevoegen van docblocks voor de Voorbeelzinnen states ([553e9abe](https://github.com/vl-wbk/vl-woordenboek/commit/553e9abe7b20bcfd9ab649e3ffc1b38c9a578dd4))
- Anders verwoorden van de documentatie voor StoreArticleSuggestion ([20e8d16c](https://github.com/vl-wbk/vl-woordenboek/commit/20e8d16cea8c33d01fb5a8fa1bef5beff6710571))
- documenteer de exception in hun benodige docblocks ([5f9d3a62](https://github.com/vl-wbk/vl-woordenboek/commit/5f9d3a62ad6cfbe25fd0f9a01e13b444f5975217))
- toevoegen van enkele missende docblocks. ([6530297a](https://github.com/vl-wbk/vl-woordenboek/commit/6530297ab3d7381c4c6b2e8ec8e4d9fe13242062))
- fix typos in het article model ([bbc1cfa7](https://github.com/vl-wbk/vl-woordenboek/commit/bbc1cfa7e724a70e930c9f46414847aeb23b31e0))
- reformat van properties in het article model. ([2c1fd58f](https://github.com/vl-wbk/vl-woordenboek/commit/2c1fd58f130db2e91c2d42c72988ecefb2ea0547))
- verhelpen van enkele todo's voor de docblocks ([0916e11f](https://github.com/vl-wbk/vl-woordenboek/commit/0916e11f9e58b50557a7cc5b5efd4a83bfb6efeb))
- toevoegen docblocks voor maintentance notificatie command ([6af9e67b](https://github.com/vl-wbk/vl-woordenboek/commit/6af9e67bb52a457958c38f85c1f5add0e3d8b87d))
- toevoegen doc blocks voor het WOTD discord commando ([1bfa25bf](https://github.com/vl-wbk/vl-woordenboek/commit/1bfa25bf4887fc62a69c4fba175087494df17d8b))
- verhelpen van enkele todo's voor de docblocks ([fbe9f2f7](https://github.com/vl-wbk/vl-woordenboek/commit/fbe9f2f780d0259cd44508fa41a99a87012a0df0))
- toevoegen docblocks voor maintentance notificatie command ([2e4167bc](https://github.com/vl-wbk/vl-woordenboek/commit/2e4167bccc3c7357e97a0f0fe08c57fd6c46ea22))
- toevoegen doc blocks voor het WOTD discord commando ([138607f6](https://github.com/vl-wbk/vl-woordenboek/commit/138607f60514cc438736e432a8889e586a51e649))
- Toevoegen docblock missende create policy ([1dd6f8a4](https://github.com/vl-wbk/vl-woordenboek/commit/1dd6f8a413cdf9ea7aed73238125738a1c83def1))
- toevoegen van docblocks voor de ModerationRulePolicy ([83a7dc9c](https://github.com/vl-wbk/vl-woordenboek/commit/83a7dc9c9f8651ec043a7c69f6fade01292f7a6c))
- implementatie docblocks CommunityExampleRelationManager ([de57c461](https://github.com/vl-wbk/vl-woordenboek/commit/de57c461da251de511046d12f8e949893c06172e))
- Toevoegen van docblocks voor de ReactionPolicy ([bfe5e7a2](https://github.com/vl-wbk/vl-woordenboek/commit/bfe5e7a264c1bdb4dc63a751942b2e0f4651fe19))
- toevoegen van docblocks in de disclaimer pages (#524) ([6bfa4206](https://github.com/vl-wbk/vl-woordenboek/commit/6bfa4206b3a006883caf8e82013dd015dc5487bd))
- toevoegen van properties in de VolunteerPosition model ([9885e02e](https://github.com/vl-wbk/vl-woordenboek/commit/9885e02efc1d777e5a570bcfb2c42522c6df7534))

### Chores

- bump filament/filament to 5.7 and higher ([127045ad](https://github.com/vl-wbk/vl-woordenboek/commit/127045ad84cfc9d295376ad78d6ae59fc2508148))
- **#632:** fix wording ([59e1c684](https://github.com/vl-wbk/vl-woordenboek/commit/59e1c684c7493c234f03bc85136a775511c05d73))
- toevoegen ondersteuning in de reports voor omgevingen ([91a535c6](https://github.com/vl-wbk/vl-woordenboek/commit/91a535c6358e65a4c0dd60d446f67c2ed5add645))
- rebuild assets and deps ([57c595f0](https://github.com/vl-wbk/vl-woordenboek/commit/57c595f06282521f11beecfd0167cc76711ef96e))
- wijzigen infomatie in package file (composer.json) ([c2eebe2a](https://github.com/vl-wbk/vl-woordenboek/commit/c2eebe2a409e3b5f775f96104a283134148e5ccd))
- hergenereren van de assets ([3d4098b3](https://github.com/vl-wbk/vl-woordenboek/commit/3d4098b31afd3c2c5c96f2c9411ab9645cff1d6a))
- oplossen van enkele phpstan-errors ([67b24ed0](https://github.com/vl-wbk/vl-woordenboek/commit/67b24ed0af850a72eed6ec618aa578d87ae5e046))
- fixen van een paar phpstan errors ([904ae235](https://github.com/vl-wbk/vl-woordenboek/commit/904ae23512d8f3dc6b29409126d2fac367377e67))
- rebuild assets and deps ([9909648c](https://github.com/vl-wbk/vl-woordenboek/commit/9909648caab5a29ee934e85c89c8b452e5141ca4))
- wijzigen infomatie in package file (composer.json) ([ff5c4023](https://github.com/vl-wbk/vl-woordenboek/commit/ff5c402377780e7526e0af0b8707eadb2d642c1d))
- hergenereren van de assets ([e39a5d8f](https://github.com/vl-wbk/vl-woordenboek/commit/e39a5d8fc0bb65a086d2cb7c0b59eb58173b949d))
- oplossen van enkele phpstan-errors ([208fb726](https://github.com/vl-wbk/vl-woordenboek/commit/208fb7262a407a3d1ffd6d5e68da792a5150ae69))
- fixen van een paar phpstan errors ([4b1ce5dd](https://github.com/vl-wbk/vl-woordenboek/commit/4b1ce5dde7fa0a2dea96b66c8238235eaa4f52e2))
- Bump minimum vereiste naar PHP 8.4 ([fae28bc2](https://github.com/vl-wbk/vl-woordenboek/commit/fae28bc2ad5f1587e5b0200757f144a22c3cbc84))
- toevoegen van een workflow voor een auto reply ([1422e59b](https://github.com/vl-wbk/vl-woordenboek/commit/1422e59b35f1cd5bbe0720af4421dd2eda5c1d8e))
- Verwijderen van ongebruikte imports. ([34643ad7](https://github.com/vl-wbk/vl-woordenboek/commit/34643ad749d06e868bb4fc5a0ded6f107d77b65a))
- prunen van databank migratie bestanden. ([511ea995](https://github.com/vl-wbk/vl-woordenboek/commit/511ea9959030148ee74cb0cdf25244dac7b57b25))
- toevoegen van de Code of Conduct aan de repository. ([58e49fb6](https://github.com/vl-wbk/vl-woordenboek/commit/58e49fb6cc8c1b4c38e8e0018568ece2974c536c))
- rebuild assets ([9500231d](https://github.com/vl-wbk/vl-woordenboek/commit/9500231d1da0ef744f9204546e51d7942f53dcb8))
- update deps ([b2d6a5bf](https://github.com/vl-wbk/vl-woordenboek/commit/b2d6a5bf7272cb5c9a4c41ea61be804264ea659f))
## [1.5.0](https://github.com/vl-wbk/vl-woordenboek/releases/tag/v1.5.0) (2026-02-26)

### Features

- toevoegen van op maat gemaakte dashboards voor de artikelen. (#493) ([5ba4923](https://github.com/vl-wbk/vl-woordenboek/commit/5ba4923ea022a95586012caa553979425508fb58))
- toevoegen van taalkundige varianten in de artikel weergave ([74fc3aa](https://github.com/vl-wbk/vl-woordenboek/commit/74fc3aa260408419a8016fbf8c9450466f5b1766))

### Bug Fixes

- Gebruiker moet vanuit naslagwerken een artikel kunnen bekijken ([6b786fb](https://github.com/vl-wbk/vl-woordenboek/commit/6b786fb4917126b1fbc485a2a0b515867154d8da))
### Features

- makkelijker zoeken vanuit artikel weergave ([08329135](https://github.com/vl-wbk/vl-woordenboek/commit/08329135587b17e0aa4e9f8c7b5c40c24b6b66bc))
- Integreren van externa data artikelen in het dashboard ([0c93576e](https://github.com/vl-wbk/vl-woordenboek/commit/0c93576eb0b2d2f48666a1edb29b6249fdc68684))

### Chores

- opschonen van het ID gebruik in de artikel view ([254fb9d1](https://github.com/vl-wbk/vl-woordenboek/commit/254fb9d1ec830824676f016e7121d364c1cf1920))
## [1.4.0](https://github.com/vl-wbk/vl-woordenboek/releases/tag/v1.4.0) (2026-02-23)

### Features

- toevoegen van een regelaar voor de tekstgrootte van de artikelen ([a894036](https://github.com/vl-wbk/vl-woordenboek/commit/a894036ee60b2051e4f7ee3d111c8705c6bf9b3b))
- Toevoegen van een administratieve toolbar in de artikel weergave (#491) ([1be4f41](https://github.com/vl-wbk/vl-woordenboek/commit/1be4f419a918c81ad40014ae9c2939d8686249aa))

### Bug Fixes

- minimum font-size in regelaar ([21fa714](https://github.com/vl-wbk/vl-woordenboek/commit/21fa714892427ffd3cb9fd4bc66e3b18f8235500))
- toevoegen object-fit: cover aan afbeeldingen in artikelen ([87b7447](https://github.com/vl-wbk/vl-woordenboek/commit/87b7447ac2abd2fe876588f332ac712847e3b16c))
- return types ([f76fa64](https://github.com/vl-wbk/vl-woordenboek/commit/f76fa6459cc2955835742f78f34c7aefd3022cfd))
- return type in voting component ([1a35e8d](https://github.com/vl-wbk/vl-woordenboek/commit/1a35e8dd8251ce21fe1c938b3e0d47b2aad1446a))
- Detacheren van redacteurs na 15 dagen inactiviteit ([c3dc2aa](https://github.com/vl-wbk/vl-woordenboek/commit/c3dc2aab78f59a3a527eb105ce8917688d465fda))
- toevoegen voor een empty state voor de taaladvizen resource ([8a876ce](https://github.com/vl-wbk/vl-woordenboek/commit/8a876ce44eb4b7bd7183576583343dbd330efd93))
- verbied AI LLM's om te scrapen op het VL-WBK ([2e93feb](https://github.com/vl-wbk/vl-woordenboek/commit/2e93febeb03da66a9e99f8933b74e77e09f1869c))

### Code Refactoring

- Gebruiker van attributes voor Elo. builders ([c2259b0](https://github.com/vl-wbk/vl-woordenboek/commit/c2259b0815f17f9061f2904e7ca102f69246952a))
- versimpelen van if/else statements ([002606f](https://github.com/vl-wbk/vl-woordenboek/commit/002606fd535038808ecf88df2b4360281e9b723c))

### Documentation

- Toevoegen van docblocks voor de ArticlesCluster ([a95dc35](https://github.com/vl-wbk/vl-woordenboek/commit/a95dc3516104823f055f16cd1f5404da85ec800f))
- Vervolledigen van de FeedbackStatus enumeratie ([4ff331c](https://github.com/vl-wbk/vl-woordenboek/commit/4ff331c5d46ce96b15029d40959ff32297497c85))
- verwijderen van v1.x hoofding in de changelog ([61065d4](https://github.com/vl-wbk/vl-woordenboek/commit/61065d4a236f6cdb9f66f1e647dcf57e253b9320))

### Continuous Integration

- Remove BooleanInIfConditionRuleFixer BooleanInIfConditionRuleFixer rector rule ([e539980](https://github.com/vl-wbk/vl-woordenboek/commit/e53998011d5b1e9f2b9e74078cfa59322723f4dc))

### Chores

- verbeter de verwoording in de FeedbackObserver docblocks ([dd68e28](https://github.com/vl-wbk/vl-woordenboek/commit/dd68e2828b35c59dd41647655a4878843eb145de))
- verhelpen van enkele grammatische fouten ([fd865c9](https://github.com/vl-wbk/vl-woordenboek/commit/fd865c9e2bdc78aa4d152e396573979ec908152d))
### Features

- Wotd refactoring (#449) ([4c29242](https://github.com/vl-wbk/vl-woordenboek/commit/4c2924224f37de1069ec3a1479508d0fb138df09))
- #447 - woord van de dag melden op Discord ([6be4dbd](https://github.com/vl-wbk/vl-woordenboek/commit/6be4dbd56e24767e0ca56c3f260917f0738e817b))

### Bug Fixes

- laden van gravatar over http: ([3eda93b](https://github.com/vl-wbk/vl-woordenboek/commit/3eda93b253e58c526f78f0e170c7ebfcf2dd1731))
- oplossen van enkele phpstan errors ([5984c57](https://github.com/vl-wbk/vl-woordenboek/commit/5984c57c0572bb65e8871a663a07b8da6332a5ad))
- #462 - Typo ([121ab5a](https://github.com/vl-wbk/vl-woordenboek/commit/121ab5acf99e2b294fae1e16fa75ed19b77ac6d4))
- editor loskoppelen verplaatst naar een meer logische plaats ([8aac010](https://github.com/vl-wbk/vl-woordenboek/commit/8aac01087a4f4eb2981f23b20b116d45127671ba))
- Registreer labels voor artikelbron infolist ([caee380](https://github.com/vl-wbk/vl-woordenboek/commit/caee380b1fe8be0ef35b024487ea7681890d8462))
- verwijder publicatie timestamp wanneer artikel uit archief komt ([f5d2c47](https://github.com/vl-wbk/vl-woordenboek/commit/f5d2c47829e7e4f2d72897745391d92727afba79))
- HTTP 500 - decrementQuietly on likewords ([1886c73](https://github.com/vl-wbk/vl-woordenboek/commit/1886c73e5e4fc0cade1a72d5ff21291d29201b0a))
- Typo's in readme bestand van het project ([c7ab0c0](https://github.com/vl-wbk/vl-woordenboek/commit/c7ab0c01747ebf67a8d259ee8a344785f69bc1fc))
- #441: Links die 1.0 URL format volgen geven 404 ([d4fd422](https://github.com/vl-wbk/vl-woordenboek/commit/d4fd42273d3e5b0b50718789fbec5bb3d484d40d))
- Tabel sortering in de gerelateerde artikelen ([8f520b9](https://github.com/vl-wbk/vl-woordenboek/commit/8f520b946e10565634b824d97841ab915c607e85))
- Redacteurs moete externe en suggesties kunnen verwijderen ([979761b](https://github.com/vl-wbk/vl-woordenboek/commit/979761bff85b1bee33bb09264c87de9a95ea5460))
- sentry TypeError issue ([df898ad](https://github.com/vl-wbk/vl-woordenboek/commit/df898ad37ae0a90056476eea59053c2b0ef60cd2))
- feedback: FB-20251230-0001 ([3dd0b69](https://github.com/vl-wbk/vl-woordenboek/commit/3dd0b699ac937eacd64b6a0b58481ecbb9c00bc4))
- scrollbar gutter naar auto in de frontend ([c76b081](https://github.com/vl-wbk/vl-woordenboek/commit/c76b081b707fbd681058580e2a14bee78145cbbc))
- scrollbar-gutter -> Filament ([f95e0d9](https://github.com/vl-wbk/vl-woordenboek/commit/f95e0d9990adf6907457597bb66feabe2b3b008b))
- toevoegen van 'vertalingen' permissie voor het vertaal systeem ([491e619](https://github.com/vl-wbk/vl-woordenboek/commit/491e6197db7ebf0c257f09c2731d1a9ece793cd8))

### Documentation

- Implementatie missende docblocks in de WordInfolist ([3885ffb](https://github.com/vl-wbk/vl-woordenboek/commit/3885ffb5c02e8b727c21ba975aa69cc39972d813))

### Continuous Integration

- implementeren van een GH workflow voor releases ([5cd18e4](https://github.com/vl-wbk/vl-woordenboek/commit/5cd18e47462bbb297c3c8191edf056bc4d1bed21))
- Verwijderen van auto changelog workflow ([f967a7d](https://github.com/vl-wbk/vl-woordenboek/commit/f967a7d3208a067432495cae8a31aecef61e7cb8))

### Chores

- bump debugbar v3 to v4 ([5ba5b10](https://github.com/vl-wbk/vl-woordenboek/commit/5ba5b10c1d4302216ebd723c83867a6c67e6dc8b))
- bump dependencies ([72330bf](https://github.com/vl-wbk/vl-woordenboek/commit/72330bff20b483891453cae73871f4cfdb2d7c4f))
- implementatie record title attribute voor artikelen ([0b69287](https://github.com/vl-wbk/vl-woordenboek/commit/0b6928738ff8844c0582a117d46351bd114612f1))
- herplannen WTOD ([28edf38](https://github.com/vl-wbk/vl-woordenboek/commit/28edf38a60fdc06fb1e1a70c665b8e7eb87cf1e7))
- verwijderen banner voor de beta ([5407e3d](https://github.com/vl-wbk/vl-woordenboek/commit/5407e3db2183ba7714af30651a2aa0c92cea077e))
- rebuild assets ([8b9b741](https://github.com/vl-wbk/vl-woordenboek/commit/8b9b741055d2ac099c677832d3beb88668a0a258))
## [1.1.0](https://github.com/vl-wbk/vl-woordenboek/releases/tag/v1.1.0) (2025-12-22)

### Features

- Toevoegen van een record title attribute voor VW artikelen ([e2b2d24](https://github.com/vl-wbk/vl-woordenboek/commit/e2b2d24417b2f6c4d6c69161fbd2f24e790e6d2b))

### Bug Fixes

- Artikelen sortering laatste creatie eerst ([7374de0](https://github.com/vl-wbk/vl-woordenboek/commit/7374de021de3e31dabb258cbd9c2b10fff67234f))
- oplossen van 60 phpstan issues ([05a3c7e](https://github.com/vl-wbk/vl-woordenboek/commit/05a3c7e4305096066d58faf9085b9d8551ea154e))

### Documentation

- Toevoegen documentatie voor de PartOfSpeechResource ([eab98da](https://github.com/vl-wbk/vl-woordenboek/commit/eab98dab99e72d84c240c4406f0b1df7dafa6b9c))
- implementeer documentatie voor het woorsoorten formulier ([bf3b9b8](https://github.com/vl-wbk/vl-woordenboek/commit/bf3b9b850ce85990dd5214b1a4cebb37a58acae5))
- Toevoegen documentatie voor de part of speeches table ([97fe4d4](https://github.com/vl-wbk/vl-woordenboek/commit/97fe4d4f88e21e56b65a381da13c4809ab63b3c8))
- Documentatie voor de form requests voor het berichtensysteem ([2aaa4e5](https://github.com/vl-wbk/vl-woordenboek/commit/2aaa4e59fa2bf303ddd0726546f430b12d33671d))

### Chores

- updàate changelog ([9b9fbdd](https://github.com/vl-wbk/vl-woordenboek/commit/9b9fbdd552dd7deae11fe4386cd2dd30fdf2e037))
### Features

- zichtbaarheid voor notities ([a3f9b6c](https://github.com/vl-wbk/vl-woordenboek/commit/a3f9b6cba5f1e83f6534d4339664127051771b6b))
- Toevoegen reply notificatie voor opmerkingen ([7c22eb6](https://github.com/vl-wbk/vl-woordenboek/commit/7c22eb6c9a77a43808e79bd6410d660f732d88f1))

### Bug Fixes

- **#428:** Aanpassen region dropdown in beheerconsole ([2030a9e](https://github.com/vl-wbk/vl-woordenboek/commit/2030a9e7e07c86578529d9a7bf6cc10448089614))
- **#407:** Linken van de disclaimer documentatie ([76e9bd1](https://github.com/vl-wbk/vl-woordenboek/commit/76e9bd1bb6c975f0a7687c00d18775762e72b518))
- Ondersteuning voor gravatar ([1660e2a](https://github.com/vl-wbk/vl-woordenboek/commit/1660e2a2a7301733b7e8151fc5d0d52ed218a117))
- faulty permission names ([64df5b3](https://github.com/vl-wbk/vl-woordenboek/commit/64df5b3fa94a5613d417ac64cd634f93dee75214))
- **#349:** Status van een artikel ([589d43a](https://github.com/vl-wbk/vl-woordenboek/commit/589d43a5e4b1e95f7ddedc284962726cc9fb6364))
- **#412:** Bookmark zoekfunctie probleem ([62c4d66](https://github.com/vl-wbk/vl-woordenboek/commit/62c4d6697abf76ef2190b1b3beda9e0ba9d34050))

### Chores

- samentrekken vertalingen ([6c17ab5](https://github.com/vl-wbk/vl-woordenboek/commit/6c17ab522a0ada6ab3b6c8707ced6234d5571067))
- Docblocks toevoegen voor de ReactionsRelationManager ([03a316a](https://github.com/vl-wbk/vl-woordenboek/commit/03a316ad596da23c53a77520e787da9139ac2979))
- **#425:** toevoegen nieuwe voorwaarden ([6fa74bc](https://github.com/vl-wbk/vl-woordenboek/commit/6fa74bcedaac4abedcc31c729b190901b52d5cd5))
- **#425:** Aanpassing van CC-licentie ([cd44fec](https://github.com/vl-wbk/vl-woordenboek/commit/cd44feccd0d211435a5edaa75dfc44660b8276f2))
- toevoegen note relatie manager vertalingen ([dab2771](https://github.com/vl-wbk/vl-woordenboek/commit/dab27718723efb5dc3f8dcbf122d3fac936e6884))
- implementatie notificatie voor afwijziing van publicatie ([e14b7c0](https://github.com/vl-wbk/vl-woordenboek/commit/e14b7c068f1d60a872d13315a46e3d941abcc1fd))

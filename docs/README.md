# Technische documentatie van het Vlaams Woordenboek 

Deze map bevat interne technische specificaties en richtlijnen voor developers die deze applicatie onderhouden.
Alle documentatie wordt bijhehouden in **Martkdown** om versiebeheer synchroon te houden met de broncode. 

## Documentatie index 

### Architectuur & conventies

- [Docblock standaarden]() \
  Onze specifieke regels voor docblocks (zoals gehanteerd in de codebase.) **Verplicht te volgen bij nieuwe methodes/classes.**

- [Architecture overview]() \
  Uitleg over de mappenstructuur, Service Layer, en de gekozen Design Patterns. 

- [Naming conventions]() \ 
  Richtlijnen voor database tabellen, veriablen en class-naming.

### Data

- [Database schema]() \ 
  Uitleg over complexe relaties, custom types en indexering.

---

Richtlijnen voor het documenteren 

1. **Code first:** Documenteer logica zoveel mogelijk *in* de code met onze standaard docblocks. Gebruik deze `.md` bestanden voor de "high-level" uitleg die niet een docblock past.
2. **Syntax Highlighting:** Gebruik bij code voorbeelden altijd de juiste taal-tag.
3. **Houd het actueel:** Bij een grote wijziging in de logica (zoals een nieuwe Service of Feature) dient de bijhorende documentatie in dezelde Pull Request te worden bijgewerkt.


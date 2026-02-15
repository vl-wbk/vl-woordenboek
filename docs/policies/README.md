# Policy register 

Dit register bevat de documentatie van alle **Laravel Policies** die binnen het Vlaams Woordenboek worden gebruikt.
Het doel is om per module exact te definieren wie welke acties mag uitvoeren. 

## Hoe we Policies gebruiken 

In dit project is de authorisatie strikt gescheiden van de controllers: 

1. **Locatie:** Alle logica staat in `app/Policies/`.
2. **Toepassing:** Controllers gebruiken `$this->authorize('method', $model)` of Middleware.
3. **Front-end:** Blade of Vue componenten gebruiken `@can` of permissie-checks gebaseerd of deze regels. 

--- 

## Beschikbare policies 

Klik op een module om de gedetailleerde regels en toegangsrechten te bekijken: 

| Module                               | Bestand | Omschrijving | 
|:-------------------------------------| :---- | :---- |
| [**Artikelen**](./article-policy.md) | `./article-policy.md` | Beheer van profielen en account instellingen. |


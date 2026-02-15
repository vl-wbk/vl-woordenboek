# Docoumentatie artikelen flow

> [!IMPORTANT]
> Dit document dient als een intern audit document voor de flow van woordenboek artikelen.

## Policies

### Policy: display *(artikelen weergeven)*

```mermaid
graph LR
    Start([Method: display]) --> AllowedStates[Define allowedStates: <br/> Draft or Approval]
    AllowedStates --> Check{"if (isPublished || isArchived) <br/> OR <br/> (user can update && state in allowedStates)"}

    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::denyAsNotFound]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

#### Logische analyse

De logica bepaalt de zichtbaarheid van een artikel op basis van de publicatiestatus en de specifieke rechten van de gebruiker. 
De kern hiervan is een balans tussen publieke toegankelijkheid en interne workflow inzage. 

- **Publieke toegang:** Artikelen die de status `Published` of `Archived` hebben zijn voor iedereen (of in ieder geval de doelgroep) zichtbaar. Dit is de "happy path" voor contentconsumptie. 
- **Interne toegang: (privileged):** gebruikers met `update`-rechten krijgen een ruimere blik. Zij kunnen artikelen zien die nog in de `Draft` of `Approval` fase zitten. 
- **Beveiligingsmechanisme:** In plaats van een generieke `Deny` (HTTP STATUS: 403), gebruikt de policy een `denyAsNotFound` (HTTP STATUS: 404). Dit is een bewuste keuze voor **[Security through obscurity](http://nl.wikipedia.org/wiki/Security_through_obscurity)**: 
  kwaadwillenden kunnen zo niet achterhalen of een artikel uberhaupt bestaat als ze niet de juiste rechten hebben.

---

### Policy: update (wijzigen van artikelen)

```mermaid
graph LR
    Start([Method: update]) --> CheckTrashed{"if article->trashed()"}

    CheckTrashed -- Yes --> Deny1[[return Response::deny]]

    CheckTrashed -- No --> DefineStates[Define allowedStates: <br/> New, Rejected, External, Draft, Archived]

    DefineStates --> CheckPubPerm{"if isPublished && <br/> user can update-published"}
    CheckPubPerm -- Yes --> Allow1[[return Response::allow]]

    CheckPubPerm -- No --> CheckLocked{"if isPublished || <br/> state is Approval"}
    CheckLocked -- Yes --> Deny2[[return DenyResponse::deny]]

    CheckLocked -- No --> CheckUpdatePerm{"if state in allowedStates <br/> && user can update"}
    CheckUpdatePerm -- Yes --> Allow2[[return Response::allow]]

    CheckUpdatePerm -- No --> Deny3[[return DenyResponse::deny]]

    style Deny1 fill:#f8d7da,stroke:#dc3545
    style Deny2 fill:#f8d7da,stroke:#dc3545
    style Deny3 fill:#f8d7da,stroke:#dc3545
    style Allow1 fill:#d4edda,stroke:#28a745
    style Allow2 fill:#d4edda,stroke:#28a745
```

#### Logische analyse 

Deze policy is aanzienlijk strenger dan de `display` policy, omdat het hier gaat om data-integriteit. De logica volgt een hierarchie van beperkingen: 

1. **Harde blokkade (Trash):** Als een artikel in de prullenbak staat, is bewerken per definitie uitgesloten. Dit voorkomt conflicten en inconsistenties in de database. 
2. **Publicatie privilege:** Er wordt een expliciet onderscheid gemaakt tussen de algemene `update` permissie en de `update-published` permissie. 
   Alleen gebruikers met dit specifieke recht mogen content aanpassen die al live staat. 
3. **Locking mechanisme:** artikelen met de status `Published` of `Approval` als "gelock" beschouwd voor reguliere editors. 
   Dit waarborgt dat content die momenteel beoordeeld wordt of al live staat, niet zomaar gewijzigd kan worden zonder de juiste bevoegdheden. 
4. **Status-afhankelijke bewerking:** is voor niet-gepubliceerde content (zoals `New`, `Draft` of `Rejected`) is bewerken alleen toegestaan als de status in de `allowedStates`
   voorkomt en de gebruiker de algemene `update` permissie heeft.

---

### Policy: sendForApproval

```mermaid
graph LR
    Start([Method: sendForApproval]) --> Check{"if state == Draft <br/> && user can send-for-approval"}

    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

#### Logische analyse 

Deze policy fungeert al de **poortwachter** voor de redactionele workflow. In tegenstelling tot de `update` policy, die 
breed kijkt naar diverse statussen, is deze methode zeer specifiek en restrictief: 

- **Status beperking:** De actie is uitsluitend toegestaan wanneer een artikel de status `Draft` heeft. 
  Dit voorkomt dat artikelen die al in `Approval` staan, of reeds `Published` zijn, opnieuw (en mogelijk redundant)
  in de wachtrij voor goedkeuring worden geplaatst.
- **Permissie validatie:** Naast de juiste status moet de gebruiker expliciet beschikken over de `send-for-approval` permissie. 
  Dit scheidt de redacteurs (die alleen draft mogen maken) van de eindredacteurs die het publicatieproces mogen initieren.
- **Workflow integriteit:** Door de `Check` strikt te houden, wordt gewaarborgd dat de staat van het artikel altijd voorspelbaar 
  blijft binnen de database transacties die volgen op deze policy check.

---

### Policy: unarchive *(Artikelen uit het archief halen)*

```mermaid
graph LR
    Start([Method: unarchive]) --> Check{"if article state == Archived <br/> && user can unarchive:article"}
    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

#### Logische analyse

De `unarchive` policy is een kritieke herstel-actie die content terugbrengt in de actieve workflow.
De logica is strikt om te voorkomen dat actieve of concept-artikelen per ongeluk in een verkeerde staat worden geforceerd:

- **Status exclusiviteit:** De actie is alleen toegestaan als het artikel zich momenteel in de `Archived` status bevindt. 
  Dit is een essentiele beveiliging; een artikel dat bijvoorbeeld in `Approval` staat, mag niet via deze methode "ge-unarchived" worden, 
  omdat dit de review logica zou omzeilen. 
- **Gespecialiseerde permissie:** er wordt gecontroleerd op de specifieke `unarchive:article` permissie. Dit is doorgaans een hogere permissie 
  dan een standaard `update`, omdat het heractiveren van oude content mogelijks juridische of redactionele gevolgen heeft (denk aan verouderde informatie die weer vindbaar wordt).
- **Binair resultaat:** Er is geen grijze zone of `NotFound` response nodig zoals bij de `display` policy. als het artikel kunt zien maar niet mag unarchiven, volgt een expliciete `deny`.

---

### Policy: publish *(Publiceren van artikelen)*

```mermaid
graph LR
    Start([Method: publish]) --> CheckState{"if article state != Approval"}
    CheckState -- Yes --> Deny1[[return Response::deny]]

    CheckState -- No --> CheckPerm{"if user cannot publish:article"}
    CheckPerm -- Yes --> Deny2[[return Response::deny]]

    CheckPerm -- No --> CheckEditor{"if editor exists && editor != user"}
    CheckEditor -- Yes --> Allow[[return Response::allow]]
    CheckEditor -- No --> Deny3[[return Response::deny]]

    style Deny1 fill:#f8d7da,stroke:#dc3545
    style Deny2 fill:#f8d7da,stroke:#dc3545
    style Deny3 fill:#f8d7da,stroke:#dc3545
    style Allow fill:#d4edda,stroke:#28a745
```

---

### Policy: unpublish **(Artikelen uit publicatie halen)**

```mermaid
graph LR
    Start([Method: unpublish]) --> Check{"if isPublished && <br/> user can unpublish"}
    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

---

### Policy: detachEditor

```mermaid
graph LR
    Start([Method: detachEditor]) --> CheckState{"if state != Draft"}

    CheckState -- Yes --> Deny1[[return Response::deny]]

    CheckState -- No --> CheckOwner{"if user is the editor"}
    CheckOwner -- Yes --> Allow1[[return Response::allow]]

    CheckOwner -- No --> CheckPerm{"if user can detach-editor"}
    CheckPerm -- Yes --> Allow2[[return Response::allow]]

    CheckPerm -- No --> Deny2[[return Response::deny]]

    style Deny1 fill:#f8d7da,stroke:#dc3545
    style Deny2 fill:#f8d7da,stroke:#dc3545
    style Allow1 fill:#d4edda,stroke:#28a745
    style Allow2 fill:#d4edda,stroke:#28a745
```

---

### Policy: attachDisclaimer

```mermaid
graph LR
    Start([Method: attachDisclaimer]) --> Check{"if user can attach-disclaimer <br/> && disclaimer does not exist"}

    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

---

### Policy: detachDisclaimer

```mermaid
graph LR
    Start([Method: detachDisclaimer]) --> Check{"if user can detach-disclaimer <br/> && disclaimer exists"}

    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

---

### Policy: archiveArticle

```mermaid
graph LR
    Start([Method: archiveArticle]) --> CheckTrashed{"if article->trashed()"}

    CheckTrashed -- Yes --> Deny1[[return Response::deny]]

    CheckTrashed -- No --> CheckArchive{"if state in [Published, Approval] <br/> && user can archive:article"}

    CheckArchive -- Yes --> Allow[[return Response::allow]]
    CheckArchive -- No --> Deny2[[return Response::deny]]

    style Deny1 fill:#f8d7da,stroke:#dc3545
    style Deny2 fill:#f8d7da,stroke:#dc3545
    style Allow fill:#d4edda,stroke:#28a745
```

---

### Policy: delete

```mermaid
graph LR
    Start([Method: delete]) --> CheckPubOverride{"1. if isPublished && <br/> can verwijder-vanuit-publicatie"}

    CheckPubOverride -- Yes --> Allow1[[return Response::allow]]

    CheckPubOverride -- No --> CheckBasePerm{"2. if user cannot delete:article"}
    CheckBasePerm -- Yes --> Deny1[[return DenyResponse::deny]]

    CheckBasePerm -- No --> CheckEditor{"3. if user is Editor && <br/> state in [External, New]"}
    CheckEditor -- Yes --> Allow2[[return Response::allow]]

    CheckEditor -- No --> CheckAdmin{"4. if user is Admin/Dev && <br/> state in [External, New, Archived]"}
    CheckAdmin -- Yes --> Allow3[[return Response::allow]]

    CheckAdmin -- No --> Deny2[[return DenyResponse::deny]]

    style Deny1 fill:#f8d7da,stroke:#dc3545
    style Deny2 fill:#f8d7da,stroke:#dc3545
    style Allow1 fill:#d4edda,stroke:#28a745
    style Allow2 fill:#d4edda,stroke:#28a745
    style Allow3 fill:#d4edda,stroke:#28a745
```

---

### Policy: restore

```mermaid
graph LR
    Start([Method: restore]) --> Check{"if user can restore:article"}

    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

---

### Policy: restoreAny

```mermaid
graph LR
    Start([Method: restoreAny]) --> Check{"if user can restore-any:article"}

    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

---

### Policy: deleteAny

```mermaid
graph LR
    Start([Method: deleteAny]) --> Check{"if user can delete-any:article"}

    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

---

### Policy: forceDelete

```mermaid
graph LR
    Start([Method: forceDelete]) --> Check{"if user can geforceerd-verwijderen:article"}

    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

---

### Policy: forceDeleteAny

```mermaid
graph LR
    Start([Method: forceDeleteAny]) --> Check{"if user can meerdere-geforceerd-verwijderen:article"}

    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
```

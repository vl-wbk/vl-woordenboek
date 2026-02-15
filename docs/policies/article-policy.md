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

### Policy: unarchive

```mermaid
graph LR
    Start([Method: unarchive]) --> Check{"if article state == Archived <br/> && user can unarchive:article"}

    Check -- Yes --> Allow[[return Response::allow]]
    Check -- No --> Deny[[return Response::deny]]

    style Allow fill:#d4edda,stroke:#28a745
    style Deny fill:#f8d7da,stroke:#dc3545
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

#### Gebruikersfeedback & foutafhandeling

| Scenario | Systeemtaal (Code) | Gebruikersmelding (UI) | Betekenis voor de gebruiker |
| :---- | :---- | :---- |

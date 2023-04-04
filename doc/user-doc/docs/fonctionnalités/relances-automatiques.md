# Relances automatiques

## Description

Les relances automatiques permettent de relancer un client à interval régulier sans avoir besoin de revenir sur le ticket. Une fois qu'une relance est activée sur un ticket, celui-ci évolue en toute transparence pour l'utilisateur, jusqu'à la fin du cycle.
Il existe plusieurs relances automatiques, elles sont configurées par votre responsable.

---

## Activation

Sur un ticket, l'emplacement de la configuration se trouve dans la colonne de gauche, rubrique "Administration Fil de discussion".

!!! warning "Fil de discussion"
    La relance automatique est gérée au niveau du fil de discussion. Il peut donc y avoir plusieurs relances auto activées sur un même ticket.

Pour activer une relance automatique, il faut :

* Se rendre sur le fil de discussion à relancer
* Sélectionner la relance à activer
* Définir le statut du ticket en "Attente client"
* Enregistrer le ticket

![select_reviaval_on_ticket](assets/select_revival_on_ticket.gif)

## Suivi

Une fois l'activation effectuée, les paramètres de la relance sont rappelés :

| Nom du paramètre  | Exemple          | Description                                                                    |
|-------------------|------------------|--------------------------------------------------------------------------------|
| Fréquence d'envoi | Tous les 3 jours | Nombre de jours entre 2 envois (1 correspondant à une relance tous les jours). |
| Messages envoyés  | 2/3              | Montre l'avancement du cycle (nombre de messages déjà envoyés).                |
| Prochaine relance | 04/04/2023       | Indique à quelle date la prochaine relance sera envoyée.                       |
| Type d'envoi      | channel          | Précise comment le message sera envoyé au client.                              |

## Fin du cycle
À la fin du cycle de relance, plusieurs actions peuvent être effectuées selon la configuration :

- Changement du statut du ticket : Attente client / Attente admin / Fermé
- Envoi d'un dernier message au client : Message identique ou différent selon la configuration.
- Ajout d'un tag au ticket

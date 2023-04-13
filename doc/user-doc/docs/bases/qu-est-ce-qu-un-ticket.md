# Qu'est-ce qu'un ticket ?

1 ticket = 1 commande

Un ticket est la relation entre un client et le service après-ventes, 

## Historique des messages
Afin de pouvoir identifier plus rapidement qui est l'auteur d'un message, nous avons un code couleur qui a été mis en place.
Voici donc les différents codes couleur utiliser :

| **Auteur**  | **Couleur** |
|-------------|-------------|
| Client      | Orange      |
| Opérateur   | Rouge       |
| Admin       | Vert        |

## Fil de discussion

Un fil de discussion est relié à une demande d'un client sur une commande.

![identifier_un_fil_de_discussion](assets/identifier_un_fil_de_discussion.png)

Selon le canal de diffusion le systems de fil de discussion est différent.

- Pour les canaux comme Amazon, Fnac et Icoza, il n'y a pas de gestion de file de discussion, on les appelle donc ("Fil de discussion principal")
- Pour les canaux qui sont gérés via Mirakl, les fils de discussion sont directement créés par la MarketPlace
- Pour ManoMano c'est pareil que pour les canaux de type Amazon..., sauf qu'il y a une distinction qui est faite sur l'auteur du message Support / Client:
  - support : Message écrit par la MarketPlace
  - Client : Message écrit par le client

!!! note Fil de discussion créé par défaut = Email. 
    Il permet d'envoyer un email au client.
    L'email est envoyé à l'adresse email définie dans le Backoffice, visible dans la rubrique Info commande.
    Ce fil de discussion n'est disponible que pour les commandes qui existent dans le Backoffice.
    Ne permet que l'envoi de messages, pas de réception.


# Réponses automatiques

Certaines demandes client font l'objet d'une réponse automatique. Selon le contenu du message, notre robot est en capacité de choisir et d'envoyer automatiquement une réponse.

Chaque message externe (client ou opérateur) est analysé 10 minutes après son arrivée afin de tenter d'y répondre.

!!! note "Pourquoi attendre 10 minutes ?"
    Le délai de réponse permet d'attendre un éventuel second message du client.
    En effet, on souhaite envoyer une seule réponse à un client, même s'il nous contacte plusieurs fois à quelques minutes d'intervalle.

Les scénarios traités sont détaillés ci-dessous.

!!! warning "Important"
    L'ordre des sujets ci-dessous est important. Le robot ne peut répondre qu'une seule fois à un message.
    Seul le premier scénario qui rempli toutes les conditions sera exécuté.

## Demande de facture
Condition de déclenchement :

- Le scénario est activé
- Le message est externe
- Le message est le premier sur le fil de discussion
- Le message n'a pas été répondu
- Le contenu du message contient "facture"

<iframe frameborder="0" style="width:100%;height:424px;" src="https://viewer.diagrams.net/?highlight=0000ff&nav=1#G1aLKJtutKWDhjRbAxM3TkeQzzF7vZ2Z7r"></iframe>

## Information sur la livraison
Condition de déclenchement :

- Le scénario est activé
- Le message est externe
- Le message est le premier sur le fil de discussion
- Le message n'a pas été répondu
- Le sujet du message est parmi les suivants :
    - Information sur la livraison
    - Article non reçu
    - Je n'ai pas reçu mon colis
    - Ma commande est expédiée, mais je ne l'ai pas reçue
    - Avez-vous expédié l'article ?
    - Demande de renseignements concernant la livraison d'une commande

<iframe frameborder="0" style="width:100%;height:600px;" src="https://viewer.diagrams.net/?highlight=0000ff&nav=1&page-id=62E9sil-pJoALsFmjKG6#G1aLKJtutKWDhjRbAxM3TkeQzzF7vZ2Z7r"></iframe>

## Accusé de réception
Condition de déclenchement :

- Le scénario est activé
- Le message est externe
- Le message n'a pas été répondu

=> Envoi d'un message accusant la réception du message client.

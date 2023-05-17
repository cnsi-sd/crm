# Automatisations

Cette section décrit les différentes automatisations en place.

## Réponses aux questions sur les offres Cdiscount

Lors de l'import des messages Cdiscount, une vérification est faite sur la nature du message. S'il s'agit d'une question sur une offre, nous envoyons une réponse automatique au client et le message n'est pas importé dans le CRM.
La réponse envoyée vise à aider le client en lui précisant où est-ce qu'il trouvera les informations relatives à la livraison, au produit, nos CGV, etc.

La réponse par défaut utilisée est configurable dans le menu `Configuration > Divers > Réponse aux questions sur les offres`.

## Discussion clôturée

Lors de l'import des messages Cdiscount, on vérifie si la discussion est ouverte ou non, dans ce cas, on attribue le Tag "Discussion clôturée".

Le Tag est configurable dans le menu `Configuration > Divers > Discussion clôturée`.

## Remboursement Mirakl

Il est possible de récupérer les demandes de remboursement sur les commandes Mirakl. Alors, on y attribue le Tag "Remboursement Mirakl'.

Le Tag est configurable dans le menu `Configuration > Divers > Remboursement Mirakl`.

## Incidents

Il est possible de récupérer les incidents (réclamations) ouverts par les clients sur les marketplaces via Prestashop.
Lors de la récupération de ces incidents, on va attribuer un Tag "Incidents" sur le ticket.

Le Tag est configurable dans le menu `Configuration > Divers > Incidents`.

## Retour Amazon

Lors de l'import des messages Amazon, on détecte lorsqu'un retour est demandé. Dans ce cas on, attribue le Tag "Autorisation de retour Amazon".

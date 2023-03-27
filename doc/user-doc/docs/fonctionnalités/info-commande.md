# Info commande

Lors du traitement d'un ticket client, il peut être utile d'afficher les informations relatives à sa commande.
Dans le CRM, vous pouvez cliquer sur l'onglet « Info commande » pour afficher ces informations.

L'affichage peut prendre plusieurs secondes car les informations sont récupérées via le backoffice de Prestashop.

Les informations sont divisées en quatre blocs distincts :

## Commande

Ce bloc résume les informations principales de la commande :

* ID Order : l'identifiant unique de la commande sur le Backoffice Prestashop
* Statut : le statut de la commande sur Prestashop (le code couleur est le même)
* Date commande : la date où la commande a été passée par le client
* Transporteur : le transporteur affecté au fournisseur du produit de la commande
* Suivi : le numéro de suivi de la commande, avec un lien cliquable vers le suivi du transporteur
* Total TTC : le total TTC payé par le client
* Marge HT : la marge HT réalisée
* Email : l'email du client fourni par la marketplace (attention cette adresse est anonymisée et n'est pas l'adresse mail réelle du client)
* Date max d'expédition : La date maximale d'expédition fournie par la marketplace

Des encarts colorés peuvent apparaitre en dessous et donnent d'autres indications si nécessaire :

* Fulfillment : Si la commande est en Fulfillment (produit stocké chez la marketplace)
* Livraison Express : Si le client à fait le choix d'une livraison express (24/48h)
* DEBALLE : Si le client à choisi une livraison avec déballage du produit
* INSTALLE : Si le client à choisi une livraison avec installation du produit
* Incident  Ouvert/Fermé : Si un incident a été ouvert par le client

Deux boutons permettes d'effectuer des actions :

* « Télécharger facture » : Si la facture est disponible, vous pouvez cliquer sur ce bouton pour y accéder
* « Accès backoffice » : Ce bouton vous permet d'accéder à la commande directement sur le backoffice de Prestashop

!!!note
    Ces deux boutons ouvrent les liens dans un nouvel onglet.

## Commentaire privé Prestashop

Ce bloc permet d'afficher le contenu du commentaire privé de la commande du backoffice.
Si le commentaire est plus long que le bloc, il est possible de scroller à l'intérieur en utilisant la molette de la souris.

!!!note
    Il n'est pas possible de modifier ce commentaire via le CRM, si besoin, rendez-vous sur la commande via le bouton « Accès backoffice » pour le faire.

## Client

Ce bloc permet d'afficher les informations de facturation et de livraison du client.

## Produits

Ce bloc permet d'afficher les produits de la commande client, le badge jaune à droite du fournisseur indique que le fournisseur est définitif et qu'il n'est plus possible d'en changer.
![definitive_supplier](assets/definitive_supplier.png)

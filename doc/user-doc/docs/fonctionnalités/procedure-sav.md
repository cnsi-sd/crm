# Procédure SAV

Lorsqu'un client fait une réclamation concernant un produit, il est possible de générer une demande de SAV.

## Créer une demande SAV pour le client

La démarche est la suivante :

* Aller sur le ticket concerné dans le CRM
* Cliquer sur l'onglet « Procédure SAV »
* Le produit concerné par le ticket apparait dans un encart jaune, dans le cas où la commande concerne plusieurs produits, il est possible de sélectionner le produit concerné
* Sélectionner le type de SAV dans la liste « Type de SAV »
* Cliquez sur « Envoyer »

Un récapitulatif s'affiche avec un lien à transmettre au client.

!!!note
    Cliquer sur le lien permet de le copier dans le presse-papier.

## Remplissage de la demande SAV par le client

Le client doit ensuite ouvrir ce lien et remplir les champs demandés : Nom, adresse, ville, numéro de téléphone,
détails de la demande et photo de la plaque signalétique (correspondant au numéro de série du produit).

Une fois fait il doit cliquer sur le bouton « Envoyer »

Lorsque c'est bon, un tag « Procédure SAV complète » sera ajouté sur le ticket et il passera en attente admin :
![procedure_sav](assets/tag_procedure_sav_complete.png)

De plus, les relances automatiques suivantes seront supprimées du ticket :

* RELANCE AUTO SAV
* RELANCE AUTO SAV RESOLU
* Relance Casse
* Relance Retour SAV

## Consulter une procédure SAV déjà existante

Si une procédure SAV existe déjà pour le ticket, elle est affichée :
![procedure_sav](assets/procedures_sav.png)

Cliquez sur Gérer pour afficher les détails.

Si le dossier est complet côté client, il est possible de le télécharger en cliquant sur le bouton « Télécharger le dossier » : 
![dossier_sav_complet](assets/dossier_sav_complet.png)

Ce fichier PDF contient un récapitulatif avec les informations du client ainsi que la photo demandée.

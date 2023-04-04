# Relances automatiques

La relance automatique est un système qui permet d'envoyer des messages aux clients sur un intervale précis et une frequence précise.

---

## Configuration sur un ticket

Sur un le ticket, l'emplacement de la configuration ce trouve au niveau de la colone de gauche, dans le rubrique "Administration Fil de discustion #(numéro du fil de discution)".

Il se presente sous cette forme :
![revival_view_on_ticket](assets/revival_view_on_ticket.png)

Pour l'activer sur le fil de discussion il faut :
* select la relance automatique désirée pour le fil de discussion actuellement ouvert
* changer le statut du ticket en "attente client"
* enregistrer le ticket

![select_reviaval_on_ticket](assets/select_revival_on_ticket.gif)

Une fois l'ajout fait sur le ticket, on peut remarquer plusieur information sont apparus au niveaux du cadre.

* **Fréquence d'envoie :** determine tous les combien de jours le message sera envoyer.
* **Messages envoyés :** affiche le nombre de message déja envoyer depuis le debut de la configuration de la relance automatique.
* **Prochaine relance :** affiche le jour ou le prochain message sera envoyer.
* **Type d'envoie :** determine comment le message sera envoyer au client.

---
## Configuration

Pour configurer la relance automatique, il faut aller dans la rubrique Configuration > relance automatique

![configuration_revival](assets/configuration_relance_auto.png)


Pour ajouter une nouvelle relance automatique, il faut renseigner les information suivante:
* **nom de la relance :** Afin de l'identifier lors de sa selection.
* **Fréquence d'envoie :** Ceci indique le nombre de jours d'interval entre 2 envoie.
* **Nombre de relance :** Est le nombre fois que sera envoyer le message
* **Cannaux autorisés :** Liste les cannaux pour lequel la repose automatique sera visible pour la configuration au ticket
* **Réponse par défault :** Prend un message pré-enregistrer pour pouvoir l'envoyer
* **Message envoyé à la fin du cycle de relance :** Prend un message pré-enregistrer pour pouvoir l'envoyer a la fin du cycle
* **Statut final :** Mettre le status qu'aura le ticket a la fin du cycle de relance
* **Type d'envoie :** Channel pour utiliser la MarketPlace ou SMS pour envoyer un message direct au client

![ajout_d'une_relance_par_default](assets/ajout_d'une_relance_par_default.gif)

# Migration Magento > CRM

## Base
Configurer le .env.
Pour générer APP_KEY, utiliser la commande `php8.2 artisan key:generate`.

## Données
- Configurer les responsables des canaux
- Importer les données de l'ancienne plateforme : 
```shell
php artisan db:magento_init "mysql:host={host};port=3306;dbname={db_name}" {db_name} {username} "{password}"
```

## Questions / réponses

### Migration CRM - Priorité
```
Hello

Sur magento nous avions plusieurs priorités.
Sur le nouveau CRM nous avons gardé que P1 et P2 à la demande de Vanessa.
Lors de la migration tout ce qui est autre que P1 et P2, je transforme en P2 ?
```
* Vanessa : Oui, c'est exactement ça !

### Migration CRM - Messages sur commandes antérieures
```
Hello

Sur les magento de Smart Tech et TKG, nous avons pas mal de messages qui concernent des commandes antérieures. J'appelle "commandes antérieures" les commandes qui ont été passées avant la mise en place des backoffices.
Tous ces messages remontent en Guest (invité) sur Magento et ne sont pas liés à une commande.
C'est déjà prévu dans le nouveau CRM de continuer d'importer ces messages. Comme aujourd'hui, la seule différence avec les autres tickets, c'est qu'il n'y aura pas de lien avec le BO.
Par contre sur le nouveau CRM, on fait le lien avec la commande. Pour la migration ça m'embête ...

Est-ce que pour vous c'est important que l'historique de ces messages soient migrés de Magento vers le CRM ?
```
* Vanessa : Pour les anciens non

### Migration CRM - Questions sur les offres
```
Bonjour,

Je travaille sur les scripts de migration de Magento vers le CRM.
Je vois sur TKG et Smart Tech qu'on reçoit des questions sur les offres et qu'on y répond.
Je n'étais pas au courant et je ne l'ai pas prévu dans le nouveau CRM. La seule chose qui est prévu c'est de répondre en auto aux questions sur les offres Cdiscount (mais pas de les importer).

Est-ce qu'il faut être en capacité d'importer et de répondre aux messages sur les offres sur le nouveau CRM ?
```
* Charles : Je laisse Vanessa y répondre mais je pense que oui 
* Vanessa : C'est soit Jerome qui répond, soit c'est gregory qui repond
* Charly : Est-ce que c'est nécessaire de continuer à y répondre directement depuis le CRM ?
* Vanessa : non

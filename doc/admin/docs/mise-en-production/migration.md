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

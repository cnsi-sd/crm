# Migration Magento > CRM

## Base
Configurer le .env.
Pour générer APP_KEY, utiliser la commande `php8.2 artisan key:generate`.

## Données

### Réponses par défaut
```sql
SELECT cdr_name, cdr_content
FROM crm_default_reply
```

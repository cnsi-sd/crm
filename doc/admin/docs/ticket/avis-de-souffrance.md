# Avis de souffrance

Pour les avis de souffrances la route "api/avis_de_souffrance" a été mise en place pour pouvoir ajouter la notion d'avis de souffrance à un ticket.

## Utilisation

Afin d'interagir avec cette route, il faut lui passer en requêtes POST un élément JSON du type suivant:
```JSON
{
    "channel": "",
    "num_command": "",
    "message_souffrance":""
}
```
Les informations entrées dans le script JSON et de type STRING

## Erreurs
### 400
Vous aurez toutes les erreurs provenant d'un type non-respecter dans le JSON

### 401
Vous retrouverez les erreurs liée au token

### 500
Est visible s'il y a une erreur qui sera levée

### 200
S'affiche si tout s'est bien déroulé

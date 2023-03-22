<?php
return [
    /** Global */
    'all' => 'Tous',
    'yes' => 'Oui',
    'no' => 'Non',
    'home' => 'Accueil',
    'save' => 'Enregistrer',
    'search' => 'Rechercher',
    'no_results' => 'Aucun résultat, vérifiez votre référence',
    'no_term' => 'Aucun mot clé recherché',
    'reset' => 'Réinitialiser',
    'edit' => 'Modifier',
    'create' => 'Création',
    'delete' => 'Suppression',
    'deleteExpression' => 'Êtes vous sûr de vouloir supprimer : ',
    'new' => 'Nouveau',
    'display' => 'Afficher',
    'logout' => 'Déconnexion',
    'no_result' => 'Aucun résultat.',
    'username' => 'Nom d\'utilisateur',
    'password' => 'Mot de passe',
    'enter_email' => 'Saisir votre email',
    'enter_password' => 'Saisir votre mot de passe',
    'enter_username' => 'Saisir votre identifiant',
    'password_confirmation' => 'Confirmation du mot de passe',
    'enter_password_confirmation' => 'Confirmer votre mot de passe',
    'email' => 'Email',
    'date' => 'Date',
    'second' => 'seconde|secondes',
    'minute' => 'minute|minutes',
    'hour' => 'heure|heures',
    'from' => 'du',
    'to' => 'au',
    'attachment' => 'Pièce jointe|Pièces jointes',
    'customer' => 'Client',
    'send_message' => 'Envoyer un message',
    'send_comment' => 'Envoyer un commentaire',
    'order_info' => 'Info commande',
    'product_return' => 'Retour produits',
    'customer_service_process' => 'Procédure SAV',

    'navbar' => [
        'dashboard' => 'Tableau de bord',
        'settings' => 'Paramètres',
        'config' => 'Configuration',
        'admin' => 'Administration'
    ],

    'axios' => [
        'unknown_error' => 'Erreur inconnue',
        'no_response_error' => 'La demande a été faite mais aucune réponse n\'a été reçue',
        'setting_up_error' => 'Something happened in setting up the request that triggered an Error',
    ],

    /** Settings */
    'admin' => [
        'admin' => 'Paramètres',
    ],

    /** Permission */
    'permission' =>[
        'user' => 'Utilisateur',
        'role' => 'Role',
        'default_message_answer' => 'Message par défaut',
        'revival' => 'Relance automatique',
        'ticket'=> 'Ticket',
        'tag'=>'Tag'
    ],

    'role' => [
        'role' => 'Rôle|Rôles',
        'new' => 'Ajouter un rôle',
        'edit' => 'Modifier un rôle',
        'save' => 'Enregistrer le rôle',
        'delete' => 'Supprimer le rôle',
        'edited' => 'Rôle modifié',
        'saved' => 'Rôle enregistré',
        'deleted' => 'Rôle supprimé',
        'name' => 'Nom du rôle',
    ],

    'user' => [
        'user' => 'Utilisateur|Utilisateurs',
        'new' => 'Ajouter un utilisateur',
        'edit' => 'Modifier un utilisateur',
        'save' => 'Enregistrer l\'utilisateur',
        'delete' => 'Supprimer l\'utilisateur',
        'edited' => 'Utilisateur modifié',
        'saved' => 'Utilisateur enregistré',
        'deleted' => 'Utilisateur supprimé',
        'firstname' => 'Prénom',
        'lastname' => 'Nom',
        'email' => 'Email',
        'password' => 'Mot de passe',
        'password_change' => 'Changer de mot de passe',
        'password_confirmation' => 'Confirmation du mot de passe',
        'active' => 'Connexion autorisée ?',
        'password_help' => 'Longueur minimum : 8 - Caractères requis : Majuscule, Minuscule, Chiffre, Symbole',
        'my_account' => 'Mon compte',
        'my_informations' => 'Mes informations',
    ],

    'ticket' => [
        'ticket' => 'Ticket|Tickets',
        'deadline' => 'Deadline',
        'owner' => 'Responsable',
        'state' => 'Statut',
        'priority' => 'Priorité',
        'created_at' => 'Date d\'ouverture',
        'all_tickets' => 'Tous les tickets',
        'my_tickets' => 'Mes tickets',
        'channel' => 'Canal de diffusion',
        'subjects' => 'Sujets',
        'mapping' => 'Association',
        'order' => 'Commande',
        'base_information' => 'Informations de base',
        'customer_mail' => 'Email client',
        'delivery_date' => 'Date de livraison',
        'admin_ticket' => 'Administration Ticket',
        'admin_thread' => 'Administration Fil de discussion',
        'customer_issue' => 'Problématique client',
        'private_comments' => 'Commentaires privés',
        'default_replies' => 'Réponses par défaut',
        'saved' => 'Ticket enregistré',

        'click_and_call' => [
            'start' => 'Cliquer pour lancer l\'appel',
            'success' => 'Appel en cours',
        ],
    ],

    'order' => [
        'order' => 'Commande',

        'empty_orders' => 'Aucune commande trouvée ...',
        'null_orders' => 'Erreur lors de la récupération des commandes ...',

        'id_order' => 'ID Order',
        'status' => 'Statut',
        'date' => 'Date commande',
        'carrier' => 'Transporteur',
        'tracking' => 'Suivi',
        'total_ttc' => 'Total TTC',
        'margin_ht' => 'Marge HT',
        'private_comment' => 'Commentaire privé Prestashop',
        'billing' => 'Facturation',
        'shipping' => 'Livraison',
        'products' => 'Produits',
        'designation' => 'Désignation',
        'qty' => 'Qté',
        'supplier' => 'Fournisseur',
        'reference' => 'Référence :',
        'ean' => 'EAN :',
        'external_link' => 'Accès backoffice',
        'download_invoice' => 'Télécharger facture',
    ],

    'login' => [
        'login' => 'Connexion',
        'sign_in' => 'Identification',
        'enter_email_and_password' => 'Saisir votre adresse email et votre mot de passe pour vous connecter.',
        'forgot_your_password' => 'Mot de passe oublié ?',
        'remember_me' => 'Se souvenir de moi',
    ],

    'config' => [
        'config' => 'Configuration',
        'channel' => 'Canal|Canaux',

        'misc' => [
            'misc' => 'Divers',
            'saved' => 'Configuration enregistrée',
            'variables' => 'Variables',

            'incidents' => 'Incidents',
            'incident_tag' => 'Tag Incident',
        ],
    ],

    'bot' => [
        'bot' => 'Bot',

        'active' => 'Activé ?',
        'saved' => 'Configuration enregistrée !',

        'acknowledgement' => [
            'acknowledgement' => 'Accusé de réception',
            'answer' => 'Réponse à envoyer',
        ],

        'invoice' => [
            'invoice' => 'Demande de facture',
            'found_answer' => 'Réponse à envoyer : facture trouvée',
            'not_shipped_answer' => 'Réponse à envoyer : facture pas encore générée',
        ],

        'shipping_information' => [
            'shipping_information' => 'Information de livraison',

            'vir_shipped_answer' => 'Réponse à envoyer : suivi VIR disponible',
            'default_shipped_answer' => 'Réponse à envoyer : suivi autre transporteur disponible',

            'in_preparation_answer' => 'Réponse à envoyer : en cours de préparation',
            'in_preparation_with_delay_answer' => 'Réponse à envoyer : en cours de préparation avec délai',

            'fulfillment_answer' => 'Réponse à envoyer : commande Fulfillment',

            'late_order_tag' => 'Tag retard de préparation',
        ],
    ],

    'defaultAnswer' => [
        'defaultAnswer' => 'Réponse par défaut|Réponses par défaut',
        'save' => 'Enregistrer la réponse',
        'name' => 'Nom de la réponse',
        'content' => 'Contenu de la réponse',
        'select_channel' => 'Canaux autorisés',
        'select_all_channel' => 'Laissez vide pour tout autoriser',
    ],

    'revival' => [
        'revival' => 'Relance automatique|Relances automatiques',
        'name' => 'Nom de la relance automatique',
        'frequency' => 'Fréquence d\'envoi (en jours):',
        'max_revival' => 'Nombre d\'envoi de relance',
        'end_default_answer' => 'Message envoyé à la fin du cycle de relance',
        'end_state' => 'Statut final',
        'start_revival' => 'À partir du',
        'select_revival' => 'Aucune',
        'select_channel' => 'Canaux autorisés',
        'frequency_details' => 'Tous les jours|Tous les :freq jours',
        'MaxRevival' => 'Messages envoyés :',
        'nextReply' => 'Prochaine relance : ',
        'sendType' => 'Type d\'envoi',
        'warningLengthSMS' => 'Attention votre message dépasse les 160 caractères. Vous enverrez donc : :nbMessage messages.',
        'select_all_channel' => 'Laissez vide pour tout autoriser',
    ],

    'tags' => [
        'tags' => 'Tag|Tags',
        'view' => 'Aperçu du tag',
        'create' => 'Création d\'un tags',
        'edit' => 'Modification d\'un tags',
        'name' => 'Nom',
        'backgroundColor' => 'Couleur du fond',
        'textColor' => 'Couleur du texte',
        'select_channel' => 'Canaux autorisés',
        'show' => 'Prévisualisation',
        'select_all_channel' => 'Laissez vide pour tout autoriser',
    ],

    'recover_password' => [
        'recover_password' => 'Récupérer mot de passe',
        'reset_password' => 'Réinitialiser le mot de passe',
        'enter_email_reset_password' => 'Saisir votre adresse email afin de recevoir les instructions pour réinitialiser votre mot de passe',
        'back_to' => 'Revenir à la',
        'login' => 'Connexion',
    ],

    'reset_password' => [
        'reset_password' => 'Réinitialiser mot de passe',
    ],

    'mail' => [
        'process_error' => 'Echec d\'un processus',
    ],

    'channel' => [
        'name' => 'Nom',
        'ext_names' => 'Nom(s) externe(s)',
        'saved' => 'Canal enregistré',
        'edit' => 'Modifier un canal',
    ],
    'sav_note' => [
        'sav_note' => 'Fiche SAV fabriquant|Fiches SAV fabriquant',
        'new' => 'Nouvelle fiche SAV fabriquant',
        'edit' => 'Modifier fiche SAV fabriquant',
        'delete' => 'Supprimer fiche SAV fabriquant',
        'manufacturer' => 'Fabriquant',
        'pms_delay' => 'Délai de PMS (Panne / Mise en service)',
        'manufacturer_warranty' => 'Garantie constructeur',
        'gc_plus' => 'Contrat GCPlus',
        'gc_plus_delay' => 'Délai pour réaliser contrat GCPlus',
        'hotline' => 'Hotline',
        'brand_email' => 'Email marque',
        'brand_information' => 'Informations marque',
        'regional_information' => 'Informations régionales',
    ]
];

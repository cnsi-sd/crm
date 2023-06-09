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
    'show' => 'Détail',
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
    'operator' => 'Opérateur',
    'shop' => 'Boutique',
    'send_message' => 'Envoyer un message',
    'send_comment' => 'Envoyer un commentaire',
    'order_info' => 'Info commande',
    'product_return' => 'Retour produits',
    'customer_service_process' => 'Procédure SAV',
    'confirm' => 'Confirmer',
    'cancel' => 'Annuler',
    'download' => 'Télécharger',
    'reset_password_sent' => 'Email de réinitialisation envoyé',
    'reset_password_success' => 'Mot de passe mis à jour',

    'navbar' => [
        'dashboard' => 'Tableau de bord',
        'settings' => 'Paramètres',
        'config' => 'Configuration',
        'admin' => 'Administration',
        'doc' => 'Documentation',
    ],

    'axios' => [
        'unknown_error' => 'Erreur inconnue',
        'no_response_error' => 'La demande a été faite mais aucune réponse n\'a été reçue',
        'setting_up_error' => 'Something happened in setting up the request that triggered an Error',
    ],

    /** Settings */
    'admin' => [
        'admin' => 'Paramètres',
        'channel' => 'Canal|Canaux',
    ],

    /** Permission */
    'permission' =>[
        'user' => 'Utilisateur',
        'role' => 'Role',
        'default_message_answer' => 'Message par défaut',
        'revival' => 'Relance automatique',
        'ticket'=> 'Ticket',
        'tag'=>'Tag',
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
        'user_id' => 'Responsable',
        'state' => 'Statut',
        'priority' => 'Priorité',
        'created_at' => 'Date d\'ouverture',
        'all_tickets' => 'Tous les tickets',
        'my_tickets' => 'Mes tickets',
        'subjects' => 'Sujets',
        'mapping' => 'Association',
        'order' => 'Commande',
        'base_information' => 'Informations de base',
        'direct_customer_email' => 'Email client',
        'delivery_date' => 'Date de livraison',
        'admin_ticket' => 'Administration Ticket',
        'admin_thread' => 'Administration Fil de discussion',
        'customer_issue' => 'Problématique client',
        'private_comments' => 'Commentaires privés',
        'default_replies' => 'Réponses par défaut',
        'saved' => 'Ticket enregistré',
        'reply_to' => 'Répondre à :',
        'replied_to' => 'Réponse à :',
        'confirm_other_channel' => 'Le message contient le nom d\'une autre marketplace, envoyer quand même le message ?',
        'reset_filter' => 'Réinitialiser les filtres',

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
        'brand' => 'Marque :',
        'reference' => 'Référence :',
        'ean' => 'EAN :',
        'external_link' => 'Accès backoffice',
        'download_invoice' => 'Télécharger facture',
        'max_shipment_date' => 'Date max d\'expédition',
        'incident_opened' => 'Incident ouvert',
        'incident_closed' => 'Incident fermé',
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

        'misc' => [
            'misc' => 'Divers',
            'saved' => 'Configuration enregistrée',
            'variables' => 'Variables',

            'incidents' => [
                'incidents' => 'Incidents',
                'incident_tag' => 'Tag Incident',
                'active' => 'Activer la récupération automatique des Incidents ?'
            ],

            'savprocess' => [
                'savprocess' => 'Procédure SAV',

                'out' => 'Configuration sortante',
                'active' => 'Activer l\'onglet Procédure SAV ?',
                'url' => 'URL',
                'token' => 'Token',

                'in' => 'Configuration entrante',
                'api_token' => 'Token API',
                'complete_tag' => 'Tag Procédure SAV complète',
                'stop_revival' => 'Relances auto à stopper',
            ],

            'default_answer_offer_questions' => [
                'default_answer_offer_questions' => 'Réponse aux questions sur les offres Cdiscount',
                'active' => 'Activer la Réponse par défaut aux questions sur les offres Cdiscount ?',
            ],

            'mirakl_refunds' => [
                'mirakl_refunds' => 'Remboursement sur MP Mirakl',
                'mirakl_refunds_tag' => 'Tag Remboursement sur MP Mirakl',
                'active' => 'Activer la récupération automatique des Remboursements sur MP Mirakl ?',
            ],

            'closed_discussion' => [
                'closed_discussion' => 'Discussion clôturée',
                'closed_discussion_tag' => 'Tag discussion clôturée',
                'active' => 'Activer l\'attribution automatique du tag Discussion clôturée ?',
                ],

            'external_features' => 'Fonctionnalités externes',

            'pm' => [
                'pm' => 'Parcel Management',

                'out' => 'Configuration sortante',
                'active' => 'Activer l\'onglet Parcel Management',
                'app_url' => 'URL Application',
                'api_url' => 'URL API',
                'api_token' => 'Token API',
                'id_shop' => 'ID Shop',

                'in' => 'Configuration entrante',
                'close_api_token' => 'Token API',
                'accepted_return_tag' => 'Tag Retour accepté',
                'refused_return_tag' => 'Tag Retour refusé',
                'return_with_reserves_tag' => 'Tag Retour avec réserves',
                'return_with_remark_tag' => 'Tag Retour avec remarques',
            ],
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

    'default_answer' => [
        'default_answer' => 'Réponse par défaut|Réponses par défaut',
        'save' => 'Enregistrer la réponse',
        'name' => 'Nom de la réponse',
        'content' => 'Contenu de la réponse',
        'select_channel' => 'Canaux autorisés',
        'select_all_channel' => 'Laissez vide pour tout autoriser',
        'is_locked' => 'Verrouillée',
        'lock' => 'Verrouiller',
        'deleted' => 'Réponse par défaut supprimée'
    ],

    'revival' => [
        'revival' => 'Relance automatique|Relances automatiques',
        'endrevival' => 'Action en fin de relance',
        'name' => 'Nom de la relance automatique',
        'frequency' => 'Fréquence d\'envoi (en jours):',
        'max_revival' => 'Nombre d\'envoi de relance',
        'default_answer'=> 'Réponse par défaut',
        'select_default_answer'=> 'Aucun',
        'end_default_answer' => 'Message envoyé à la fin du cycle de relance',
        'select_end_default_answer' => 'Aucun',
        'end_state' => 'Statut final',
        'select_end_state' => 'Aucun',
        'start_revival' => 'À partir du',
        'select_revival' => 'Aucun',
        'select_channel' => 'Canaux autorisés',
        'frequency_details' => 'Tous les jours|Tous les :freq jours',
        'MaxRevival' => 'Messages envoyés :',
        'nextReply' => 'Prochaine relance : ',
        'sendType' => 'Type d\'envoi',
        'warningLengthSMS' => 'Attention votre message dépasse les 160 caractères. Vous enverrez donc : :nbMessage messages.',
        'select_all_channel' => 'Laissez vide pour tout autoriser',
        'endTag' => 'Tag à ajouter',
        'no_end_default_answer' => 'Pas de message de fin',
        'no_end_state' => 'Pas de status de fin',
        'no_end_tag' => 'Pas de tag de fin',
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
        'is_locked' => 'Verrouillé',
        'lock' => 'Verrouiller',
        'deleted' => 'Tag supprimé',
        'addTagList' => 'Ajouter une ligne de tags',
        'select_tag' => 'Ajouter un tag',
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
        'order_url' => 'URL Commande',
        'order_url_desc' => '@ sera remplacé par le numéro de commande.',
        'ext_names' => 'Nom(s) externe(s)',
        'is_active' => 'Activé',
        'saved' => 'Canal enregistré',
        'edit' => 'Modifier un canal',
    ],
    'sav_note' => [
        'sav_note' => 'Fiche SAV fabriquant|Fiches SAV fabriquant',
        'new' => 'Nouvelle fiche SAV fabriquant',
        'edit' => 'Modifier fiche SAV fabriquant',
        'delete' => 'Supprimer fiche SAV fabriquant',
        'manufacturer' => 'Fabriquant',
        'pms_delay' => 'Délai de PMS (Panne mise en service)',
        'manufacturer_warranty' => 'Garantie constructeur',
        'gc_plus' => 'Contrat GCPlus',
        'gc_plus_delay' => 'Délai pour réaliser contrat GCPlus',
        'hotline' => 'Hotline',
        'brand_email' => 'Email marque',
        'brand_information' => 'Informations marque',
        'supplier_information' => 'Informations fournisseurs',
        'delete_confirm' => 'Supprimer la fiche SAV ?',
        'deleted' => 'Fiche SAV fabricant supprimée',
        'saved' => 'Fiche SAV fabricant enregistrée',
        'show' => 'Fiche SAV fabricant',
        'short_desc' => 'Fiche SAV Fab.'
    ],
    'historical' => [
        'histories' => 'Historique',
        'date' => 'Date',
        'user' => 'Utilisateur',
        'type' => 'Type',
        'update' => 'Modification',
        'empty_historical' => 'Aucun historique trouvée ...',
        'null_historical' => 'Erreur lors de la récupération de l\'historique ...'
    ],

];

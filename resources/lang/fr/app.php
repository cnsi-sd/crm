<?php
return [
    /** Global */

    'yes' => 'Oui',
    'no' => 'Non',
    'cancel' => 'Annuler',
    'confirm' => 'Confirmer',
    'edit' => 'Modifier',
    'end' => 'Terminer',
    'retake' => 'Reprendre',
    'display' => 'Afficher',
    'save' => 'Enregistrer',
    'close' => 'Fermer',
    'choose' => 'Choisir',
    'delete' => 'Supprimer',
    'create' => 'Créer',
    'retry' => 'Réessayer',
    'no_result' => 'Aucun résultat.',
    'download' => 'Télécharger',
    'clone' => 'Dupliquer',
    'search' => 'Rechercher',
    'reset' => 'Réinitialiser',
    'export' => 'Exporter',
    'date' => 'Date',
    'second' => 'seconde|secondes',
    'minute' => 'minute|minutes',
    'hour' => 'heure|heures',
    'from' => 'du',
    'to' => 'au',
    'exception' => 'Exception',
    'home' => 'Accueil',
    'email' => 'Email',
    'username' => 'Nom d\'utilisateur',
    'password' => 'Mot de passe',
    'enter_email' => 'Saisir votre email',
    'enter_password' => 'Saisir votre mot de passe',
    'enter_username' => 'Saisir votre identifiant',
    'password_confirmation' => 'Confirmation du mot de passe',
    'enter_password_confirmation' => 'Confirmer votre mot de passe',
    'address1' => 'Adresse',
    'address2' => 'Complément d\'adresse',
    'postcode' => 'Code postal',
    'city' => 'Ville',
    'phone' => 'Téléphone',
    'all' => 'Tous',
    'download_all' => 'Tout télécharger',
    'download_selected' => 'Télécharger la sélection',
    'admin' => 'Admin',
    'logout' => 'Déconnexion',
    'advanced_search' => 'Recherche avancée',
    'next' => 'Suivant',
    'reminder' => 'RAPPEL',
    'scan' => 'Scan',
    'ok' => 'OK',
    'return' => 'Retour',
    'contact_details' => 'Coordonnées',
    'for' => 'pour',
    'add' => 'Ajouter',
    'more' => ' de plus ...',
    'show' => 'Détails',

    'drop_file' => 'Déposez votre fichier ici ou cliquez pour le télécharger.|Déposez vos fichiers ici ou cliquez pour les télécharger.',
    'uploading' => 'Téléchargement en cours ...',

    'search_term' => "Recherche \":term\"",
    'search_example' => "ean, référence, ...",
    'search_results' => "Résultats trouvés : ",

    'misc' => 'Divers',

    'axios' => [
        'unknown_error' => 'Erreur inconnue',
        'no_response_error' => 'La demande a été faite mais aucune réponse n\'a été reçue',
        'setting_up_error' => 'Something happened in setting up the request that triggered an Error',
    ],

    'alert_level' => [
        'success' => 'Succès !',
        'info' => 'Information',
        'confirm' => 'Confirmation',
        'warning' => 'Attention !',
        'error' => 'Erreur !',
    ],

    /** Settings */
    'settings' => [
        'settings' => 'Paramètres',
        'owner' => [
            'vir' => [
                'vir' => 'Paramètres VIR',
                'contractor_siret' => 'SIRET donneur d\'ordre',
                'tiers_number' => 'Numéro de tiers',
                'ftp_host' => 'Hôte FTP',
                'ftp_port' => 'Port FTP',
                'ftp_username' => 'Utilisateur FTP',
                'ftp_password' => 'Mot de passe FTP',
            ],
            'chronopost' => [
                'chronopost' => 'Paramètres Chronopost',
                'account_number' => 'Numéro de compte',
                'sub_account_number' => 'Numéro de sous-compte',
                'password' => 'Mot de passe',
                'address1' => 'Adresse',
                'address2' => 'Complément d\'adresse',
                'city' => 'Ville',
                'postcode' => 'Code postal',
                'email' => 'Email',
                'name' => 'Raison sociale',
                'phone' => 'Téléphone',
            ],
            'loctrans' => [
                'loctrans' => 'Paramètres Limousin Loctrans',
                'customer_code' => 'Code client',
                'siret' => 'Siret',
            ],
        ]
    ],

    /** Models */

    'third_parties' => [
        'third' => 'Tier|Tiers',
        'company_identification' => 'Identification entreprise',
        'siren' => 'Siren',
        'siret' => 'Siret',
        'vat_number' => 'N° TVA',
    ],

    'brand' => [
        'brand' => 'Marque|Marques',
        'new' => 'Ajouter une marque',
        'edit' => 'Modifier une marque',
        'save' => 'Enregistrer la marque',
        'delete' => 'Supprimer la marque',
        'edited' => 'Marque modifiée',
        'saved' => 'Marque enregistrée',
        'deleted' => 'Marque supprimée',
        'name' => 'Nom de la marque',
        'gdd_code' => 'Code GDD',
    ],

    'inventory' => [
        'inventory' => 'Inventaire|Inventaires',
        'name' => 'Nom',
        'state' => 'État',
        'expected_quantity' => 'Qté attendue',
        'counted_quantity' => 'Qté comptée',
        'difference' => 'Écart|Écarts',
        'created_at' => 'Date',
        'recap' => 'Récapitulatif des écarts de stock',
        'confirm' => 'Validation des écarts de stock',
        'missing_product' => 'Pour les écarts négatifs, les unitées de ventes excédentaires seront supprimées',
        'surplus_product' => 'Pour les écarts positif, les unitées de ventes manquantes seront créées',
        'concern_product' => 'Les produits concernées sont les suivants :',
        'adjustment' => 'Ajustements',

        'filters' => 'Filtres',
        'filters_desc' => 'Les filtres permettent de réduire le périmètre de l\'inventaire. Laissez tous les filtres vide pour un inventaire complet. Sélectionnez les lignes à inclure pour réduire le périmètre.',
        'product_filter_desc' => 'Renseigner un code barre par ligne',

        'new' => 'Nouvel inventaire',
        'edit' => 'Modifier l\'inventaire',
        'detail' => 'Détail de l\'inventaire',
        'delete' => 'Annuler l\'inventaire',
        'end' => 'Terminer l\'inventaire',
        'retake' => 'Reprendre l\'inventaire',
        'close' => 'Clôturer',
        'close_inventory' => 'Clôturer l\'inventaire',
        'saved' => 'Inventaire enregistré',
        'refresh_expected' => 'Rafraichir les qté attendues (+écarts)',
        'refresh_differences' => 'Rafraichir les écarts',

        'add' => 'Ajouter des qté comptées',
        'remove' => 'Supprimer des qté comptées',
        'set' => 'Définir la qté comptée',
    ],

    'carrier' => [
        'carrier' => 'Transporteur|Transporteurs',
        'new' => 'Ajouter un transporteur',
        'edit' => 'Modifier un transporteur',
        'save' => 'Enregistrer le transporteur',
        'delete' => 'Supprimer le transporteur',
        'edited' => 'Transporteur modifié',
        'saved' => 'Transporteur enregistré',
        'deleted' => 'Transporteur supprimé',
        'name' => 'Nom du transporteur',
        'type' => 'Type d\'expéditions',
    ],

    'carrier_code' => [
        'carrier_code' => 'Code transport|Codes transport',
        'new' => 'Ajouter un code transport',
        'edit' => 'Modifier un code transport',
        'save' => 'Enregistrer le code transport',
        'delete' => 'Supprimer le code transport',
        'edited' => 'Code transport modifié',
        'saved' => 'Code transport enregistré',
        'deleted' => 'Code transport supprimé',
        'carrier' => 'Transporteur',
        'chronopost_product_code' => "Code produit Chronopost",
    ],

    'stock_update' => [
        'stock_update' => 'Mouvement de stock|Mouvements de stocks',
        'motive' => 'Motif',
        'quantity' => 'Mouvement',
        'created_at' => 'Date',
        'comment' => 'Commentaire',
    ],

    'category' => [
        'category' => 'Catégorie|Catégories',
        'new' => 'Ajouter une catégorie',
        'edit' => 'Modifier une catégorie',
        'save' => 'Enregistrer la catégorie',
        'delete' => 'Supprimer la catégorie',
        'edited' => 'Catégorie modifiée',
        'saved' => 'Catégorie enregistrée',
        'deleted' => 'Catégorie supprimée',
        'name' => 'Nom de la catégorie',

        'sub_category' => 'Sous-catégorie|Sous-catégories',
        'new_sub' => 'Ajouter une sous-catégorie',
        'new_sub_of' => 'Ajouter une sous-catégorie de ',
    ],

    'category_group' => [
        'group' => "Groupe|Groupes",
        'category_group' => "Groupe de catégories|Groupes de catégories",
        'code' => "Code",
        'name' => "Nom",
        'new' => "Nouveau groupe de catégories",
        'edit' => "Modifier un groupe de catégories",
        'saved' => "Groupe enregistré",
    ],

    'country' => [
        'country' => "Pays|Pays",
        'isocode' => "Code",
        'name' => "Nom",
        'new' => "Nouveau pays",
        'edit' => "Modifier un pays",
        'saved' => "Pays enregistré",
    ],

    'document' => [
        'document' => 'Document|Documents',
        'type' => 'Type',
        'created_at' => 'Téléchargé le',

        'new' => 'Ajouter doc.',
    ],

    'entry_request' =>[
        'entry_request' => 'Demande de rendez-vous| Demandes de rendez-vous',

        //general
        'new' => 'Demander un rendez-vous',
        'saved' => 'Demande enregistrée',
        'waiting_for_confirm' => 'Demande en attente de confirmation',
        'can_send_entry_request' => 'Votre demande n\'est pas finalisée. Vous pouvez continuer l\'édition ou l\'envoyer.',
        'cant_send_entry_request' => 'Des erreurs se trouvent dans les détails de votre demande. Vous devez modifier votre demande afin de pouvoir l\'envoyer.',
        'can_confirm' => 'Vous pouvez confirmer ou refuser la demande de rendez-vous.',
        'cant_confirm' => 'Une erreur se trouve dans les détails de la commande.',
        'booked_delivery_slot' => 'Demande de rendez-vous en attente de confirmation.',
        'waiting' => 'Le créneau est réservé. Un opérateur va traiter votre demande.',
        'expected_reception' => 'En attente de reception',
        'date_start' => 'Date de début',
        'date_start_time' => 'Heure de début',
        'date_end' => 'Date de fin',
        'requested_appointment' => ' Rendez-vous demandé',
        'sure' => 'Etes vous sûr?',
        'refused' => 'Demande de rendez-vous refusée',
        'confirmed' => 'Demande de rendez-vous confirmée',

        //invalidEntries
        'errors' => 'Erreurs',
        'not_expected' => 'Commande introuvable',
        'no_product_in_entry' => 'Produit non attendu',
        'not_announced' => 'Trop de produits',

        //fields
        'stock_entry_reference' => 'Référence commande',
        'add_stock_entry_reference' => 'Ajouter une commande',
        'product_reference' => 'Référence produit',
        'free_delivery_slots' => 'Créneaux de livraison disponibles',
        'select_slot' => 'Choisissez un créneau',
        'green_reserved' => 'Les créneaux verts vous sont spécialement attribués, faîtes vos demandes en priorité sur ceux-çi.',

        //states
        'save_draft' => 'Enregistrer comme brouillon',
        'send_request' => 'Terminer et envoyer',
        'confirm' => 'Confirmer',
        'refuse' => 'Refuser',

        //refusing
        'reasons_for_refusal' => 'Motifs de refus',
        'no_free_slot' => 'Créneau non disponible',
        'wrong_content' => 'Contenu de la livraison invalide',
        'wrong_place' => 'Lieu de livraison non conforme',
        'other' => 'Autre',
        'comment' => 'Commentaire',
        'supplier_receive_reasons' => 'Le fournisseur sera notifié du refus et le créneau de rendez-vous sera libéré.',
    ],

    'delivery_slot' => [
        'delivery_slot' => 'Créneau de rendez-vous | Créneaux de rendez-vous',

        //general
        'default_delivery_slot_duration' => 'Durée d\'un créneau de rendez-vous par défaut',
        'week_schedule' => 'Programmer une semaine type',
        'day' => 'Jour',
        'starting_hour' => 'Heure de début',
        'new' => 'Ajouter un créneau',
    ],

    'expected_reception' => [
        'expected_reception' => 'Attendu de réception | Attendus de réception',

        // general
        'new' => 'Ajouter un attendu de réception',
        'edit' => 'Modifier un attendu de réception',
        'save' => 'Enregistrer l\'attendu de réception',
        'delete' => 'Supprimer l\'attendu de réception',
        'edited' => 'Attendu de réception modifié',
        'saved' => 'Attendu de réception enregistré',
        'deleted' => 'Attendu de réception supprimé',
        'show' => 'Afficher un attendu de réception',
        'appointment' => 'Rendez-vous',
        'close' => "Clôturer",
        'close_confirm' => 'Confirmer la clôture ?',
        'cancel_confirm' => 'Confirmer l\'annulation ? Toutes les pièces attendues retourneront dans les annonces.',
        'to_string' => 'Attendu de réception de %s pour %s (%s) le %s à %s',
        'summary' => 'Récapitulatif',
        'state' => "État",

        // fields
        'appointment_start' => 'Heure début',
        'appointment_end' => 'Heure fin',
        'select' => 'Attendus de réception du jour',

        // other fields
        'announced_entry' => 'Entrée annoncée|Entrées annoncées',
        'expected_entry' => 'Entrée attendue|Entrées attendues',
        'treated_entry' => 'Entrée traitée|Entrées traitées',
        'expected_quantity' => 'Quantité attendue',
        'in_stock_quantity' => 'Quantité intégré',

        // planning
        'planning' => [
            'planning' => 'Planning réceptions',
            'pieces_count' => 'Nombre de pièces',
        ],

        'terminal' => [
            'appointment_start' => 'Heure',
            'pieces_count' => 'Pièces',
            'supplier' => 'Fourn.',
            'owner' => 'Prop.',
        ],

        // other
        'entry_successfully_moved' => 'L\'entrée annoncée à bien été déplacée.',
        'entry_unsuccessfully_moved' => 'Une erreur est survenue lors du déplacement.',
        'entry_successfully_closed' => 'L\'entrée annoncée a bien été clôturée.',
        'set_expected' => 'Ajouter dans l\'attendu',
        'cancel_expected' => 'Retirer de l\'attendu',
        'no_litigation' => 'Aucun litige sur cette réception',
        'no_unexpected' => 'Aucun excédent sur cette réception',
        'reception' => 'Réception',

        // validation
        'validation' => [
            'appointment_end_time' => [
                'after' => 'L\'heure de fin ne peux pas être avant l\'heure de début'
            ]
        ],

        // receipt
        'receipt' => "Récépissé de réception",
        'receipt_short' => "Récépissé",
        'qty_expected' => "attendue",
        'qty_received' => "reçue",
        'litigation' => "Litige|Litiges",
    ],

    'jobs' => [
        'job' => "Tâche|Tâches",
        'action_scheduled' => "Tâche ajoutée à la file|Tâches ajoutées à la file",
        'job_deleted' => "Tâche supprimée|Tâches supprimées",
        'job_running_not_deleted' => "Tâche en cours. Impossible de la supprimer",
        'failed_job' => "Tâche en erreur|Tâches en erreur",
        'no_jobs' => "Aucune tâche en cours",
        'no_failed_jobs' => "Aucune tâche en erreur",
        'display_name' => "Classe",
        'tries' => "Tentatives",
        'status' => "Statut",
        'created_at' => "Créée le",
        'available_at' => "Disponible le",
        'running_at' => "Lancée le",
        'failed_at' => "Echouée le",
        'queue' => "Queue",
        'job_detail' => "Détails de la tâche",
        'payload' => "Payload",
        'command' => "Commande",
    ],

    'reception_litigation' => [
        'type' => 'Type'
    ],

    'unexpected_product' => [
        'unexpected_product' => "Produit inattendu|Produits inattendus",
    ],

    'location' => [
        'location' => 'Emplacement|Emplacements',
        'new' => 'Ajouter un emplacement',
        'edit' => 'Modifier un emplacement',
        'save' => 'Enregistrer l\'emplacement',
        'delete' => 'Supprimer l\'emplacement',
        'edited' => 'Emplacement modifiée',
        'saved' => 'Emplacement enregistrée',
        'deleted' => 'Emplacement supprimée',
        'code' => 'Code',
        'type' => 'Type',

        'is_sellable' => 'Disponible à la vente',
        'unsellable' => 'Indisponible à la vente',
        'is_picking' => 'Zone de picking',
        'available_sales_units' => 'Unités de vente disponibles',
        'reserved_sales_units' => 'Unités de vente réservées',
        'total_sales_units' => 'Total unités de ventes',
        'total_receiving_units' => 'Total unités de réception',
        'detail' => 'Détail emplacement de stockage',

        'code_desc' => 'Format imposé pour les racks : A-00-00. Exemple : C-35-01 représente l\'emplacement de la travée C, colonne 35, niveau 1.',
        'capacity' => 'Capacité',
        'max_depth_cm' => 'Profondeur max (cm)',
        'max_width_cm' => 'Largueur max (cm)',
        'max_height_cm' => 'Hauteur max (cm)',
        'max_weight_kg' => 'Poids max (kg)',

        'reception' => 'Emplacement de réception',
        'prepackaging' => 'Emplacement de pré-emballage',
        'packaging' => 'Emplacement d\'emballage',
        'loading' => 'Emplacement de chargement',
    ],

    'mail' => [
        'notification_inputs_document_subject' => "Un nouveau document de réception est disponible",
        'failed_jobs_subject' => "Des jobs sont en erreur",
        'undated_empty_receiving_units' => "Unités de vente vide et sans date de sortie",
        'out_of_stock_subject' => "Rupture de stock",
        'stock_removal_detail_cancel' => "Annulation en phase %s",
        'entry_request_subject' => 'Demande de rendez-vous',
        'accept_entry_request_subject' => 'Demande de rendez-vous acceptée',
        'refuse_entry_request_subject' => 'Demande de rendez-vous refusée',
        'process_error' => 'Echec d\'un processus',
    ],

    'manufacturer' => [
        'manufacturer' => 'Fabricant|Fabricants',
        'new' => 'Ajouter un fabricant',
        'edit' => 'Modifier un fabricant',
        'save' => 'Enregistrer le fabricant',
        'delete' => 'Supprimer le fabricant',
        'edited' => 'Fabricant modifié',
        'saved' => 'Fabricant enregistré',
        'deleted' => 'Fabricant supprimé',
        'name' => 'Nom du fabricant',
        'gdd_code' => 'Code GDD',
    ],

    'owner' => [
        'owner' => 'Propriétaire|Propriétaires',
        'new' => 'Ajouter un propriétaire',
        'edit' => 'Modifier un propriétaire',
        'save' => 'Enregistrer le propriétaire',
        'delete' => 'Supprimer le propriétaire',
        'edited' => 'Propriétaire modifié',
        'saved' => 'Propriétaire enregistré',
        'deleted' => 'Propriétaire supprimé',
        'code' => 'Code du propriétaire',
        'name' => 'Nom du propriétaire',
        'shipper_name' => 'Nom d\'expéditeur',
        'phone' => 'Téléphone',
        'api_key' => 'Clé API',
        'generate_api_key' => 'Générer une clé API',
        'api_access' => 'Accès API',
        'api_authorized_ips' => 'Adresses IPs autorisées',
        'api_authorized_ips_placeholder' => 'Liste des adresses IPs autorisées à utiliser l\'API, séparées par des virgules',
        'code_placeholder' => '2 caractères maximum',
        'choice' => 'Choix proriétaire',
        'notification' => 'Notification|Notifications',
        'notification_base_url' => 'Base url de l\'api de notifications',
        'email_recipients_inputs' => 'Emails recevant les notifications des réceptions',
        'email_recipients_helper' => 'Emails séparés par des virgules',
        'validation' => [
            'notification_base_url' => 'La base url de notification doit se terminer par un slash (/)'
        ],
        'ftp_import' => 'Import FTP',
        'ftp_import_activated' => 'Import des entrées et sorties par FTP activé',
        'ftp_access' => 'Accès FTP',
        'ftp_username' => 'login',
        'ftp_password' => 'mot de passe',
        'ftp_server' => 'serveur',
        'ftp_port' => 'port',
        'management_mode' => 'Mode de gestion',
    ],

    'product' => [
        'product' => 'Produit|Produits',
        'product_page' => 'Fiche produit|Fiches produits',
        'new' => 'Ajouter un produit',
        'edit' => 'Modifier un produit',
        'save' => 'Enregistrer le produit',
        'delete' => 'Supprimer le produit',
        'edited' => 'Produit modifié',
        'saved' => 'Produit enregistré',
        'deleted' => 'Produit supprimé',
        'detail' => 'Détail du produit',
        'barcode' => 'Code barre',
        'reference' => 'Référence',
        'reference_or_barcode' => 'Référence ou Code barre',
        'pallet_number' => 'Numéro de palette',
        'pallet_number_abbr' => 'N° palette',
        'designation' => 'Désignation',
        'quantity' => 'Quantité',
        'requires_serial_numbers' => 'Requiert l\'utilisation de numéros de série',
        'list' => 'Liste des produits',
        'requires_unit_label' => 'Imprimer une étiquette par produit à la réception',
        'requires_weight' => 'Requiert la saisie du poids',
        'options' => 'Options',
        'product_group' => 'Groupe produit',

        'dimensions' => 'Dimensions',
        'depth_cm' => 'Prodondeur (cm)',
        'width_cm' => 'Largeur (cm)',
        'height_cm' => 'Hauteur (cm)',
        'weight_kg' => 'Poids (kg)',
        'volume_m3' => 'Volume (m³)',
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
        'dashboard' => 'Type de dashboard',
    ],

    'stock' => [
        'stock' => 'Stock|Stocks',
        'new' => 'Ajouter un stock',
        'edit' => 'Modifier un stock',
        'save' => 'Enregistrer le stock',
        'delete' => 'Supprimer le stock',
        'edited' => 'Stock modifié',
        'saved' => 'Stock enregistré',
        'deleted' => 'Stock supprimé',
        'weighted_price_tax_excl' => 'Prix moyen pondéré',
        'physical' => 'Stock physique',
        'coming_entries' => 'Entrées à venir',
        'coming_outputs' => 'Sorties à venir',
        'detail' => 'Détail du stock',
        'stock_sales_units_list' => 'Liste des unités de vente',
        'stock_package_units_list' => 'Liste des unités de colisage',
        'stock_receiving_units_list' => 'Liste des unités de réception',
        'estimated' => 'Stock prévisionnel',
        'available' => 'Stock disponible',
        'location' => 'Emplacement',
        'preparing' => 'Stock en préparation',
        'reserved' => 'Stock réservé',
        'no_sellable_stock' => 'Stock non vendable',
        'error' => 'Stock erreur',
        'broken' => 'Stock cassé',
        'stock_by_location' => 'Stock par zone',
        'stock_by_receiving_unit' => 'Stock par UR',
        'stock_by_reference' => 'Stock par référence',
        'stock_by_reference_detail' => 'Détail stock par référence',
        'scan' => 'Scan produit ou UR',
        'consult_stock' => 'Consulter les emplacements',
        'product_scan' => 'Scan produit',
        'stock_by_serial_number' => 'Stock par N° de série',
    ],

    'stock_package_unit' => [
        'abbr' => 'UC',
        'stock_package_unit' => 'Unité de colisage|Unités de colisage',
        'code' => 'Code unité de colisage',
    ],

    'stock_receiving_unit' => [
        'abbr' => 'UR',
        'stock_receiving_unit' => 'Unité de réception|Unités de réception',
        'code' => 'Code UR',
        'type' => 'Type de palette',
        'create' => 'Créer une palette',
        'list' => 'Liste des UR',
        'entry_date' => 'Date d\'entrée',
        'removal_date' => 'Date de sortie',
        'detail' => 'Détail unité de réception',
        'out' => 'Sortie',
    ],

    'stock_sales_unit' => [
        'abbr' => 'UV',
        'stock_sales_unit' => 'Unité de vente|Unités de vente',
        'code' => 'Code unité de vente',
        'stock_entry_date' => 'Date d\'entrée',
        'stock_removal_date' => 'Date de sortie',
        'stock_error_date' => 'Date de mises en erreur',
        'error_reason' => 'Motif d\'erreur',
        'serial_number' => 'Numéro de série',
        'serial_number_abbr' => 'N° série',
    ],

    'stock_entry' => [
        'stock_entry' => 'Entrée|Entrées',
        'announced_stock_entry' => 'Entrée annoncée|Entrées annoncées', // @todo : no!
        'cancel' => 'Annuler l\'entrée',
        'cancel_confirm' => 'Confirmer l\'annulation ?',
        'new' => "Annoncer une entrée",
        'new_announced' => "Créer une entrée de stock annoncée",
        'edit_announced' => "Modifier une entrée de stock annoncée",
        'edit' => 'Modifier l\'entrée',
        'delete_announced' => "Supprimer une entrée de stock annoncée",
        'reference' => "Référence cmd.",
        'saved' => "Entrée de stock enregistrée",
        'state' => "État",
        'source' => "Source",
        'pallet_numbers' => 'Numéros de palette',
        'pallet_numbers_abbr' => 'N° palette',
        'pallet_numbers_example' => 'ex : num-palette-12345',
        'pallet_numbers_help' => 'Séparés des sauts de ligne',
        'pallet_numbers_add' => 'Ajouter les numéros de série',
        'pallet_numbers_add_or_weight' => 'Ajouter les numéros de série ou les poids',
        'weight' => 'Poids',
        'weight_example' => 'ex : 100.10',
        'input_select' => array(
            'warehouse' => "-- Entrepôt --",
            'owner' => "-- Propriétaire --",
            'supplier' => "-- Fournisseur --",
        ),

        'created_at' => 'Date annonce',
        'quantity' => 'Quantité',

        'validation' => [
            'reference_not_unique' => 'Un entrée existe déjà avec cette référence'
        ],
    ],

    'stock_entry_detail' => [
        'stock_entry_detail' => 'Détails',
        'state' => "État",
        'unit_price_tax_excl' => "Prix Unitaire (HT)",
        'detail' => "Détail",
        'new' => "Ajouter un détail",
        'edit' => "Modifier un détail",
        'announced_stock_entry_detail' => "Détail de l'entrée en stock",
        'saved' => "Détail ajouté à l'entrée de stock",
        'search_product' => "Rechercher un produit",
        'quantity' => "Quantité",
        'is_gdd' => "Le produit doit être déclaré à la centrale d’achat GDD",
        'is_gdd_short' => "GDD",
    ],

    'supplier' => [
        'supplier' => 'Fournisseur|Fournisseurs',
        'new' => 'Ajouter un fournisseur',
        'edit' => 'Modifier un fournisseur',
        'save' => 'Enregistrer le fournisseur',
        'delete' => 'Supprimer le fournisseur',
        'edited' => 'Fournisseur modifié',
        'saved' => 'Fournisseur enregistré',
        'deleted' => 'Fournisseur supprimé',
        'name' => 'Nom du fournisseur',
        'choice' => 'Choix fournisseur',
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
        'name' => 'Nom complet',
        'email' => 'Email',
        'phone_number' => 'Numéro de téléphone',
        'password' => 'Mot de passe',
        'password_change' => 'Changer de mot de passe',
        'password_confirmation' => 'Confirmation du mot de passe',
        'active' => 'Connexion autorisée ?',
        'owners_managed' => 'Propriétaires autorisés',
        'warehouses_managed' => 'Entrepôts autorisés',
        'suppliers_managed' => 'Fournisseurs autorisés',
        'password_help' => 'Longueur minimum : 8 - Caractères requis : Majuscule, Minuscule, Chiffre, Symbole',
        'my_account' => 'Mon compte',
        'my_informations' => 'Mes informations',
        'empty_field_help' => 'Laisser le champ vide pour tout autoriser'
    ],

    'warehouse' => [
        'warehouse' => 'Entrepôt|Entrepôts',
        'new' => 'Ajouter un entrepôt',
        'edit' => 'Modifier un entrepôt',
        'save' => 'Enregistrer l\'entrepôt',
        'delete' => 'Supprimer l\'entrepôt',
        'edited' => 'Entrepôt modifié',
        'saved' => 'Entrepôt enregistré',
        'deleted' => 'Entrepôt supprimé',
        'code' => 'Code de l\'entrepôt',
        'name' => 'Nom de l\'entrepôt',
        'email' => 'Email de l\'entrepôt',
        'color' => 'Couleur des événements l\'entrepôt',
        'none' => 'Aucun entrepôt configuré',
        'all' => 'Tous les entrepôts',
    ],

    'packaging' => [
        'packaging' => 'Emballage|Emballages',
        'new' => 'Ajouter un emballage',
        'edit' => 'Modifier un emballage',
        'save' => 'Enregistrer l\'emballage',
        'delete' => 'Supprimer l\'emballage',
        'edited' => 'Emballage modifié',
        'saved' => 'Emballage enregistré',
        'deleted' => 'Emballage supprimé',
        'code' => 'Code',
        'designation' => 'Désignation',
    ],

    'stock_removal' => [
        'add' => 'Annoncer une sortie',
        'edit' => 'Modifier la sortie',
        'stock_removal' => 'Sortie|Sorties',
        'reference' => 'Référence cmd.',
        'vir_final_hub' => 'Hub final VIR',
        'vir_delivery_type' => 'Type livraison VIR',
        'state' => 'État',
        'source' => "Source",
        'requested_warehouse' => 'Entrepôt requis',
        'requested_shipment_date' => 'Date sortie demandée',
        'effective_shipment_date' => 'Date expédition',
        'wait_for_stock' => 'Reliquat accepté ?',
        'save_draft' => 'Enregistrer le brouillon',
        'save_announce' => 'Enregistrer et annoncer',
        'created_at' => 'Date annonce',
        'saved' => "Sortie annoncée",
        'request_warehouse' => "Entrepôt d'expédition",
        'request_warehouse_help' => "Force l'expédition à partir de cet entrepôt. Pas d'expédition à partir d'un autre entrepôt en cas de rupture.",
        'trsf_expected_reception' => "Attendu de réception associée",

        'details' => 'Détail produits',
        'details_pallets' => 'Détail palettes',
        'pallet' => 'Palette',
        'summary' => 'Récapitulatif',
        'quantity' => 'Quantité',
        'add_pallet' => 'Ajouter une palette',

        'dashboard_exits' => 'Tableau de bord sorties',
        'dashboard_picking' => 'Tableau de bord picking',

        'cancel' => 'Annulation demande propriétaire',
        'cancel_confirm' => 'Confirmer l\'annulation ?',

        'carrier_bulk_print' => 'Téléchargement .ZPL',
        'no_carrier_label_to_print' => 'Aucune étiquette avec les critères sélectionnés',
        'label_printed' => ':count étiquette(s) téléchargée(s)',
        'print_label_removal_pallet' => 'Imprimer étiquette palette',
        'modify_removal_pallet' => 'Modification palette',

        'serialnumbers_add' => 'Ajouter les numéros de série',
    ],

    'prioritised_shipment' => [
        'prioritised_shipment' => 'Expéditions priorisées',
        'no_prioritisation' => 'Aucune priorisation en cours.',
        'prioritised_desc' => 'Les commandes sont traitées jusqu\'au (inclus)',
        'add' => 'Ajouter / modifier',
        'delete_all' => 'Supprimer tous',
    ],

    'stock_removal_detail' => [
        'state' => 'État',
        'shipment_internal_code' => 'EAN 128 VIR',
        'declared_value_tax_excl' => 'Valeur déclarée u. HT'
    ],

    'preparation_configuration' => [
        'preparation_configuration' => "Configurations de préparation",
        'disactivate' => "Désactiver",
        'activate' => "Activer",
        'auto_prepare' => "Préparation auto."
    ],

    'state_history' => [
        'state_history' => 'États',
        'state' => 'État',
        'created_at' => 'Date',
    ],

    'stock_removal_detail_remove' => [
        'cancel_product' => 'Annulation produit',
        'logistic_error' => 'Annulation erreur logistique',
        'owner_cancel_detail' => 'Annulation suite à une demande du propriétaire, le produit sera remis dans le stock disponible',
        'logistic_error_detail' => 'Annulation suite à une erreur logistique, le produit sera mis en stock erreur',
    ],

    'recipient' => [
        'recipient' => 'Destinataire',
        'company_name' => 'Société',
        'firstname' => 'Prénom',
        'lastname' => 'Nom',
        'address1' => 'Adresse',
        'address2' => 'Compl. adresse',
        'postcode' => 'Code postal',
        'city' => 'Ville',
        'phone' => 'Téléphone',
        'phone_mobile' => 'Portable',
        'email' => 'Email',
        'country' => 'Pays',
        'search' => 'Chercher un destinataire',
        'search_help' => 'Recherche par nom, prénom, ou société : cela pré-remplira les données ci-dessous'
    ],

    'filling_rate' => [
        'filling_rate' => 'Taux de remplissage prévisionnel',
        'filling_top_rate' => 'Taux de remplissage top',
        'estimated_filling' => 'Remplissage prévisionnel',
        'estimated_top_filling' => 'Remplissage prévisionnel top',
        'products_count' => 'Nombre de produits',
        'total_volume' => 'Volume total (m³)',
        'unmapped_categories' => 'Catégories non mappées',
        'unmapped_categories_alert' => 'Les produits dont les catégories ne sont pas mappées ne sont pas pris en compte dans le calcul du taux de remplissage prévisionnel. Merci de contacter votre administrateur',
        'unmapped_categories_list' => 'Liste de catégories non mappées',
    ],

    'truckload' => [
        'truckload' => 'Chargement|Chargements',
        'state' => 'État',
        'created_at' => 'Date création',
        'closed_at' => 'Date de clôture',
        'is_loaded' => "Terminer",
        'close' => "Clôturer",
        'close_confirm' => 'Confirmer la clôture ?',
        'pieces_count' => 'Nombre de pièces',
        'piece' => "Pièce|Pièces",
        'total_weight' => 'Poids total (kg)',
        'unit_weight' => 'Poids total (kg)',
        'volume' => 'Volume (m³)',
        'content' => 'Contenu',
        'action' => "Action|Actions",
        'download' => "Téléchargement|Téléchargements",
        'voucher' => "Bon de transport",
        'delivery_voucher' => "Bon de livraison|Bons de livraison"
    ],

    /** Views */

    'dashboard' => [
        'not_closed_expected_receptions' => 'Réceptions terminées non clôturées',
        'reception_products' => 'Réceptions non rangés',
        'cancellation_products' => 'Annulations non rangés',
        'prepackaging_products' => 'Produits en zone de pré-emballage',
        'not_closed_truckloads' => 'Chargements non clôturées',
        'waiting_entry_requests' => 'Demandes de rendez-vous en attente',
    ],

    'navbar' => [
        'dashboard' => 'Tableau de bord',
        'article' => 'Base article',
        'storage' => 'Stockage',
        'third_parties' => 'Tiers',
        'advanced' => 'Avancés',
        'history' => "Historiques",
    ],

    'import_history' => [
        'import_history'    => 'Historique des imports',
        'failed'            => 'Echec',
        'message'           => 'Message',
        'imported_rows'     => 'Lignes importées',
        'imported_units'    => 'Produits concernés',
        'file'              => 'Fichier',
        'date'              => 'Date',
        'type'              => 'Type',
        'status'            => 'Etat',
    ],

    'login' => [
        'login' => 'Connexion',
        'sign_in' => 'Identification',
        'enter_email_and_password' => 'Saisir votre adresse email et votre mot de passe pour vous connecter.',
        'forgot_your_password' => 'Mot de passe oublié ?',
        'remember_me' => 'Se souvenir de moi',
    ],

    'recover_password' => [
        'recover_password' => 'Récupérer mot de passe',
        'reset_password' => 'Réinitialiser le mot de passe',
        'enter_email_reset_password' => 'Saisir votre adresse email afin de recevoir les instructions pour réinitialiser votre mot de passe',
        'back_to' => 'Retour',
        'login' => 'Connexion',
    ],

    'reset_password' => [
        'reset_password' => 'Réinitialiser mot de passe',
    ],

    'vir_delivery_form' => [
        'distribution_agency' => 'Agence de distribution',
        'transport_plan' => 'Plan de transport',
        'recipient' => 'Destinataire',
        'creating_date' => 'Date de création',
        'entry_point' => 'Point d\'entrée',
        'article_ref' => 'Réf Article',
        'denomination' => 'Dénomination',
        'instructions' => 'Instructions',
        'cellphone' => 'Tél portable',
        'phone' => 'Tél fixe',
        'sender' => 'Expéditeur',
        'tel' => 'Tél',
        'order' => 'Commande',
        'packing' => 'Colissage',
        'broken_instructions' => 'Lors de la livraison, les dommages ou spoliations doicent faire l\'objet de la part du destinataire de réserves ecrites, précises, complètes, datées et signées sur le bordereau de livraison.',
    ],

    'stock_movement' => [
        'stock_movement' => 'Déplacement stock|Déplacements stock',
        'tidy_stock_movement' => 'Rangement réception',
        'scan' => 'Scan produit ou UR',
        'receiving_unit_scan' => 'Scan UR',
        'no_receiving_unit_product' => 'Produit hors palette',
        'source_scan' => 'Scan zone source',
        'quantity' => 'Saisie quantité déplacée',
        'quantity_moved' => 'Quantité déplacée',
        'destination_scan' => 'Scan zone destination',
        'receiving_unit' => 'Palette',
        'source' => 'Zone source',
        'destination' => 'Zone de destination',
        'create_receiving_unit' => 'Créer une palette',
        'locations_filter' => 'Filtre par zone',
    ],

    'terminal_config' => [
        'config' => 'Configuration',

        'ip' => 'Adresse IP imprimante',

        'saved' => 'Configuration enregistrée',
        'test_print' => 'Test impression',
        'printed' => 'Impression lancée',
    ],

    'invoicing' => [
        'invoicing' => 'Facturation',
        'context' => 'Contexte',
        'period' => 'Période',
        'details' => 'Détails',
        'total' => 'Total',

        'logistic_invoices' => 'Facturation logistique',
        'pallet_entry' => 'LOG 1 - Réception palette',
        'unit_entry' => 'LOG 1 - Réception unité',
        'pallet_daily_storage' => 'LOG 2 - Stockage palette par jour',
        'unit_daily_storage' => 'LOG 2 - Stockage unité par jour',
        'unit_removal' => 'LOG 3 - Expédition B2C',
        'pallet_removal' => 'LOG 3 - Expédition palette B2B',
        'packaging' => 'LOG 4 - Suremballages',

        'nb_days' => 'Nb jour période',
        'pallet_count' => 'Palettes',
    ],
];

<?php

return [
    'internal' => 'Erreur inconnue',

    'stock_movements' => [
        'incompatible_warehouses' => "Impossible de réaliser un mouvement de stock entre deux entrepôts.",
        'barcode_error' => "Code barre invalide ou introuvable",
        'requested_quantity' => "Quantité demandée : ",
        'available_quantity' => "Quantité disponible : ",
        'receiving_unit_quantity' => "Quantité demandée : :quantity, Quantité disponible pour :owner : :stock_sales_units_quantity ",
        'quantity' => "Quantité demandée : :quantity, Quantité disponible pour :owner : :stock_sales_units_quantity ",
        'unmovable_stock_sales_units' => "Unité de vente non déplacable",
        'destination_equal_source' => "La source et la destination doivent être différentes",
        'no_one_out_of_ur' => 'Produit non disponible hors palette',
        'absent_product' => 'Produit non présent dans cet emplacement',
        'receiving_unit_location' => 'Erreur emplacement palette',
        'receiving_unit_error' => 'La palette de destination doit être différente de celle d\'origine',
        'receiving_unit_owner' => 'La palette n\'appartient pas à :owner',
    ],
    'expected_receptions' => [
        'too_few_stock_entry_details' => 'Pas assez de quantités disponibles.',
    ],
    'stock_entry_details' => [
        'cannot_set_expected_when_not_announced' => 'Impossible de renseigner "Attendu" une entrée qui n\'est pas dans un état "Annoncé".',
        'cannot_set_announced_when_not_expected' => 'Impossible de renseigner "Annoncé" une entrée qui n\'est pas dans un état "Attendu".',
        'incompatibles_expected_receptions' => 'Impossible de réaliser un mouvement d\'un attendu étranger.',
        'cannot_set_missing_when_not_expected' => 'Impossible de renseigner "Litige - produit manquant" une entrée qui n\'est pas dans un état "Attendu".',
        'cannot_set_integrated_in_stock_when_not_expected' => 'Impossible de renseigner "Intégré au stock" une entrée qui n\'est pas dans un état "Attendu".',
        'cannot_set_broken_when_not_expected' => 'Impossible de renseigner "Litige - produit cassé" une entrée qui n\'est pas dans un état "Attendu".',
        'cannot_cancel' => 'Impossible d\'annuler une entrée qui n\'est pas dans un état "Annoncé"',
    ],

    'expected_reception' => [
        'unexpected_barcode' => 'Le code barre :barcode ne fait la partie de l\'attendu.',
        'exceeded_quantity' => 'Quantité dépassée pour le code barre : :barcode.',
        'select_owner_or_supplier' => 'Sélectionner un propriétaire et/ou un fournisseur.',
        'too_much_quantity' => 'La quantité saisie est supérieure à la quantité attendu restante.',
        'movement_not_allowed' => 'Le déplacement n\'est pas autorisé',
        'no_pallet_numbers' => 'les détails de l\'attendu de réception non pas de numéro de palette',

        'no_product_integrated' => 'ANNULATION IMPOSSIBLE. Le code barre n\'a pas encore été intégré au stock dans cet attendu.',
        'no_product_in_reception' => 'ANNULATION IMPOSSIBLE. Le code barre n\'est plus dans la zone de réception.',
    ],

    'warehouse' => [
        'missing_reception_location' => 'La zone de réception n\'a pas été configuré sur l\'entrepôt',
        'missing_packaging_location' => 'La zone d\'emballage n\'a pas été configuré sur l\'entrepôt',
        'missing_loading_location' => 'La zone de chargement n\'a pas été configuré sur l\'entrepôt',
    ],

    'stock_entries' => [
        'non_editable' => "Cette annonce ne peut plus être modifiée.",
        'is_gdd_unavailable' => "Le produit :barcode ne peut pas être déclaré à la centrale GDD car sa marque ou son fabricant n'ont pas de code GDD d'associés",
        'reference_already_exist' => "La référence de la commande existe déjà",
        'pallet_number_already_exist' => "Le numéro de palette :serial_number est déjà utilisé pour :supplier",
        'enter_quantity' => "Veuillez saisir une quantité",
        'enter_price' => "Veuillez saisir un prix",
        'enter_pallet_number' => 'Veuillez saisir un numéro de palette',
        'enter_weight' => 'Veuillez saisir un poids',
    ],

    'stock_removal_details' => [
        'is_not_cancelable' => 'Le statut du détail ne lui permet pas d\'être annulé.',
        'pallet_number_invalid' => 'Le numéro de palette :serial_number n\'existe pas pour :owner_name / :product',
        'pallet_number_unavailable' => 'Le numéro de palette :serial_number pour :owner_name n\'est pas disponible',
        'no_enough_removal_detail_on_pallet' => 'Pas assez d\'exemplaires du produit :product sur la palette :removalPalletId',
        'no_enough_removal_detail_out_of_pallet' => 'Pas assez d\'exemplaires du produit :product hors palette',
        'product_not_exist' => 'Le produit :product n\'existe pas',
        'product_not_in_removal' => 'Le produit :product n\'est pas dans la sortie :removal',
    ],

    'reception_litigations' => [
        'product_missing_from_expected_reception' => 'Le produit :barcode n\'existe pas dans l\'attendu de réception',
        'not_enough_products_in_expected_reception' => 'Impossible de renseigner :quantity du produit :barcode en litige car l\'attendu de réception n\'en n\'attend que :max_quantity',
    ],

    // terminal
    'picking' => [
        'no_carrier_code_to_pick' => 'Aucun picking en attente',
        'not_in_wave' => 'Le produit scanné ne fait pas partie de la vague',
        'not_in_wave_multiple' => 'La quantité saisie (:requested_quantity) dépasse le nombre de produit dans la vague (:expected_quantity)',
        'quantity_not_available' => 'La quantité demandée (:requested_quantity) dépasse le nombre de produit disponibles (:available_quantity) pour ce propriétaire',
    ],

    'packaging' => [
        'not_in_wave' => 'Le produit n\'est pas dans la vague d\'emballage / étiquetage',
        'no_result_to_package' => 'Aucun emballage en attente',
        'no_chronopost_tracking' => 'Aucun numéro de suivi Chronopost associé.',
        'no_chronopost_zpl' => 'Étiquette Chronopost introuvable.',
    ],

    'palette_creation' => [
        'empty' => 'Aucun produit scanné',
        'error_location_receiving_unit' => 'L\'UR scannée n\'est pas dans le bon emplacement',
        'no_out_receiving' => 'Pas de produit en dehors d\'une palette',
    ],

    'loading' => [
        'no_carrier_code_to_load' => 'Aucun chargement en attente',
        'bad_carrier_code' => 'Le colis scanné ne fait pas partie de la vague en cours',
        'cancellation_requested' => 'EXPÉDITION ANNULÉE (demande propriétaire)',
        'not_loading' => 'Le colis n\'est pas en attente de chargement',
    ],

    'migration' => [
        'bad_warehouse' => 'La zone sélectionnée ne fait pas partie de l\'entrepôt',
        'invalid_ean13' => 'EAN13 invalide',
    ],

    'migration_filterie' => [
        'error_stock_it_barcode' => 'Le code barre scanner n\'est pas un code barre stock-it pour la filterie',
        'stock_it_number_already_use' => 'Le N° Stock-It :stock_it_number est déjà utilisé dans l\'export Stock-It',
        'pallet_number_already_use' => 'Le N° de palette :pallet_number est déjà utilisé dans l\'export Stock-It',
    ],

    'inventory' => [
        'no_inventory_in_progress' => 'Aucun inventaire en cours.',
        'cancel_not_counted_product' => 'Vous essayer d\'annuler un produit qui n\'a pas été compté.',
        'cancel_too_much_quantities' => 'Vous essayez d\'annuler plus de produit qu\'il n\'en a été compté.',
        'pallet_number_already_scan' => 'Cette palette est déjà comprise dans l\'inventaire',
        'error_location_pallet_number' => 'Cette palette est censée être dans l\'emplacement :location',
    ],

    'messages' => [
        'channel_given_does_not_exists' => 'La canal donné n\'existe pas',
    ]
];

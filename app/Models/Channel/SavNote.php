<?php

namespace App\Models\Channel;

use DateTime;

/**
 * @property int $id
 * @property string $manufacturer
 * @property string $pms_delay
 * @property string $manufacturer_warranty
 * @property string $gc_plus
 * @property boolean $gc_plus_delay
 * @property string $hotline
 * @property string $brand_email
 * @property string $brand_information
 * @property string $regional_information
 * @property Datetime $created_at
 * @property Datetime $updated_at
 */
class SavNote
{
    protected $fillable = [
        'manufacturer',
        'pms_delay',
        'manufacturer_delay',
        'gc_plus',
        'gc_plus_delay',
        'hotline',
        'brand_email',
        'brand_information',
        'regional_information',
    ];
}

<?php

namespace App\Models\Channel;

use Cnsi\Searchable\Trait\Searchable;
use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $manufacturer
 * @property string $pms_delay
 * @property string $manufacturer_warranty
 * @property boolean $gc_plus
 * @property string $gc_plus_delay
 * @property string $hotline
 * @property string $brand_email
 * @property string $brand_information
 * @property string $supplier_information
 * @property Datetime $created_at
 * @property Datetime $updated_at
 */
class SavNote extends Model
{
    use Searchable;

    protected $fillable = [
        'manufacturer',
        'pms_delay',
        'manufacturer_warranty',
        'gc_plus',
        'gc_plus_delay',
        'hotline',
        'brand_email',
        'brand_information',
        'supplier_information',
    ];

    protected $searchable = [
        'manufacturer',
    ];

    protected $casts = [
        'gc_plus' => 'boolean',
    ];

    public function getShowRoute(): string
    {
        return 'show_sav_note';
    }

    public function __toString(): string
    {
        return $this->manufacturer;
    }
}

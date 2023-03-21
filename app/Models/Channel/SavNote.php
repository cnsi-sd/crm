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
 * @property string $regional_information
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
        'regional_information',
    ];



    protected $searchable = [
        'id',
        'manufacturer',
    ];

    public function getShowRoute(): string
    {
        // TODO: Implement getShowRoute() method.
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }


}

<?php

namespace App\Models\Ticket\Revival;

use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $frequency
 * @property int $max_revival
 * @property string $end_state
 * @property DateTime $created_at
 * @property Datetime $updated_at
 *
 * @property DefaultAnswer $defaultAnswer
 * @property Channel[] $channels
 */
class Revival extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'frequency',
        'default_answer_id',
        'max_revival',
        'end_default_answer_id',
        'end_state',
        'created_at',
        'updated_at'
    ];

    public function default_answer(): BelongsTo
    {
        return $this->belongsTo(DefaultAnswer::class);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'channel_default_answer', 'default_answer_id','channel_id');
    }
}

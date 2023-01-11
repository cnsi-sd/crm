<?php

namespace App\Models\Tags;

use App\Models\Ticket\Thread;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 *
 * @property Thread[] $threads
 * @property Tags[] $tags
 *
 * @property Datetime $created_at
 * @property Datetime $updated_at
 */
class TagList extends Model
{
    protected $table = 'tagLists';

    protected $fillable = [
        'created_at',
        'updated_at'
    ];

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'tag_tagLists', 'taglist_id', 'tag_taglist_id');
    }
}

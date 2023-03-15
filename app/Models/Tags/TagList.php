<?php

namespace App\Models\Tags;

use App\Models\Ticket\Thread;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 *
 * @property Thread[] $thread
 * @property Tag[] $tags
 *
 * @property Datetime $created_at
 * @property Datetime $updated_at
 */
class TagList extends Model
{
    protected $table = 'tagLists';

    protected $fillable = [
        'thread_id',
        'created_at',
        'updated_at'
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_tagLists', 'tagList_id', 'tag_id');
    }

    public function addTag(int $tagId){
        $tag = Tag::findOrFail($tagId);
        $this->tags()->attach($tag->id);
        return $tag;
    }
}

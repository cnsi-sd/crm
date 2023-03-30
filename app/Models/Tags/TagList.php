<?php

namespace App\Models\Tags;

use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $ticket_id
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property Ticket $ticket
 * @property Tag[] $tags
 */
class TagList extends Model
{
    protected $table = 'tagLists';

    protected $fillable = [
        'ticket_id',
        'created_at',
        'updated_at'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tag_tagLists', 'tagList_id', 'tag_id');
    }

    public function addTag(Tag $tag): void
    {
        $data = Tag::query()
            ->select('tags.*')
            ->join('tag_tagLists', 'tag_tagLists.tag_id', 'tags.id')
            ->join('tagLists', 'tag_tagLists.tagList_id', 'tagLists.id')
            ->where('tags.id', $tag->id)
            ->count();
        $c = 'cd';
        if ($data == 0){
            $this->tags()->attach($tag->id);
        }
    }
}

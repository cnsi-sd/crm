<?php

namespace App\Models\Channel;

use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Helpers\TinyMCE;
use App\Models\Ticket\Message;
use App\Models\Ticket\Revival\Revival;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $content
 * @property boolean $is_locked
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $deleted_at
 *
 * @property Channel[] $channels
 * @property Revival[] $revivals
 * @property Message[] $messages
 */
class DefaultAnswer extends Model
{
    use SoftDeletes;

    /**
     * @var mixed|true
     */
    protected $fillable = [
        'name',
        'content',
        'is_locked',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    public function isChannelAuthorized(Channel $channel) {
        return $this->channels->keyBy('id')->has($channel->id);
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'channel_default_answer', 'default_answer_id','channel_id');
    }

    public function revivals(): HasMany
    {
        return $this->hasMany(Revival::class);
    }

    public function getAuthorizedChannels()
    {
        return $this->channels->count() === 0 ? Channel::all() : $this->channels;
    }

    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = TableColumnBuilder::id()
            ->setSearchable(false);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.default_answer.name'))
            ->setKey('name');
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.default_answer.content'))
            ->setKey('content')
            ->setCallback(fn(DefaultAnswer $defaultAnswer) => TinyMCE::toText($defaultAnswer->content));
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.default_answer.select_channel'))
            ->setCallback(function (DefaultAnswer $defaultAnswer){
                $channels = $defaultAnswer->getAuthorizedChannels();
                if (count($channels) === Channel::all()->count()) {
                    return __('app.all');
                } else {
                    return $channels->pluck('name')->implode(', ');
                }
            })
            ->setKey('channels')
            ->setSortable(false)
            ->setSearchable(false);
        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.default_answer.is_locked'))
            ->setkey('is_locked')
            ->settype(ColumnTypeEnum::BOOLEAN);
        $columns[] = TableColumnBuilder::actions()
            ->setCallback(function (DefaultAnswer $defaultAnswer) {
                return view('configuration.defaultAnswer.inline_table_actions')
                    ->with('defaultAnswer', $defaultAnswer);
            });

        return $columns;
    }

    public function softDeleted(){
        return $this->delete();
    }

}

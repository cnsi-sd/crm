<?php

namespace App\Models\Ticket;

use App\Enums\AlignEnum;
use App\Enums\ColumnTypeEnum;
use App\Enums\FixedWidthEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketPriorityEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\Builder\Table\TableColumnBuilder;
use App\Models\User\User;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $type
 * @property string $modification
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property User $user
 * @property Ticket $ticket
 */
class History extends Model
{

    protected $fillable = [
        'user_id',
        'type',
        'modification',
        'ticket_id',
        'created_at',
        'updated_at'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getTableColumns(): array
    {
        $columns = [];

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.historical.date'))
            ->setType(ColumnTypeEnum::DATE)
            ->setSearchable(false)
            ->setKey('date')
            ->setSortable(false)
            ->setFixedWidth(FixedWidthEnum::MD)
            ->setCallback(function (History $historical) {
                return $historical->created_at->format('d/m/y H:i');
            });

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.historical.user'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setSearchable(false)
            ->setSortable(false)
            ->setFixedWidth(FixedWidthEnum::MD)
            ->setCallback(function (History $historical) {
                if (isset($historical->user))
                    return $historical->user->__toString();
                else
                    return TicketMessageAuthorTypeEnum::SYSTEM ;
            });

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.historical.type'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setSearchable(false)
            ->setSortable(false)
            ->setFixedWidth(FixedWidthEnum::MD)
            ->setCallback(function (History $historical) {
                return __('app.ticket.' . $historical->type);
            });

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.historical.update'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setSearchable(false)
            ->setSortable(false)
            ->setFixedWidth(FixedWidthEnum::LG)
            ->setCallback(function (History $historical) {
                return TicketStateEnum::getMessage($historical->modification);
            });


        return $columns;
    }
}

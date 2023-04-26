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
 * @property Datetime $date
 * @property string $type
 * @property string $modification
 * @property Datetime $created_at
 * @property Datetime $updated_at
 *
 * @property User $user
 * @property Ticket $ticket
 */
class Historical extends Model
{protected $table = 'historical';

    protected $fillable = [
        'date',
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
/*
        $columns[] = TableColumnBuilder::id()
            ->setSearchable(true)
            ->setSortable(true)
            ->setWhereKey('historical.id');*/

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.historical.date'))
            ->setType(ColumnTypeEnum::DATE)
            ->setSearchable(false)
            ->setKey('date')
            ->setSortable(false)
            ->setFixedWidth(FixedWidthEnum::MD)
            ->setCallback(function (Historical $historical) {
                return date('d/m/Y h:i:s', strtotime($historical->date));
            });

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.historical.user'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setSearchable(false)
            ->setSortable(false)
            ->setFixedWidth(FixedWidthEnum::MD)
            ->setCallback(function (Historical $historical) {
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
            ->setCallback(function (Historical $historical) {
                return __('app.historical_type.' . $historical->type);
            });

        $columns[] = (new TableColumnBuilder())
            ->setLabel(__('app.historical.update'))
            ->setType(ColumnTypeEnum::TEXT)
            ->setSearchable(false)
            ->setSortable(false)
            ->setFixedWidth(FixedWidthEnum::LG)
            ->setCallback(function (Historical $historical) {
                return TicketStateEnum::getMessage($historical->modification);
            });


        return $columns;
    }
}

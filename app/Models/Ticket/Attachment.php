<?php

namespace App\Models\Ticket;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $message_id
 * @property string $filename
 * @property Datetime $created_at
 * @property Datetime $updated_at
 */

class Attachment extends Model
{
    protected $table = 'ticket_thread_message_attachments';

    protected $fillable = [
        'message_id',
        'filename',
        'created_at',
        'updated_at'
    ];

}

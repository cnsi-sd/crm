<?php

namespace App\Models\User;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $role_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property boolean $active
 * @property Datetime $created_at
 * @property Datetime $updated_at
 */

class User extends Model
{

}

<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;
       /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'user_cards';
}

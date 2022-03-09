<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
class UserGroup extends Model
{
       /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'tbl_user_group';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];


}
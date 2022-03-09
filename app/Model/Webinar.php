<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use DB;
use \Carbon\Carbon;

class Webinar extends Model
{
       /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_webinar';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    public static function getRecent(){
        
        $webinar=Self::where("is_deleted",0)->orderBy("date","ASC")->first();
        return $webinar;
    }


    public function getAllowedGroups()
    {
        $groups = json_decode($this->groups_allowed,true);
       
        if(is_array($groups)) {
            return array_keys($groups);
        }

        return array();
    }
}
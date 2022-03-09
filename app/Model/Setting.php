<?php
namespace App\Model;
use App\Model\Types;
use App\Model\HabitTypes;
use App\Model\Logs;
use App\Model\Goals;
use Illuminate\Database\Eloquent\Model;
use Config;
use Carbon\Carbon;
use DB;

class Setting extends Model
{
	
	protected $table = 'setting';
	
	protected $guarded = [
	        'id',
	    ];

	public static function addSetting($user_id,$data){
    	
    	$meta=Self::updateOrCreate(['user_id'=>$user_id],$data);
    	
    	return $meta;
	}



	public static function getSetting($user_id){
    	
    	$meta=Self::where('user_id',$user_id)->where('is_deleted',0)->first();
    	
    	return $meta;
	}



	
}
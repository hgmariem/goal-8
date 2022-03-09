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

class Device extends Model
{
	
	protected $table = 'devices';
	
	protected $guarded = [
	        'id',
	    ];

	public static function addDeviceId($data){
    	

    	$meta=Self::updateOrCreate(['user_id'=>$data['user_id'],'device_id'=>$data['device_id'],'device_type'=>$data['device_type']],$data);
    	
    	return $meta;
	}



	public static function logoutDeviceId($data){
    	
    	
    	$meta=Self::updateOrCreate(['user_id'=>$data['user_id'],'device_id'=>$data['device_id']],$data);
    	
    	return $meta;
	}

	
}
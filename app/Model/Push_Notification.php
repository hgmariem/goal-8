<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Config;
use Carbon\Carbon;

class Push_Notification extends Model
{
	
	protected $table = 'push_notifications';

	protected $guarded = [
        'id',
    ];

    public static function addPushNotification($user_id,$item_id,$device_type,$device_id,$type,$data){
    	$result = Self::updateOrCreate(['user_id'=>$user_id,'item_id'=>$item_id,'module_type'=>$type,'device_id'=>$device_id,'device_type'=>$device_type],$data);
    	return $result;
    }


    public static function getNotifyData($user_id,$item_id,$device_type,$device_id,$type){
    	$result = Self::where(['user_id'=>$user_id,'item_id'=>$item_id,'module_type'=>$type,'device_id'=>$device_id,'device_type'=>$device_type])->first();
    	return $result;
    } 

}

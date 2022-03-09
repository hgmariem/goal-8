<?php
namespace App\Model;
use DB;
use Illuminate\Database\Eloquent\Model;

class User_Subscription extends Model
{
	
	protected $table = 'user_subscription';
	protected $guarded = [
			'id',
    ];

    public static function addSubscriptionPlan($data){
       return Self::create($data);
    }

    public static function updateSubscriptionPlanData($order_id,$data){
        $newUser = Self::updateOrCreate([
            'transaction_id'   => $order_id,
        ],$data);
    }

}
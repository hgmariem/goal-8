<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Trophy;
use App\Model\Setting;
use App\Model\User;
use App\Model\Goals;
use App\Model\Workshop;
use App\Model\Push_Notification;
//use Auth;
use Config;
use App\Helper\UserIdentity;
use Validator;
use Carbon\Carbon;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use DB;
use Edujugon\PushNotification\PushNotification;

class CroneController extends Controller{
	
	public function __construct()
    {
         
        // $this->middleware('auth');
        // parent::__construct();
    }


    public function setPushNotification(){
      
       $userData = User::getAllUsers();

       foreach($userData as $u){
          $setting_details = Setting::getSetting($u->guid);

          if(isset($setting_details) && !empty($setting_details)){
            $notify_data = json_decode($setting_details->notify_data,true);

            if(isset($notify_data) && !empty($notify_data)){
       
              if(isset($notify_data['daily_reminder']) && $notify_data['daily_reminder'] == "on"){

               (isset($notify_data['timezone'])&& !empty($notify_data['timezone']))?date_default_timezone_set($notify_data["timezone"]):date_default_timezone_set('Asia/Kolkata');
               $dayOfTheWeek = Carbon::now()->dayOfWeek;
               $time = date('G:i');
               
                if(isset($notify_data['days']) && !empty($notify_data['days']) && in_array($dayOfTheWeek,$notify_data['days'])){
                  
                  $notify_time = isset($notify_data['time'])?date('G:i',strtotime($notify_data['time'])):"";
                  if(isset($notify_time) && ($time == $notify_time)){
                      
                      $data['msg'] = isset($notify_data['text'])?$notify_data['text']:"Please Check Habits and Tasks.";
                      $data['firebase_token'] = isset($u->firebase_token)?$u->firebase_token:"";
                      $data['device_id'] = isset($u->device_id)?$u->device_id:"";
                      $this->sendPushNotification($data);
                  }
                }
              }
            }
            
          }
       }die;

    }


    public function checkCrone(){
      $data = array('name'=>'welcome');
      $result = DB::table("test_table")->insert($data);
      return $result;
    }


    public function SetWorkshopnotification()
    {
        $current_date = date("Y-m-d");
        $userData = User::getAllUsers();
        $workshop = Workshop::getAfterCurrentDateWorkshop();

        foreach($workshop as $w){
          $workshop_date = date("Y-m-d",strtotime($w->date.' -1 day'));
          if($workshop_date == $current_date){
            foreach($userData as $u){
              $setting_details = Setting::getSetting($u->guid);
              if(isset($setting_details) && !empty($setting_details)){
                $notify_data = json_decode($setting_details->notify_data,true);
              if(isset($notify_data) && !empty($notify_data)){
              if(isset($notify_data['workshop_notify']) && $notify_data['workshop_notify'] == "on"){
                  $pushData['user'] = $u;
                  $pushData['workshop'] = $w;
                  $postData = json_encode($pushData);

                  $device_id = $u->device_id;
                  $device_type = $u->device_type;
                  $type = "workshop";
                  $addData['user_id'] = $u->guid;
                  $addData['item_id'] = $w->id;
                  $addData['device_id'] = $u->device_id;
                  $addData['device_type'] = $u->device_type;
                  $addData['module_type'] = $type;
                  $addData['notify_text'] = $postData;
                  $addData['status'] = 1;
                  $data['msg'] = "Workshop is Conducted on ".date("Y-m-d",strtotime($w->date));
                  $data['firebase_token'] = isset($u->firebase_token)?$u->firebase_token:"";
                  $data['device_id'] = isset($u->device_id)?$u->device_id:"";
                  $notifyData = Push_Notification::getNotifyData($u->guid,$w->id,$device_type,$device_id,$type);

                  if(empty($notifyData)){
                    
                    $this->sendPushNotification($data);
                    Push_Notification::addPushNotification($u->guid,$w->id,$type,$device_type,$device_id,$addData);
                  }

                   }
                 }
              }

            }
          }
        }die;


    }


public function sendPushNotification()
{
  
  $push = new PushNotification('fcm');
  $response = $push->setMessage(['message'=>'Hello World'])
      ->setApiKey('asdfasdffasdfasdfasdf')
      ->setConfig(['dry_run' => false])
      ->sendByTopic('test')
      ->getFeedback();
      
      echo "<pre/>";
      print_r($response);die;

}



}    
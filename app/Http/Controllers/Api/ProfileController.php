<?php
namespace App\Http\Controllers\Api;

use App\Helper\UserIdentity;
use App\Http\Controllers\Controller;
use App\Model\Setting;
use App\Model\User;
use App\Traits\ApiResponser;
//use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use App\Repositories\IUserRepository;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{

    use ApiResponser;
   
	public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }
    // Get Profile informations
    public function index(Request $request)
    {
        $post = $request->all();
        $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;

        if (!is_null($userDetails)) {
            return $this->success($userDetails, 'User Fetched Successfully');

        } else {
            return $this->error('User Not Found!', 404);
        }

    }

    public function changeCredPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'old_pass' => 'required',
            'new_pass' => 'required',
            'confirm_pass' => 'required',
        ]);
        $post = $request->all();
        if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }
        $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        $hashedPassword = $userDetails->password;
        if (\Hash::check($request->old_pass , $hashedPassword )) {

          
            $userDetails->password = bcrypt($request->new_pass);
            $userDetails->save();
            return $this->success($userDetails,'Credential Changed Successfully');
              }
              else{
                return $this->error('Credential Not Changed!',403);
                }
        /*
        $helper = new UserIdentity();
        if ($post = $request->all()) {

            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;
            //return $this->success( $userDetails );
            $user_data['sess_id'] = $userDetails->sessionid;
            $user_data['user_name'] = $post['user_name'];
            $user_data['old_pass'] = $post['old_pass'];
            $user_data['new_pass'] = $post['new_pass'];
            $user_data['confirm_pass'] = $post['confirm_pass'];

            if (isset($user_data) && !empty($user_data)) {

                $response = $helper->credChangePass($user_data);
                //print_r($response);die;
                 return $this->success($response);
                if ($response == '{}') {

                    return $this->success($response,'Credential Changed Successfully'); 

                } else {
                    return $this->error('Credential Not Changed!',403);
                }
            } else {

                return $this->error('User Data Not Coming!',403);
            }
        } else {

            return $this->error('Somthing Went Wrong!',403);
        }*/

    }

    public function changeImage(Request $request)
    {

        $helper = new UserIdentity();
        if ($post = $request->all()) {
            $post = $request->all();

            //echo "<pre/>";
            //  print_r($post);die;
            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;
            $user_data['sess_id'] = $userDetails->sessionid;
            $user_data['operation'] = "saveavatar";
            $user_data['x'] = $post['x'];
            $user_data['y'] = $post['y'];
            $user_data['w'] = $post['width'];
            $user_data['h'] = $post['height'];
            $user_data['uploadedFile'] = $_FILES['uploadedFile'];

            if (isset($user_data) && !empty($user_data)) {

                $response = $helper->changeImage($user_data);
                //print_r($response);die;
                if ($response == '{}') {

                    return $this->success($response,'Credential Changed Successfully');

                } else {
                    return $this->error('Credential Not Changed!',403);                }
            } else {

                return $this->error('User Data Not Coming!',403);
            }
        } else {

            return $this->error('Somthing Went Wrong!',403);
        }

    }

    public function updateProfile(Request $request)
    {
      
      //$this->validator($request->all())->validate();
      $post = $request->all();
      $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
      if(!$userDetails) {
        return $this->error('User not found', 404); 
      }
      $userDetails->fullname=$request->fullname;
      $userDetails->name=$request->username;
      $userDetails->email=$request->email;
      $userDetails->gender=$request->gender;
      $userDetails->telephone=$request->telephone;
      $userDetails->country=$request->country;
      $userDetails->city=$request->city;
      $userDetails->street=$request->street;
      $userDetails->post_code=$request->post_code;
      $userDetails->save();
      return $this->success($userDetails,'Profile updating with success'); 

    }
    public function unsubscribe_user(Request $request)
    {
        $post = $request->all();
        $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        if(!$userDetails) {
            return $this->error('User not found', 404); 
        }
        $userDetails->activated=0;
        $userDetails->unsubscription_date=Carbon::now();
        $userDetails->save();
        return $this->success($userDetails,'Profile has been updated successfully. ');
        
        
       
    }

    public function programAvailabity(Request $request)
    {

        $helper = new UserIdentity();
        if ($post = $request->all()) {

            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;

            $user_data['sess_id'] = $userDetails->sessionid;
            $user_data['_returnSmallPagesInsteadOfEmptyOnes'] = $post['_returnSmallPagesInsteadOfEmptyOnes'];
            $user_data['_withReceivedDetails'] = $post['_withReceivedDetails'];
            $user_data['_withCollectionsDetails'] = $post['_withCollectionsDetails'];
            $user_data['_onlyItemsThatNeedAttention'] = $post['_onlyItemsThatNeedAttention'];
            $user_data['_timeZoneOffset'] = $post['_timeZoneOffset'];

            if (isset($user_data) && !empty($user_data)) {

                $response = $helper->programAvailabity($user_data);

                if ($response) {

                    return $this->success($response,'get programAvailabity');
                    

                } else {
                    return $this->error('Not Found!',403);
                }
            } else {

                return $this->error('User Data Not Coming!',403);
            }
        } else {

            return $this->error('Somthing Went Wrong!',403);
        }

    }

    public function changePreferenceOnMail(Request $request)
    {

        $helper = new UserIdentity();
        if ($post = $request->all()) {

            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;
            $user_data['sess_id'] = $userDetails->sessionid;
            $user_data['key'] = $post['key'];
            $user_data['value'] = $post['value'];

            if (isset($user_data) && !empty($user_data)) {

                $response = $helper->changePreferenceRequest($user_data);

                /*echo "<pre/>";
                print_r($response);die;*/

                if ($response == '{}') {

                    return $this->success($response,'Preference Updated Successfully');


                } else {
                    return $this->error('Profile Not Update!',403);

                    
                }
            } else {

                return $this->error('User Data Not Coming!',403);
                
            }
        } else {

            return $this->error('Somthing Went Wrong!',403);

        }

    }

    public function getUserDetails(Request $request)
    {
        $post = $request->all();
        $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        $id=$userDetails->id;
        $model = new User();
        $user_data=$model->get_user($id);
		if(!$user_data)
		{
			return $this->error('Not Found',404);
			//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Not Found"]);
		}

		return $this->success($user_data,'Get user details');

    }

    public function getLoginuserMailBoxDetails(Request $request)
    {

        $helper = new UserIdentity();
        if ($post = $request->all()) {

            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;
            $user_data['sess_id'] = $userDetails->sessionid;

            if (isset($user_data) && !empty($user_data)) {

                $response = $helper->getLoginMailboxDetails($user_data);

                if ($response) {

                    return $this->success($response,'Get login User Mailbox Details Successfully');

                } else {
                    return $this->error('Not Get!',403);
                }
            } else {

                return $this->error('User Data Not Coming!',403);
            }
        } else {

            return $this->error('Somthing Went Wrong!',403);
        }

    }

    public function getFiveChat(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'filteredGroupOrFriend' => 'required',
            'maxCount' => 'required',
            'marker' => 'required',
        ]);

        if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }
        $helper = new UserIdentity();
        if ($post = $request->all()) {

            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;
            $user_data['sess_id'] = $userDetails->sessionid;
            $user_data['filteredGroupOrFriend'] = $post['filteredGroupOrFriend'];
            $user_data['maxCount'] = $post['maxCount'];
            $user_data['marker'] = (isset($post['marker']) && !empty($post['marker']) && $post['marker'] == "null") ? null : $post['marker'];

            if (isset($user_data) && !empty($user_data)) {

                $response = $helper->getFiveChat($user_data);

                if ($response) {

                    return $this->success($response,'Get login User Mailbox Details Successfully');
                    

                } else {
                    return $this->error('Not Get!',403);
                }
            } else {

                return $this->error('User Data Not Coming!',403);
            }
        } else {

            return $this->error('Somthing Went Wrong!',403);
        }

    }

    public function addReply(Request $request)
    {

        $helper = new UserIdentity();
        if ($post = $request->all()) {

            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;
            $data["toGuid"] = $post['guid'];
            $data["messageBody"] = $post['msg'];
            if (isset($post['inReplyTo']) && !empty($post['inReplyTo'])) {
                $data["inReplyTo"] = $post['inReplyTo'];
            }
            $data["anonym"] = (isset($post['anonym']) && $post['anonym'] == 0) ? false : true;
            $user_data['massege'] = json_encode($data);
            $user_data['sess_id'] = $userDetails->sessionid;
            if (isset($user_data) && !empty($user_data)) {

                $response = $helper->addChat($user_data);

                $result = json_decode($response);

                if ($result->_message->guid) {

                    return $this->success($response,'Message Successfully Added');

                } else {
                    return $this->error('Not Get!',403);
                }
            } else {

                return $this->error('User Data Not Coming!',403);
            }
        } else {

            return $this->error('Somthing Went Wrong!',403);
        }

    }

    public function deleteChat(Request $request)
    {

        $helper = new UserIdentity();
        if ($post = $request->all()) {

            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;
            $user_data['sess_id'] = $userDetails->sessionid;
            $user_data["msguid"] = $post['msguid'];
            if (isset($user_data) && !empty($user_data)) {

                $response = $helper->deleteChat($user_data);

                if ($response == '{}') {

                    return $this->success($response,'Message Successfully Deleted');
                   

                } else {
                    return $this->error('Not found!',403);
                }
            } else {

                return $this->error('User Data Not Coming!',403);
            }
        } else {

            return  $this->error('Somthing Went Wrong!',403);
        }

    }
    public function getUsers ()
    {
        $model = new User();
        $users=$model->getAll(); 
        if ($users) {
            return $this->success($users, 'users Listed Successfully.');
        } else {
            return $this->error('users is empty!', 404);
        }
    }

    public function changeSetting(Request $request)
    {
        $post = $request->all();

        if (isset($post['daily_reminder']) && $post['daily_reminder'] == 'on') {
            $validator = Validator::make($request->all(), [
                'text' => 'required',
                'time' => 'required',
                'daily_reminder' => 'required',
                'massage_notify' => 'required',
                'workshop_notify' => 'required',
                'timezone' => 'required',
            ]);

            if($validator->fails()){
                return $this->error($validator->errors(), 403);
            }
        }

        if ($post = $request->all()) {
            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;
            $post['user_id'] = isset($userDetails->guid) ? $userDetails->guid : "";
            unset($post['token']);
            $notify_data = json_encode($post);
            $data = array('user_id' => $post['user_id'], 'notify_data' => $notify_data);
            $setting = new Setting();
            $result = $setting->addSetting($post['user_id'], $data);
            return $this->success($result,'Setting Successfully changed');

        }
    }

    public function getSharedInfo(Request $request)
    {

        $helper = new UserIdentity();
        if ($post = $request->all()) {

            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;
            $user_data['sess_id'] = $userDetails->sessionid;
            $user_data['_returnSmallPagesInsteadOfEmptyOnes'] = true;
            $user_data['_withReceivedDetails'] = true;
            $user_data['_withCollectionsDetails'] = true;
            $user_data['_onlyItemsThatNeedAttention'] = true;
            $user_data['_timeZoneOffset'] = 19800000;

            echo "<pre/>";
            print_r($post);die;
            if (isset($user_data) && !empty($user_data)) {

                $response = $helper->programAvailabity($user_data);

                if ($response) {

                    return  $this->success($response,'get programAvailabity');

                } else {
                    return $this->error('Not Found!',403);
                }
            } else {

                return $this->error('User Data Not Coming!',403);
            }
        } else {

            return  $this->error('Somthing Went Wrong!',403);
        }

    }

    public function getSetting(Request $request)
    {
        if ($post = $request->all()) {
            $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);;
            $user_id = isset($userDetails->guid) ? $userDetails->guid : "";

            $setting = new Setting();
            $result = $setting->getSetting($user_id);
            if (isset($result) && !empty($result)) {
                $result->notify_data = json_decode($result->notify_data, true);
                return  $this->success($result,'Setting Successfully get');
            } else {
                return $this->error('Not Found"',404);
            }
        }
    }

    public function setPushNotification()
    {
        $userData = User::getAllUsers();
        date_default_timezone_set('Asia/Kolkata');
        $dayOfTheWeek = Carbon::now()->dayOfWeek;
        $time = date('h:i');
        foreach ($userData as $u) {
            $setting_details = Setting::getSetting($u->guid);
            if (isset($setting_details) && !empty($setting_details)) {
                $notify_data = json_decode($setting_details->notify_data, true);
                if (isset($notify_data) && !empty($notify_data)) {
                    echo $u->guid;
                    if (isset($notify_data['daily_reminder']) && $notify_data['daily_reminder'] == "on") {

                        if (isset($notify_data['days']) && !empty($notify_data['days']) && in_array($dayOfTheWeek, $notify_data['days'])) {

                            if (isset($notify_data['time']) && "5:37" == $notify_data['time']) {
                                echo "Reached here.....";
                                $data['msg'] = isset($notify_data['text']) ? $notify_data['text'] : "Please Check Habits and Tasks.";
                                $data['firebase_token'] = isset($u->firebase_token) ? $u->firebase_token : "";
                                $data['device_id'] = isset($u->device_id) ? $u->device_id : "";
                                $this->sendPushNotification($data);
                            }
                        }
                    }
                }

            }
        }die;
    }

    public function sendPushNotification($post)
    {
        echo "<pre/>";
        print_r($post);
        # $optionBuilder = new OptionsBuilder();
        # $optionBuilder->setTimeToLive(60*20);
        # $notificationBuilder = new PayloadNotificationBuilder('Remider');
        # $notificationBuilder->setBody($post['msg'])
        #             ->setSound('default');

        # $dataBuilder = new PayloadDataBuilder();
        # $dataBuilder->addData(['a_data' => 'my_data']);

        # $option = $optionBuilder->build();
        # $notification = $notificationBuilder->build();
        # $data = $dataBuilder->build();

        #     $token = $post['firebase_token'];

        #     $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
        #   $downstreamResponse->numberSuccess();
        #   $downstreamResponse->numberFailure();
        #   $downstreamResponse->numberModification();

        #   // return Array - you must remove all this tokens in your database
        #   $downstreamResponse->tokensToDelete();

        #   // return Array (key : oldToken, value : new token - you must change the token in your database)
        #   $downstreamResponse->tokensToModify();

        #   // return Array - you should try to resend the message to the tokens in the array
        #   $downstreamResponse->tokensToRetry();

        #   // return Array (key:token, value:error) - in production you should remove from your database the tokens
        #   $downstreamResponse->tokensWithError();

        #     //var_dump($downstreamResponse);die;

    }

}

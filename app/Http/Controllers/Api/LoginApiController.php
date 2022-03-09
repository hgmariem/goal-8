<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Helper\UserIdentity;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;
use App\Model\User;
use App\Model\Device;
use App\Model\Setting;
use Log;
use Edujugon\PushNotification\PushNotification;


class LoginApiController extends Controller
{



	public function postLogin(Request $request)
    {
    	$post = $request->all();

    	$device_type = $post['device_type'];
    	$device_id  = $post['device_id'];
    	$timezone = $post['timezone'];

    	unset($post['device_id']);
    	unset($post['device_type']);
    	unset($post['timezone']);

    	$helper = new UserIdentity();
		
		if($post = $request->all()){
		$response = $helper->postLogin($post);
			
			$result = json_decode($response,true);
			if(!empty($result) && strtolower($result['_resultCode'])=='success'){
			
			$result['groups']=$helper->getUserGroups($result['_userGuid'] );
			
			$result['password']=$post['password'];
			

			$token = $this->makeToken();

			//$response->token=$token;
			
			$user=Self::create_new_user($result);


			$data = array('user_id'=>$user->guid,'device_id'=>$device_id,'firebase_token'=>$post['firebase_token'],'device_type'=>$device_type,'user_token'=>$token);
			
			$userDetails = Device::addDeviceId($data);
			$user_id = $user->guid;
			
			$setting = Setting::getSetting($user->guid);
			if(!isset($setting) && empty($setting)){
				$settingData['user_id'] = $user_id;
				$notify_data = array('daily_reminder'=>'on','text'=>'Please Check Habits and Tasks.','time'=>'20:30','timezone'=>$timezone,'user_id'=>$user->guid,'days'=>'["0","1","2","3","4","5","6"]');
				$settingData['notify_data'] = json_encode($notify_data);
				Setting::addSetting($user_id,$settingData);
			}

			
			/*$data = array('user_id'=>$user->guid,
						  'device_id'=>$post['device_id'],
						  'device_type'=>$post['device_type'],
						  'firebase_token'=>$post['firebase_token']
						);
			update_device_id($data);*/

			$remember_me=isset($post['remember_token'])?true:false;

			$userData = Auth::login($user,$remember_me);

			$userData = Auth::user();
			
			$userData['user_token'] = $userDetails->user_token;
			
			if($remember_me){
				setcookie('credentials', json_encode($post));
			}else{
				setcookie('credentials', null);
			}
			
			setcookie('sessionId', $result['_sessionId']);
			setcookie('userGuid', $result['_userGuid']);
			$group = isset($userData->groups)?json_decode($userData->groups):"";

			if(!empty($group)){
				$group_id = $group[0];
				$userGroupData = DB::table('tbl_user_group')->where('_guid',$group_id)->first();
				$userData['_filteredGroupOrFriend'] = $userGroupData->_ownerGuid;
			}

			return response()->json(['data'=>$userData,'status'=>1,'msg'=>"Login successfully",'error'=>""]);
			}else if(!empty($result) && $result['_resultCode']=='UserNameNotFound'){
				return response()->json(['data'=>array(),'status'=>0,'msg'=>"User Name Not Found",'error'=>"User Name Not Found"]);
			}else if(!empty($result) && $result['_resultCode']=='PasswordIncorrect'){
				return response()->json(['data'=>array(),'status'=>0,'msg'=>"Password Incorrect",'error'=>"Password Incorrect"]);
			}else if(!empty($result) && $result['_resultCode']=='InsufficientPrivileges'){
				return response()->json(['data'=>array(),'status'=>0,'msg'=>"Insufficient Privileges",'error'=>"Insufficient Privileges"]);
			}else{
				return response()->json(['data'=>array(),'status'=>0,'msg'=>"Error Unknown Identity",'error'=>"Error Unknown Identity"]);
			}
			}else{
				return response()->json(['data'=>array(),'status'=>0,'msg'=>"User Name OR Password is Empty.",'error'=>"User Name OR Password is Empty."]);
			}

			//$credentials=isset($_COOKIE['credentials'])?json_decode($_COOKIE['credentials']):array();
			//return view('auth.login',['credentials'=>$credentials]);
		}


	
	public function login(Request $request)
	{
		
		$helper = new UserIdentity();
		
		if($post = $request->all()){
			
			if(isset($post['username']) && !empty($post['username']) && isset($post['password']) && !empty($post['password'])){
				
				$response = $helper->authenticate($post['username'],$post['password']);
				//echo "<pre/>";
				//print_r($response);die;
				if(!is_null($response) && strtolower($response->resultCode)=='success'){
					
					$response->groups=$helper->getUserGroups($response->userGuid);
					
					$response->password=$post['password'];
					
					$token = $this->makeToken();

					//$response->token=$token;
					
					$user=Self::create_user($response);

					$data = array('user_id'=>$user->guid,'device_id'=>$post['device_id'],'device_type'=>$post['device_type'],'user_token'=>$token);

					Device::addDeviceId($data);

					
					$remember_me=isset($post['remember_token'])?true:false;
					
					Auth::login($user,$remember_me);
					
					/*if($remember_me){
						setcookie('credentials', json_encode($post));
					}else{
						setcookie('credentials', null);
					}*/
					return response()->json(['data'=>Auth::user(),'status'=>1,'msg'=>"Login successfully",'error'=>""]);					
					//return redirect('/');
				}else if(!is_null($response) && $response->resultCode=='UserNameNotFound'){
					return response()->json(['data'=>array(),'status'=>0,'msg'=>"Login successfully",'error'=>"User Name Not Found"]);
				}else if(!is_null($response) && $response->resultCode=='PasswordIncorrect'){
					return response()->json(['data'=>array(),'status'=>0,'msg'=>"Password Incorrect",'error'=>""]);
				}else if(!is_null($response) && $response->resultCode=='InsufficientPrivileges'){
					return response()->json(['data'=>array(),'status'=>0,'msg'=>"Login successfully",'error'=>"Insufficient Privileges"]);
				}else{
					return response()->json(['data'=>array(),'status'=>0,'msg'=>"Login successfully",'error'=>"Error Unknown Identity"]);
				}
			}else{
				return response()->json(['data'=>array(),'status'=>0,'msg'=>"Login successfully",'error'=>"User Name OR Password is Empty."]);
			}
		}
		
		//$credentials=isset($_COOKIE['credentials'])?json_decode($_COOKIE['credentials']):array();
	}


	public function makeToken()
	{
		$token = sha1(mt_rand(1, 90000) . 'SALT');
		
		if(checkUniqueToken($token))
		{
		   $this->makeToken();
		}

		
		return $token;
	}

	public function create_new_user($response){
			
		if(!$user=User::where('username',$response['_userName']) -> first()){
			$user = new User();
		}
		
		$user->sessionid=isset($response['_sessionId'])?$response['_sessionId']:"";
		$user->username=isset($response['_userName'])?$response['_userName']:"";
		$user->email= isset($response['email'])?$response['email']:"";
		$user->password= isset($response['password'])?$response['password']:"";
		$user->guid= isset($response['_userGuid'])?$response['_userGuid']:"";
		$user->fullname= isset($response['_fullName'])?$response['_fullName']:"";
		$user->gender= isset($response['_gender'])?$response['_gender']:"";
		$user->lastloginms=isset($response['lastLoginMs'])?$response['lastLoginMs']:"";
		$user->logincount=isset($response['loginCount'])?$response['loginCount']:"";
		$user->telephone=isset($response['telephone'])?$response['telephone']:"";
		$user->user_token=isset($response['token'])?$response['token']:"";
		$user->groups=json_encode($response['groups']);
		$user->meta_data=json_encode($response);
		$user->save();
		
		return $user;
	}
	
	
	public function create_user($response){
		
		if(!$user=User::where('username',$response->userName) -> first()){
			$user = new User();
		}
		
		$user->sessionid=$response->sessionId;
		$user->username=$response->userName;
		$user->email=$response->email;
		$user->password=$response->password;
		$user->user_token=$response->token;
		$user->guid=$response->userGuid;
		$user->fullname=$response->fullName;
		$user->gender=$response->gender;
		$user->lastloginms=$response->lastLoginMs;
		$user->logincount=$response->loginCount;
		$user->telephone=$response->telephone;
		$user->groups=json_encode($response->groups);
		$user->meta_data=json_encode($response);
		$user->save();
		
		return $user;
	}	



	public function logout(Request $request){
		
		$post = $request->all();
		$userDetails = getUserDetailByToken($post['token']);
    	$user_id = $userDetails->guid;
		$user = User::find($userDetails->id);

		$device = Device::where('user_id',$user_id)->where('user_token',$post['token'])->first();
		
		
		$device->user_token = "";
		$device->save();

		/*$data = array("user_id"=>$user_id,
					  "device_id"=>$post['device_id'],
					  "firebase_token"=>""
					 );

		Device::logoutDeviceId($data);*/

        Log::info('User Logged Out. ', [$user]);
		
		setcookie('sessionId', null);
		setcookie('userGuid', null);
				
        Auth::logout();

        Session::flush();
		
        return response()->json(['data'=>array(),'status'=>1,'msg'=>"successfully Log Out",'error'=>""]);
  }


  function random_strings($length_of_string) 
{ 
  
    // String of all alphanumeric character 
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
  
    // Shufle the $str_result and returns substring 
    // of specified length 
    return substr(str_shuffle($str_result),  
                       0, $length_of_string); 
} 


public function sendPushNotification()
{

	$push     = new PushNotification('fcm');
	$response = $push->setMessage([
	   'notification' => [
			   'title'=>'Test For Keyhabits',
			   'body'=>'Hello Keyhabits',
			   'sound' => 'default'
			   ],
	   'data' => [
			   'request_id' => '123456',
			   'extraPayLoad1' => 'value1'
			   ]
	   ])
	   ->setApiKey('AAAAp94g98M:APA91bHBYv7DxsGG-0HCDEDFkuSrh9jD3cArcy2nqinbtQS7EdseHoUWGKh4ui9ZTU_LyMFEFGMR5q8IWeCIi9Htzq0EJT2Fh8WrBNjN-Um9II2725B2IKJYiCQyY_S95aREJoJHkpVY')
	   ->setConfig(['dry_run' => false])
	   ->sendByTopic('KEYHABITS')
	   ->setDevicesToken('eTIXvR_R9_4:APA91bEbaNVGlEI4bd1NVmHer6SaTkosWUEEzCKSBqmBi-v4qD6iwTzan86ZFC6vKpBWf3UtJQfJfJcODqn4mcjE4zmgsF_FUZ04xP8KQ5JDKSshBBE8oUON8wi5O8VatefDxUhFQo4S')->send()->getFeedback();
	   
	return response()->json(['error' =>'','data'=>$response]); 

}


}



?>
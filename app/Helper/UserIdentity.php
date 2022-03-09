<?php
namespace App\Helper;
 use Request;
use config\restApi;
use Config;
use Session;
//use Auth;
use Cookie;
/**
 * Class UserIdentity
 * represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity {

    private $_guid;
    private $_fullname;
    private $_gender;
    private $_yearOfBirth;
    private $_lastLoginMs;
    private $_loginCount;
    private $_resultCode;
    private $_groups;
    private $_email;
    private $_telephone;

    const ERROR_INSUFFICIENT_PRIVILEGES = 3;


    public function authenticate($username,$password,$params=null) { //print_r($attribute); exit();


        // Set array of parameters for login
        $arrParams = array(
            'username' => $username,
            'password' => $password,
        );

        // Call login api
        $jsonResult = $this->callApi($arrParams);

        // Convert json to array
        $arrResult = json_decode($jsonResult);

		  return ($arrResult);



       /* if (!empty($arrResult)){

			$config=Config::get("config.errorCode");
			print_r($arrResult); exit();
            $this->errorCode = $config['error_unknown'];
			if(!isset($arrResult->resultCode)){
				return $this->errorCode;
			     }
			//var_dump($arrResult);
       /*      //exit();
        switch ($arrResult->resultCode) {
            case 'Success':

                $userGroups = $this->getUserGroups($arrResult->userGuid);
                $this->errorCode = $config['error_none'];
                $this->_resultCode = $arrResult->resultCode;
                $this->_guid = $arrResult->userGuid;
                $this->_fullname = $arrResult->fullName;
                $this->_gender = $arrResult->gender;
                $this->_lastLoginMs = $arrResult->lastLoginMs;
                $this->_loginCount = $arrResult->loginCount;
                $this->username = $arrResult->userName;
                $this->_groups = $userGroups;
                $this->_email = $arrResult->email;
                $this->_telephone = $arrResult->telephone;

				$vars = [
                     "email" => $this->_email,
                     "telephone" => $this->_telephone,
                     "userGuid" => $this->_guid,
                     "fullName" => $this->_fullname,
                     "gender" => $this->_gender,
					 "lastLoginMs"=>$this->_lastLoginMs,
					 "loginCount"=>$this->_loginCount,
					 "userName"=>$this->username,
					 "resultCode"=>$this->_resultCode
                      ];



                   session()->put('user_data', $vars);
				   $userData=Session::get('user_data');
				  return ($userData);
				      //print_r('user_data'); exit();

				  //$cookie= Cookie::make('user_data',$vars);
				  //print_r($cookie); exit();

				   //print_r($userData); exit();

                /*$this->setState('groups', $this->_groups);
                $this->setState('email', $this->_email);
                $this->setState('phone', $this->_telephone);

			   */

                //Yii::app()->request->cookies['sessionId'] = new CHttpCookie('sessionId', $arrResult['sessionId']);
                //Yii::app()->request->cookies['userGuid'] = new CHttpCookie('userGuid', $arrResult['userGuid']);
             /*   break;

            case 'UserNameNotFound':
                $this->errorCode = $config['error_username'];
                break;

            case 'PasswordIncorrect':
                $this->errorCode = $config['error_password'];
                break;

            case 'InsufficientPrivileges':
                $this->errorCode = $config['error_privileges'];
                break;

            default:
                $this->errorCode = $config['error_username'];
                break;
        }

        return false;
		*/

    }



    /**
     * @return string the result code of the user record after call login api
     */
    public function getResultCode() {
        return $this->_resultCode;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId() {
        return $this->_guid;
    }

    /**
     * @return string the fullname of the user record
     */
    public function getName() {
        return $this->_fullname;
    }

    /**
     * @return string the gender of the user record
     */
    public function getGender() {
        return $this->_gender;
    }

    /**
     * @return integer the year of birth of the user record
     */
    public function getYearOfBirth() {
        return $this->_yearOfBirth;
    }

    /**
     * @return integer last login timestamp
     */
    public function getLastLogin() {
        return $this->_lastLoginMs;
    }

    /**
     * @return integer login times count
     */
    public function getLoginCount() {
        return $this->_loginCount;
    }

    /**
     * @return mixed email
     */
    public function getEmail() {
        return $this->_email;
    }

    /**
     * @return mixed user phone
     */
    public function getTelephone() {
        return $this->_telephone;
    }

    /*
      |-------------------|
      | Private functions |
      |-------------------|
     */

    /**
     * Call api login
     *
     * @param  array  $arrParams Contains username & hashed password
     * @return json
     */
    private function callApi($arrParams) {
       //print_r($arrParams); exit();
		$config=config("restApi.loginApi");
		//print_r($arrParams); exit();
        // Build url with parameters
        $url = $config['url'] . http_build_query($arrParams);
        //echo "";print_r($url);exit;
        $ch = curl_init();
         //print_r( $ch); exit();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        // Grab URL and pass it to the browser
        $result = curl_exec($ch);
         //print_r($result); exit();
        curl_close($ch);

        return $result;
    }


    public function getUser($id) {
		//var_dump($id);exit();

        $config=config("restApi.userApi");
		//print_r($config); //exit();
        $data["_userGuid"] = $id;
        $data_string =$data;
		//print_r($data_string);exit();
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_string));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);
        //print_r($result); exit();
        curl_close($ch);

        return ($result);
    }


public function getUserDetails($user_data) {
    //var_dump($user_data);exit();

        $config=config("restApi.userDetailsApi");
    //print_r($config); //exit();
        $data["_sessionId"] = $user_data['sess_id'];

       //print_r(json_encode($data));die();
    //print_r($data_string);exit();
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }

 public function credChangePass($user_data) {
    //var_dump($user_data);exit();

        $config=config("restApi.credChangeApi");
    //print_r($config); //exit();
        $data["_sessionId"] = $user_data['sess_id'];
        $data["_username"] = $user_data['user_name'];
        $data["_oldPwd"] = $user_data['old_pass'];
        $data["_newPwd1"] = $user_data['new_pass'];
        $data["_newPwd2"] = $user_data['confirm_pass'];

    //print_r($data_string);exit();
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }


 public function changeImage($user_data) {
    //var_dump($user_data);exit();
        $config=config("restApi.changeImageApi");
        /*echo "<pre/>";
        print_r($user_data);die;*/ //exit();
        $data['uploadedFile'] = $user_data['uploadedFile'];

        $config['url'] .= "?operation=saveavatar&sessionId=".$user_data['sess_id']."&x=".$user_data['x']."&y=".$user_data['y']."&w=".$user_data['w']."&h=".$user_data['h'];
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
             'Content-Type: multipart/form-data;charset=utf-8',
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }



    public function getLoginMailboxDetails($user_data) {
    //var_dump($user_data);exit();

        $config=config("restApi.userApi");
    //print_r($config); //exit();
        $data["_sessionId"] = $user_data['sess_id'];

       //print_r(json_encode($data));die();
    //print_r($data_string);exit();
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }

 public function addChat($user_data) {
    //var_dump($user_data);exit();

        $config=config("restApi.addMessage");
           // print_r($config);die; //exit();
        $data["_sessionId"] = $user_data['sess_id'];
        $data["_message"] = json_decode($user_data['massege']);

       // echo "<pre/>";
       // print_r($data);die;
        //print_r(json_encode($data));die;
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }


    public function deleteChat($user_data) {
    //var_dump($user_data);exit();

        $config=config("restApi.deleteMessage");
    //print_r($config); //exit();
        $data["_sessionId"] = $user_data['sess_id'];
        $data["_messageGuid"] = $user_data['msguid'];

        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }



    public function getFiveChat($user_data) {
         //var_dump($user_data);exit();

        //print_r($config); exit();
        $config=config("restApi.getAllChatApi");
        //echo "<pre/>";
        //print_r($config);die;
        $data["_sessionId"] = $user_data['sess_id'];
        $data["_filteredGroupOrFriend"] = $user_data['filteredGroupOrFriend'];
        $data["_maxCount"] = $user_data['maxCount'];
        $data["_marker"] = $user_data['marker'];
       //print_r(json_encode($data));die();
    //print_r($data_string);exit();
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);

        
    }



    /**
     * Get user groups
     *
     * @param $id
     * @return array
     */
    public function getUserGroups($id) {

		$user = $this->getUser($id);

		$user = json_decode($user);

        $groups = array();
		if($user && isset($user->_groupsToChatIn) && !empty($user->_groupsToChatIn)){
			foreach ($user->_groupsToChatIn as $group) {
				$groups[] = $group->_season;
			}
		}

        return $groups;

    }



        public function postLogin($user_data) {

        $config=config("restApi.postloginApi");
        $data["_userName"] = $user_data['username'];
        $data["_pwd"] = $user_data['password'];
        $data["_lifecoaching"] = true;
        $data["_accountName"] = "xps";
        // echo "<pre/>";
        // print_r($data);die;exit();
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }

    public function postRegister($user_data) {
        $config=config("restApi.postRegisterApi");
        $data["_firstname"] = $user_data['firstname'];
        $data["_lastname"] = $user_data['lastname'];
        $data["_email"] = $user_data['email'];
        $data["_phone"] = $user_data['mobile'];
        $data["_lname"] = $user_data['username'];
        $data["_pass"] = $user_data['password'];
        $data["_refcode"] = null;
        $data["_country"] = "Iceland";
        $data["_account"] = "2513173816573179904";
        $data["_role"] = "5143014745869004800";
        $data["_expiredays"] = 2;
        $data["_usertype"] = 10;
        $data["_sport"] = "Other";
        $data["_isteamsport"] = 0;
        $data["_languagecode"] = "is";
        $data["_regsource"] = "khis";
        $data["_reusabledepositcode"] = "C6WBHFD";
        // echo "<pre/>";
        // print_r(json_encode($data));die;exit();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL , $config['url']);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array( "Content-Type: application/json" )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
        // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);
        curl_close($ch);

        return ($result);
    }



	public function forgotpwd($email){

		$body=array("_email"=>$email);

		$config=config("restApi.messageApi");
		$function="JsonPasswordRequest";
		$arrParams = array(
            'json'    => $config['prefix_function'] . $function,
            'charset' => 'UTF-8',
        );

        // Build url with parameters
        $url = $config['url'] . http_build_query($arrParams);
        // Json encode body
        $body = json_encode($body);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body),
            'Content-Md5: ' . base64_encode(md5($body, true)),
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , FALSE); //
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , FALSE); //
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        // Grab URL and pass it to the browser
        $result = curl_exec($ch);
		$response=json_decode($result);
        curl_close($ch);
        return $response;
	}



    public function programAvailabity($user_data) {
    //var_dump($user_data);exit();



        $config=config("restApi.programAvailabity");
        $data["_sessionId"] = $user_data['sess_id'];
        $data['_returnSmallPagesInsteadOfEmptyOnes'] = $user_data['_returnSmallPagesInsteadOfEmptyOnes'];
        $data['_withReceivedDetails'] = $user_data['_withReceivedDetails'];
        $data['_withCollectionsDetails'] = $user_data['_withCollectionsDetails'];
        $data['_onlyItemsThatNeedAttention'] = $user_data['_onlyItemsThatNeedAttention'];
        $data['_timeZoneOffset'] = $user_data['_timeZoneOffset'];


        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }


        public function getSharedInfo($user_data) {
    //var_dump($user_data);exit();

        $config=config("restApi.receivedSharedInfoApi");
        echo "<pre/>";
        print_r($config);die; //exit();
        $data["_sessionId"] = $user_data['sess_id'];
        if(isset($user_data['_withReceivedDetails']) && !empty($user_data['_withReceivedDetails']))
        {
            $data['_withReceivedDetails'] = $user_data['_withReceivedDetails'];
        }
        if(isset($user_data['_withCollectionsDetails']) && !empty($user_data['_withCollectionsDetails']))
        {
            $data['_withCollectionsDetails'] = $user_data['_withCollectionsDetails'];
        }
        if(isset($user_data['_onlyItemsThatNeedAttention']) && !empty($user_data['_onlyItemsThatNeedAttention']))
        {
            $data['_onlyItemsThatNeedAttention'] = $user_data['_onlyItemsThatNeedAttention'];
        }
        if(isset($user_data['_timeZoneOffset']) && !empty($user_data['_timeZoneOffset']))
        {
            $data['_timeZoneOffset'] = $user_data['_timeZoneOffset'];
        }
        if(isset($user_data['_returnSmallPagesInsteadOfEmptyOnes']) && !empty($user_data['_returnSmallPagesInsteadOfEmptyOnes']))
        {
            $data['_returnSmallPagesInsteadOfEmptyOnes'] = $user_data['_returnSmallPagesInsteadOfEmptyOnes'];
        }

    //print_r($data_string);exit();
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }


    public function changePreferenceRequest($user_data) {
    //var_dump($user_data);exit();

        $config=config("restApi.changePreferenceOnMail");
    //print_r($config); //exit();
        $data["_sessionId"] = $user_data['sess_id'];
        $data["_key"] = $user_data['key'];
        $data['_value'] = $user_data['value'];
       //echo "<pre/>";
       //print_r(json_encode($data));die();
    //print_r($data_string);exit();
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }



        public function changeProfileRequest($user_data) {
    //var_dump($user_data);exit();

        $config=config("restApi.updateProfileApi");
    //print_r($config); //exit();
        $data["_sessionId"] = $user_data['sess_id'];
        $data["_firstName"] = $user_data['first_name'];
        $data["_lastName"] = $user_data['last_name'];
        $data["_country"] = $user_data['country'];
        $data["_city"] = $user_data['city'];
        $data["_gender"] = $user_data['gender'];
        $data["_yearOfBirth"] = $user_data['dob'];
        $data["_emailAddress"] = $user_data['email'];
        $data["_userName"] = $user_data['userName'];
        $data["_weight"] = $user_data['weight'];
        $data["_height"] = $user_data['height'];
        $data["_sports"] = $user_data["sports"];

       //echo "<pre/>";
      // print_r(json_encode($data));die();
    //print_r($data_string);exit();
        $ch = curl_init($config['url']);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $config['curl_timeout']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt(
                $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            //'Content-Length: ' . strlen($data_string),
                )
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');

        $result = curl_exec($ch);

        curl_close($ch);

        return ($result);
    }


}

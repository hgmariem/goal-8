<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */namespace App\Model;
use Config;
use config\restApi;
use Illuminate\Database\Eloquent\Model;
use App\Helper\UserIdentity;

class LoginForm extends Model
{
     
	public $username;
	public $password;
	public $rememberMe;
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params=null)
	{ //print_r($post); exit();
	//$config=Config::get("config.errorCode");
		if( ! empty($attribute))
		{
			
			$user_identity = new UserIdentity();
			//print_r($user_identity); exit();
			$login = $user_identity->authenticate($attribute['username'],$attribute['password']);
           //print_r($post); exit();
		}
	}
			/*if ( $user_identity->errorCode == $config['error_privileges'] ) {
				 return $config['error_privileges'];
			}
//                        if ( $this->_identity->errorCode == UserIdentity::ERROR_USERNAME_INVALID ) {
//				$this->addError('password','Invalid username or password.');
//			}

			if (    $user_identity->errorCode == $config['error_unknown']
				 || $user_identity->errorCode == $config['error_username']
				 || $user_identity->errorCode == $config['error_password']
			) {
				
				return $config['error_privileges'];
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	/*public function login()
	{
		if( $user_identity === null)
		{
			 $user_identity = new UserIdentity($this->username, $this->password);
			 $user_identity->authenticate();
		}
		if( $user_identity->errorCode === $config['error_none'])
		{
			//$duration = $this->rememberMe ? 3600*24*30 : 3600*12; // 30 days or 1 hours
			$duration = $this->rememberMe ? 3600*24*30 : 3600*12; // 30 days or 1 hours
			//Yii::app()->user->login($this->_identity, $duration);
			
			if($this->rememberMe){
				$json=base64_encode(json_encode(array("username"=>$this->username,"password"=>$this->password)));
				$remember_login = new CHttpCookie('remember_login', $json);
				$remember_login->expire = time()+$duration; 
				///Yii::app()->request->cookies['remember_login'] = $remember_login;
			}else{
				//unset(Yii::app()->request->cookies['remember_login']);
			}
			
			return true;
		}
		else
			return false;
	}*/
}
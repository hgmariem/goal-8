<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Helper\UserIdentity;

class UserController extends Controller{
	
    public function __construct()
    {
         
        //$this->middleware('auth');
        parent::__construct();
    }
	
	public function forgotpwd(Request $request)
	{
		if($post = $request->all()){
			
			$helper = new UserIdentity();
			
			if(!empty($post['email']) && filter_var($post['email'], FILTER_VALIDATE_EMAIL)){
				
				if($data=$helper->forgotpwd($post['email'])){
					
					if(isset($data->_failureTextForUser) && $data->_failureTextForUser=='Not found'){
						return redirect("/forgotpwd")->with("error", "Email Not Found.");
					}else{
						return redirect("/login")->with("success", "Check your email for your password!");
					}
					
				}else{
					return redirect("/forgotpwd")->with("error", "Unable to complete request. Please try again later.");	
				}
				
			}else{
				return redirect("/forgotpwd")->with("error", "Invalid email.");
			}
		}
		
		return view('auth.forgot');
	}
	public function getUsers(){
		return getAllUsers();
	}
}
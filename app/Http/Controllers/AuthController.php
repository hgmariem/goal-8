<?php
namespace App\Http\Controllers;
 use Illuminate\Http\Request;
 use App\Model\LoginForm;
 use App\Helper\UserIdentity;
 use Illuminate\Support\Facades\Input;
 use Auth;
 
 class AuthController extends Controller{
	 
	 
	 
	 public function postLogin(Request $request) {
		 $model=new LoginForm;
		 $post=$request->all();
		 print_r($post); exit();
		 $get=$model->authenticate($post);
		 
		 
	 }

	 public function cheklogin()
    {
        $user           = Auth::user();  
        if (isset($user->id) && !empty($user->id)) {

            return response()->json(['data'=>$user,'status'=>1,'msg'=>"user login"]);

        }else{
        	return response()->json(['data'=>array(),'status'=>0,'msg'=>"user logout"]);
        }

         
    }
 } 
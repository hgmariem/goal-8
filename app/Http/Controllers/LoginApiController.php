<?php 
namespace App\Http\Controllers;
use App\Helper\UserIdentity;
use Illuminate\Http\Request;
use DB;
use Auth;
use Session;




class LoginApiController extends Controller
{
	
	
	public function login_Api(Request $request){
		
		$helper = new UserIdentity();
		$post = $request->all();
		//print_r($post); exit();
		$response = $helper->authenticate($post['username'],$post['password']);
		//print_r($response); exit();
		if(strtolower($response->resultCode)=='success'){
			Self::register_Api($response,$post);
		}
		
	}
		
	public function register_Api($response,$post){
		
		$users = array(
		'username' => $post['username'],
		'password' => bcrypt($post['password']),
		
		);
		$user = DB::table('users')->where('username', $post['username'])->first();
		//int_r($user); exit();
		if(count($user)==1){
			Session::flash ( 'message', "Already exits in database" );
		}else{
			$var=DB::table('users')->insert($users);	
		}
		
		
		if ($user){
			Self::login_auth($user,$post);
		}
	}
	
	public function login_auth($user,$post){
	     print_r($user); exit();
	     
		if(auth::attempt(['username' => $post['username'], 'password' =>bcrypt($post['password'])] )){ 
			session([ 
                'username' => $post['username'] 
            ]); 
			print_r('hey'); exit();
            return redirect('/dashboard');
			
		}
		
		else{
			print_r('heyds');exit();
			Session::flash ( 'message', "Invalid Credentials , Please try again." );
            return back();
		}
			
		
	
	}
		
		
		
		
		
}



?>
<?php
namespace App\Http\Controllers;
use App\Model\Def_goals;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request; 

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\Store;


class CheckloginController extends Controller{

	 /**
     * @var Store
     */
    protected $session;

    /**
     * @var mixed
     */
    protected $timeout;

    /**
     * @param Store $session
     */
    public function __construct(Store $session)
    {
        $this->session = $session;
        $this->timeout = config('session.timeout');
    } 

	public function cheklogin(Request $request)
    {

    	   $user           = Auth::user();  
	       /* var_dump(session('lastActivityTime'))."<br/>";
	        var_dump(time())."----"."<bre/>";
	        var_dump($this->timeout)."<br/>";
	        var_dump(time() - session('lastActivityTime'));

	         var_dump((time() - session('lastActivityTime') > $this->timeout));die; || (time() - session('lastActivityTime') > $this->timeout)*/
	        if (isset($user) && !empty($user) ) {		 
	             //$this->session->forget('lastActivityTime');
	              //Auth::logout(); 

	             return response()->json(['data'=>$user,'status'=>1,'msg'=>"user login "]);
	        }else{
	             return response()->json(['data'=>array(),'status'=>0,'msg'=>"user logout"]);
	        }
    }



    public function checkBetalogin()
    {

       $user = Auth::user();

       if(isset($user) && !empty($user)){
        $data['user'] = $user;
        $data['status'] = 1;
       }else{
       $data['user'] = array();
       $data['status'] = 0;

       }

       echo json_encode($data); 
    }

}
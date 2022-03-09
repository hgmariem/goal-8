<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\Trophy;
use App\Model\Goals;
use Auth;
use Config;

class MessageController extends Controller{
	
	public function __construct()
    {
         
        $this->middleware('auth');
        parent::__construct();
    }


    public function index(){

		$model = new Trophy();
		
		$user_id=Auth::user()->id;
		
		$filter=array();
		
		$filter['user_id']=$user_id;

		//$trophies= $model->getAllTrophies($filter);

		return view('message.index');
	}

}
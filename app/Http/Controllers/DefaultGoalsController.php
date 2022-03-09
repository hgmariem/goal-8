<?php
namespace App\Http\Controllers;
use App\Model\Def_goals;
use App\Model\Goals;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Auth;

class DefaultGoalsController extends Controller{
	
	public function __construct()
    {
         
        $this->middleware('auth');
        parent::__construct();
    }

	public function index(Request $request){
        
        $model=new Goals();
        
        $user_id=Auth::user()->id;
        $get_goals=$model->get_goals($user_id,1);

        return view('default_goals.goals_list', ['goals'=>$get_goals]);
	}
	
}
<?php
namespace App\Http\Controllers;
use App\Model\Def_goals;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;  

class Default_goalsController extends Controller{

	 
	
	public function index2(Request $request){
		$model=new Def_goals();
		$post=$request->all();
        //print_r($post); exit();
	    $get_Def_goals=$model->get_Def_goals();
        //print_r($get_goals); exit();
		if( $get_Def_goals){
			return view('home.goals_list',['Def_goals'=>$get_Def_goals]);
		}
	}
	
	public function add(){
		return view('home.goals_def_add');
	} 
}
<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Model\Trophy;
use App\Model\Goals;
use Auth;
use Config;

class TrophyController extends Controller{
	
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
		$filter['deleted']=0;
		$trophies= $model->getAllTrophies($filter);

		return view('trophy.alltrophy',['trophies'=>$trophies]);
	}

	public function add(Request $request){

		$post=$request->all();
		
		if(isset($post['id']) && !empty($post['id'])){
			
			$id=$post['id'];
			$user_id=Auth::user()->id;
			$goal=Goals::where("id",$id)->first();
			
			if($goal && isset($goal->id) && $goal->type_id==Config::get('constants.goal_task')){
				
				$goal->is_in_trophy=1;
				$goal->save();
				$data=array();
				$data['name']=$goal->name;
				$data['item_id']=$goal->id;
				$data['user_id']=$user_id;
				$trophy_model = new Trophy();
				if($trophy=$trophy_model->add($data)){
					$response = array(
						'status' => 1,
						'msg' => 'Status Changed Successfully.',
						"data"=>$trophy
					);	
				}else{
					$response = array(
						'status' => 0,
						'msg' => 'Unable to add trophy.'
					);
				}

				
			}else{
				$response = array(
					'status' => 0,
					'msg' => 'Task Does not exists.'
				);	
			}
		}else{
			$response = array(
				'status' => 0,
				'msg' => 'Invalid request'
			);	
		}

		return \Response::json($response);
	}

	public function create_data(){
		$model = new Trophy();
		
		$user_id=Auth::user()->id;
		
		$filter=array();
		
		$filter['user_id']=$user_id;

		$trophy= $model->getAllTrophies($filter);
		echo "<pre/>";print_r($trophy);exit;
		if($trophy){
			return view('home.alltrophy',['trophies'=>$trophy]);
		}else{
			return false;
		}
	}

	public function edit(Request $request){
		$post=$request->all();
		$id=$post['id'];
		$name=$post['name'];
		$date=$post['date'];

		$trophy = Trophy::where("id",$id)->first();
		if($trophy){
			$trophy->name=$name;
			$trophy->trophy_date=$date;
			if($trophy->save()){
				$response = array(
					'status' => 1,
					'msg' => 'Task Updated Successfully'
				);	
				return \Response::json($response);
			}
		}
		$response = array(
			'status' => 0,
			'msg' => 'Unable to update Task'
		);	
		return \Response::json($response);
	}

	public function delete(Request $request){
		$post=$request->all();
		$id=$post['id'];
		$trophy = Trophy::where("id",$id)->first();
		if($trophy){
			$trophy->deleted=1;
			if($trophy->save()){
				if($trophy->item_id){
					$goal=Goals::where("id",$trophy->item_id)->first();
					if($goal){
						$goal->is_in_trophy=0;
						$goal->save();
					}
				}
				
				$response = array(
					'status' => 1,
					'msg' => 'Task Deleted Successfully'
				);	
				return \Response::json($response);
			}
		}
		$response = array(
			'status' => 0,
			'msg' => 'Unable to delete Task'
		);	
		return \Response::json($response);
	}

	public function editTrophy($id){
		$model = new Trophy();
		$trophy_data=$model->get_trophy_data($id);
		
		return view('home.alltrophy',['trophy_data'=>$trophy_data]);
		
	}

	public function updateTrophy(Request $request){
		$model = new Trophy();
		$post = $request->input();
		$add = $model->edit_trophy($post);
		if($add){
			return redirect('assignment/trophy')->with('success','Success');
		}
		else{
			return back()->withErrors()->withInput();
		}
		
	}

	public function movetotrophy(Request $request){

		$user_id=Auth::user()->id;
		$post=$request->all();
		$model = new Trophy();
		$data=array();
		$data['name']=$post['name'];
		$data['item_id']=$post['item_id'];
		$data['user_id']=$user_id;

		$goal=Goals::where("id",$post['item_id'])->first();
			
		if($goal && isset($goal->id) && $goal->type_id==Config::get('constants.goal_task')){
			
			$goal->is_in_trophy=1;
			$goal->save();
			if($trophy=$model->add($data)){
				$response = array(
					'status' => 1,
					'msg' => 'Task added to trophy successfully.',
					"data"=>$trophy
				);	
			}else{
				$response = array(
					'status' => 0,
					'msg' => 'Unable to add trophy.'
				);
			}
		}else{
			$response = array(
				'status' => 0,
				'msg' => 'Invalid task.'
			);
		}

		return \Response::json($response);
	}

	public function sync(){
		
		$model = new Trophy();

		$user_id=Auth::user()->id;
		$default_filter=['is_delete'=>0, "is_in_trophy"=>0, "is_active"=>1, "type_id"=>Config::get('constants.goal_task'), "is_default"=>0];
		$tasks=Goals::select("id","name","type_id","user_id","updated_at")->where($default_filter)->get()->toArray();
		
		//echo "<pre/>";
		//print_r($tasks);

		if($tasks){

			foreach ($tasks as $key => $task) {
				# code...
				$trophy = Trophy::where("item_id",$task['id'])->first();

				if(!$trophy){
					$data=array();
					$data['name']=$task['name'];
					$data['item_id']=$task['id'];
					$data['user_id']=$task['user_id'];
					$data['trophy_date']=date("Y-m-d",strtotime($task['updated_at']));
					$model->add($data);
				}
			}
		}

	}
	
}

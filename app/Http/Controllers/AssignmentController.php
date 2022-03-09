<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Assignment;
use App\Model\Default_Assignment;
use Illuminate\Support\Facades\Input;
use Auth;
use Validator;

class AssignmentController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    public function index(Request $request){
		
		$user_id=Auth::user()->id;
		$model = new Assignment();
		$model_def=new Default_Assignment();
		$assignments = $model->getAssignments($user_id);
		$default_assignments = $model_def->defaultAssignments();
		if($assignments){
			return view('assignment.assignment_list', [
				'assignments' => $assignments,
				'default_assignments'=>$default_assignments,
				]);
		}
	}
	

	public function add(){
		
		return view('assignment.assignment_add');
		
	}
	
	
	public function create(Request $request){

		$validator = Validator::make($request->all(), [
		    'name' => 'required',
		    'content' => 'required'
		]);

		if (!$validator->fails()) {

			$model = new Assignment();
			$post = $request->input();
			$user_id=Auth::user()->id;
			$post['user_id']=$user_id;

			$add = $model->add_Assignment($post);
			if($add){
				return redirect('assignment/list')->with('success','Assignment created Successfully');
			}else{
				return back()->with('error','Unable to create Assignment.');
			}

		}else{
			return back()->withErrors($validator);
		}
		
	}

		public function createAssignment(Request $request){

			$model = new Assignment();
			$post = $request->input();
			$user_id=Auth::user()->id;
			$post['user_id']=$user_id;

			$add = $model->addAssignment($post);
			if(!empty($add))
			{
				$assignmentId = $add->id;

			    $response = array(
				'status' => 1,
				'gid' => $assignmentId,
				'msg' => 'Data Saved Successfully.',
			);		
		}
		else
		{
			$response = array(
				'status' => 0,
				'gid' => "",
				'msg' => 'Data not Saved Successfully.',
			);		
		}
			
		return \Response::json($response);
			//return \Response::json($response);

		
		
	}




    public function edit($id){
        $user_id=Auth::user()->id;
		$model = new Assignment();
		$assigment_data=$model->get_assignment_data($id);
		
		if(!$assigment_data || ($assigment_data->user_id != $user_id))
		{
			return redirect('assignment/list')->with("success","Invalid Assignment.");
		}
		return view('assignment.assignment_edit',['assigment_data'=>$assigment_data]);
		
	}
	
	public function update(Request $request){
		
		$validator = Validator::make($request->all(), [
		    'name' => 'required',
		    'content' => 'required'
		]);

		if (!$validator->fails()) {

			$model = new Assignment();
			$post = $request->input();
			$add = $model->edit_Assignment($post);
			
			/*if($add){
				return redirect('assignment/list')->with('success','Assignment updated Successfully.');
			}else{
				return redirect('assignment/list')->with('error','Unable to create Assignment.');
			}*/

			return \Response::json($add);

		}else{

			return back()->withErrors($validator);
			//return \Response::json($validator->errors());
		}
		
	}

	

	public function delete(Request $request){
		$model = new Assignment();
			$post = $request->all();
			
			$response = array(
				'status' => 0,
				'msg'	 => "Unable to delete assignment."
			);

			if($id=$post['id']){
				$add = $model->_delete($id);
				if($add){
					$response = array(
						'status' => 1,
						'msg'	 => "Assignment Delete Successfully."
					);
				}
			}else{
				$response = array(
					'status' => 0,
					'msg'	 => "Unable to delete assignment."
				);	
			}
		
		return \Response::json($response);
	}

	public function delete_defuser(Request $request,$id){
			$model = new Default_Assignment();
			$post = $request->input();
			$add = $model->delete_defuser($id);
			if($add){
				return redirect('assignment/list')->with('success','Success');
			}
			else{
				return back()->withErrors()->withInput();
			}
		
	}

	public function sort_list(Request $request){
		
		parse_str($request->input("data"),$post);	
		
		if($post && isset($post['item']) && !empty($post['item'])){
		
			$assignment_ids=array_reverse($post["item"]);
			foreach ($assignment_ids as $order => $id) {
				
				if($assignment=Assignment::find($id)){
					$assignment->list_order=$order;
					$assignment->save();
				}
				
			}

			$response = array(
				'status' => 1,
				'msg' => 'List Order Changed Successfully.',
			);	
		}else{
			$response = array(
				'status' => 0,
				'msg' => 'Unable to change list order.',
			);	
		}
		
		return \Response::json($response);
	}

	

	public function addtomylist($id){
		
		$response=array("status"=>0,"msg"=>"Unable to add assignment to your list.");
		$model = new Assignment();
		$data =Default_Assignment::where("id",$id)->first()->toArray();
		//print_r($data);
		unset($data['id']);
		$user_id=Auth::user()->id;
		$data['user_id']=$user_id;
		$data['list_order']=$model->get_max_list_order($user_id)+1;
		$data['created_at']=date("Y-m-d H:i:s");
		$data['updated_at']=date("Y-m-d H:i:s");
		if($assignment=Assignment::create($data)){
			
			$response['status']=1;
    		$response['msg']="Assignment Added to your list.";
    		$response['data']=$assignment;

    		return \Response::json($response);

			//return redirect('assignment/list')->with('success','Success');
		}else{
			$response['status']=0;
    		$response['data']=array();
    		$response['msg']="Unable to add assignment to your list.";
    		return \Response::json($response);
		}
	}


	/************************Default Assignment**************************/

	public function add_default(){
		return view('assignment.assignment_add_default');
	}

	public function default_create(Request $request){
		$model = new Default_Assignment();
		$post=$request->input();
		
		$add_default=$model->default_Assignment($post);

		if($add_default){

			return redirect('assignment/default/list')->with('success','Success');
			
		}else{
			return back()->withError()->withInput();
		}
		
	}

	public function default_edit($id){
		$model = new Default_Assignment();
		$defassigment_data=$model->get_defassignment_data($id);
		//echo "<pre/>";print_r($assigment_data);
		return view('assignment.assignment_def_edit',['defassigment_data'=>$defassigment_data]);
		
	}

	public function defupdate(Request $request){
		$model = new Default_Assignment();
		$post = $request->input();
		$add = $model->edit_def_Assignment($post);
		if($add){
			return redirect('assignment/default/list')->with('success','Success');
		}
		else{
			return back()->withErrors()->withInput();
		}
	}

	public function list_default(){
		
		$user_id=Auth::user()->id;
		$model=new Default_Assignment();
		$assignments = $model->defaultAssignments();
		
		if($assignments){
			return view('assignment.default_assignment_list', ['assignments' => $assignments]);
		}
	}

	public function delete_default(Request $request){

		$model = new Default_Assignment();
		
		$post = $request->all();
		
		$response = array(
			'status' => 0,
			'msg'	 => "Unable to delete assignment."
		);

		if($id=$post['id']){
			$add = $model->_delete($id);
			if($add){
				$response = array(
					'status' => 1,
					'msg'	 => "Assignment Delete Successfully."
				);
			}
		}else{
			$response = array(
				'status' => 0,
				'msg'	 => "Unable to delete assignment."
			);	
		}
		
		return \Response::json($response);
	}

	public function default_sort_list(Request $request){
		
		parse_str($request->input("data"),$post);	
		
		if($post && isset($post['item']) && !empty($post['item'])){
		
			$assignment_ids=array_reverse($post["item"]);
			foreach ($assignment_ids as $order => $id) {
				
				if($assignment=Default_Assignment::find($id)){
					$assignment->list_order=$order;
					$assignment->save();
				}
				
			}

			$response = array(
				'status' => 1,
				'msg' => 'List Order Changed Successfully.',
			);	
		}else{
			$response = array(
				'status' => 0,
				'msg' => 'Unable to change list order.',
			);	
		}
		
		return \Response::json($response);
	}
	
}
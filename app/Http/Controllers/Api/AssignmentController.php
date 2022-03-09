<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Assignment;
use App\Model\Default_Assignment;
use Illuminate\Support\Facades\Input;
use App\Repositories\IUserRepository;
use App\Traits\ApiResponser;
//use Auth;
use Validator;

class AssignmentController extends Controller
{

	use ApiResponser;
   
	public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request){
		
		$post = $request->all();
		$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
		$model = new Assignment();
		$assignments = $model->getAssignments($userDetails->id);
		
		
		if($assignments){
			return $this->success($assignments, 'Listed Successfully');
		}
		else
		{
			return $this->error('Not Found', 404);
		}
	}


	public function getDefaultAssignment(Request $request){
		
		$post = $request->all();
		$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
       
		$model_def=new Default_Assignment();
		$default_assignments = $model_def->defaultAssignments();
		
		if($default_assignments){
			return $this->success($default_assignments, 'Listed Successfully');
		}
		else
		{
			return $this->error('Not Found', 404);
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
			//$user_id=Auth::user()->id;
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

			$validator = Validator::make($request->all(), [
		    'name' => 'required'
			]);

        /* if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
		 //response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response])
          return $this->error("Failed.",403) ;
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
		}

		if($post=$request->input()){

			$userDetails =  $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        	//$user_id = $userDetails->id;
			$model = new Assignment();
			$post['user_id']=$userDetails->id;

			$add = $model->addAssignment($post);
			if(!empty($add))
			{
				$assignmentId = $add->id;

				return  $this->success($add, 'Assignment Created Successfully');		
		}
		else
		{
			return $this->error('Data not Saved Successfully.', 403);
			
		}
	}
	}

    public function edit(Request $request){

    	$post = $request->all();
    	$userDetails =  $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        $user_id = $userDetails->id;
		//$user_id = $userDetails->id;
    	$id = $post['id'];
        //$user_id=Auth::user()->id;
		$model = new Assignment();
		$assigment_data=$model->get_assignment_data($id);
		
		if(!$assigment_data || ($assigment_data->user_id != $user_id))
		{
			return $this->error('Not Found',404);
			// response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Not Found"]);
		}

		return $this->success($assigment_data,'get Assignment detail'); 
		//response()->json(['data'=>$assigment_data,'status'=>1,'msg'=>"get Assignment detail",'error'=>""]);

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

		$validator = Validator::make($request->all(), [
		    'id' => 'required'
			]);

			if($validator->fails()){
				return $this->error($validator->errors(), 403);
			}

			$model = new Assignment();
			$post = $request->all();
			
			if($id=$post['id']){
				$add = $model->_delete($id);
				if($add){
					return $this->success($add,'Assignment Deleted Successfully');
					//response()->json(['data'=>$add,'status'=>1,'msg'=>"Assignment Deleted Successfully",'error'=>""]);		
				}
				else
				{
					return $this->error('Failed,Assignment Not Found', 404);
					//response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>"Assignment Not Found"]);	
				}
			}else{

				return $this->error('Unable to delete assignment.',404);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>"Unable to delete assignment."]);	
			}
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
		
		//parse_str(,$post);
		$validator =   Validator::make($request->all(),[
			'item.*'    => 'required'
		]);

		if($validator->fails()){
			return $this->error($validator->errors(), 403);
		}


		$post = $request->all();
		//return $this->success($post['item']);
		if($post && isset($post['item']) && !empty($post['item'])){
		
			$assignment_ids=array_reverse($post["item"]);
			foreach ($assignment_ids as $order => $id) {
				
				if($assignment=Assignment::find($id)){
					$assignment->list_order=$order;
					$assignment->save();
				}
				
			}

		return $this->success($post,'List Order Changed Successfully.');
		//response()->json(['data'=>$post,'status'=>1,'msg'=>"List Order Changed Successfully.",'error'=>""]);	
				
		}else{

		return $this->error('Unable to change list order.',404);
		//response()->json(['data'=>array(),'status'=>1,'msg'=>"Failed",'error'=>"Unable to change list order."]);		
		}
	}

	

	public function addtomylist(Request $request){
		
		$validator = Validator::make($request->all(), [
		    'id'=>'required'
			]);
            // validator to correct
			if($validator->fails()){
				return $this->error($validator->errors(), 403);
			}

		$post = $request->all();
		$id = $post['id'];
		$model = new Assignment();
		$data =Default_Assignment::where("id",$id)->first()->toArray();
		//print_r($data);
		unset($data['id']);
		
		$data['user_id']=$post['oauth_token']->user_id;
		$data['list_order']=$model->get_max_list_order($post['oauth_token']->user_id)+1;
		$data['created_at']=date("Y-m-d H:i:s");
		$data['updated_at']=date("Y-m-d H:i:s");
		if($assignment=Assignment::create($data)){
			
       return $this->success($assignment, 'Add Default Assignment Successfully');
		}else{
			return $this->error("Unable to add assignment to your list.", 403);
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

	public function default_edit(Request $request){
		$post = $request->all();
    	$userDetails =  $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        $user_id = $userDetails->id;
    	$id = $post['id'];
		$model = new Default_Assignment();
		$defassigment_data=$model->get_defassignment_data($id);
		if(!$defassigment_data)
		{
			return $this->error('Not Found',404);
			//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Not Found"]);
		}

		return $this->success($defassigment_data,'get Assignment detail');
		//response()->json(['data'=>$defassigment_data,'status'=>1,'msg'=>"get Assignment detail",'error'=>""]);
		
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
		
		//$user_id=Auth::user()->id;
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
			return $this->error('Unable to change list order.', 403);
			
		}
		
		return \Response::json($response);
	}
	
}
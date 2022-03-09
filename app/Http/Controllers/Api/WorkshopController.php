<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Model\Workshop;
use App\Model\Default_Assignment;
use Illuminate\Support\Facades\Input;
use Auth;
use Validator;

class WorkshopController extends Controller
{
    use ApiResponser;

	public function __construct()
    {
        # $this->middleware('auth');
        # parent::__construct();
    }


	public function get(Request $request){

		$post = $request->all();
        $user_id = $post['oauth_token']->user_id;
		$model = new Workshop();
		$data['latest'] = $model->getAllWorkshop($user_id);
		$data['oldest'] = $model->getAllOlderWorkshop($user_id);
		if(isset($data) && !empty($data))
		{
		return $this->success($data, 'WorkShop Fetched Successfully!');

		}else{
			return $this->error('Not Found!', 404);
		}
		
	}

	public function create(Request $request){
			$result = array();

			$post = $request->input();
			$model = new Workshop();

			$post['user_id']=$post['oauth_token']->user_id;

			foreach($post['title'] as $key => $res){
			$data['title'] = isset($res)?$res:"";
			$data['id']    = isset($post['id'][$key])?$post['id'][$key]:"";
			$data['day'] = isset($post['day'][$key])?$post['day'][$key]:"";
			$data['month'] = isset($post['month'][$key])?$post['month'][$key]:"";
			$data['year'] = isset($post['year'][$key])?$post['year'][$key]:"";

			if($data['year'] == '' || $data['month'] == '' || $data['day'] == ''){
				$data['status'] = 2;
			}
			$workshop_date = $data['year']."-".$data['month']."-".$data['day'];
			$data['date']  = $workshop_date;
			$data['user_id'] = $post['user_id'];

			if(isset($res) && !empty($res)){

				$add = $model->addWorkshop($data);
				$result[] = $add;
			}
			}
			return $this->success($result, 'Data saved Successfully!');		
	}

		public function createAssignment(Request $request){

			$model = new Assignment();
			$post = $request->input();
			$post['user_id']=$post['oauth_token']->user_id;

			$add = $model->addAssignment($post);
			if(!empty($add))
			{
				$assignmentId = $add->id;
			return $this->success($assignmentId, 'Data saved Successfully!');		
		}
		else
		{
			return $this->error('Data not saved!', 403);		
		}
		
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
						return $this->success($add, 'Data updated Successfully!');

		}else{
			if ($validator->fails()) {
				return $this->error($validator->errors(), 403);
			}
		}
		
	}

	

	public function delete(Request $request){
		$model = new Assignment();
			$post = $request->all();
			if($id=$post['id']){
				$add = $model->_delete($id);
				if($add){
				return $this->success(null, 'Assignment Delete Successfully!');
				}
			}else{
				return $this->error('Unable to delete assignment.', 403);	
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
			return $this->success(null, 'List Order Changed Successfully.');	
		}else{
			$response = array(
				'status' => 0,
				'msg' => 'Unable to change list order.',
			);	
			return $this->error('Unable to change list order.', 403);
		}
	}

	public function addtomylist($id){
		
		$model = new Assignment();
		$data =Default_Assignment::where("id",$id)->first()->toArray();
		//print_r($data);
		unset($data['id']);
		$user_id=Auth::user()->guid;
		$data['user_id']=$user_id;
		$data['list_order']=$model->get_max_list_order($user_id)+1;
		$data['created_at']=date("Y-m-d H:i:s");
		$data['updated_at']=date("Y-m-d H:i:s");
		if($assignment=Assignment::create($data)){
			
			$response['status']=1;
    		$response['msg']="Assignment Added to your list.";
    		$response['data']=$assignment;
			return $this->success($assignment, 'Assignment Added to your list.');
		}else{
    		return $this->error('Unable to add assignment to your list.', 403);
		}
	}


	/************************Default Assignment**************************/

	public function delete_default(Request $request){

		$model = new Default_Assignment();
		
		$post = $request->all();

		if($id=$post['id']){
			$add = $model->_delete($id);
			if($add){
				return $this->success(null,'Assignment Delete Successfully!');
			}
		}else{
			return $this->success('Unable to delete assignment.', 403);
		}
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
			return $this->success(null, 'List Order Changed Successfully.');
		}else{
			return $this->error('Unable to change list order.', 403);	
		}
	}
	
}
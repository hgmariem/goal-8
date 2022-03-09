<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser; 
use Illuminate\Http\Request;
use App\Model\Trophy;
use App\Model\Goals;
//use Auth;
use Config;
use Validator;

class TrophyController extends Controller{
	
	use ApiResponser; 


	public function index(Request $request){

		$post=$request->all();
		$trophy = array();
		$model = new Trophy();
		$filter=array();
		$filter['user_id']= $post['oauth_token']->user_id;
		$filter['deleted']=0;
		$trophies= $model->getAllTro($filter);

		$datas = $this->objectToArray($trophies); 
		
		if($datas){
			return $this->success($datas, 'Trophies Fetched Successfully');
		}else{
			return $this->error('No Trophies Found!', 404);
		}
	}

	private function objectToArray($object) {
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        if (is_array($object)) {
            return array_map(array($this, 'objectToArray'), $object);
        } else {
            return $object;
        }
    }


	public function add(Request $request){

		$validator =   Validator::make($request->all(),[
            'id'       => 'required',
        ]);
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }
		$post=$request->all();
		
		if(isset($post['id']) && !empty($post['id'])){
			
			$id=$post['id'];
            
			$goal=Goals::where("id",$id)->first();
			
			if($goal && isset($goal->id) && $goal->type_id==Config::get('constants.goal_task')){
				
				$goal->is_in_trophy=1;
				$goal->save();
				$data=array();
				$data['name']=$goal->name;
				$data['item_id']=$goal->id;
				$data['user_id']= $post['oauth_token']->user_id;
				$trophy_model = new Trophy();
				if($trophy=$trophy_model->add($data)){
					return $this->success($trophy, 'status Changed Successfully!');	
				}else{
					return $this->error('Unable to add Trpohy!', 403);
				}

				
			}else{
				return $this->error('Task Does not exists', 404);
			}
		}else{
			return $this->error('Invalid request', 403);
		}
	}


	public function edit(Request $request){
		$validator =   Validator::make($request->all(),[
            'id'       => 'required',
            'name'       => 'required',
            'date'       => 'required'
        ]);

        if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }
		$post=$request->all();
		$id=$post['id'];
		$name=$post['name'];
		$date=$post['date'];

		$trophy = Trophy::where("id",$id)->first();
		if($trophy){
			$trophy->name=$name;
			$trophy->trophy_date=$date;
			if($trophy->save()){
				return $this->success($trophy, 'Trophy Updated Successfully!');	
			}
		}
				return $this->error('Unable to edit Trpohy!', 403);  
	}

	public function delete(Request $request){
		$validator =   Validator::make($request->all(),[
            'id'       => 'required'
        ]);

		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

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
				return  $this->success($trophy, 'Trophy Deleted Successfully!');	
			}
		}
				return $this->error('Unable to delete Trpohy!', 403);  
	}
 // Fetch Trpophy Details
	public function editTrophy(Request $request){

		$validator =   Validator::make($request->all(),[
            'id'       => 'required'
        ]);

        if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

        if($post=$request->input()){
        $id = $post['id'];
		$model = new Trophy();
		$trophy_data=$model->get_trophy_data($id);
		if($trophy_data){

			return $this->success($trophy_data, 'Trophy Details Fetched Successfully!');
		}else{
			return $this->error('No Details Found!', 404); 
		}
	}else{
		return $this->error('Unable to fetch Trpohy Details!', 403);  
		}
	}


	public function movetotrophy(Request $request){
		$post=$request->all();
		$model = new Trophy();
		$data=array();
		$data['name']=$post['name'];
		$data['item_id']=$post['item_id'];
		$data['user_id']= $post['oauth_token']->user_id;

		$goal=Goals::where("id",$post['item_id'])->first();
			
		if($goal && isset($goal->id) && $goal->type_id==Config::get('constants.goal_task')){
			
			$goal->is_in_trophy=1;
			$goal->save();
			if($trophy=$model->add($data)){
				return $this->success($trophy, 'Task added to Trophy successfully!');	
			}else{
				$response = array(
					'status' => 0,
					'msg' => 'Unable to add trophy.'
				);
				return $this->error('Unable to add Trophy!', 403);
			}
		}else{
			return $this->error('Invalid Request!', 419);
		}
	}

	public function sync(){
		
		$model = new Trophy();
		$default_filter=['is_delete'=>0, "is_in_trophy"=>0, "is_active"=>1, "type_id"=>Config::get('constants.goal_task'), "is_default"=>0];
		$tasks=Goals::select("id","name","type_id","user_id","updated_at")->where($default_filter)->get()->toArray();

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

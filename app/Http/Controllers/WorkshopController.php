<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Workshop;
use App\Model\Default_Assignment;
use Illuminate\Support\Facades\Input;
use Auth;
use Validator;

class WorkshopController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }


	public function add(){
	 if(is_admin()){
		$user_id=Auth::user()->id;
		$model = new Workshop();
		$workshopData = $model->getAllWorkshop($user_id);
		$old_data = $model->getAllOlderWorkshop($user_id);
		return view('workshop.add_workshop',['workshop'=>$workshopData,'old_data'=>$old_data]);
		}else{
			return redirect("/home");
		}
		
	}


	public function manage(){
		
		return view('workshop.manage_workshop');
		
	}
	
	
	public function create(Request $request){
			$result = array();
			$post = $request->input();
			$model = new Workshop();
			$user_id=Auth::user()->id;
			$post['user_id']=$user_id;

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
			$data['user_id'] = $user_id;

			if(isset($res) && !empty($res)){

				$add = $model->addWorkshop($data);
				$result[] = $add->id;
			}

			$response = array(
				'status' => 1,
				'gid' => $result,
				'msg' => 'Data Saved Successfully.',
			);

			}
			return \Response::json($response);
			//return \Response::json($response);
		
	}

	

	public function delete($id){
		$model = new Workshop();
			$response = array(
				'status' => 0,
				'msg'	 => "Unable to delete Workshop"
			);

			if($id){
				$add = $model->deleteWorkshop($id);
				if($add){
					$response = array(
						'data'=> $add,
						'status' => 1,
						'msg'	 => "Workshop Delete Successfully."
					);
				}
			}else{
				$response = array(
					'status' => 0,
					'msg'	 => "Unable to delete Workshop"
				);	
			}
		
		return \Response::json($response);
	}
	
}
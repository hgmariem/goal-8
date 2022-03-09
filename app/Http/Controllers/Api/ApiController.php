<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\UserIdentity;
use App\Model\Goals;
use App\Model\Trophy;
use App\Model\Types;
use App\Model\StatementValues;
//use Auth;
use Validator;
use Config;

class ApiController extends Controller {
    
    public function __construct()
    {
         
        //$this->middleware('auth');
        //parent::__construct();
    }

   /*@function for show listing of task*/

   public function get_task_list(Request $request){

    	$post_data=$request->all();
    	

		 $validator =   Validator::make($request->all(),[
		        'view_type'    => 'required',
		    ]);

	      if($validator->fails()){

	           
	             foreach ($validator->errors()->toArray() as $key => $value) { 
	                   $response[]=$value;
	             }
	            return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
	         }

    	 $token = (isset($post_data['token']) && !empty($post_data['token'])) ? $post_data['token'] :"";

   		 if(isset($token) && !empty($token)){

   	  		$userDetails = getUserDetailByToken($token);
   	  		if(isset($userDetails) && !empty($userDetails)){
   	  			$guid = (isset($userDetails->guid) && !empty($userDetails->guid) ) ? $userDetails->guid :"";		
				$filter=array();
		        $user_id=$guid;
		        $model=new Goals();
		        
		        $filter['start_date']=isset($post_data['date'])&&!empty($post_data['date'])?$post_data['date']:date("Y-m-d");
		    	
		    	$filter['user_id']=$user_id;
		    	
		    	$filter['is_default']=0;
		    	
		    	$filter['view_type']=$post_data['view_type'];
		    		


				$tasks_list=$model->get_tasks($filter);


				$view_type = (isset($post_data['view_type']) && !empty($post_data['view_type'])) ? $post_data['view_type'] :"";

				$tasks['view_type']=$view_type;
				$tasks['items']=$tasks_list;
				$msg =  "Listed Successfully.";
				$status = 1;

          
   	  		}else{
   	  			$msg = "User not Found.";
   	  			$tasks = array();
   	  			$status  =0;
   	  		}

   		  }else{
	   			$msg = "Please Provide Token.";
	   			$tasks = array();
	   			$status = 0;
   		 }
         return response()->json(['data'=>$tasks,'status'=>$status,'msg'=>$msg,'error'=>""]);
   } 
 /*@end of function*/ 

  /*@function for sorting data of habbit*/ 

  public  function sort_habbit(Request $request){

	 $post_data=$request->all();

		 $validator =   Validator::make($request->all(),[
			'item.*'    => 'required'
		]);

		if($validator->fails()){

		 foreach ($validator->errors()->toArray() as $key => $value) { 
		       $response[]=$value;
		 }

		  return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);

		}else{
			
			if($post_data && isset($post_data['item']) && !empty($post_data['item'])){
					// echo "<pre/>";
	 			// 	print_r($post_data);die;
					$goal_ids=array_reverse($post_data['item']);
						foreach ($goal_ids as $i => $id) {
							$goal=Goals::find($id);
							if($goal)
							{
								$goal->self_order=$i;
								$goal->save();
							}
							
						}

						$res = array(
							'data'=>array(),
							'status'=>1,
							'msg'=>'List Order Changed Successfully.',
							'error'=>""	
						);		
				}else{
					 $res = array(
							'data'=>array(),
							'status'=>0,
							'msg'=>'Unable To Change List Order.',
							'error'=>""	
					     );		
				}
		  }

 		 return response()->json($res);
   }

  /*@en of function*/ 


  /*@function for mark like task*/ 

  	public function movetotrophy(Request $request){

  		$post = $request->all();
	    $validator =   Validator::make($request->all(),[
			'name'       => 'required',
			'item_id'    => 'required',
			'trophy_date'=> 'required'
		]);

		if($validator->fails()){

		 foreach ($validator->errors()->toArray() as $key => $value) { 
		       $response[]=$value;
		 }

		  return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);

		}else{

			$token = (isset($post['token']) && !empty($post['token'])) ? $post['token'] :"";

			$userDetails = getUserDetailByToken($token);
			$user_id = (isset($userDetails->guid) && !empty($userDetails->guid)) ?  $userDetails->guid:"";

			$data=array();
			$model = new Trophy();
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
						"data"=>$trophy,
						"error"=>""
					);	
				}else{
					$response = array(
						'status' => 0,
						'msg' => 'Unable to add trophy.',
						'data'=>array(),
						'error'=>""
					);
				}
			}else{
				$response = array(
					'status' => 0,
					'msg' => 'Invalid task.',
					'data'=>array(),
					'error'=>""
				);
			}
	   } 
   		 return response()->json($response);

  	}

  /*@end of function*/ 


  /*@function for listing the goal branch*/

   public function show_goal_branch(Request $request){

   	$post = $request->all();
	$model=new Goals();
	
			if($status=$model->change_state($post['auto_save_id'], $post['self'])){
			$response = array(
				'status' => $status,
				'msg' => 'Status Changed Successfully.',
			);	
		}else{
			$response = array(
				'status' => $status,
				'msg' => 'Unable Change Status',
			);	
		}


   }
  /*@end of function*/ 



  public function getUserDetail(Request $request)
  {
  	$post = $request->all();
  	$userDetails = getUserDetailByToken($post['token']);
    $user_id = $userDetails->guid;
  	$helper = new UserIdentity();

  	$userData = $helper->getUser($user_id);

  	$res = array(
							'data'=>$userData,
							'status'=>1,
							'msg'=>'Get User Details.',
							'error'=>""	
					     );		
return response()->json($res);
  	
  }



}

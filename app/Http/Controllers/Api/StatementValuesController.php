<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\Goals;
use App\Model\HabitTypes;
use App\Model\Types;
use App\Model\Trophy;
use App\Model\Def_goals;
use App\Model\StatementValues;
use App\Model\TaskTemplate;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use \Carbon\Carbon;
//use Auth;
use View;
use Config;
use DB;
use URL;
use Validator;
use App\Traits\ApiResponser;
use App\Repositories\IUserRepository;
class StatementValuesController extends Controller{
	
    use ApiResponser;
   
	public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index(Request $request){

    	$post = $request->all();
    	$characters=array();
    	$tasks=array();
    	$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
		
        $user_id = $userDetails->id;
    	$statement_values=new StatementValues();
    	$statements=$statement_values->list("statement", $user_id);
    	
    	
    	//$data['statements'] = (isset($statements) && !empty($statements))?$statements:array();
    	

    	$values=$statement_values->list("values", $user_id);

    	$new_arr = array_merge($statements,$values);

    	$data['values'] = $new_arr;
    	//$data['values'] = (isset($values) && !empty($values))?$values:array();
    	
    	
    	/*echo "<pre/>";
    	print_r($statements);die;*/
    	$sfilter=array();
    	$sfilter['user_id']=$user_id;
    	$sfilter['is_deleted']=0;
    	$sfilter['goal_id']=0;
    	$sfilter['meta_type']="statement";
        //$sfilter['show_in_lobby']=1;
    	$allStatement=$statement_values->get_all_personal_attrs($user_id);
    	// echo "<pre/>";
    	// print_r($single_statement);die;
    	$data['allStatement'] = (isset($allStatement) && !empty($allStatement))?$allStatement:array();
    	
    	return $this->success($data,'get compass listed Successfully');
		
    }


    public function statement_view(Request $request){

    	$characters=array();
    	$tasks=array();
    	//$user_id=Auth::user()->guid;
    	$statement_values=new StatementValues();
    	$statements=$statement_values->list("statement", $user_id);
    	$values=$statement_values->list("values", $user_id);

    	$sfilter=array();
    	$sfilter['user_id']=$user_id;
    	$sfilter['is_deleted']=0;
    	$sfilter['goal_id']=0;
    	$sfilter['meta_type']="statement";
        //$sfilter['show_in_lobby']=1;
    	$single_statement=$statement_values->get_lobby_statement($sfilter);
    	#print_r($values);

    	return view('statement-values.partials.statement_view', ['values'=>$values, 'statements'=>$statements, "single_statement"=>$single_statement]);
    }


	public function attribute_delete_sheet(Request $request){

		$validator =   Validator::make($request->all(),[
            'sheet_id'       => 'required',
            'sheet_number'    => 'required',
            'sheet_name'=> 'required',
            'attr'=> 'required',
            'auto_save_id'=> 'required'
        ]);

        /* if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

		if($post=$request->input()){

			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
			
        	$user_id = $userDetails->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::delete_meta($_habit->id, $meta_key);

				$meta->meta_attr = json_decode($meta->meta_attr);

				return $this->success($meta,'successfully Deleted');
				//response()->json(['data'=>$meta,'status'=>1,'msg'=>"successfully Deleted",'error'=>""]);

			}else{
				return $this->error('Invalid Habit.',403);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>"Invalid Habit."]);
					
			}
		}else{

			return $this->error('Not Deleted.',403);
			//response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>"Not Deleted."]);
						
		}
	}


	public function attribute_get_sheet(Request $request){

		$validator =   Validator::make($request->all(),[
		            'sheet_id'       => 'required',
		            'sheet_number'    => 'required',
		            'sheet_name'=> 'required',
		            'attr'=> 'required',
		            'auto_save_id'=> 'required'
		        ]);

         /*if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

		if($post=$request->input()){

			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        	$user_id = $userDetails->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				
				$meta_key=$post['attr']."-".$post['sheet_id'];
				
				StatementValues::deactivate_sheets($_habit->id, $post['attr']);

				$meta=StatementValues::get($_habit->id, $meta_key);
				
				//print_r($meta);

				$meta->meta_attr=json_decode($meta->meta_attr);

				$breaks = array("<br />","<br>","<br/>");  
				
				$html = str_ireplace($breaks, "", $meta->meta_value); 

				$meta->meta_value = $html;

				//print_r($meta->meta_attr);

				$meta->meta_attr->html = str_ireplace($breaks, "", $meta->meta_attr->html); 

				$meta->meta_attr = json_encode($meta->meta_attr);
				
				if($meta){

					$meta->is_active=1;

					$meta->save();

				}

				$meta->meta_attr = json_decode($meta->meta_attr);

				return $this->success($meta,'Get Sheet Successfully');
				//response()->json(['data'=>$meta,'status'=>1,'msg'=>"Get Sheet Successfully",'error'=>""]);

			}else{
				return $this->error('Invalid Habit.',404);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Invalid Habit."]);	
			}
		}else{

			return $this->error('Invalid Request.',404);
			//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Invalid Request."]);	
		}
	}


	public function attribute_duplicate_sheet(Request $request){

				$validator =   Validator::make($request->all(),[
		            'sheet_id'       => 'required',
		            'sheet_number'    => 'required',
		            'sheet_name'=> 'required',
		            'attr'=> 'required',
		            'auto_save_id'=> 'required'
		        ]);

        /* if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

		if($post=$request->input()){

			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        	$user_id = $userDetails->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			

			if($_habit){

				$metas=StatementValues::get_all_attrs($_habit->id);

				
				$sheetNumber = isset($post['sheet_number'])?$post['sheet_number']:rand(111,999);
				
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::get($_habit->id, $meta_key);
				
				$_meta=$meta->replicate();

				$meta_attr = json_decode($_meta->meta_attr);
				
				#print_r($meta_attr);

				$new_sheet_id = rand(1111111111,9999999999);
				
				$meta_attr->sheet_id = $new_sheet_id;
				
				$meta_attr->sheet_number = $sheetNumber;
				
				$meta_attr->is_active = $_meta->is_active;

				$_meta->meta_key = $meta_attr->attr."-".$meta_attr->sheet_id;

				if(isset($meta_attr->token) && !empty($meta_attr->token))
				{
				unset($meta_attr->token);

				}

				$_meta->meta_attr = json_encode($meta_attr);

				$_meta->is_active = 0;
				
				$_meta->save();

				$_meta->meta_attr = json_decode($_meta->meta_attr);

				return $this->success($_meta,'Sheet Duplicated successfully');
				//response()->json(['data'=>$_meta,'status'=>1,'msg'=>"Sheet Duplicated successfully",'error'=>""]);

			}else{
				return $this->error('Invalid Habit.',404);
				 //response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Invalid Habit."]);		
			}
		}else{

			return $this->error('Invalid Habit.',404);
			//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Invalid Habit."]);
		}
	}
	
	
	public function attribute_rename_sheet(Request $request){

		$validator =   Validator::make($request->all(),[
            'sheet_id'       => 'required',
            'sheet_number'    => 'required',
            'sheet_name'=> 'required',
            'attr'=> 'required',
            'auto_save_id'=> 'required'
        ]);

         /*if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

		if($post=$request->input()){

			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        	$user_id = $userDetails->id;


			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::get($_habit->id, $meta_key);

				$meta_attr = json_decode($meta->meta_attr);
				
				$meta_attr->sheet_name = $post['sheet_name'];

				$meta->meta_attr = json_encode($meta_attr);

				$meta->save();

				$meta->meta_attr = json_decode($meta->meta_attr);

				return $this->success($meta,'Renamed successfully');
				//response()->json(['data'=>$meta,'status'=>1,'msg'=>"Renamed successfully",'error'=>""]);
				
			}else{
				return $this->error('Invalid Request.',404);
				// response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>"Invalid Request."]);	
			}
		}else{
			return  $this->error('Invalid Request.',404);
			//response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>"Invalid Request."]);	
		}

	}

	public function save_statements_values(Request $request){
		
		$validator =   Validator::make($request->all(),[
            'sheet_id'       => 'required',
            'sheet_number'    => 'required',
            'sheet_name'=> 'required',
            'attr'=> 'required',
            'auto_save_id'=> 'required'
        ]);

        /* if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

		if($post=$request->input()){

			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
			
        	$user_id = $userDetails->id;
			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();
			

			if($_habit){
				
				$post_data=array();
				
				$post_data['meta_key']=$post['attr']."-".$post['sheet_id'];
				
				StatementValues::deactivate_sheets($_habit->id, $post['attr']);

				$html = $post['html'];

				$post['html']=$html;
				
				$post_data['meta_attr']=json_encode($post);
				
				$post_data['meta_type']=$post['attr'];

				$post_data['meta_value']=$html;
				
				$post_data['goal_id']=$_habit['id'];
				
				$post_data['is_active']=1;

				$post_data['user_id']=$user_id;

				//print_r($post_data);

				//exit();

				$statement = StatementValues::add($post_data);

				$statement->meta_attr = json_decode($statement->meta_attr);

				return $this->success($statement,'statement sheet successfully added');
				//response()->json(['data'=>$statement,'status'=>1,'msg'=>"statement sheet successfully added",'error'=>""]);
			}else{

				return $this->error('Invalid request.',404);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Invalid request."]);		
			}

		}else{

			return $this->error('Invalid request.',404);
			//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Invalid request."]);
				
		}

	}

	public function get_statements_values(Request $request){
			
			$validator =   Validator::make($request->all(),[
            'auto_save_id'       => 'required'
        	]);

         /*if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }
		if($post=$request->input()){

			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        	$user_id = $userDetails->guid;
			//$user_id=Auth::user()->guid;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			$_default = array();
			$_default_value=array();
			$_default_statement=array();

			$_default_value["attr"]="values";
			$_default_value["auto_save_id"]=$post['auto_save_id'];
			$_default_value["html"]="";
			$_default_value["is_active"]="true";
			$_default_value["sheet_id"]="default";
			$_default_value["sheet_name"]=date("d.m.Y");
			$_default_value["sheet_number"]="1";
			$jsonValueData = json_encode($_default_value);
			
			
			$_default_va["meta_type"]="values";
			$_default_va["is_active"]=1;
			$_default_va['goal_id'] = (!empty($_habit) && isset($_habit->id))?$_habit->id:"";
			$_default_va["user_id"]= $user_id;
			$_default_va['meta_key']=$_default_value['attr']."-".$_default_value['sheet_id'];
			$_default_va["meta_attr"] = $jsonValueData;

			$_default_statement["attr"]="statement";
			$_default_statement["auto_save_id"]=$post['auto_save_id'];
			$_default_statement["html"]="";
			$_default_statement["is_active"]="true";
			$_default_statement["sheet_id"]="default";
			$_default_statement["sheet_name"]=date("d.m.Y");
			$_default_statement["sheet_number"]="1";
			$jsonStatementData = json_encode($_default_statement);
			
				
			
			$_default_st["meta_type"]="statement";
			$_default_st["is_active"]=1;
			$_default_st['goal_id'] = (!empty($_habit) && isset($_habit->id))?$_habit->id:"";
			$_default_st["user_id"]= $user_id;
			$_default_st['meta_key']=$_default_statement['attr']."-".$_default_statement['sheet_id'];
			$_default_st["meta_attr"] = $jsonStatementData;

			$_default_value_array= array("meta_attr"=>$_default_value, "meta_value"=>"", "is_active"=>1,"meta_type"=>"values");
			$_default_statement_array = array("meta_attr"=>$_default_statement, "meta_value"=>"", "is_active"=>1,"meta_type"=>"statement");



			$_default['values'][] = $_default_value_array;
			$_default['statement'][] = $_default_statement_array;

			if(!empty($_habit)){

				
				$metas=StatementValues::get_all_attrs($_habit->id);

				$statements_values=array();

				$new_statement_values = array();

				foreach ($metas as $key => $meta) {
					
					$meta->meta_attr=json_decode($meta->meta_attr);
					$breaks = array("<br />","<br>","<br/>");  
    				$html = str_ireplace($breaks, "", $meta->meta_value); 

					$meta->meta_value = $html;

					
					$meta->meta_attr->is_active = ($meta->is_active)?'true':'false';

					$meta->meta_attr->html=str_ireplace($breaks, "", $meta->meta_attr->html); 
					
					$statements_values[$meta->meta_type][] = $meta;
					
				}

				//$new_statement_values = $statements_values;
				
				if(empty($statements_values))
				{
					StatementValues::add($_default_st);
					StatementValues::add($_default_va);
				}
				
				$data = ($statements_values)?$statements_values:$_default;
				/*echo "<pre/>";
				print_r($data['values']);die;*/

				if(!in_array("statement", array_column($data['statement'], 'meta_type')))
				{
					
					//$data['statement'] = "hiii";
					StatementValues::add($_default_st);
					//$statements_value[] = $statements_values;
					//$data['statement'] = $_default_statement_array;
				}
				
				if(!in_array("values", array_column($data['values'], 'meta_type')))
				{
					//$data['values'] = "hey man";
					StatementValues::add($_default_va);
					//$data['values'] = $_default_value_array;
				}

				//echo "<pre/>";
				//print_r($statements_values);die;
				return $this->success($data,'Get Statements and Values');
				//response()->json(['data'=>$data,'status'=>1,'msg'=>"Get Statements and Values",'error'=>""]);

			}else{

				return $this->error('Invalid Request.',404);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Invalid Request."]);

			}

		}else{

			return $this->error('Invalid Request.',404);
			//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Invalid Request."]);

		}

		//return \Response::json($response);
	}


	public function save_statementValue(Request $request)
	{
        $post = $request->all();
        $extras = $post['extras'];

		$validator =   Validator::make($request->all(),[
            'extras.*.sheet_id'       => 'required',
            'extras.*.sheet_number'    => 'required',
            'extras.*.sheet_name'=> 'required',
            'extras.*.attr'=> 'required',
            'auto_save_id'=>'required'
        ]);

        /* if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

        $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
        $user_id = $userDetails->id;

       $_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if(!empty($_habit)){
				$post_data=array();

				$new_post_data = array();

				if(!empty($extras))
		        {
					return $this->success($extras,'sdfds');
		        	foreach ($extras as $key => $value) {
		        		
		        		$post_data['meta_key']=$value['attr']."-".$value['sheet_id'];
				
						StatementValues::deactivate_sheets($_habit->id, $value['attr']);

						$html = $value['html'];

						$value['html']=$html;
						
						
						$post_data['meta_type']=$value['attr'];

						$post_data['meta_value']=$html;
						
						$post_data['goal_id']=$_habit['id'];
						
						$post_data['is_active']=1;

						$post_data['user_id']=$user_id;

						$post_data['meta_attr']=json_encode($value);

						$statement = StatementValues::add($post_data);


						$statement->meta_attr = json_decode($statement->meta_attr);
						

						$new_post_data[$value['attr']][] = $statement;	
		        	}

				return $this->success($new_post_data,'Goal statement sheet successfully added');
				//response()->json(['data'=>$new_post_data,'status'=>1,'msg'=>"Goal statement sheet successfully added",'error'=>""]);
		        }
		        else{

					return $this->error('Invalid request.',404);
					//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Invalid request."]);		
				}


			}else{

				return $this->error('Invalid request.',404);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Invalid request."]);		
			}
	}

	public function save_statement(Request $request){
		
		$validator =   Validator::make($request->all(),[
            'sheet_id'       => 'required',
            'sheet_number'    => 'required',
            'sheet_name'=> 'required',
            'attr'=> 'required'
        ]);

       /*  if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

		if($post=$request->input()){
			/*echo "<pre/>";
			print_r($post);die;*/
			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
            $user_id = $userDetails->id;

			$post_data=array();
				


				if(isset($post['extras']) && !empty($post['extras'])){
					StatementValues::process_staments_values($post['extras']);

					unset($post['extras']);
				}
				

				$post_data['meta_key']=$post['attr']."-".$post['sheet_id'];
				
				StatementValues::deactivate_Personalsheets($user_id);

				$html = $post['html'];

				
				//print_r($post);

				$post['html']=$html;
				
				
				$post_data['id']=isset($post['id'])?$post['id']:"";

				$post_data['meta_type']=$post['attr'];

				$post_data['meta_value']=$html;

				$post_data['show_in_lobby']= ($post['show_in_lobby'])?1:0;
				
				$post_data['goal_id']=0;
				
				$post_data['is_active']=1;

				$post_data['user_id']=$user_id;

				$post_data['meta_attr']=json_encode($post);

				if($post['show_in_lobby'] == 1)
				{
					StatementValues::updateShowInLobby($user_id);
				}

				
				$response=StatementValues::add_single_statement_by_meta_key($post_data);

				return $this->success($post_data,'Compass Saved Successfully');
				//response()->json(['data'=>$post_data,'status'=>1,'msg'=>"Compass Saved Successfully",'error'=>""]);
		}else{

				return $this->error('Somthing Went Wrong.',404);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Somthing Went Wrong"]);
		}
	}

	public function addto_lobby(Request $request){

		if($post=$request->input()){
			
			//$user_id=Auth::user()->guid;

			$data=StatementValues::get_by_id($post['id']);

			$data->show_in_lobby =($post['show_in_lobby'])?1:0;
			$data->save();

			$response = array(
					'status' => 1,
					'data' => $data,
					'msg' => 'Valid Habit.'
				);	

			return \Response::json($response);
		}
	}

	public function get_personal_statements_values(Request $request){
		
		
			$user_id=Auth::user()->guid;

			//$_habit=Goals::where("user_id",$user_id)->first();

			//print_r($_habit);

			$_default = array();
			$_default_statement=array();

			$_default_statement["attr"]="statement";
			//$_default_statement["auto_save_id"]=$post['auto_save_id'];
			$_default_statement["html"]="";
			$_default_statement["is_active"]="true";
			$_default_statement["sheet_id"]="default";
			$_default_statement["sheet_name"]=date("d.m.Y");
			$_default_statement["sheet_number"]="1";

			$_default=array(array("meta_attr"=>$_default_statement, "meta_value"=>"", "is_active"=>1));

			/*if($_habit){
*/
				$metas=StatementValues::get_all_personal_attrs($user_id);

				$statements_values=array();

				foreach ($metas as $key => $meta) {
					
					$meta->meta_attr=json_decode($meta->meta_attr);
					$breaks = array("<br />","<br>","<br/>");  
    				$html = str_ireplace($breaks, "", $meta->meta_value); 

					$meta->meta_value = $html;
					$meta->meta_attr->is_active = ($meta->is_active)?'true':'false';
					$meta->meta_attr->html=str_ireplace($breaks, "", $meta->meta_attr->html); 
					$statements_values[]=$meta;
				}
				
				$response = array(
					'status' => 1,
					'data' => ($statements_values)?$statements_values:$_default,
					'msg' => 'Meta Habit.'
				);

			/*}else{

				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);
			}*/

		return \Response::json($response);
	}

	public function personal_statement_attribute_get_sheet(Request $request){

		$validator =   Validator::make($request->all(),[
            'sheet_id'       => 'required',
            'sheet_number'    => 'required',
            'sheet_name'=> 'required',
            'attr'=> 'required'
        ]);

         /*if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

		if($post=$request->input()){
			//echo "<pre/>";
			//print_r($post);die;
			 $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
             $user_id = $userDetails->id;

			//$_habit=Goals::where("user_id",$user_id)->first();
				//$sheet_id = $post['sheet_id'];
				$meta_key=$post['attr']."-".$post['sheet_id'];
				
				StatementValues::deactivate_Personalsheets($user_id);


				$meta=StatementValues::getPersonalStatement($user_id, $meta_key);
				//echo "<pre/>";
				//print_r($meta);die;

				$meta->meta_attr=json_decode($meta->meta_attr);

				$breaks = array("<br />","<br>","<br/>");  
				
				$html = str_ireplace($breaks, "", $meta->meta_value); 

				$meta->meta_value = $html;

				//print_r($meta->meta_attr);

				$meta->meta_attr->html = str_ireplace($breaks, "", $meta->meta_attr->html); 

				$meta->meta_attr = json_encode($meta->meta_attr);
				
				if($meta){

					$meta->is_active=1;
					
					$meta->save();

				}

				return $this->success($meta,'Compass Sheet Fetched Successfully');
				//response()->json(['data'=>$meta,'status'=>1,'msg'=>"Compass Sheet Fetched Successfully",'error'=>""]);
				
		}else{
				return $this->error('Fetching Failed',404);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Fetching Failed"]);	
		}

	}


	public function attribute_delete_personal_sheet(Request $request){

		$validator =   Validator::make($request->all(),[
            'sheet_id'       => 'required',
            'sheet_number'    => 'required',
            'sheet_name'=> 'required',
            'attr'=> 'required'
        ]);

         /*if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

		if($post=$request->input()){

			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
            $user_id = $userDetails->id;

			//$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			/*if($_habit){
				*/
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::delete_personal_statement_meta($user_id, $meta_key);

				return $this->success($meta,'Compass Sheet Deleted Successfully');
				//response()->json(['data'=>$meta,'status'=>1,'msg'=>"Compass Sheet Deleted Successfully",'error'=>""]);
				
		}else{
				return $this->error('Sheet Deletion Failed',403);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Sheet Deletion Failed"]); 	
		}

	}
	
		public function attribute_duplicate_personal_sheet(Request $request){

		$validator =   Validator::make($request->all(),[
            'sheet_id'       => 'required',
            'sheet_number'    => 'required',
            'sheet_name'=> 'required',
            'attr'=> 'required'
        ]);

         /*if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }	*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }
		if($post=$request->input()){

			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
            $user_id = $userDetails->id;

			//$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			/*if($_habit){*/
				
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::getPersonalStatement($user_id, $meta_key);
				if(!empty($meta))
				{
					$_meta=$meta->replicate();

					$meta_attr = json_decode($_meta->meta_attr);
					//$metas=StatementValues::get_all_attrs($_habit->id);				
				    $sheetNumber = isset($post['sheet_number'])?$post['sheet_number']:rand(111,999);
					#print_r($meta_attr);
					//$sheetNumber = $post['sheet_number']+1;
					$new_sheet_id = rand(1111111111,9999999999);
					
					$meta_attr->sheet_id = $new_sheet_id;
					
					$meta_attr->sheet_number = $sheetNumber;
					
					$meta_attr->is_active = 0;

					$_meta->meta_key = $meta_attr->attr."-".$meta_attr->sheet_id;

					$_meta->meta_attr = json_encode($meta_attr);

					$_meta->is_active = 0;

					$_meta->save();

					return $this->success($_meta,'Compass Sheet Replica Done Successfully');
					//response()->json(['data'=>$_meta,'status'=>1,'msg'=>"Compass Sheet Replica Done Successfully",'error'=>""]);

				}
				else
				{
					return $this->error('Replica Failed',403);
					///response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Replica Failed"]);
				}
				

		}else{
				return $this->error('Replica Failed',403);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Replica Failed"]);
		}

	}

		public function attribute_personal_rename_sheet(Request $request){

			$validator =   Validator::make($request->all(),[
            'sheet_id'       => 'required',
            'sheet_number'    => 'required',
            'sheet_name'=> 'required',
            'attr'=> 'required'
        ]);

        /* if($validator->fails()){
         foreach ($validator->errors()->toArray() as $key => $value) {
               $response[]=$value;
         }
          return response()->json(['data'=>array(),'status'=>0,'msg'=>"Failed",'error'=>$response]);
        }*/
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }

		if($post=$request->input()){

			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
			
            $user_id = $userDetails->id;

			//$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			/*if($_habit){
				*/
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::getPersonalStatement($user_id, $meta_key);

				$meta_attr = json_decode($meta->meta_attr);
				
				$meta_attr->sheet_name = $post['sheet_name'];

				$meta->meta_attr = json_encode($meta_attr);

				$meta->save();

				return $this->success($meta,'Compass Sheet Renamed Successfully');
				//response()->json(['data'=>$meta,'status'=>1,'msg'=>"Compass Sheet Renamed Successfully",'error'=>""]);
		
		}else{
				return $this->error('Renamed Faild',403);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Renamed Faild"]);
		}

	}

	public function save_personl_statements_values(Request $request){
		
		$validator =   Validator::make($request->all(),[
            'sheet_id'       => 'required',
            'sheet_number'    => 'required',
            'sheet_name'=> 'required',
            'attr'=> 'required'
        ]);

       
		if($validator->fails()){
			return $this->error($validator->errors(), 403);
        }
		if($post=$request->input()){
			
			$userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);
            $user_id = $userDetails->id;

			//$_habit=Goals::where("user_id",$user_id)->first();

			/*if($_habit){
				*/
				$post_data=array();
				
				$post_data['meta_key']=$post['attr']."-".$post['sheet_id'];
				
				//StatementValues::deactivate_sheets($_habit->id, $post['attr']);
				StatementValues::deactivate_Personalsheets($user_id);
				$html = $post['html'];

				 $post['html']=$html;
				
				 $post_data['meta_attr']=json_encode($post);
				
				 $post_data['meta_type']=$post['attr'];

				 $post_data['meta_value']=$html;
				
				 
				if(isset($post['goal_id']))
				{
					$post_data['goal_id']= $post['goal_id'];
				}
				
				 $post_data['is_active']=1;

				 $post_data['user_id']=$user_id;
				//echo "<pre/>";
				//print_r($post_data);die;

				//exit();

				$add_compass_sheet = StatementValues::addPersonalStatement($post_data);	

				return $this->success($add_compass_sheet,'Compass Sheet Added Successfully');
				//response()->json(['data'=>$add_compass_sheet,'status'=>1,'msg'=>"Compass Sheet Added Successfully",'error'=>""]);

		}else{
				return $this->error('Not Added',403);
				//response()->json(['data'=>array(),'status'=>0,'msg'=>"",'error'=>"Not Added"]);	
		}

	}
}
<?php
namespace App\Http\Controllers;
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
use Auth;
use View;
use Config;
use DB;
use URL;
use App\Traits\ApiResponser;
use App\Repositories\IUserRepository;
use Validator;
class StatementValuesController extends Controller{
	
    use ApiResponser;
   
	public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request){

    	$characters=array();
    	$tasks=array();
    	$user_id=Auth::user()->id;
    	$statement_values=new StatementValues();
    	$statements=$statement_values->list("statement", $user_id);
    	$values=$statement_values->list("values", $user_id);
    	/*echo "<pre/>";
    	print_r($statements);die;*/
    	$sfilter=array();
    	$sfilter['user_id']=$user_id;
    	$sfilter['is_deleted']=0;
    	$sfilter['goal_id']=0;
    	$sfilter['meta_type']="statement";
        //$sfilter['show_in_lobby']=1;
    	$single_statement=$statement_values->get_lobby_statement($sfilter);
    	/*echo "<pre/>";
    	print_r($single_statement);die;*/

    	return view('statement-values.index', ['values'=>$values, 'statements'=>$statements, "single_statement"=>$single_statement]);
    }


    public function statement_view(Request $request){

    	$characters=array();
    	$tasks=array();
    	$user_id=Auth::user()->id;
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

		if($post=$request->input()){


			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::delete_meta($_habit->id, $meta_key);
			
				$response = array(
					'status' => 1,
					'data' => $meta,
					'msg' => 'Meta Habit.'
				);

			}else{
				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);		
			}
		}else{
			$response = array(
				'status' => 0,
				'data' => array(),
				'msg' => 'Invalid Request.'
			);	
		}

		return \Response::json($response);
	}


	public function attribute_get_sheet(Request $request){

		if($post=$request->input()){


			$user_id=Auth::user()->id;

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

				$response = array(
					'status' => 1,
					'data' => $meta,
					'msg' => 'Meta Habit.'
				);

			}else{
				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);		
			}
		}else{
			$response = array(
				'status' => 0,
				'data' => array(),
				'msg' => 'Invalid Request.'
			);	
		}

		return \Response::json($response);
	}


	public function attribute_duplicate_sheet(Request $request){

		if($post=$request->input()){

			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::get($_habit->id, $meta_key);
				
				$_meta=$meta->replicate();

				$meta_attr = json_decode($_meta->meta_attr);
				
				#print_r($meta_attr);

				$metas=StatementValues::get_all_attrs($_habit->id);

				
				$sheetNumber = count($metas->toArray());

				$new_sheet_id = rand(1111111111,9999999999);
				
				$meta_attr->sheet_id = $new_sheet_id;
				
				$meta_attr->sheet_number = $sheetNumber;
				
				$meta_attr->is_active = $_meta->is_active;

				$_meta->meta_key = $meta_attr->attr."-".$meta_attr->sheet_id;

				$_meta->meta_attr = json_encode($meta_attr);

				$_meta->is_active = 0;
				
				$_meta->save();

				$response = array(
					'status' => 1,
					'data' => $_meta,
					'msg' => 'Meta Habit.'
				);

			}else{
				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);		
			}
		}else{
			$response = array(
				'status' => 0,
				'data' => array(),
				'msg' => 'Invalid Request.'
			);	
		}

		return \Response::json($response);
	}
	
	
	public function attribute_rename_sheet(Request $request){

		if($post=$request->input()){

			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::get($_habit->id, $meta_key);

				$meta_attr = json_decode($meta->meta_attr);
				
				$meta_attr->sheet_name = $post['sheet_name'];

				$meta->meta_attr = json_encode($meta_attr);

				$meta->save();

				$response = array(
					'status' => 1,
					'data' => $meta,
					'msg' => 'Meta Habit.'
				);

			}else{
				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);		
			}
		}else{
			$response = array(
				'status' => 0,
				'data' => array(),
				'msg' => 'Invalid Request.'
			);	
		}

		return \Response::json($response);
	}

	public function save_statements_values(Request $request){
		
		if($post=$request->input()){

				//echo "<pre/>";
				//print_r($post);die;
			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				
				$post_data=array();
				
				$post_data['meta_key']=$post['attr']."-".$post['sheet_id'];
				
				StatementValues::deactivate_sheets($_habit->id, $post['attr']);

				//$html = nl2br($post['html']);

				//$post['html']=$post['html'];
				
				$post_data['meta_attr']=json_encode($post);
				
				$post_data['meta_type']=$post['attr'];

				$post_data['meta_value']=$post['html'];
				
				$post_data['goal_id']=$_habit['id'];
				
				$post_data['is_active']=1;

				$post_data['user_id']=$user_id;

				//print_r($post_data);

				//exit();

				StatementValues::add($post_data);

				$response = array(
					'status' => 1,
					'data' => $post,
					'msg' => 'Valid Habit.'
				);	

			}else{
				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);		
			}

		}else{
				$response = array(
					'status' => 0,
					'data' => array(),
					'msg' => 'Invalid Request.'
				);	
		}

		return \Response::json($response);
	}

	public function get_statements_values(Request $request){
		
		if($post=$request->input()){
			
			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			//print_r($_habit);

			$_default = array();
			$_default_value=array();
			$_default_statement=array();

			$_default_value["attr"]="values";
			$_default_value["auto_save_id"]=$post['auto_save_id'];
			$_default_value["html"]="values";
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
			$_default_statement["html"]="statement";
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

			$_default_value_array = array("meta_attr"=>$_default_value, "meta_value"=>"", "is_active"=>1,"meta_type"=>"values");
			$_default_statement_array = array("meta_attr"=>$_default_statement, "meta_value"=>"", "is_active"=>1,"meta_type"=>"statement");

			$_default=array($_default_value_array, $_default_statement_array);

			if($_habit){
				
				$metas=StatementValues::get_all_attrs($_habit->id);
				
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
				// echo "<pre/>";
				// print_r($statements_values);die;
				
				if(empty($statements_values))
				{
					StatementValues::add($_default_st);
					StatementValues::add($_default_va);
				}
				
				$data = ($statements_values)?$statements_values:$_default;

				if(!in_array("statement", array_column($data, 'meta_type')))
				{
					
					StatementValues::add($_default_st);
					//$statements_value[] = $statements_values;
					$data[] = $_default_statement_array;
				}
				
				if(!in_array("values", array_column($data, 'meta_type')))
				{
					StatementValues::add($_default_va);
					$data[] = $_default_value_array;
				}

				//echo "<pre/>";
				//print_r($statements_values);die;
				
				$response = array(
					'status' => 1,
					'data' => $data,
					'msg' => 'Meta Habit.'
				);

			}else{

				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);
			}

		}else{
			$response = array(
				'status' => 0,
				'data' => array(),
				'msg' => 'Invalid Request.'
			);	
		}

		return \Response::json($response);
	}

	public function save_statement(Request $request){

		if($post=$request->input()){
			$post = $request->all();
			//echo "<pre/>";
			//print_r($post);die;
			$user_id=Auth::user()->id;

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
				
				$post_data['meta_attr']=json_encode($post);
				
				$post_data['id']=$post['id'];

				$post_data['meta_type']=$post['attr'];

				$post_data['meta_value']=$html;

				$post_data['show_in_lobby']= ($post['show_in_lobby'])?1:0;
				
				$post_data['goal_id']=0;
				
				$post_data['is_active']=1;

				$post_data['user_id']=$user_id;
				if($post['show_in_lobby'] == 1)
				{
					StatementValues::updateShowInLobby($user_id);
				}
				//print_r($post_data);
				$response=StatementValues::add_single_statement_by_meta_key($post_data);

				$response = array(
					'status' => 1,
					'data' => $response,
					'msg' => 'Valid Habit.'
				);	

			return \Response::json($response);
		}
	}

	public function addto_lobby(Request $request){

		if($post=$request->input()){
			
			$user_id=Auth::user()->id;

			$data=StatementValues::get_bysheet_id($post['id']);

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
		
		
			$user_id=Auth::user()->id;

			//$_habit=Goals::where("user_id",$user_id)->first();

			//print_r($_habit);

			$_default = array();
			$_default_statement=array();

			$_default_statement["attr"]="statement";
			//$_default_statement["auto_save_id"]=$post['auto_save_id'];
			$_default_statement["html"]="statement";
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

		if($post=$request->input()){
			//echo "<pre/>";
			//print_r($post);die;
			 $user_id=Auth::user()->id;

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

				$response = array(
					'status' => 1,
					'data' => $meta,
					'msg' => 'Meta Habit.'
				);

			
		}else{
			$response = array(
				'status' => 0,
				'data' => array(),
				'msg' => 'Invalid Request.'
			);	
		}

		return \Response::json($response);
	}


	public function attribute_delete_personal_sheet(Request $request){

		if($post=$request->input()){

			$user_id=Auth::user()->id;

			//$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			/*if($_habit){
				*/
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::delete_personal_statement_meta($user_id, $meta_key);

				$response = array(
					'status' => 1,
					'data' => $meta,
					'msg' => 'Meta Habit.'
				);

			/*}else{
				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);		
			}*/
		}else{
			$response = array(
				'status' => 0,
				'data' => array(),
				'msg' => 'Invalid Request.'
			);	
		}

		return \Response::json($response);
	}
	
		public function attribute_duplicate_personal_sheet(Request $request){

		if($post=$request->input()){

			$user_id=Auth::user()->id;

			//$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			/*if($_habit){*/
				
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::getPersonalStatement($user_id, $meta_key);
				
				if(empty($meta))
				{
					$response = array(
								'status' => 0,
								'data' => array(),
								'msg' => 'Invalid Request.'
							);	
					return \Response::json($response);
				}

				$_meta=$meta->replicate();

				$meta_attr = json_decode($_meta->meta_attr);
				
				#print_r($meta_attr);
				$metas=StatementValues::get_all_personal_attrs($user_id);

				
				$sheetNumber = count($metas->toArray());
				$new_sheet_id = rand(1111111111,9999999999);
				
				$meta_attr->sheet_id = $new_sheet_id;
				
				$meta_attr->sheet_number = $sheetNumber+1;
				
				$meta_attr->is_active = 0;

				$_meta->meta_key = $meta_attr->attr."-".$meta_attr->sheet_id;

				$_meta->meta_attr = json_encode($meta_attr);

				$_meta->is_active = 0;

				$_meta->save();

				$response = array(
					'status' => 1,
					'data' => $_meta,
					'msg' => 'Meta Habit.'
				);

			/*}else{
				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);		
			}*/
		}else{
			$response = array(
				'status' => 0,
				'data' => array(),
				'msg' => 'Invalid Request.'
			);	
		}

		return \Response::json($response);
	}

		public function attribute_personal_rename_sheet(Request $request){

		if($post=$request->input()){

			$user_id=Auth::user()->id;

			//$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			/*if($_habit){
				*/
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=StatementValues::getPersonalStatement($user_id, $meta_key);

				$meta_attr = json_decode($meta->meta_attr);
				
				$meta_attr->sheet_name = $post['sheet_name'];

				$meta->meta_attr = json_encode($meta_attr);

				$meta->save();

				$response = array(
					'status' => 1,
					'data' => $meta,
					'msg' => 'Meta Habit.'
				);

			/*}else{
				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);		
			}*/
		}else{
			$response = array(
				'status' => 0,
				'data' => array(),
				'msg' => 'Invalid Request.'
			);	
		}

		return \Response::json($response);
	}

	public function save_personl_statements_values(Request $request){
		
		if($post=$request->input()){
			//echo "<pre/>";
			//print_r($post);die;
			$user_id=Auth::user()->id;

			//$_habit=Goals::where("user_id",$user_id)->first();

			/*if($_habit){
				*/
				$post_data=array();
				
				$post_data['meta_key']=$post['attr']."-".$post['sheet_id'];
				
				//StatementValues::deactivate_sheets($_habit->id, $post['attr']);
				StatementValues::deactivate_Personalsheets($user_id);
				$html = (isset($post['html']) && !empty($post['html']))?$post['html']:"";

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

				StatementValues::addPersonalStatement($post_data);

				$response = array(
					'status' => 1,
					'data' => $post,
					'msg' => 'Valid Habit.'
				);	

			/*}else{
				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);		
			}*/

		}else{
				$response = array(
					'status' => 0,
					'data' => array(),
					'msg' => 'Invalid Request.'
				);	
		}

		return \Response::json($response);
	}


		public function get_statements_valuesByAttr(Request $request){
		
		if($post=$request->input()){
			
			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			//print_r($_habit);

			$_default = array();
			$_default_value=array();
			$_default_statement=array();

			$_default_value["attr"]=$post['attr'];
			$_default_value["auto_save_id"]=$post['auto_save_id'];
			$_default_value["html"]="";
			$_default_value["is_active"]="true";
			$_default_value["sheet_id"]="default";
			$_default_value["sheet_name"]=date("d.m.Y");
			$_default_value["sheet_number"]="1";
			$jsonValueData = json_encode($_default_value);
			
			
			$_default_va["meta_type"]=$post['attr'];
			$_default_va["is_active"]=1;
			$_default_va['goal_id'] = (!empty($_habit) && isset($_habit->id))?$_habit->id:"";
			$_default_va["user_id"]= $user_id;
			$_default_va['meta_key']=$_default_value['attr']."-".$_default_value['sheet_id'];
			$_default_va["meta_attr"] = $jsonValueData;

			$_default_value_array = array("meta_attr"=>$_default_value, "meta_value"=>"", "is_active"=>1,"meta_type"=>$post['attr']);

			$_default[] = $_default_value_array;

			if($_habit){
				
				$metas=StatementValues::get_all_sheetsByattrs($_habit->id,$post['attr']);
				
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
				// echo "<pre/>";
				// print_r($statements_values);die;
				
				if(empty($statements_values))
				{
					StatementValues::add($_default_va);
				}
				
				$data = ($statements_values)?$statements_values:$_default;

				//echo "<pre/>";
				//print_r($statements_values);die;
				
				$response = array(
					'status' => 1,
					'data' => $data,
					'msg' => 'Meta Habit.'
				);

			}else{

				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Habit.'
				);
			}

		}else{
			$response = array(
				'status' => 0,
				'data' => array(),
				'msg' => 'Invalid Request.'
			);	
		}

		return \Response::json($response);
	}



	public function getStatementAndValues(Request $request){

    	
    	$user_id=Auth::user()->id;
    	$statement_values=new StatementValues();
    	$statements=$statement_values->listData($user_id);
    	
    	if(isset($statements) && !empty($statements)){
    	$response = array(
					'status' => 1,
					'data' => $statements,
					'msg' => 'Meta Habit.'
				);

    }else{
    	$response = array(
					'status' => 0,
					'data' => array(),
					'msg' => 'No Data found.'
				);
    }

    	return \Response::json($response);
    }



}
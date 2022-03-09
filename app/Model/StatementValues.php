<?php
namespace App\Model;
use App\Model\Types;
use App\Model\HabitTypes;
use App\Model\Logs;
use App\Model\Goals;
use Illuminate\Database\Eloquent\Model;
use Config;
use Carbon\Carbon;
use DB;

class StatementValues extends Model
{
	
	protected $table = 'tbl_goal_statement_values';
	protected $tbl_goals = "tbl_goals";
	protected $guarded = [
	        'id',
	    ];


	public static function add($data){
    	
    	$data['is_deleted']=0;
    	
    	$meta=Self::updateOrCreate(['goal_id'=>$data['goal_id'], 'meta_key'=>$data['meta_key']],$data);
    	
    	return $meta;
	}

	public static function addPersonalStatement($data){
    	
    	$data['is_deleted']=0;
    	
    	$meta=Self::updateOrCreate(['goal_id'=>0,'meta_key'=>$data['meta_key']],$data);
    	//echo "<pre/>";
    	//print_r($meta);die;
    	return $meta;
	}

	public static function getPersonalStatement($user_id, $meta_key){
		
		$meta=Self::where(['user_id'=>$user_id, "meta_key"=>$meta_key, "is_deleted"=>0])->first();

		//echo "<pre/>";
		//print_r($meta);die;
		return $meta;
	}

	public static function get_all_personal_attrs($user_id){

		$metas=Self::where(['user_id'=> $user_id, "is_deleted"=>0,"meta_type"=>"statement","goal_id"=>0])->get();
		
		return $metas;
	}

	public static function delete_personal_statement_meta($user_id, $meta_key){
		
		#print_r(["goal_id"=>$goal_id, "meta_key"=>$meta_key]);

		$meta=Self::where(["user_id"=>$user_id, "meta_key"=>$meta_key])->first();
		
		$meta->is_deleted=1;

		$meta->save();
		
		return $meta;
	}

	public static function add_single_statement($data){
    	
    	$data['is_deleted']=0;
    	
    	$meta=Self::updateOrCreate(['sheet_id'=>$data['sheet_id']],$data);
    	
    	return $meta;
	}

	public static function add_single_statement_by_meta_key($data){
    	
    	$data['is_deleted']=0;
    	
    	$meta=Self::updateOrCreate(['meta_key'=>$data['meta_key']],$data);
    	
    	return $meta;
	}


	public static function get($goal_id, $meta_key){
		
		$meta=Self::where(['goal_id'=>$goal_id, "meta_key"=>$meta_key, "is_deleted"=>0])->first();

		return $meta;
	}

	public static function get_bysheet_id($attr_id){
		$meta_key = "statement-".$attr_id;
		$meta=Self::where(['meta_key'=>$meta_key,'is_deleted'=>0])->first();
		return $meta;
	}
	
	public static function get_by_id($attr_id){
		$meta=Self::where(['id'=>$attr_id])->first();
		return $meta;
	}

	public static function get_all_attrs($goal_id){

		$metas=Self::where(['goal_id'=> $goal_id, "is_deleted"=>0])->get();
		
		return $metas;
	}

	

	public static function process_attr($goal_id, $attr){
		
		//DB::enableQueryLog();
		
		$metas=Self::select("meta_attr", "is_active")->where(['goal_id'=> $goal_id, "is_deleted"=>0])->whereRaw("meta_key like '%".$attr."%'")->get();
		
		//print_r(DB::getQueryLog());exit();

		$attributes=false;

		if(!$metas->isEmpty()){
			$attributes=array();
			
			foreach ($metas as $key => $meta) {

				$attrs = json_decode($meta->meta_attr, true);
				
				$attrs['is_active']=$meta['is_active'];
				
				$attrs['html']=clean_html(nl2br($attrs['html']));
				
				$attributes[]=$attrs;
			}
		}

		return $attributes;
	}

	public static function get_by_attr($goal_id, $attr){
		
		//DB::enableQueryLog();
		
		$metas=Self::process_attr($goal_id, $attr);//Self::select("meta_attr")->where(['goal_id'=> $goal_id, "is_deleted"=>0])->whereRaw("meta_key like '%".$attr."%'")->get();
		
		//print_r(DB::getQueryLog());exit();

		$attributes=array();

		if($metas){

			$attributes=$metas;

		}else{

			$meta_key = $attr."-default";
			
			$goal_id = $goal_id;
			
			$goal = Goals::where(["is_delete"=>0,'id'=>$goal_id])->first();
			
			$html = clean_html(nl2br($goal->{$attr}));

			if(!Self::get($goal_id, $meta_key)){
				
				$data['goal_id'] = $goal_id;
				
				$data['is_active'] = 1;

				$data['meta_key'] = $meta_key;
				
				$data['meta_value'] = $html;
				
				$data['meta_attr'] = json_encode(
					array("goal_id"=>$goal_id, "html"=>$html, 'is_active'=>1, "sheet_id"=>"default", "sheet_number"=>1, "attr"=>$attr, "auto_save_id"=> $goal->auto_save_id,"sheet_name"=>date("d.m.Y",strtotime($goal->created_at)))
				);

				//print_r($data);

				Self::add($data);

			}

			$attributes=Self::process_attr($goal_id, $attr);
		}


		return $attributes;
	}

	public static function delete_meta($goal_id, $meta_key){
		
		#print_r(["goal_id"=>$goal_id, "meta_key"=>$meta_key]);

		$meta=Self::where(["goal_id"=>$goal_id, "meta_key"=>$meta_key])->first();
		
		$meta->is_deleted=1;

		$meta->save();
		
		return $meta;
	}

	

	public static function deactivate_sheets($goal_id, $attr){

		return Self::where(['goal_id'=> $goal_id])->whereRaw("meta_key like '%".$attr."%'")->update(['is_active'=>0]);

	}


	public static function deactivate_Personalsheets($user_id){

		return Self::where('goal_id',0)->where("user_id",$user_id)->update(['is_active'=>0]);

	}

	public function list($type, $user_id){

		$metas=Self::select($this->tbl_goals.".*", $this->table.".meta_type")->where([$this->table.".is_deleted"=>0, $this->table.".meta_type"=>$type])
		 ->join($this->tbl_goals, $this->tbl_goals.'.id', '=', $this->table.'.goal_id')->where([$this->tbl_goals.'.is_delete'=>0, $this->tbl_goals.".parent_id"=>0, $this->tbl_goals.'.user_id'=>$user_id])->groupBy($this->table.".goal_id")->get();

		 $goals=array();

		 foreach ($metas as $key => $meta) {
		 	$meta->statement_values=$this->get_last_statement_values($meta->id, $meta->meta_type);
		 	$goals[]=$meta;
		 }

		return $goals;
	}


	public function listData($user_id){

		$metas=Self::select($this->tbl_goals.".*", $this->table.".meta_type")->where([$this->table.".is_deleted"=>0])
		 ->join($this->tbl_goals, $this->tbl_goals.'.id', '=', $this->table.'.goal_id')->where([$this->tbl_goals.'.is_delete'=>0, $this->tbl_goals.".parent_id"=>0, $this->tbl_goals.'.user_id'=>$user_id])->groupBy($this->table.".goal_id")->get();

		 $goals=array();

		 foreach ($metas as $key => $meta) {
		 	$statement_values=$this->get_last_statementAndvalues($meta->id);
		 	foreach ($statement_values as $value) {
		 	$goals[]=$value;
		 	
		 	}
		 }

		return $goals;
	}

	public function get_last_statement_values($goal_id, $meta_type){
		$meta=Self::where(['goal_id'=> $goal_id, "meta_type"=>$meta_type, "is_deleted"=>0,"is_active"=>1])->orderBy("id", "DESC")->first();
		/*echo "<pre/>";
		print_r($meta);die;*/
		return $meta;
	}


	public function get_last_statementAndvalues($goal_id){
		$meta=Self::where(['goal_id'=> $goal_id, "is_deleted"=>0,"is_active"=>1])->orderBy("id", "DESC")->get();
		/*echo "<pre/>";
		print_r($meta);die;*/
		return $meta;
	}

	public static function get_lobby_statement($filter){
		
		$metas=Self::where($filter)->where('meta_value','!=','')->where('is_active',1)->orderBy("id", "DESC")->first();

		return $metas;
	}

	public static function get_lobby_values($filter){
		$result = array();
		$metas=Self::where($filter)->where('meta_value','!=','')->where('is_active',1)->orderBy("id", "DESC")->get();
		if(isset($metas) && !empty($metas)){
			foreach($metas as $meta){
			if(isset($meta->goal_id) && !empty($meta->goal_id)){
					$goalData =	DB::table('tbl_goals')->where('id',$meta->goal_id)->where('is_delete',0)->first();
					if(isset($goalData) && !empty($goalData)){
						$meta->auto_save_id = $goalData->auto_save_id;
						$result[] = $meta;
					}
				}else{
					$meta->auto_save_id = "";
					$result[] = $meta;
				}

			}
		}
		
		return $result;
	}

	public static function process_staments_values($stament_values){
		
		foreach ($stament_values as $key => $stament_value) {
			
			$row=Self::get_by_id($stament_value['id']);
			
			$row->meta_value=$stament_value['html'];

			$attr=json_decode($row->meta_attr);
			
			$attr->html=$stament_value['html'];

			$row->show_in_lobby=$stament_value['addto_lobby'];

			$row->meta_attr=json_encode($attr);

			$row->save();
		}
		
	}


	public static function updateShowInLobby($user_id)
	{
		$result = DB::table("tbl_goal_statement_values")->where("user_id",$user_id)->where("goal_id",0)->update(["show_in_lobby"=>0]);
		return $result;
	}



	public static function get_all_sheetsByattrs($goal_id,$attr){

		$metas=Self::where(['goal_id'=> $goal_id, "meta_type"=>$attr, "is_deleted"=>0])->get();
		
		return $metas;
	}
}
<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Config;
use Carbon\Carbon;
use App\Model\Goals;
use DB;

class GoalsMeta extends Model
{
	
	protected $table = 'tbl_goal_meta';

	protected $guarded = [
        'id',
    ];



    public static function add($data){
    	
    	$data['is_deleted']=0;
    	
    	$meta=Self::updateOrCreate(['goal_id'=>$data['goal_id'], 'meta_key'=>$data['meta_key']],$data);
    	
    	return $meta;
	}

	public static function get_prefill_status_data($goal_id){
		
		$meta_key = "addtomylist_id";
		
		$data = Self::get($goal_id, $meta_key);

		if($data){
			return $data;
		}
	}

	public static function get($goal_id, $meta_key){
		
		$meta=Self::where(['goal_id'=>$goal_id, "meta_key"=>$meta_key, "is_deleted"=>0])->get()->first();
		

		return $meta;
	}

	public static function get_by_id($attr_id){
		$meta=Self::where(['id'=>$attr_id])->get()->first();
		return $meta;
	}

	public static function get_all_attrs($goal_id){

		$metas=Self::where(['goal_id'=> $goal_id, "is_deleted"=>0])->get();
		
		return $metas;
	}

	public static function process_attr($goal_id, $attr){
		
		$attr1 = $attr."-";
		//DB::enableQueryLog();
		$metas=Self::select("meta_attr", "is_active")->where(['goal_id'=> $goal_id, "is_deleted"=>0])->whereRaw("meta_key like '%".$attr1."%'")->get();

		$sheetNum = sizeof($metas);
		
		$checkActive = 1;
		//print_r(DB::getQueryLog());exit();
		$attributes=false;

		if(!$metas->isEmpty()){

			$attributes=array();

			
			
			foreach ($metas as $key => $meta) {

				$new_key = $key+1;
				
				$attrs = json_decode($meta->meta_attr, true);

				if($meta['is_active'] == 1){
					$checkActive = 0;
				}

				$attrs['is_active']= $meta['is_active'];
				if($sheetNum == $new_key)
				  {

					if($checkActive ==1){
				
						$attrs['is_active']=1;
					}
				}
				
				$attrs['html']=isset($attrs['html'])?clean_html(nl2br($attrs['html'])):'';
				$attrs['goal_id'] = $goal_id;
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
			
			$goal = Goals::where(["is_delete"=>0,'id'=>$goal_id])->get()->first();
			
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

		$meta=Self::where(["goal_id"=>$goal_id, "meta_key"=>$meta_key])->get()->first();
		
		$meta->is_deleted=1;

		$meta->save();
		
		return $meta;
	}

	public static function deactivate_sheets($goal_id, $attr){
		return Self::where(['goal_id'=> $goal_id])->whereRaw("meta_key like '%".$attr."%'")->update(['is_active'=>0]);
	}
}

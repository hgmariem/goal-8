<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Def_goals extends Model
{
	
protected $table = 'tbl_default_goals';

protected $guarded = [
        'id',
    ];
	
	 public function add_Def_goals($post){
	   //print_r($post); exit();
	   
	  
	   $this->name=$post['name'];
	   
	   $this->due_date=$post['due_date'];
	   $this->habit_start_date=$post['habit_start_date'];
	   $this->status=$post['status'];
	   $this->improvement=$post['improvement'];
	   $this->risk=$post['risk'];
	   $this->benefits=$post['benefits'];
	   $this->vision=$post['vision'];
	   $this->vision_decades=$post['vision_decades'];
	   $this->barriers=$post['barriers'];
	   $this->priority=$post['priority'];
	   $this->initiative=$post['initiative'];
	   $this->help=$post['help'];
	   $this->support=$post['support'];
	   $this->environment=$post['environment'];
	   $this->imagery=$post['imagery'];
	   
	   
	   if($this->save()){
		   return $this->id;
		   
	   }else{
		   return false;
	   } 
    }
	 
	public function get_Def_goals(){
		$get =  Self::where(['is_delete'=>0])->get();
		//print_r(); exit();
		if($get){
			return $get;
		}
		else{
			return false;
		}
	} 
	
	public function get_by_id($id){
		$get =  Self::where('id',$id)->first();
		
		if($get){
			return $get;
		}
		else{
			return false;
		}
	}

	public function edit_Def_goals($post){
	   
	   $update=Self::find($post['id']);
	  
	   $update->name=$post['name'];
	   
	   $update->due_date=$post['due_date'];
	    //print_r( $update->due_date); exit();
	   $update->habit_start_date=$post['habit_start_date'];
	   $update->status=$post['status'];
	   $update->improvement=$post['improvement'];
	   $update->risk=$post['risk'];
	   $update->benefits=$post['benefits'];
	   $update->vision=$post['vision'];
	   $update->vision_decades=$post['vision_decades'];
	   $update->barriers=$post['barriers'];
	   $update->priority=$post['priority'];
	   $update->initiative=$post['initiative'];
	   $update->help=$post['help'];
	   $update->support=$post['support'];
	   $update->environment=$post['environment'];
	   $update->imagery=$post['imagery'];
	   //print_r($update->save()); exit();
		if($update->save()){
			return true;
		}else{
			return false;
		}
	}

	public function get_child_goals($goal_id){
		
		$goals =  Self::where(["is_delete"=>0,'parent_id'=>$goal_id])->orderBy("id", "ASC")->get()->toArray();
		
		$_goals=array();
		
		if($goals){
			
			foreach($goals as $goal){

				$children=$this->get_child_goals($goal['id']);
				
				if($children){
					$goal['type']=Self::get_type_by_id($goal['type_id']);
					$goal['habit_types']=Self::get_habit_types($goal['id']);
					$goal['children']=$children;
				}

				$_goals[]=$goal;
			}
		}

		return $_goals;
	}

	public function _get_goal($goal_id){

		$goal =  Self::where(["is_delete"=>0,'id'=>$goal_id])->first()->toArray();
		
		if($goal && $children=$this->get_child_goals($goal['id'])){
			$goal['type']=Self::get_type_by_id($goal['type_id']);
			//$goal->type=Self::get_type_by_id($goal->type_id);
			$goal['habit_types']=Self::get_habit_types($goal['id']);
			$goal['children']=$children;
		}
		return $goal;
	}
	
	public static function get_type_by_id($type_id){
		return $type=Types::where("id",$type_id)->first()->toArray();
	}	
	
    public static function get_habit_types($goal_id){
		$data=HabitTypes::where("goal_id", $goal_id)->first();
		$data=($data)?$data->toArray():array();
		return $data;
	}

	public function get_parent_goals(){
		$goals =  Self::where(["is_delete"=>0,'parent_id'=>null,"top_parent_id"=>null])->orderBy("detail_order", "ASC")->get()->toArray();
		return $goals;
	}


}	
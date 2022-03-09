<?php
namespace App;
use App\Model\Types;
use App\Model\HabitTypes;
use App\Model\Logs;
use App\Model\GoalsMeta;
use Illuminate\Database\Eloquent\Model;
use Config;
use Carbon\Carbon;
use DB;

class Goals extends Model
{
	
protected $table = 'tbl_goals';

protected $guarded = [
        'id',
    ];

//protected $dateFormat = 'Y-m-d';	
	
 //protected $dates = ['habit_start_date'];	
	
/*
   public function add_goals($post){
	  
	   $this->name=$post['name'];
	 
	   $this->due_date=date("y/m/d",strtotime($post['due_date']));
	 
	   $this->habit_start_date=date("y/m/d",strtotime($post['habit_start_date']));
	   
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
	   $this->auto_save_id=$post['auto_save_id'];
	  
	   if($this->save()){
		   return $this->id;
		   
	   }else{
		   return false;
	   } 
   }*/
	
	public function add_goals($data){
		
		if(empty($data['name'])){
			return false;
		}

		#print_r($data['auto_save_id']);

		//DB::enableQueryLog();

		if(!$this->get_goal_by_autosave($data['auto_save_id'])){

			if(!isset($data['list_order'])){
				$data['list_order']=$this->get_max_list_order($data['user_id'])+1; // default max number
			}

			if(!isset($data['self_order'])){
		    	$data['self_order']=$this->get_max_self_order($data['user_id'])+1; // default max number
			}
		}

		$goal=Self::updateOrCreate(['auto_save_id'=>$data['auto_save_id']],$data);

		//print_r(DB::getQueryLog());exit();
		/*
		if(196==$data['auto_save_id']){
			print_r($data);
		}*/

		/*$goal=Self::where('auto_save_id',$data['auto_save_id'])->first();
		
		if(!$goal){
			$goal = new static($data);
		}
		
		$goal->fill($data);
		
		$goal->save();*/

		return $goal->id;
	}
	
	public function get_goal_by_autosave($auto_save_id){

		$goal=Self::where('auto_save_id',$auto_save_id)->first();
		if($goal){
			return $goal;	
		}

		return false;
	}

    public function get_goals($user_id,$is_default=0){
    	
    	$default_filter=['is_delete'=>0, "parent_id"=>0, "top_parent_id"=>0, "type_id"=>Config::get('constants.goal_undefined'), "user_id"=>$user_id, "is_default"=>$is_default];
    	
    	if($is_default){
    		unset($default_filter['user_id']);
    	}

		$get =  Self::where($default_filter)->orderBy("list_order", "DESC")->get();
		if($get){ 
			return $get;
		}
		else{
			return false;
		}
	}
	
	public function get_recursive_goals($goal_id){
		
	}


	 
	public function get_child_goals($goal_id){
		$goals =  Self::where(["is_active"=>1, "is_delete"=>0,'parent_id'=>$goal_id])->orderBy("detail_order", "ASC")->get();
		$_goals=array();
		
		if($goals){
			
			foreach($goals as $goal){

				$children=$this->get_child_goals($goal->id);
				$goal->type=Self::get_type_by_id($goal->type_id);
				$goal->habit_types=Self::get_habit_types($goal->id);
				if($children){
					$goal->children=$children;
				}

				$_goals[]=$goal;
			}
		}

		return $_goals;
	}

	public function get_child_goals_by_top_ParentId($user_id,$goal_id){
		$goals =  Self::where(["user_id"=>$user_id, "is_delete"=>0,'top_parent_id'=>$goal_id])->get();
		return $goals;
	}
	
	public function _get_child_goals($goal_id){
		$goals =  Self::where(["is_delete"=>0,'parent_id'=>$goal_id])->orderBy("detail_order", "ASC")->get();
		$_goals=array();
		
		if($goals){
			
			foreach($goals as $goal){

				$children=$this->_get_child_goals($goal->id);
				$goal->type=Self::get_type_by_id($goal->type_id);
				$goal->habit_types=Self::get_habit_types($goal->id);
				if($children){
					$goal->children=$children;
				}

				$_goals[]=$goal;
			}
		}

		return $_goals;
	}
	
	
	public function render_goals($goal_id){
		$goal=$this->get_goal($goal_id);
		if($goal && $goal->children){
			//$goal->html=$this->generate_sub_goal_tree($goal->children);
		}
		return $goal;
	}
	
	public function get_goal($goal_id){
		$goal =  Self::where(["is_active"=>1, "is_delete"=>0,'id'=>$goal_id])->first();
		
		if($goal && $children=$this->get_child_goals($goal->id)){
			$goal->type=Self::get_type_by_id($goal->type_id);
			//$goal->type=Self::get_type_by_id($goal->type_id);
			$goal->habit_types=Self::get_habit_types($goal->id);
			$goal->children=$children;
		}
		return $goal;
	}

	public function getGoalTypes($user_id)
	{
		$default_filter=['is_delete'=>0, "is_end"=>0, "is_active"=>1, "is_show_lobby"=>1, "parent_id"=>0,"top_parent_id"=>0, "user_id"=>$user_id];
		$result = Self::where($default_filter)->get();
		return $result;
	}

	public function get_Alltask_list(){
		$default_filter=['is_delete'=>0, "is_end"=>0, "is_active"=>1, "is_show_lobby"=>1, "type_id"=>Config::get('constants.goal_task')];
		$tasks =  Self::where($default_filter)->orderBy("due_date", "ASC")->get();
		return $tasks;
	}
	public function _get_goal($goal_id){
		//echo $goal_id;die;
		$goal =  Self::where(["is_delete"=>0,'id'=>$goal_id])->first();
		
		if($goal){
			
			$goal->type=Self::get_type_by_id($goal->type_id);
			//$goal->type=Self::get_type_by_id($goal->type_id);
			$goal->habit_types=Self::get_habit_types($goal->id);
			$children=$this->_get_child_goals($goal->id);

			if($children){
				$goal->children=$children;
			}
		}

		return $goal;
	}
	

	public function _get_goal_attributes($goal_id){

		$goal = $this->_get_goal($goal_id);
		if(empty($goal))
		{
			return false;
		}
		$attributes=array();
		
		$default_attr=array("auto_save_id"=>$goal->auto_save_id, "sheet_id"=>"default", "sheet_number"=>1, "is_active"=>1, "sheet_name"=>date("d.m.Y",strtotime($goal->created_at)));
		
		//if($goal->status){
			
			$statuss = GoalsMeta::get_by_attr($goal_id, "status");

			if($statuss){
				$attributes['status']=$statuss;
			}else{
				$default_attr['attr']="status";
				$default_attr['html']=html_entity_decode(stripcslashes($goal->status));
				$attributes['status']=array($default_attr);
			}
		//}

		//if($goal->improvement){
			$improvements = GoalsMeta::get_by_attr($goal_id, "improvement");
			if($improvements){
				$attributes['improvement']=$improvements;
			}else{
				$default_attr['attr']="improvement";
				$default_attr['html']=$goal->improvement;
				$attributes['improvement']=array($default_attr);
			}
		//}

		//if($goal->risk){
			$risks = GoalsMeta::get_by_attr($goal_id, "risk");
			if($risks){
				$attributes['risk']=$risks;
			}else{
				$default_attr['attr']="risk";
				$default_attr['html']=$goal->risk;
				$attributes['risk']=array($default_attr);
			}
		//}

		//if($goal->benefits){
			$benefitss = GoalsMeta::get_by_attr($goal_id, "benefits");
			if($benefitss){
				$attributes['benefits']=$benefitss;
			}else{
				$default_attr['attr']="benefits";
				$default_attr['html']=$goal->benefits;
				$attributes['benefits']=array($default_attr);
			}
		//}

		//if($goal->vision){
			$visions = GoalsMeta::get_by_attr($goal_id, "vision");
			//echo "<pre/>";
			//print_r($visions);die;
			if($visions){
				$attributes['vision']=$visions;
			}else{
				$default_attr['attr']="vision";
				$default_attr['html']=$goal->vision;
				$attributes['vision']=array($default_attr);
			}
		//}


		//if($goal->vision_decades){
			$vision_decadess = GoalsMeta::get_by_attr($goal_id, "vision_decades");
			if($vision_decadess){
				$attributes['vision_decades']=$vision_decadess;
			}else{
				$default_attr['attr']="vision_decades";
				$default_attr['html']=$goal->vision_decades;
				$attributes['vision_decades']=array($default_attr);
			}
		//}

		//if($goal->barriers){
			$barrierss = GoalsMeta::get_by_attr($goal_id, "barriers");
			if($barrierss){
				$attributes['barriers']=$barrierss;
			}else{
				$default_attr['attr']="barriers";
				$default_attr['html']=$goal->barriers;
				$attributes['barriers']=array($default_attr);
			}
		//}

		//if($goal->priority){
			$prioritys = GoalsMeta::get_by_attr($goal_id, "priority");
			if($prioritys){
				$attributes['priority']=$prioritys;
			}else{
				$default_attr['attr']="priority";
				$default_attr['html']=$goal->priority;
				$attributes['priority']=array($default_attr);
			}
		//}

		//if($goal->initiative){
			$initiatives = GoalsMeta::get_by_attr($goal_id, "initiative");
			if($barrierss){
				$attributes['initiative']=$initiatives;
			}else{
				$default_attr['attr']="initiative";
				$default_attr['html']=$goal->initiative;
				$attributes['initiative']=array($default_attr);
			}
		//}

		//if($goal->help){
			$helps = GoalsMeta::get_by_attr($goal_id, "help");
			if($helps){
				$attributes['help']=$helps;
			}else{
				$default_attr['attr']="help";
				$default_attr['html']=$goal->help;
				$attributes['help']=array($default_attr);
			}
		//}

		//if($goal->support){
			$supports = GoalsMeta::get_by_attr($goal_id, "support");
			if($supports){
				$attributes['support']=$supports;
			}else{
				$default_attr['attr']="support";
				$default_attr['html']=$goal->support;
				$attributes['support']=array($default_attr);
			}
		//}

		//if(isset($goal->environment)){
			$environments = GoalsMeta::get_by_attr($goal_id, "environment");
			if($environments){
				$attributes['environment']=$environments;
			}else{
				$default_attr['attr']="environment";
				$default_attr['html']=$goal->environment;
				$attributes['environment']=array($default_attr);
			}
		//}


		//if(isset($goal->imagery)){
			$imagerys = GoalsMeta::get_by_attr($goal_id, "imagery");
			if($imagerys){
				$attributes['imagery']=$imagerys;
			}else{
				$default_attr['attr']="imagery";
				$default_attr['html']=html_entity_decode(stripcslashes($goal->imagery));
				$attributes['imagery']=array($default_attr);
			}
		//}
		
		$goal->goal_attributes = $attributes;
		
		return $goal;
	}

	public function change_state($auto_save_id, $self=true){
		
		$goal =  Self::where(["is_active"=>1, "is_delete"=>0, 'auto_save_id'=>$auto_save_id])->first();	 
	
		$saved=false;

		if ($goal) {
            
			if($self=='true'){
            	$goal->self_collapse = ($goal->self_collapse) ? 0 : 1;
        	}else if($goal->type_id==2 && $self=='false'){
        		 $goal->list_collapse = ($goal->list_collapse) ? 0 : 1;
        	}

        	$saved=$goal->save();
        }

		//$goal->percent = intval($goal->percent);
        return $saved;
	}
	
    public function get_by_id($id){
		$get =  Self::where(["is_active"=>1, "is_delete"=>0, 'id'=>$id])->first();
		if($get){
			return $get;
		}
		else{
			return false;
		}
	}	 
	
	public static function get_type_by_id($type_id){
		return $type=Types::where("id",$type_id)->first();
	}	
	
    public static function get_habit_types($goal_id){
		
		$data=HabitTypes::where("goal_id", $goal_id)->first();
		//var_dump($data);
		return $data;
	}
     
	
    public static function edit_goals($post){
	   
	   $update=Self::find($post['id']);
	  
	   
	   $update->name=$post['name'];
	   $update->due_date=date("y/m/d",strtotime($post['due_date']));
	   $this->habit_start_date=date("y/m/d",strtotime($post['habit_start_date']));
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
		if($update->save()){
			return true;
		}else{
			return false;
		}
	}
	   
	public function delete_goals($id){

		$goal=Self::find($id);
		$goal->is_delete=1;
		if($goal->save()){
			$this->delete_childrens($id);
			return true;
		}else{
			return false;
		}
	}
    
    public function delete_childrens($parent_id){

    	$goals=Self::where("parent_id",$parent_id)->get();

    	if($goals && !empty($goals)){
	    	foreach ($goals as $key => $goal) {
	    		$goal->is_delete=1;
	    		$goal->save();
	    		$this->delete_childrens($goal->id);
	    	}
    	}
    } 

    public function save_goals($post){
		 
		$this->name=$post['name'];

		if($this->save()){
			 return $this->id;
		}
	}

	public function is_parents_deleted($parent_id, $top_parent_id){
		
		$ids=[];

		$default_filter=[];

		if($parent_id>0){
			$ids[]=$parent_id;
			$default_filter['parent_id']=$parent_id;
		}

		if($top_parent_id>0){
			$ids[]=$top_parent_id;
			$default_filter['top_parent_id']=$top_parent_id;
		}

		if(empty($default_filter)){
			return false;
		}

		if(!empty($ids)){
			
			$goal =  Self::whereIn("id",$ids)->where("is_delete",1)->orderByRaw("self_order ASC")->first();
			
			if($goal){
				return true;
			}

			return false;
		}

		return false;
	}


	public function get_habits($filter){

		$default_filter=['is_delete'=>0, "is_end"=>0, "is_active"=>1, "is_show_lobby"=>1, "type_id"=>Config::get('constants.goal_habit'), "user_id"=>$filter['user_id'], "is_default"=>$filter['is_default']];
    	if($filter['is_default']){
    		unset($default_filter['user_id']);
    	}

    	//print_r($default_filter);

		$_habits =  Self::where($default_filter)->orderBy("self_order", "DESC")->get();
		
		$habits=array();

		if($_habits && !empty($_habits)){
			foreach ($_habits as $key => $habit) {

				if(!$this->is_parents_deleted($habit->parent_id,$habit->top_parent_id)){
					$_filter['parent_id']=$habit->parent_id;
					$_filter['top_parent_id']=$habit->top_parent_id;
					
					//if($this->validate_item($_filter)){
						if($filter['isMobile']){ // single day log for mobile view
							$habit->days=$this->weekday_logs($habit->id, $filter['start_date']);
						}else{
							$habit->days=$this->weekdays_logs($habit->id, $filter['start_date']);
						}
						
						$habit->percentage=$this->get_percentage($habit->id);
						$habit->habit_type=$this->get_habit_types($habit->id);
						$habits[]=$habit;
					//}
				}
				
			}
		}

		return $habits;
	}		

	public function get_tasks($filter){
		
		if(isset($filter['view_type']) && $filter['view_type']=='list'){
			$tasks=$this->get_task_list($filter);
		}else if(isset($filter['view_type']) && $filter['view_type']=='leaf'){
			$tasks=$this->get_task_leaf($filter);
		}else{
			$tasks=$this->get_task_tree($filter);
		}

		return $tasks;
	}

	public function get_task_list($filter){
		$default_filter=['is_delete'=>0, "is_end"=>0, "is_active"=>1, "is_show_lobby"=>1, "type_id"=>Config::get('constants.goal_task'), "user_id"=>$filter['user_id'],"is_default"=>$filter['is_default']];
    	if($filter['is_default']){
    		unset($default_filter['user_id']);
    	}

		$tasks =  Self::where($default_filter)->where("percent","<",100)->orderBy("due_date", "ASC")->get();
		return $tasks;
	}

	public function get_task_tree($filter){
		
		$default_filter=['is_delete'=>0, "is_end"=>0, "is_active"=>1, "is_show_lobby"=>1, "type_id"=>Config::get('constants.goal_task'), "user_id"=>$filter['user_id'],"is_default"=>$filter['is_default']];
    	if($filter['is_default']){
    		unset($default_filter['user_id']);
    	}

		$tasks =  Self::select("id")->where($default_filter)->where("percent","<",100)->orderBy("level", "ASC")->get();
		//echo "<pre/>"; print_r($tasks->toArray());die;
		//exit();
		//echo "<pre/>";
		$tasks=$this->process_task_tree($tasks,$filter);
		return $tasks;
	}

	public function process_task_tree($goals, $filter){

		$parents=array();
		
		if($goals && !empty($goals)){
			foreach ($goals as $key => $goal) {
				$filter['id']=$goal->id;
				if($id=$this->recursive_parent($filter)){
					$parents[$id]=$id;	
				}
			}
			/*echo "<pre/>";
			print_r($parents);
			die;*/
		}
		
		//echo "<pre/>";
		
		/*
		print_r($parents);
		
		exit();
		*/

		$tasks=$this->get_all_active_tasks($parents);

		//print_r($tasks);

		//exit();

		krsort($tasks);
		return $tasks;
	}
/*
	public function eleminate_completed_calculate_percetange($task){
		$tasks=array();
		if(isset($task['children']) && count($task['children'])){
			$child=array();
			$task['total_child']=count($task['children']);
			foreach ($task['children'] as $key => $child) {
				$task['completed']+=$child['percent']>=100?1:0;

				if($child['percent']>=100){
					continue;
				}else{
					return $task['children']=$this->eleminate_completed_calculate_percetange($child);
				}

				$childs[]=$task;
			}

			$tasks[]=$childs;
		}

		return $tasks;
	}
*/

	public function get_all_active_tasks($goal_ids){

		$tasks=array();
		if(!empty($goal_ids)){
			foreach ($goal_ids as $key => $id) {

				$goal=$this->get_by_id($id);
				if($goal){
					$goal=$goal->toArray();
					#var_dump($goal);
					$self_order=$goal['self_order']>0?$goal['self_order']:rand(1000,9999);
					if($goal['type_id']==Config::get('constants.goal_task')){
						$children=$this->recursive_tasks($id);
						$goal['children']=$children;
						$tasks[$self_order][]=$goal;
					}else{
						//echo "i m here...";

						$tasks[$self_order]=$this->recursive_tasks($id);
					}
				}
				
			}
		}

		return $tasks;
	}

	public function recursive_tasks($id){
		
		$tasks=array();
		$is_default=0;
		$default_filter=['is_delete'=>0, "is_end"=>0, "is_active"=>1, "is_show_lobby"=>1, "is_default"=>$is_default, "parent_id"=>$id];
		
		#DB::enableQueryLog();
		
		$goals =  Self::where($default_filter)->where("percent","<",100)->orderByRaw("detail_order ASC")->get()->toArray();
		
		#print_r(DB::getQueryLog());

		if($goals && !empty($goals)){

			foreach ($goals as $key => $goal) {

				if($goal['type_id']==Config::get('constants.goal_task')){
					/*echo "<br/>";
					echo "Yes its a task....";
					echo "<br/>";*/
					if($children=$this->recursive_tasks($goal['id'])){
						
						$goal['children']=$children;
						
						if(isset($children[0]['parent_id'])){
							$completed_percentage=$this->get_completed_child_tasks($children[0]['parent_id']);
							$goal['total_children']=$completed_percentage['total_children'];
							$goal['total_completed']=$completed_percentage['total_completed'];
							$goal['completed_percetange']=$completed_percentage['completed_percetange'];
							$goal['completed_badge']=$completed_percentage['completed_badge'];
						}
						
						$childs=array();
						foreach ($children as $ckey => $child) {
							
							//var_dump($child['percent']);

							if($child['percent']!=100){
								$childs[]=$child;
							}
						}

						//print_r($childs);

						$goal['children']=$childs;/**/

					}
		
					$tasks[]=$goal;

					//print_r($tasks);

				}else{
					/*echo "<br/>";
					echo "Not a task, looping again";
					echo "<br/>";
					echo "<pre/>";
					print_r($tasks);
					*/
					$rtasks=$this->recursive_tasks($goal['id']);

					//echo "<pre/>";

					//print_r($rtasks);

				    $tasks = array_merge($tasks,$rtasks);
				}
			}
		}
		//echo "<pre/>";
		//print_r($tasks);

		return $tasks;
	}

	public function recursive_parent($filter){

		$default_filter=['is_delete'=>0, "is_end"=>0, "is_active"=>1, "is_show_lobby"=>1, "user_id"=>$filter['user_id'], "is_default"=>$filter['is_default'], "id"=>$filter['id']];
    	if($filter['is_default']){
    		unset($default_filter['user_id']);
    	}

		$goal =  Self::where($default_filter)->where("percent","<",100)->orderByRaw("self_order ASC")->first();
		/*echo "<pre/>";
		print_r($goal);die;*/
		if($goal && $goal->parent_id >0){
			 $filter['id']=$goal->parent_id;
			return $this->recursive_parent($filter);	
		}else if($goal){
			return $goal->id;
		}
		return false;
	}

	public function get_completed_child_tasks($id){
		
		$response=array("total_children"=>0,"total_completed"=>0,"completed_percetange"=>0,"completed_childs"=>array());

		$default_filter=['is_delete'=>0, "is_end"=>0, "is_active"=>1, "is_show_lobby"=>1, "parent_id"=>$id];

		$goals =  Self::where($default_filter)->orderBy("self_order", "ASC")->get();

		if($goals){
			$total_children=0;
			$total_completed=0;
			$completed_childs=array();
			foreach ($goals as $key => $goal) {
				if($goal->percent==100){
					$total_completed++;
					$completed_childs[]=$goal->id;
				}	
				$total_children++;	
			}
			$response['total_children']=$total_children;
			$response['total_completed']=$total_completed;
		    $completed_percetange=round(($total_completed)?($total_completed/$total_children)*100:0);
			
			$response['completed_percetange']=$completed_percetange;

			if ($completed_percetange <= 33) {
	            $badge = "danger";
	        } else if ($completed_percetange > 33 && $completed_percetange <= 66) {
	            $badge = "warning";
	        } else if ($completed_percetange > 66) {
	            $badge = "success";
	        }
	        $response['completed_badge']=$badge;
	        $response['completed_childs']=$completed_childs;
		}
		return $response;
	}

	public function get_task_leaf($filter){
		$default_filter=['is_delete'=>0, "is_end"=>0, "is_active"=>1, "is_show_lobby"=>1, "has_sub"=>0, "type_id"=>Config::get('constants.goal_task'), "user_id"=>$filter['user_id'],"is_default"=>$filter['is_default']];
    	if($filter['is_default']){
    		unset($default_filter['user_id']);
    	}
    	
    	//DB::enableQueryLog();

		$tasks =  Self::where($default_filter)->where("percent","<",100)->orderBy("due_date", "ASC")->groupBy("id")->get();
		//print_r(DB::getQueryLog());exit();

		return $tasks;
	}

	public function get_characters($filter){
		$default_filter=['is_delete'=>0, "is_end"=>0, "is_active"=>1, "is_show_lobby"=>1, "type_id"=>Config::get('constants.goal_character'), "user_id"=>$filter['user_id'],"is_default"=>$filter['is_default']];
    	if($filter['is_default']){
    		unset($default_filter['user_id']);
    	}
    	
		$characters =  Self::where($default_filter)->orderBy("self_order", "ASC")->get();

		return $characters;
	}


	public function weekdays_logs($goal_id, $start_date=null){

		$start_date=($start_date==null)?date("Y-m-d"):$start_date;

		$_weekdays=$this->weekdays($start_date);

		$weekdays=array();

		foreach ($_weekdays as $date => $day) {
			
			$log=$this->get_log($goal_id, $date);
			
			$day['log']=$log;

			$weekdays[$date]=$day;	
		}

		return $weekdays;
	}

	public function weekday_logs($goal_id, $start_date=null){

		$start_date=($start_date==null)?date("Y-m-d"):$start_date;

		$_weekdays=$this->weekday($start_date);

		$weekdays=array();

		foreach ($_weekdays as $date => $day) {
			
			$log=$this->get_log($goal_id, $date);
			
			$day['log']=$log;

			$weekdays[$date]=$day;	
		}

		return $weekdays;
	}

	public function weekday($date){

		$weekdays=array();
		
		$date=Date('Y-m-d', strtotime($date));
		
		$day=Date('D', strtotime($date));
		
		$sdate=Date('d M', strtotime($date));
		
		$day_of_week = Carbon::parse($date)->dayOfWeek;
		
		$weekdays[$date]=array("day"=>$day,"date"=>$sdate,"day_of_week"=>$day_of_week);
		
		return $weekdays;

	}

	public function weekdays($start_date=null){

		if($start_date==date("Y-m-d")){
			$weekdays=$this->current_weekdays();
		}else{
			$weekdays=$this->common_weekdays($start_date);
		}

		return $weekdays;
	}

	public function current_weekdays(){

		$start_date=date("Y-m-d");

		$weekdays=array();
		
		$min=0;

		$max=7;

		$current = 6;

		 //$current = Carbon::parse($start_date)->dayOfWeek;

		for ($i1=-($current-1); $i1 < $min; $i1++) { 
			
			$date=Date('Y-m-d', strtotime($i1." days"));
			
			$day=Date('D', strtotime($i1." days"));
			
			$sdate=Date('d M', strtotime($i1." days"));
			
			$day_of_week = Carbon::parse($date)->dayOfWeek;
			
			$weekdays[$date]=array("day"=>$day,"date"=>$sdate, "day_of_week"=>$day_of_week);
		}
		
		for ($i=$current; $i <= $max; $i++) { 
			
			$day_index=$i-$current;
			
			$date=Date('Y-m-d', strtotime("+".$day_index." days"));
			
			$day=Date('D', strtotime("+".$day_index." days"));
			
			$sdate=Date('d M', strtotime("+".$day_index." days"));
			
			$day_of_week = Carbon::parse($date)->dayOfWeek;
			
			$weekdays[$date]=array("day"=>$day,"date"=>$sdate, "day_of_week"=>$day_of_week);
		}
		//echo "<pre/>";
		//print_r($weekdays);die;
		return $weekdays;
	}

	public function common_weekdays($start_date){

		$start_date=($start_date==null)?date("Y-m-d"):$start_date;

		$weekdays=array();
		
		$min=0;

		$max=6;

		$curr_date_obj=Carbon::parse($start_date);

		for ($i=$min; $i <= $max; $i++) { 

			$date_obj=$curr_date_obj->addDays(1);
			
			$date=$date_obj->format("Y-m-d");//Date('Y-m-d', strtotime("+".$day_index." days"));
			
			$day=$date_obj->format("D");//Date('D', strtotime("+".$day_index." days"));
			
			$sdate=$date_obj->format("d M");//Date('d M', strtotime("+".$day_index." days"));
			
			$day_of_week = Carbon::parse($date)->dayOfWeek;
			
			$weekdays[$date]=array("day"=>$day, "date"=>$sdate, "day_of_week"=>$day_of_week);
		}

		return $weekdays;
	}
	
	public function get_completed_dates($goal_id, $start_date, $current_date, $count=0){
		
		$logs=Logs::where("goal_id",$goal_id)->where("value",1)->whereRaw("date >= '".$start_date."'")->whereRaw("date < '".$current_date."'")->get();
		
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}


		/*-------------------FOR SCALE NUMBER OPTION-----------------*/

		public function get_completed_dates_scale_number($goal_id,$scale,$min,$max, $start_date, $current_date, $count=0){
		
		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereRaw("date >= '".$start_date."'")->whereRaw("date < '".$current_date."'")->get();
		
		if($count){
			$logs=$logs->count();
		}

		//echo $logs;
		return $logs;
	}


	public function get_completed_between_dates_scale_number($goal_id,$scale,$min,$max, $date_array, $count=0){
		//echo $min;
		//DB::enableQueryLog();
		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereIn("date",$date_array)->get();

		//dd(DB::getQueryLog());
		
		if($count){
			$logs = $logs->count();
			
		}
		return $logs;
	}


	public function get_completed_between_dates_for_percentage_scale_number($goal_id,$scale,$min,$max, $date_array, $count=0){
		//echo $min;
		//DB::enableQueryLog();
		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereIn("date",$date_array)->get();

		//dd(DB::getQueryLog());
		
		if($logs){
			foreach ($logs as $log) {
				$count = $count+$log->value;
			}
			
		}
		return $count;
	}


	public function get_completed_dates_scale_number_for_percentage($goal_id,$scale,$min,$max, $start_date, $current_date, $count=0){
		
		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereRaw("date >= '".$start_date."'")->whereRaw("date < '".$current_date."'")->get();
		
		if($logs){
			foreach ($logs as $log) {
				$count = $count+$log->value;
			}
			
		}
		return $count;
	}


	public function get_all_completed_dates_scale_number($goal_id,$scale,$min,$max, $count=0){
		
		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->get();
		
		if($count){
			$logs = $logs->count();
			
		}

		return $logs;
	}

	public function get_completed_month_dates_scale_number($goal_id,$scale,$min,$max, $dates, $count=0){
		
		//$month_year=date("Y-m",strtotime($date));

		if(empty($dates)){
			return 0;
		}

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereRaw("date IN(".implode(",", $dates).")")->get();
		
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}


	public function get_completed_month_dates_for_percentage_scale_number($goal_id,$scale,$min,$max, $dates, $count=0){
		
		//$month_year=date("Y-m",strtotime($date));

		if(empty($dates)){
			return 0;
		}

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereRaw("date IN(".implode(",", $dates).")")->get();
		//$log = $logs->toArray();
		
		

		if($logs){
			foreach ($logs as $log) {
				$count = $count+$log->value;
			}
			
		}
		
		return $count;
	}


	public function get_maximum_and_minimum_scale_number($goal_id,$scale,$min,$max, $dates, $count=0){
		
		//$month_year=date("Y-m",strtotime($date));
		$result = array();
		$logy = array();
		$highest = 0;
		$lowest  = 0;

		if(empty($dates)){
			return 0;
		}

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereRaw("date IN(".implode(",", $dates).")")->get();
		//$log = $logs->toArray();
		
		

		if($logs){
			foreach ($logs as $log) {
				 array_push($logy, $log->value);
			}
			
		}
		
		$highest = (!empty($logy))?max($logy):0;
		$lowest = (!empty($logy))?min($logy):0;
		$result['highest'] = $highest;
		$result['lowest'] = $lowest;
		/*echo "<pre/>";
		print_r($result);die;*/
		return $result;
	}


	public function get_completed_days_for_Graph_scale_number($goal_id,$scale,$dates){

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where("date",$dates)->first();
		$value = 0;
		if($logs){
			$value = $logs->value;
		}

		return $value;
	}


	public function get_disabled_dates_scale_number($goal_id,$scale,$start_date, $current_date, $count=0){
		
		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where("value",-1)->whereRaw("date >= '".$start_date."'")->whereRaw("date < '".$current_date."'")->get();
		//echo "<pre/>";
		//print_r($logs);die;
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}

	public function get_all_disabled_dates_scale_number($goal_id,$scale, $count=0){

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where("value",-1)->get();
		
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}

	public function get_disabled_month_dates_scale_number($goal_id,$scale, $dates, $count=0){
		//echo "<pre/>";
		//print_r($dates);
		//$month_year=date("Y-m",strtotime($date));
		if(empty($dates)){
			return 0;
		}

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where("value",-1)->whereRaw("date IN(".implode(",", $dates).")")->get();
		if($count){
			$logs=$logs->count();
		}
		return $logs;
	}
	
	public function get_prev_completed_dates_scale_number($goal_id,$scale,$min,$max, $start_date, $count=0){

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereRaw("date < '".$start_date."'")->get();
		
		//print_r(DB::getQueryLog());exit();

		if($count){
			$logs=$logs->count();
		}
		return $logs;
	}

	public function get_prev_completed_month_dates_scale_number($goal_id,$scale,$min,$max,$date, $count=0){

		$month_year=date("Y-m",strtotime($date));

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereRaw("date LIKE '%".$month_year."%'")->get();
	
		if($count){
			$logs=$logs->count();
		}
		return $logs;
	}

	public function get_additional_completed_dates_scale_number($goal_id,$scale,$min,$max,$current_date, $count=0){

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereRaw("date > '".$current_date."'")->get();
		
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}


	public function get_additional_completed_month_dates_scale_number($goal_id,$scale,$min,$max,$dates, $count=0){
		
		//$month_year=date("Y-m",strtotime($date));
		
		//DB::enableQueryLog();


		if(empty($dates)){
			return 0;
		}

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereRaw("date IN(".implode(",", $dates).")")->get();
		
		//print_r(DB::getQueryLog());//exit();

		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}

	public function get_additional_completed_month_dates_for_percentage_scale_number($goal_id,$scale,$min,$max,$dates, $count=0){
		
		//$month_year=date("Y-m",strtotime($date));
		
		//DB::enableQueryLog();


		if(empty($dates)){
			return 0;
		}

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',$scale)->where(function($q) use ($min,$max) {
          $q->where('value','>=',$min)
            ->where('value','<=',$max)
            ->orWhere('value',0);
      })->whereRaw("date IN(".implode(",", $dates).")")->get();
		
		//print_r(DB::getQueryLog());//exit();

		if($logs){
			foreach ($logs as $log) {
				$count = $count+$log->value;
			}
			
		}

		return $count;
	}
	


	public function get_completed_between_dates($goal_id, $date_array, $count=0){
		
		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',0)->where("value",1)->whereIn("date",$date_array)->get();
		
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}


	public function get_all_completed_dates($goal_id, $count=0){
		
		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',0)->where("value",1)->get();
		
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}

	public function get_completed_month_dates($goal_id, $dates, $count=0){
		
		//$month_year=date("Y-m",strtotime($date));

		if(empty($dates)){
			return 0;
		}

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',0)->where("value",1)->whereRaw("date IN(".implode(",", $dates).")")->get();
		
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}

	public function get_disabled_dates($goal_id, $start_date, $current_date, $count=0){
		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',0)->where("value",2)->whereRaw("date >= '".$start_date."'")->whereRaw("date < '".$current_date."'")->get();
		
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}

	public function get_all_disabled_dates($goal_id, $count=0){

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',0)->where("value",2)->get();
		
		if($count){
			$logs=$logs->count();
		}
		return $logs;
	}

	public function get_disabled_month_dates($goal_id, $dates, $count=0){
		
		//$month_year=date("Y-m",strtotime($date));
		if(empty($dates)){
			return 0;
		}

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',0)->where("value",2)->whereRaw("date IN(".implode(",", $dates).")")->get();
		
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}
	
	public function get_prev_completed_dates($goal_id, $start_date, $count=0){

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',0)->where("value",1)->whereRaw("date < '".$start_date."'")->get();
		
		//print_r(DB::getQueryLog());exit();

		if($count){
			$logs=$logs->count();
		}
		return $logs;
	}

	public function get_prev_completed_month_dates($goal_id, $date, $count=0){

		$month_year=date("Y-m",strtotime($date));

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',0)->where("value",1)->whereRaw("date LIKE '%".$month_year."%'")->get();
	
		if($count){
			$logs=$logs->count();
		}
		return $logs;
	}

	public function get_additional_completed_dates($goal_id, $current_date, $count=0){

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',0)->where("value",1)->whereRaw("date > '".$current_date."'")->get();
		
		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}


	public function get_additional_completed_month_dates($goal_id, $dates, $count=0){
		
		//$month_year=date("Y-m",strtotime($date));
		
		//DB::enableQueryLog();


		if(empty($dates)){
			return 0;
		}

		$logs=Logs::where("goal_id",$goal_id)->where('is_scale',0)->where("value",1)->whereRaw("date IN(".implode(",", $dates).")")->get();
		
		//print_r(DB::getQueryLog());//exit();

		if($count){
			$logs=$logs->count();
		}

		return $logs;
	}

	public function get_log($id, $date){
		return Logs::where("goal_id",$id)->where("date",$date)->first();
	}

	public function get_first_log_date($id){
		return Logs::where("goal_id",$id)->orderBy("date", "ASC")->first();
	}
	
	public function get_percentage($habit_id=null){
		
		$response=array();
		
		if($habit_id){
			$habit=Self::find($habit_id);
		}else{
			$habit=$this;
		}
		
		$end = Carbon::parse($habit->habit_start_date);		

		$now = Carbon::now();//->addDays(1); // add 1 more days, so that it calculates including today

	    $length = $end->diffInDays($now);
		
		$type_data=Self::get_habit_types($habit->id);
		if($type_data->is_scale == 0)
		{
			$days_per_week=($type_data &&$type_data->count_per_week!='')?$type_data->count_per_week:7;
		

		if($days_per_week!=7){

			$day_numbers=($type_data && $type_data->value!='')?explode(",", $type_data->value):array();

			$week_days_array=[];

			//print_r($day_numbers);

			if($day_numbers){
				
				$dayOfTheWeek = Carbon::parse($habit->habit_start_date)->dayOfWeek;

				foreach ($day_numbers as $key => $day_number) {
					
					if($dayOfTheWeek==$day_number){
						if(Carbon::parse($habit->habit_start_date)->format('Y-m-d')!==date("Y-m-d")){
							$week_days_array[] = Carbon::parse($habit->habit_start_date)->format('Y-m-d');
						}
					}

					$startDate = Carbon::parse($habit->habit_start_date)->next($day_number); // Get the first friday.
					$endDate = Carbon::now();

					for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
						if($date->format('Y-m-d')!==date("Y-m-d")){
						    $week_days_array[] = $date->format('Y-m-d');
						}
					}
				}

				//print_r($week_days_array);

			}
			//echo "<pre/>";
			//print_r($week_days_array);die;
			$days = count($week_days_array);
			$completed=$this->get_completed_between_dates($habit->id, $week_days_array, 1);

		}else{
			
			$weeks=$length/$days_per_week;
			$days=$weeks*$days_per_week;
			$completed=$this->get_completed_dates($habit->id, $habit->habit_start_date, date("Y-m-d"), 1);
		}


		
		$prev_days=$this->get_prev_completed_dates($habit->id, $habit->habit_start_date, 1); // get prev days if user selects  before $habit->habit_start_date date

		$addtional_days=$this->get_additional_completed_dates($habit->id, $habit->habit_start_date /*date("Y-m-d")*/, 1);	// get addtional days if user selects beyond current date
		
		$disabled_days=$this->get_disabled_dates($habit->id, $habit->habit_start_date, date("Y-m-d"), 1); 
		
		$days = ($days-$disabled_days);

		$response['days']=$days;
		
		$response['disabled_days']=$disabled_days;

		//$days=($days+$prev_days+$addtional_days)-$disabled_days; // updating... dates will considered only in start date and current range

		$response['habit_start_date']=$habit->habit_start_date;
		
		$response['prev_days']=$prev_days;
		
		$response['addtional_days']=$addtional_days;

		$response['total_days'] = $days;
		
		$response['completed'] = $completed;

		//$completed=$completed+$prev_days+$addtional_days;

		$response['completed_days'] = $completed;
		
		$raw_percentage=($days!=0)?(($completed/$days)*100):0;

		$percentage=($days!=0)?round(($completed/$days)*100):0;

		if ($percentage <= 33) {
            $badge = "badge-danger";
        } else if ($percentage > 33 && $percentage <= 66) {
            $badge = "badge-warning";
        } else if ($percentage > 66) {
            $badge = "badge-success";
        }
        
        $response['badge']=$badge;
        
        $response['percentage']=$percentage;

		$response['raw_percentage']=$raw_percentage;
			
		}
		else
		{
			$days_per_week=($type_data &&$type_data->count_per_week!='')?$type_data->count_per_week:7;
		

		if($days_per_week!=7){

			$day_numbers=($type_data && $type_data->value!='')?explode(",", $type_data->value):array();

			$week_days_array=[];

			//print_r($day_numbers);

			if($day_numbers){
				
				$dayOfTheWeek = Carbon::parse($habit->habit_start_date)->dayOfWeek;

				foreach ($day_numbers as $key => $day_number) {
					
					if($dayOfTheWeek==$day_number){
						if(Carbon::parse($habit->habit_start_date)->format('Y-m-d')!==date("Y-m-d")){
							$week_days_array[] = Carbon::parse($habit->habit_start_date)->format('Y-m-d');
						}
					}

					$startDate = Carbon::parse($habit->habit_start_date)->next($day_number); // Get the first friday.
					$endDate = Carbon::now();

					for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
						if($date->format('Y-m-d')!==date("Y-m-d")){
						    $week_days_array[] = $date->format('Y-m-d');
						}
					}
				}

				//print_r($week_days_array);

			}
			//echo "<pre/>";
			//print_r($week_days_array);die;
			$days = count($week_days_array);
		    $completed=$this->get_completed_between_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $week_days_array, 1);
		     $total_scale_number = $this->get_completed_between_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $week_days_array);

		}else{
			
			$weeks=$length/$days_per_week;
			$days=$weeks*$days_per_week;
			$completed=$this->get_completed_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit->habit_start_date, date("Y-m-d"), 1);
		     $total_scale_number = $this->get_completed_dates_scale_number_for_percentage($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit->habit_start_date, date("Y-m-d"));
		}


		
		$prev_days=$this->get_prev_completed_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit->habit_start_date, 1); // get prev days if user selects  before $habit->habit_start_date date

		$addtional_days=$this->get_additional_completed_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit->habit_start_date /*date("Y-m-d")*/, 1);	// get addtional days if user selects beyond current date
		
		$disabled_days=$this->get_disabled_dates_scale_number($habit->id,$type_data->is_scale,$habit->habit_start_date, date("Y-m-d"), 1);
		
		  $days = ($days-$disabled_days);

		//$scale_days = $days*$type_data->maximum;

		$response['days']=$days;
		
		$response['disabled_days']=$disabled_days;

		//$days=($days+$prev_days+$addtional_days)-$disabled_days; // updating... dates will considered only in start date and current range

		$response['habit_start_date']=$habit->habit_start_date;
		
		$response['prev_days']=$prev_days;
		
		$response['addtional_days']=$addtional_days;

		$response['total_days'] = $days;
		
		$response['completed'] = $completed;

		//$completed=$completed+$prev_days+$addtional_days;

		$response['completed_days'] = $completed;
		
		$raw_percentage=($days!=0)?(($total_scale_number/$days)):0;

	    $percentage=($days!=0)?round(($total_scale_number/$days)):0;
	    $danger = round($type_data->maximum/3);
	    $yellow = $danger+$danger;
	    $green  = $danger+$danger+$danger;
		if ($percentage <= $danger) {
            $badge = "badge-danger";
        } else if ($percentage > $danger && $percentage <= $yellow) {
            $badge = "badge-warning";
        } else if ($percentage > $yellow) {
            $badge = "badge-success";
        }
        
        $response['badge']=$badge;
        
        $response['percentage']=$percentage;

		$response['raw_percentage']=$raw_percentage;
		}
		

		/*if($habit_id==61351){
			
			var_dump(Carbon::FRIDAY);

			print_r($response);

			var_dump($days_per_week);

			var_dump($weeks);

			var_dump($length);

		}*/

		return (object)$response;
	}


	public function get_monthly_percentage($habit_id, $date){
		
		$response = array();
		
		if($habit_id){
			$habit = Self::find($habit_id);
		}
		

		$month = date("m",strtotime($date));

		$month_name = date("M",strtotime($date));

		$year = date("Y",strtotime($date));
			
		$end = Carbon::parse($habit->habit_start_date);		

		$now = Carbon::now()->addDays(1); // add 1 more days, so that it calculates including today

		$is_current_month=date("Y-m",strtotime($date))==date("Y-m")?true:false;
		
		$current_day_number=date("d")-1; // remove current date..., we are not calculating current date..
		
		$length = ($is_current_month)?$current_day_number:cal_days_in_month(CAL_GREGORIAN, $month, $year);

		//var_dump($length);

		$type_data = Self::get_habit_types($habit->id);
		if($type_data->is_scale == 0)
		{
			$applicable_dates = array();
		
		$not_applicable_dates = array();
		
		$allowed_days = explode(",", $type_data->value);


		for($i = 1; $i <= $length; $i++){
			
			$d_prefix=($i<10)?"0".$i:$i;

			$loop_date = date("Y-m-".$d_prefix, strtotime($date));
			
			$log=$this->get_log($habit->id, $loop_date);

			//var_dump($loop_date,$end->format("Y-m-d"));

			/*if(($loop_date <= date("Y-m-d")) || ($log && $log->value==1)){
				
				if(date("Y-m")==date("Y-m",strtotime($loop_date)) && $loop_date < $habit->habit_start_date && !($log && $log->value==1)){
					continue;
				}


				$day_of_week = Carbon::parse($loop_date)->dayOfWeek;
				
				if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
					$applicable_dates[$loop_date] = "'".$loop_date."'";
				}else if($type_data->value != 7){
					$not_applicable_dates[$loop_date] = "'".$loop_date."'";
				}else if($type_data->value == 7){
					$applicable_dates[$loop_date] = "'".$loop_date."'";
				}

			
			}else{

			}*/

			if($loop_date >= $habit->habit_start_date){


				$day_of_week = Carbon::parse($loop_date)->dayOfWeek;
					
				if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
					$applicable_dates[$loop_date] = "'".$loop_date."'";
				}else if($type_data->value != 7){
					$not_applicable_dates[$loop_date] = "'".$loop_date."'";
				}else if($type_data->value == 7){
					$applicable_dates[$loop_date] = "'".$loop_date."'";
				}
			}

		}

		//echo "<pre/>";
		//print_r($applicable_dates);die;
		
		$response['month_year']=array("month"=>$month,"month_name"=>$month_name,"year"=>$year);

		$response['allowed_dates'] = $applicable_dates;
		
		$response['disallowed_dates'] = $not_applicable_dates;

		$response['allowed_days'] = count($applicable_dates);
		
		$response['disallowed_days'] = count($not_applicable_dates);

		/*echo "<br/>";
		echo "**********************************applicable_dates*****************************************";
		echo "<br/>";
		print_r($applicable_dates);
		
		echo "<br/>";
		echo "**********************************not_applicable_dates*****************************************";
		echo "<br/>";

		print_r($not_applicable_dates);*/

		
		//$prev_days=$this->get_prev_completed_month_dates($habit->id, $date, 1); // get prev days if user selects  before $habit->habit_start_date date
		
		$prev_days = 0;
		
		$addtional_days = $this->get_additional_completed_month_dates($habit->id, $not_applicable_dates, 1);	//not applicable but still checked..
		
		//$addtional_days=0;

		 $disabled_days = $this->get_disabled_month_dates($habit->id, $applicable_dates, 1); // if disabled allowed dates...
		
		
		 $days = (count($applicable_dates)+$addtional_days)-$disabled_days;
		
		$response['days'] = $days;
		
		$response['days_in_month'] = $length;

		$response['disabled_days'] = $disabled_days;

		$response['habit_start_date'] = $habit->habit_start_date;
		
		$response['prev_days'] = $prev_days;
		
		$response['addtional_days'] = $addtional_days;

		$response['total_days'] = $days;
		
	    $completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
		
		$response['completed'] = $completed;

	    $completed = $completed+$addtional_days;

		$response['completed_days'] = $completed;

		
		//print_r($response);

		//exit();

		$percentage = ($days != 0)? round(($completed/$days)*100) : 0;

		if ($percentage <= 33) {
            $badge = "badge-danger";
        } else if ($percentage > 33 && $percentage <= 66) {
            $badge = "badge-warning";
        } else if ($percentage > 66) {
            $badge = "badge-success";
        }
        
        $response['badge'] = $badge;

		$response['percentage'] = $percentage;
		
		}
		else
		{
			$applicable_dates = array();
		
		    $not_applicable_dates = array();
		
		   $allowed_days = explode(",", $type_data->value);


		for($i = 1; $i <= $length; $i++){
			
			$d_prefix=($i<10)?"0".$i:$i;

			$loop_date = date("Y-m-".$d_prefix, strtotime($date));
			
			$log=$this->get_log($habit->id, $loop_date);

			//var_dump($loop_date,$end->format("Y-m-d"));

			/*if(($loop_date <= date("Y-m-d")) || ($log && $log->value==1)){
				
				if(date("Y-m")==date("Y-m",strtotime($loop_date)) && $loop_date < $habit->habit_start_date && !($log && $log->value==1)){
					continue;
				}


				$day_of_week = Carbon::parse($loop_date)->dayOfWeek;
				
				if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
					$applicable_dates[$loop_date] = "'".$loop_date."'";
				}else if($type_data->value != 7){
					$not_applicable_dates[$loop_date] = "'".$loop_date."'";
				}else if($type_data->value == 7){
					$applicable_dates[$loop_date] = "'".$loop_date."'";
				}

			
			}else{

			}*/

			if($loop_date >= $habit->habit_start_date){


				$day_of_week = Carbon::parse($loop_date)->dayOfWeek;
					
				if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
					$applicable_dates[$loop_date] = "'".$loop_date."'";
				}else if($type_data->value != 7){
					$not_applicable_dates[$loop_date] = "'".$loop_date."'";
				}else if($type_data->value == 7){
					$applicable_dates[$loop_date] = "'".$loop_date."'";
				}
			}

		}

		/*echo "<pre/>";
		print_r($applicable_dates);
		print_r($not_applicable_dates);*/
		
		$response['month_year']=array("month"=>$month,"month_name"=>$month_name,"year"=>$year);

		$response['allowed_dates'] = $applicable_dates;
		
		$response['disallowed_dates'] = $not_applicable_dates;

		$response['allowed_days'] = count($applicable_dates);
		
		$response['disallowed_days'] = count($not_applicable_dates);

		/*echo "<br/>";
		echo "**********************************applicable_dates*****************************************";
		echo "<br/>";
		print_r($applicable_dates);
		
		echo "<br/>";
		echo "**********************************not_applicable_dates*****************************************";
		echo "<br/>";

		print_r($not_applicable_dates);*/

		
		//$prev_days=$this->get_prev_completed_month_dates($habit->id, $date, 1); // get prev days if user selects  before $habit->habit_start_date date
		
		$prev_days = 0;
		
		//echo "<pre/>";
		$addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
		
		//$addtional_days=0;

		$disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
		
		
		$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
		$scale_days = $days*$type_data->maximum;
		$response['days'] = $days;
		
		$response['days_in_month'] = $length;

		$response['disabled_days'] = $disabled_days;

		$response['habit_start_date'] = $habit->habit_start_date;
		
		$response['prev_days'] = $prev_days;
		
		$response['addtional_days'] = $addtional_days;

		$response['total_days'] = $days;
		
		$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
		$response['completed'] = $completed;

		 $scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
		$scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

	    $completed = $completed+$addtional_days;

		$response['completed_days'] = $completed;

		$scale_completed = $scale_completed_days+$scale_additional_days;

		$response['lowest'] = $scale_completed_days['lowest'];
		$response['highest'] = $scale_completed_days['highest'];

		//print_r($response);

		//exit();

		$percentage = ($days != 0)? round(($scale_completed/$scale_days)*100) : 0;

		if ($percentage <= 33) {
            $badge = "badge-danger";
        } else if ($percentage > 33 && $percentage <= 66) {
            $badge = "badge-warning";
        } else if ($percentage > 66) {
            $badge = "badge-success";
        }
        
        $response['badge'] = $badge;

		$response['percentage'] = $percentage;
	}
		
		return (object)$response;
	}


		public function getWeekly_average($habit_id,$weekdate)
		{

		$habit = Goals::where('id',$habit_id)->first();
		//var_dump($length);
		$type_data = Self::get_habit_types($habit->id);

		foreach ($weekdate as $value) {
		$habit_start_date = date("Y-m-d",strtotime($value));

		$applicable_dates = array();
		
		$not_applicable_dates = array();
		
		$allowed_days = explode(",", $type_data->value);

		//$log = $this->get_log($habit->id, $habit_start_date);
			$i=1;
			while ($i < 7){
			//$d_prefix=($i<10)?"0".$i:$i;

			$loop_date = date("Y-m-d", strtotime($habit_start_date));
			if($loop_date >= $habit->habit_start_date)
			{
			$log=$this->get_log($habit->id, $loop_date);

			$day_of_week = Carbon::parse($loop_date)->dayOfWeek;

			if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
				$applicable_dates[$loop_date] = "'".$loop_date."'";
			}else if($type_data->value != 7){
				$not_applicable_dates[$loop_date] = "'".$loop_date."'";
			}else if($type_data->value == 7){
				$applicable_dates[$loop_date] = "'".$loop_date."'";
			}
		}

			$i++;
			$habit_start_date = date("Y-m-d", strtotime("+1 day", strtotime($habit_start_date)));
			
		}
				/* echo "<pre/>";
				 print_r($not_applicable_dates);
				 print_r($applicable_dates);die;*/

		 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
		
		//$addtional_days=0;

		 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
		
		
		echo $days = (count($applicable_dates)+$addtional_days)-$disabled_days;
		$scale_days = $days*$type_data->maximum;
		
		
		$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
		$response['completed'] = $completed;

		 $scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
		 $scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

	    $completed = $completed+$addtional_days;

		$response['completed_days'] = $completed;

	    $scale_completed = $scale_completed_days+$scale_additional_days;
	    $monthly_average = 0;
	    if($days > 0)
	    {
	    	$monthly_average = $scale_completed/$days;
	    }
	    
	    

	    $response['weekly_total'] = $scale_completed;
		$response['weekly_average'] = round($monthly_average);
		echo "<pre/>";
		print_r($response);
		//return $response;
	}
	die;

	}



	public function getMonthly_average($habit_id)
	{
		$habit = Goals::where('id',$habit_id)->first();
		//var_dump($length);

		$type_data = Self::get_habit_types($habit->id);

		$habit_start_date = date("Y-m-d",strtotime($habit->habit_start_date));

		$current_month_date = date("Y-m-d");

		$applicable_dates = array();
		
		$not_applicable_dates = array();

		$scale_lowest_value = array();
		$scale_highest_value = array();

		//$labels = array();
		
		$allowed_days = explode(",", $type_data->value);
		$current = date("Y-m",strtotime($current_month_date));
		$habitMonth = date("Y-m",strtotime($habit_start_date));
		if($habitMonth == $current)
		{

				$log = $this->get_log($habit->id, $habit_start_date);

				while (strtotime($habit_start_date) < strtotime($current_month_date)) {
				    if($habit_start_date >= $habit->habit_start_date){
					$day_of_week = Carbon::parse($habit_start_date)->dayOfWeek;
						
					if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
						$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
					}else if($type_data->value != 7){
						$not_applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
					}else if($type_data->value == 7){
						$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
					}
			}
				    $habit_start_date = date ("Y-m-d", strtotime("+1 day", strtotime($habit_start_date)));
				 }
				 //echo "<pre/>";
				 //print_r($not_applicable_dates);
				 //print_r($applicable_dates);die;

		 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
		
		//$addtional_days=0;

		 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
		
		
		$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
		$scale_days = $days*$type_data->maximum;
		
		
		$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
		$response['completed'] = $completed;

		 $scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
		 $scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

	    $completed = $completed+$addtional_days;

		$response['completed_days'] = $completed;

	    $scale_completed = $scale_completed_days+$scale_additional_days;
	    $monthly_average = 0;
		if($days > 0)
		{
		   $monthly_average = $scale_completed/$days;                        
		}
	    //$monthly_average = $scale_completed/$days;
		$response['lowest'] = $scale_completed_days['lowest'];
		$response['highest'] = $scale_completed_days['highest'];
	    $response['monthly_total'] = $scale_completed;
		$response['monthly_average'] = round($monthly_average);
		}
		else
		{
			$current_month__first_date = date("Y-m-01");
			$current_month_end_date = date("Y-m-d");
			$log = $this->get_log($habit->id, $habit_start_date);

				while (strtotime($current_month__first_date) < strtotime($current_month_end_date)) {
				    if($current_month__first_date >= $habit->habit_start_date){
					$day_of_week = Carbon::parse($current_month__first_date)->dayOfWeek;
						
					if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
						$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
					}else if($type_data->value != 7){
						$not_applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
					}else if($type_data->value == 7){
						$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
					}
			}
				    $current_month__first_date = date ("Y-m-d", strtotime("+1 day", strtotime($current_month__first_date)));
				 }

		 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
		
		//$addtional_days=0;

		 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
		
		
		$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
		$scale_days = $days*$type_data->maximum;
		
		
		$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
		$response['completed'] = $completed;

		$scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
		$scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

	    $completed = $completed+$addtional_days;

		$response['completed_days'] = $completed;

		$scale_completed = $scale_completed_days+$scale_additional_days;
		$monthly_average = 0;
		if($days > 0)
		{
		   $monthly_average = $scale_completed/$days;                        
		}

		$response['lowest'] = $scale_completed_days['lowest'];
		$response['highest'] = $scale_completed_days['highest'];
	    $response['monthly_total'] = $scale_completed;
		$response['monthly_average'] = round($monthly_average);
		}
		return $response;

	}


	public function dayByHabitIdAverage($habit_id,$start_date,$end_date)
	{
		$habit = Goals::where('id',$habit_id)->first();
		//var_dump($length);

		$type_data = Self::get_habit_types($habit->id);

		$habit_start_date = date("Y-m-d",strtotime($habit->habit_start_date));
		$start_date = date("Y-m-d",strtotime($start_date));
		$end_date = date("Y-m-d",strtotime($end_date));

		$current_month_date = date("Y-m-d");

		$labels = array();
		
		$datasets = array();
		
		$allowed_days = explode(",", $type_data->value);
		$current = date("Y-m",strtotime($current_month_date));
		$habitMonth = date("Y-m",strtotime($habit_start_date));
		
		$log = $this->get_log($habit->id, $habit_start_date);
		$i =0;  
		while (strtotime($start_date) <= strtotime($end_date)) {
		    if($start_date >= $habit->habit_start_date){
			/*$day_of_week = Carbon::parse($habit_start_date)->dayOfWeek;
				
			if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
				$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
			}else if($type_data->value != 7){
				$not_applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
			}else if($type_data->value == 7){
				$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
			}*/
			$value = $this->get_completed_days_for_Graph_scale_number($habit_id,$type_data->is_scale,$start_date);
			if($value != -1)
			{
			$datasets[$i] = $value;
			$labels[$i] = date("d.m",strtotime($start_date));
			$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
			$i++;
			}
			else
			{
				$start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
				continue;
			}
		}
		   
		 }

	    $response['labels'] = $labels;
		$response['datasets'] = $datasets;
		$response['graph']	= "Day Graph";
		$response['label1']	= "Yearly Total";
		$response['label2']	= "";
		$response['xAxes'] = "Days";
		$response['yAxes'] = "Scale Number";
		return $response;
	}

	public function getMonthScaleAndTotal($habit_id,$start_date,$end_date)
	{
		//echo $start_date;
		//echo $end_date;die;
		$habit = Goals::where('id',$habit_id)->first();
		
		$type_data = Self::get_habit_types($habit->id);

		$habit_start_date = date("Y-m-d",strtotime($habit->habit_start_date));

		$current_month_date = date("Y-m-d");

		$allowed_days = explode(",", $type_data->value);
		$current = date("Y-m",strtotime($current_month_date));
		$habitMonth = date("Y-m",strtotime($habit_start_date));
		$start_month = date("Y-m",strtotime($start_date));
		$end_month = date("Y-m",strtotime($end_date));
		$monthly_tot = array();
		$monthly_aver   = array();
		$month_lowest  = array();
		$month_highest = array();
		$labels = array();
		//$scale_completed_days = array();
		$i =0;
		while(strtotime($start_month) <= strtotime($end_month))
		{

			$applicable_dates = array();
			$not_applicable_dates = array();
			$scale_completed =0;
			$days = 0;
				if($start_month == $habitMonth && $start_month != $current)
				{
					$month = date("m",strtotime($start_month));
					$year = date("Y",strtotime($start_month));
					$day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$current_month_date = date($year."-".$month."-".$day);
					$log = $this->get_log($habit->id, $habit_start_date);

						while (strtotime($habit_start_date) <= strtotime($current_month_date)) {
						    if($habit_start_date >= $habit->habit_start_date){
							$day_of_week = Carbon::parse($habit_start_date)->dayOfWeek;
								
							if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
								$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}else if($type_data->value != 7){
								$not_applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}else if($type_data->value == 7){
								$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}
					}
						    $habit_start_date = date ("Y-m-d", strtotime("+1 day", strtotime($habit_start_date)));
						 }

				 $habit_all_dates = array_merge($applicable_dates,$not_applicable_dates);

				 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
				
				//$addtional_days=0;

				 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
				
				
				$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
				$scale_days = $days*$type_data->maximum;
				
				
				$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
				$response['completed'] = $completed;

				 $scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);

				 $highest_lowest = $this->get_maximum_and_minimum_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit_all_dates);
				 
				 $scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

			    $completed = $completed+$addtional_days;

				$response['completed_days'] = $completed;

			    $scale_completed = $scale_completed_days+$scale_additional_days;
			    $monthly_average = 0;
				if($days > 0)
				{
				   $monthly_average = $scale_completed/$days;                        
				}
			    //$monthly_average = $scale_completed/$days;
				$month_lowest[$i] = $highest_lowest['lowest'];
				$month_highest[$i] = $highest_lowest['highest'];
			    $monthly_tot[$i] = $scale_completed;
				$monthly_aver[$i] = round($monthly_average);
				}
				else if($start_month == $habitMonth && $start_month == $current)
				{
					$month = date("m",strtotime($start_month));
					$year = date("Y",strtotime($start_month));
					$day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
					$current_month_date = date("Y-m-d");
					$log = $this->get_log($habit->id, $habit_start_date);

						while (strtotime($habit_start_date) < strtotime($current_month_date)) {
						    if($habit_start_date >= $habit->habit_start_date){
							$day_of_week = Carbon::parse($habit_start_date)->dayOfWeek;
								
							if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
								$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}else if($type_data->value != 7){
								$not_applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}else if($type_data->value == 7){
								$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}
					}
						    $habit_start_date = date ("Y-m-d", strtotime("+1 day", strtotime($habit_start_date)));
						 }

				 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
				
				//$addtional_days=0;

				 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
				
				
				$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
				$scale_days = $days*$type_data->maximum;
				
				
				$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
				$response['completed'] = $completed;

				 $scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
				 
				 $scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);
				 $habit_all_dates = array_merge($applicable_dates,$not_applicable_dates);
				 $highest_lowest = $this->get_maximum_and_minimum_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit_all_dates);
				$month_lowest[$i] = $highest_lowest['lowest'];
				$month_highest[$i] = $highest_lowest['highest'];

			    $completed = $completed+$addtional_days;

				$response['completed_days'] = $completed;

			    $scale_completed = $scale_completed_days+$scale_additional_days;
			    $monthly_average = 0;
				if($days > 0)
				{
				   $monthly_average = $scale_completed/$days;                        
				}
			    //$monthly_average = $scale_completed/$days;
			    $monthly_tot[$i] = $scale_completed;
				$monthly_aver[$i] = round($monthly_average);
				}
			 else if($start_month == $current)
			 {

				$month = date("m",strtotime($start_month));
				$year = date("Y",strtotime($start_month));
				//$current_month_start_date; //= date ("Y-m-d", strtotime("+1 day", strtotime($current_month_start_date)));
				$current_month_date = date("Y-m-d");
				//$day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$current_month_start_date = date($year."-".$month."-01");
				$log = $this->get_log($habit->id, $habit_start_date);

					while (strtotime($current_month_start_date) < strtotime($current_month_date)) {
					    if($current_month_start_date >= $habit->habit_start_date){
						$day_of_week = Carbon::parse($current_month_start_date)->dayOfWeek;
							
						if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
							$applicable_dates[$current_month_start_date] = "'".$current_month_start_date."'";
						}else if($type_data->value != 7){
							$not_applicable_dates[$current_month_start_date] = "'".$current_month_start_date."'";
						}else if($type_data->value == 7){
							$applicable_dates[$current_month_start_date] = "'".$current_month_start_date."'";
						}
				}
					    $current_month_start_date = date ("Y-m-d", strtotime("+1 day", strtotime($current_month_start_date)));
					 }

					 // echo "<pre/>";
					 //print_r($applicable_dates);die;

			 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
			
			//$addtional_days=0;

			 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
			
			
			$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
			$scale_days = $days*$type_data->maximum;
			
			
			$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
			$response['completed'] = $completed;

			 $scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
			    /*echo "<pre/>";
			    print_r($scale_completed_days);die;  */
			 $scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);
			 $habit_all_dates = array_merge($applicable_dates,$not_applicable_dates);
			 $highest_lowest = $this->get_maximum_and_minimum_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit_all_dates);
			
			$month_lowest[$i] = $highest_lowest['lowest'];
			$month_highest[$i] = $highest_lowest['highest'];

		    $completed = $completed+$addtional_days;

			$response['completed_days'] = $completed;

		    $scale_completed = $scale_completed_days+$scale_additional_days;
		    $monthly_average = 0;
			if($days > 0)
			{
			   $monthly_average = $scale_completed/$days;                        
			}
		    //$monthly_average = $scale_completed/$days;
		    $monthly_tot[$i] = $scale_completed;
			$monthly_aver[$i] = round($monthly_average);
			}
		else
		{
			
			$month = date("m",strtotime($start_month));
			$year = date("Y",strtotime($start_month));
			$day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
			$current_month__first_date = date($year."-".$month."-01");
			$current_month_end_date = date($year."-".$month."-".$day);
			$log = $this->get_log($habit->id, $habit_start_date);

				while (strtotime($current_month__first_date) < strtotime($current_month_end_date)) {
				    if($current_month__first_date >= $habit->habit_start_date){
					$day_of_week = Carbon::parse($current_month__first_date)->dayOfWeek;
						
					if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
						$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
					}else if($type_data->value != 7){
						$not_applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
					}else if($type_data->value == 7){
						$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
					}
			}
				    $current_month__first_date = date ("Y-m-d", strtotime("+1 day", strtotime($current_month__first_date)));
				 }

				 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
				
				//$addtional_days=0;

				 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
				
				
				$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
				$scale_days = $days*$type_data->maximum;
				
				
				$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
				$response['completed'] = $completed;

				$scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
				 
				$scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

				$habit_all_dates = array_merge($applicable_dates,$not_applicable_dates);
				 $highest_lowest = $this->get_maximum_and_minimum_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit_all_dates);
				$month_lowest[$i] = $highest_lowest['lowest'];
				$month_highest[$i] = $highest_lowest['highest'];

			    $completed = $completed+$addtional_days;

				$response['completed_days'] = $completed;

				$scale_completed = $scale_completed_days+$scale_additional_days;
				$monthly_average = 0;
				if($days > 0)
				{
				   $monthly_average = $scale_completed/$days;                        
				}

			    $monthly_tot[$i] = $scale_completed;
				$monthly_aver[$i] = round($monthly_average);
				}
				$labels[$i] = date("Y-m",strtotime($start_month));
				$start_month = date ("Y-m", strtotime("+1 month", strtotime($start_month)));
				$i++;	
		}
				$response['lowest'] = $month_lowest;
				$response['highest'] = $month_highest;
				$response['monthly_tot'] = $monthly_tot;
				$response['monthly_aver'] = $monthly_aver;
				$response['labels']       = $labels;
				$response['xAxes'] = "Month";
				$response['yAxes'] = "Scale Number";
				/*echo "<pre/>";
				print_r($response);die;*/
				return $response;
	}


public function getYearScaleAndTotal($habit_id,$start_date,$end_date)
	{
		$habit = Goals::where('id',$habit_id)->first();
		//var_dump($length);

		$type_data = Self::get_habit_types($habit->id);

		$habit_start_date = date("Y-m-d",strtotime($habit->habit_start_date));

		$current_month_date = date("Y-m-d");

		$allowed_days = explode(",", $type_data->value);
		$current = date("Y-m",strtotime($current_month_date));
		$habitMonth = date("Y-m",strtotime($habit_start_date));
		$habitYear = date("Y",strtotime($habit_start_date));
		$start_year = $start_date;
		$end_year   = $end_date;

		$last_year = Carbon::now();
		$lastYear = $last_year->addDays(365);
		$lastYear = date("Y-m-d",strtotime($lastYear));
		$lastGraphYear = date("Y",strtotime($lastYear));
		$currentYear = date("y");
		$yearly_tot = array();
		$yearly_aver   = array();
		$labels = array();
		$i =0;
		while(strtotime($start_year) <= strtotime($end_year))
		{
			$applicable_dates = array();
			$not_applicable_dates = array();
			$scale_completed =0;
			$days = 0;
				if($start_year == $habitYear)
				{
					$day = cal_days_in_month(CAL_GREGORIAN, 12, $start_year);
					$end_month_date = date($start_year."-"."12-".$day);
					$log = $this->get_log($habit->id, $habit_start_date);

						while (strtotime($habit_start_date) < strtotime($end_month_date)) {
						    if($habit_start_date >= $habit->habit_start_date){
							$day_of_week = Carbon::parse($habit_start_date)->dayOfWeek;
								
							if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
								$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}else if($type_data->value != 7){
								$not_applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}else if($type_data->value == 7){
								$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}
					}
						    $habit_start_date = date ("Y-m-d", strtotime("+1 day", strtotime($habit_start_date)));
						 }


				 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
				
				//$addtional_days=0;

				 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
				
				
				$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
				$scale_days = $days*$type_data->maximum;
				
				
				$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
				$response['completed'] = $completed;

				$scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
				$scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

			    $completed = $completed+$addtional_days;

				$response['completed_days'] = $completed;

			    $scale_completed = $scale_completed_days+$scale_additional_days;
			    $monthly_average = 0;
				if($days > 0)
				{
				   $monthly_average = $scale_completed/$days;                        
				}
			    //$monthly_average = $scale_completed/$days;

			    $yearly_tot[$i] = $scale_completed;
				$yearly_aver[$i] = round($monthly_average);
				}
				else if($start_year == $lastGraphYear)
				{
				//$day = cal_days_in_month(CAL_GREGORIAN, 12, $year);
				$current_month__first_date = date($start_year."-01","-01");
				//$current_month_end_date = date($year."-12","-".$day);
				$log = $this->get_log($habit->id, $habit_start_date);

					while (strtotime($current_month__first_date) < strtotime($lastYear)) {
					    if($current_month__first_date >= $habit->habit_start_date){
						$day_of_week = Carbon::parse($current_month__first_date)->dayOfWeek;
							
						if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
							$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
						}else if($type_data->value != 7){
							$not_applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
						}else if($type_data->value == 7){
							$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
						}
				}
					    $current_month__first_date = date ("Y-m-d", strtotime("+1 day", strtotime($current_month__first_date)));
					 }

				 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
				
				//$addtional_days=0;

				 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
				
				
				$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
				$scale_days = $days*$type_data->maximum;
				
				
				$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
				$response['completed'] = $completed;

				$scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
				$scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

			    $completed = $completed+$addtional_days;

				$response['completed_days'] = $completed;

				$scale_completed = $scale_completed_days+$scale_additional_days;
				$monthly_average = 0;
				if($days > 0)
				{
				   $monthly_average = $scale_completed/$days;                        
				}
			    $yearly_tot[$i] = $scale_completed;
				$yearly_aver[$i] = round($monthly_average);
				}
				else
				{
					$day = cal_days_in_month(CAL_GREGORIAN, 12, $start_year);
					$current_month__first_date = date($start_year."-01","-01");
					$current_month_end_date = date($start_year."-12","-".$day);
					$log = $this->get_log($habit->id, $habit_start_date);

					while (strtotime($current_month__first_date) < strtotime($current_month_end_date)) {
					    if($current_month__first_date >= $habit->habit_start_date){
						$day_of_week = Carbon::parse($current_month__first_date)->dayOfWeek;
							
						if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
							$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
						}else if($type_data->value != 7){
							$not_applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
						}else if($type_data->value == 7){
							$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
						}
				}
					    $current_month__first_date = date ("Y-m-d", strtotime("+1 day", strtotime($current_month__first_date)));
					 }

				 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
				
				//$addtional_days=0;

				 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
				
				
				$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
				$scale_days = $days*$type_data->maximum;
				
				
				$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
				$response['completed'] = $completed;

				$scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
				$scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

			    $completed = $completed+$addtional_days;

				$response['completed_days'] = $completed;

				$scale_completed = $scale_completed_days+$scale_additional_days;
				$monthly_average = 0;
				if($days > 0)
				{
				   $monthly_average = $scale_completed/$days;                        
				}
			    $yearly_tot[$i] = $scale_completed;
				$yearly_aver[$i] = round($monthly_average);

				}

				$labels[$i] = $start_year;
				$start_year++;
				$i++;	
		}

				$response['yearly_tot'] = $yearly_tot;
				$response['yearly_aver'] = $yearly_aver;
				$response['labels']       = $labels;
				$response['graph']	= "Yearly Graph";
				$response['label1']	= "Yearly Total";
				$response['label2']	= "Yearly Average";
				$response['xAxes'] = "Years";
				$response['yAxes'] = "Scale Number";
				return $response;
	}

		public function getMonthly_averageToChangeMonth($habit_id,$month,$year,$check)
	    {
	    	//echo $month;die;
		$habit = Goals::where('id',$habit_id)->first();
		//var_dump($length);
		//$current_month__first_date = date($year."-".$month."01");
		
		$type_data = Self::get_habit_types($habit->id);

		$habit_start_date = date("Y-m-d",strtotime($habit->habit_start_date));
		$newmonth = $month;
		if($month <= 9)
		{
			   $newmonth = "0".$month;
		}
		 $length = cal_days_in_month(CAL_GREGORIAN, $newmonth, $year);
		$current_month_date = date($year."-".$newmonth."-".$length);

		$applicable_dates = array();
		
		$not_applicable_dates = array();
		$highest = 0;
		$lowest = 0;
		$allowed_days = explode(",", $type_data->value);
		$current = date($year."-".$newmonth);
		$habitMonth = date("Y-m",strtotime($habit_start_date));
		if($habitMonth == $current)
		{
			//echo "if";die;
		    $current = date($year."-".$newmonth);
		    $current_month_value = date("Y-m-d");
		    $current_month = date("Y-m");
		    $habitMonth = date("Y-m",strtotime($habit_start_date));
			if($current == $current_month)
			{
				//echo "reached if";die;
				$log = $this->get_log($habit->id, $habit_start_date);
				if($check ==2 )
				{
						while (strtotime($habit_start_date) < strtotime($current_month_value)) {
						    if($habit_start_date >= $habit->habit_start_date){
							$day_of_week = Carbon::parse($habit_start_date)->dayOfWeek;
								
							if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
								$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}else if($type_data->value != 7){
								$not_applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}else if($type_data->value == 7){
								$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
							}
					}
						    $habit_start_date = date ("Y-m-d", strtotime("+1 day", strtotime($habit_start_date)));
						 }
				}
				else
				{
					while (strtotime($habit_start_date) < strtotime($current_month_value)) {
						    if($habit_start_date >= $habit->habit_start_date){
								$day_of_week = Carbon::parse($habit_start_date)->dayOfWeek;
								$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
					       }

						    $habit_start_date = date ("Y-m-d", strtotime("+1 day", strtotime($habit_start_date)));
						}
				}
				 /*echo "<pre/>";
				 print_r($not_applicable_dates);
				 print_r($applicable_dates);*/

			     $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates,1);	//not applicable but still checked..
			
			//$addtional_days=0;

			     $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates,1);// if disabled allowed dates...
			
				if($check == 2)
				{
					   $days = (count($applicable_dates)+$addtional_days)-$disabled_days;
					   $scale_days = $days*$type_data->maximum;	
				}
				else
				{
					$days = (count($applicable_dates)+$addtional_days);
				    $scale_days = $days*$type_data->maximum;
				}
		
				    $completed = $this->get_completed_month_dates($habit->id, $applicable_dates,1);
					$response['completed'] = $completed;

					 $scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
					 $scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

				    $completed = $completed+$addtional_days;

					$response['completed_days'] = $completed;

					$habit_all_dates = array_merge($applicable_dates,$not_applicable_dates);
				    $highest_lowest = $this->get_maximum_and_minimum_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit_all_dates);
				    //$month_lowest[$i] = $highest_lowest['lowest'];
				    //$month_highest[$i] = $highest_lowest['highest'];

				    $scale_completed = $scale_completed_days+$scale_additional_days;
				    $monthly_average = 0;
					if($days > 0)
					{
					   $monthly_average = $scale_completed/$days;                        
					}

				    //$monthly_average = $scale_completed/$days;
				    if($highest_lowest['lowest'])
				    {
				    	$highest = $highest_lowest['lowest'];
				    }

				    if($highest_lowest['lowest'])
				    {
				    	$lowest = $highest_lowest['lowest'];
				    }
				    
					$response['lowest'] = $lowest;
					$response['highest'] = $highest;
				    $response['monthly_total'] = $scale_completed;
					$response['monthly_average'] = $monthly_average;
			}
			else
			{
				$log = $this->get_log($habit->id, $habit_start_date);
					if($check == 2)
					{
						while (strtotime($habit_start_date) <= strtotime($current_month_date)) {
						    if($habit_start_date >= $habit->habit_start_date){
							    $day_of_week = Carbon::parse($habit_start_date)->dayOfWeek;
								
								if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
									$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
								}else if($type_data->value != 7){
									$not_applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
								}else if($type_data->value == 7){
									$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
								}
					        }
						    $habit_start_date = date ("Y-m-d", strtotime("+1 day", strtotime($habit_start_date)));
						 }
				    }else
				    {
				    	while (strtotime($habit_start_date) < strtotime($current_month_date)){
						    if($habit_start_date >= $habit->habit_start_date){
							    $day_of_week = Carbon::parse($habit_start_date)->dayOfWeek;
								$applicable_dates[$habit_start_date] = "'".$habit_start_date."'";
					        }
						    $habit_start_date = date ("Y-m-d", strtotime("+1 day", strtotime($habit_start_date)));
						 }
				    }
				 
				 $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
				
				//$addtional_days=0;

				 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates,1); // if disabled allowed dates...
				
				
				/*$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
				$scale_days = $days*$type_data->maximum;*/

				
				if($check == 2)
				{
					  $days = (count($applicable_dates)+$addtional_days)-$disabled_days;
					  $scale_days = $days*$type_data->maximum;	
				}
				else
				{
					$days = (count($applicable_dates)+$addtional_days)+$disabled_days;
				    $scale_days = $days*$type_data->maximum;
				}
				
				$completed = $this->get_completed_month_dates($habit->id, $applicable_dates,1);
				$response['completed'] = $completed;

				 $scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
				 $scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

			    $completed = $completed+$addtional_days;

				$response['completed_days'] = $completed;

				$habit_all_dates = array_merge($applicable_dates,$not_applicable_dates);
				 $highest_lowest = $this->get_maximum_and_minimum_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit_all_dates);
				
			    $scale_completed = $scale_completed_days+$scale_additional_days;
			    $monthly_average = 0;
				if($days > 0)
				{
				   $monthly_average = $scale_completed/$days;                        
				}
			    //$monthly_average = $scale_completed/$days;
				 if($highest_lowest['lowest'])
				    {
				    	$highest = $highest_lowest['lowest'];
				    }

				    if($highest_lowest['lowest'])
				    {
				    	$lowest = $highest_lowest['lowest'];
				    }
				    
				$response['lowest'] = $lowest;
				$response['highest'] = $highest;
			    $response['monthly_total'] = $scale_completed;
				$response['monthly_average'] = $monthly_average;
			}

		}
		else
		{
			$current_month__first_date = date($year."-".$newmonth."-01");
			$select_month = date($year."-".$newmonth);
			$length = cal_days_in_month(CAL_GREGORIAN, $newmonth, $year);
			$today_month_date = date("Y-m-d");
			$today_month = date("Y-m");
			//$length = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		    $current_month_end_date = date($year."-".$newmonth."-".$length);
		    if($select_month == $today_month)
		    {
		    	$log = $this->get_log($habit->id, $habit_start_date);
		    	if($check == 2)
		    	{
						while (strtotime($current_month__first_date) < strtotime($today_month_date)) {
						    if($current_month__first_date >= $habit->habit_start_date){
							    $day_of_week = Carbon::parse($current_month__first_date)->dayOfWeek;
								
								if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
									$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
								}else if($type_data->value != 7){
									$not_applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
								}else if($type_data->value == 7){
									$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
								}
					       }
						    $current_month__first_date = date ("Y-m-d", strtotime("+1 day", strtotime($current_month__first_date)));
						 }

				}
				else
				{
					while (strtotime($current_month__first_date) < strtotime($today_month_date)) {
				    if($current_month__first_date >= $habit->habit_start_date){
						$day_of_week = Carbon::parse($current_month__first_date)->dayOfWeek;
						$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
			         }
				     $current_month__first_date = date ("Y-m-d", strtotime("+1 day", strtotime($current_month__first_date)));
				   }
				}
		    }
		    else{

		    	       $log = $this->get_log($habit->id, $habit_start_date);

				    	if($check == 2)
				    	{
						          while (strtotime($current_month__first_date) < strtotime($current_month_end_date)) {
									    if($current_month__first_date >= $habit->habit_start_date){
										$day_of_week = Carbon::parse($current_month__first_date)->dayOfWeek;
											
									if($type_data->value !=7 && in_array($day_of_week, $allowed_days)){
										$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
									}else if($type_data->value != 7){
										$not_applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
									}else if($type_data->value == 7){
										$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
									}
								}
						        $current_month__first_date = date ("Y-m-d", strtotime("+1 day", strtotime($current_month__first_date)));
						     }

						}
						else
						{
							while (strtotime($current_month__first_date) < strtotime($current_month_end_date)) {
									    if($current_month__first_date >= $habit->habit_start_date){
										$day_of_week = Carbon::parse($current_month__first_date)->dayOfWeek;
										$applicable_dates[$current_month__first_date] = "'".$current_month__first_date."'";
					                   }
						           $current_month__first_date = date ("Y-m-d", strtotime("+1 day", strtotime($current_month__first_date)));
						         }
						}
		    }


		    	 $habit_all_dates = array_merge($applicable_dates,$not_applicable_dates);
				 $highest_lowest = $this->get_maximum_and_minimum_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $habit_all_dates);
				//$month_lowest[$i] = $highest_lowest['lowest'];
				//$month_highest[$i] = $highest_lowest['highest'];

		  $addtional_days = $this->get_additional_completed_month_dates_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum,$not_applicable_dates, 1);	//not applicable but still checked..
		
		//$addtional_days=0;

		 $disabled_days = $this->get_disabled_month_dates_scale_number($habit->id,$type_data->is_scale, $applicable_dates, 1); // if disabled allowed dates...
		
		if($check == 2)
		{
			$days = (count($applicable_dates)+$addtional_days)-$disabled_days;
		    $scale_days = $days*$type_data->maximum;
		}
		else
		{
			$days = (count($applicable_dates)+$addtional_days);
		    $scale_days = $days*$type_data->maximum;
		}
		
		$completed = $this->get_completed_month_dates($habit->id, $applicable_dates, 1);
		$response['completed'] = $completed;

		$scale_completed_days = $this->get_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $applicable_dates);
		$scale_additional_days = $this->get_additional_completed_month_dates_for_percentage_scale_number($habit->id,$type_data->is_scale,$type_data->minimum,$type_data->maximum, $not_applicable_dates);

	    $completed = $completed+$addtional_days;

		$response['completed_days'] = $completed;

		$scale_completed = $scale_completed_days+$scale_additional_days;

		$monthly_average = 0;
		if($days > 0)
		{
		   $monthly_average = $scale_completed/$days;                        
		}

	    //$monthly_average = $scale_completed/$days;
		 if($highest_lowest['lowest'])
	    {
	    	$highest = $highest_lowest['lowest'];
	    }

	    if($highest_lowest['lowest'])
	    {
	    	$lowest = $highest_lowest['lowest'];
	    }
	    
		$response['lowest'] = $lowest;
		$response['highest'] = $highest;
	    $response['monthly_total'] = $scale_completed;
		$response['monthly_average'] = $monthly_average;	    
		}
		/*echo "<pre/>";
		print_r($response);die;*/
		return $response;

	}

	public function get_max_list_order($user_id){
		return DB::table($this->table)/*->where("user_id",$user_id)*/->max('list_order');
	}


	public function get_max_detail_order($task_id){
		return DB::table($this->table)->where("parent_id",$task_id)->max('detail_order');
	}

	public function get_max_self_order($user_id){
		return DB::table($this->table)/*->where("user_id",$user_id)*/->max('self_order');
	}

	public function addtomylist($goal_id, $user_id){

		$_goal=$this->_get_goal($goal_id);

		if($_goal){

	    	/*echo "<pre/>";
	    	print_r($_goal);
	    	exit();*/

	    	$goal=$_goal->replicate();
	    	
	    	if($goal){
	    		
	    		//
				$children=isset($goal->children)?$goal->children:null;
				
				unset($goal->id);

				unset($goal->children);
				
				unset($goal->type);
				
				unset($goal->habit_types);

	    		$goal->user_id=$user_id;
	    		
	    		$goal->auto_save_id=$this->generate_autosaveid();
	    		$goal->is_default=0;
	    		
	    		$goal->list_order=$this->get_max_list_order($user_id)+1; // default max number
	    		$goal->self_order=$this->get_max_self_order($user_id)+1; // default max number
	    		
	    		$goal->created_at=date("Y-m-d H:i:s");
	    		$goal->updated_at=date("Y-m-d H:i:s");
	    		
	    		$goal->parent_id=0;
	    		$goal->top_parent_id=0;

	    		$goal->save();
	    		
	    		$_meta =array();
    			$meta_attr=array();

    			$meta_attr['addtolist_parent_id']	=	$_goal->id;
    			$meta_attr['status']	=	clean_html(nl2br($_goal->status));//$_goal->id;

	    		$_meta['meta_key']	=	"addtomylist_id";
	    		$_meta['goal_id']	=	$goal->id;
	    		$_meta['meta_attr']	=	json_encode($meta_attr);


	    		GoalsMeta::add($_meta);

	    		if($children){

	    			$parent_id=$goal->id;
	    			$top_parent_id=$goal->id;
	    			$this->addchildtomylist($children, $user_id, $parent_id, $top_parent_id);
	    		}
	    	}

	    	return $goal;
    	}else{
    		return false;
    	}
	}

	public function addchildtomylist($goals, $user_id, $parent_id, $top_parent_id){
		
		if($goals){
			
			foreach ($goals as $key => $goal) {

				$_goal=$goal->replicate();

				$children=isset($_goal->children)?$_goal->children:null;
				
				unset($_goal->id);

				unset($_goal->children);
				
				unset($_goal->type);
				
				unset($_goal->habit_types);
	    		
	    		$_goal->user_id=$user_id;
	    		
	    		$_goal->auto_save_id=$this->generate_autosaveid();
	    		
	    		$_goal->is_default=0;
	    		
	    		$_goal->parent_id=$parent_id;
	    		
	    		$_goal->top_parent_id=$top_parent_id;

	    		$_goal->list_order=-1;//$this->get_max_list_order($user_id)+1; // default max number
    			
    			$_goal->self_order=-1;//$this->get_max_self_order($user_id)+1; // default max number
	    		
	    		$_goal->created_at=date("Y-m-d H:i:s");
    			
    			$_goal->updated_at=date("Y-m-d H:i:s");
	    		
	    		$_goal->save();

	    		if($children){
	    			
	    			$this->addchildtomylist($children, $user_id, $_goal->id, $top_parent_id);
    			}

			}

			return $goals;
		}
	}


	function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber) {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        $dateArr = array();

        do {
            if (date("w", $startDate) != $weekdayNumber) {
                $startDate += (24 * 3600); // add 1 day
            }
        } while (date("w", $startDate) != $weekdayNumber);


        while ($startDate <= $endDate) {
            $dateArr[] = date('Y-m-d', $startDate);
            $startDate += (7 * 24 * 3600); // add 7 days
        }

        return($dateArr);
    }

    public static function countDays($day, $start, $end,$not_applicble='') {
        //get the day of the week for start and end dates (0-6)
      
        $w = array(date('w', $start), date('w', $end));

        //get partial week day count
        if ($w[0] < $w[1]) {
            $partialWeekCount = ($day >= $w[0] && $day <= $w[1]);
        } else if ($w[0] == $w[1]) {
            $partialWeekCount = $w[0] == $day;
        } else {
            $partialWeekCount = ($day >= $w[0] || $day <= $w[1]);
        }

        //first count the number of complete weeks, then add 1 if $day falls in a partial week.
        return floor(( $end - $start ) / 60 / 60 / 24 / 7) + $partialWeekCount;
    }

    public static function getna_days($not_applicble,$v,$habit_end_dates_arr)
	{
		error_reporting(0);
		$na_c = 0;
		$current_month = date('m', strtotime($habit_end_dates_arr));
        $current_year = date('Y', strtotime($habit_end_dates_arr));
		$month = $current_year.'-'.$current_month;
		//echo "<pre>";
		//print_r($not_applicble);exit;
		$not_applicble = $not_applicble[$month];

		
		if(is_array($not_applicble) && count($not_applicble) > 0) {	
			//print_r($not_applicble);
			for($i=0;$i<count($not_applicble);$i++) {
				$w = date("w",strtotime($not_applicble[$i]));
				if($w == $v) {
					$na_c++;
				}					
			}
		}
		
		return $na_c;
		
	}

	public static function getHabitPercent2($habit_end_dates, $habit_dates, $habitTypesValue, $d, $not_applicble) {

        $habit_start_dates = date('Y-m-d');
        if ($d == 0) {
            $habit_start_dates = date("Y-m-01");
        } else {
            $habit_start_dates = date("Y-m-01", strtotime(date("Y-m-01", strtotime($habit_end_dates)) . "-0 month"));
        }
        //echo  $habit_start_dates.$habit_end_dates;
        $day_array = array(
            'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'
        );
        $total_req = 0;
        $totalcountDays = 0;
        $count = '0';
        $per = '0';
        $date_day_array = array();
        $habitTypesValue = explode(',', $habitTypesValue);
        //echo "<pre>";
        //print_r($habitTypesValue);
        if (!empty($habitTypesValue) && !empty($habit_end_dates)) {

            foreach ($habitTypesValue as $k => $v) {
                $totalcountDays += Self::countDays($v, strtotime($habit_end_dates), strtotime($habit_start_dates));
				$na = Self::getna_days($not_applicble,$v,$habit_end_dates);
				//echo "<hr>";
				
                $ddarray = Self::getDateForSpecificDayBetweenDates($habit_start_dates, $habit_end_dates, $v);
                $date_day_array = array_merge($ddarray, $date_day_array);
                $total_req += count($ddarray);
				$total_req = $total_req - $na;
                //print_r($ddarray);	
            }

            if (!empty($habit_dates)) {
                foreach ($habit_dates as $k => $v) {
                    if (in_array($v, $date_day_array)) {
                        $count++;
                    }
                }
                
                if($totalcountDays > 0){
                    $per = round(($count / $totalcountDays) * 100);
                    $per = -$per;
                }else{
                    $per ="0";
                }
                
            }
        }

        $response['count'] = $count;
        $response['totalcountDays'] = $totalcountDays;

        $per = round(($count / $total_req) * 100);

        $response['percent'] = $per;

        return $response;
    }

	public function generate_autosaveid(){
		//LPAD(FLOOR(RAND()*99999),5,0)
		$sql='SELECT LPAD(FLOOR(RAND()*9999999999),10,0) AS random_num FROM tbl_goals WHERE "random_num" NOT IN (SELECT auto_save_id FROM tbl_goals) LIMIT 1';
		$results = DB::select( DB::raw($sql) )[0];
		if($results){
			return $results->random_num;
		}
		//print_r($results);
	}

	public function validate_item($filter){
		$goal =  Self::whereRaw("top_parent_id=".$filter['top_parent_id']." OR parent_id=".$parent_id)->orderByRaw("self_order ASC")->first();
		if($goal){
			return $goal;
		}
		return false;
	}


		public function addLobby($post,$user_id)
			{
				//echo "<pre/>";
				//print_r($post);die;
			 $data['parent_id'] = $post['lobby_undefined'];
			 $data['top_parent_id'] = $post['lobby_undefined'];
			 
			 $data['type_id']  = $post['type']; 
			 $data['name']  = $post['sub_goals'];
			 $data['level']  = 1;

			 if(empty($post['id']))
			 {
			 		$getDetailOrder = Self::get_child_goals_by_top_ParentId($user_id,$post['lobby_undefined']);
					 foreach ($getDetailOrder as $value) 
					 {
					 	$autoSaveId = $value->auto_save_id;
					 	$detailOrder = $value->detail_order;
					 	$orderDetails = $detailOrder+1;
					 	Self::where('auto_save_id',$autoSaveId)->where('user_id',$user_id)->update(['detail_order'=>$orderDetails]);
					 }

					 $data['list_order'] =$this->get_max_list_order($user_id)+1; // default max number
    				 $data['self_order'] =$this->get_max_self_order($user_id)+1; // default max number
					 $data['detail_order'] = 1;
			 }
			 
			  
			 if($post['type'] == 1)
			{
				$sub_habit_start_date = $post['sub_habit_start_date'];
		        $start_date = date("Y-m-d",strtotime($sub_habit_start_date));
				$data['habit_start_date'] = $start_date;
			}
			else if($post['type'] == 2)
			{
				$sub_habit_start_date = $post['sub_habit_start_date'];
		        $start_date = date("Y-m-d",strtotime($sub_habit_start_date));
				$data['due_date'] = $start_date;
			}
			else
			{
				$data['due_date'] = NULL;
				$data['habit_start_date'] = NULL;
			}
			 $data['user_id'] = $user_id;
			 if(isset($post['id']) && !empty($post['id']))
			 {
			 $data['auto_save_id'] = $post['id'];	
			 }
			 else
			 {
			 	$autosaved_id = Self::generate_autosaveid();
			 	$data['auto_save_id'] = $autosaved_id;
			 }
			 
			$lobbyGoal = Self::updateOrCreate(["auto_save_id"=>$data['auto_save_id']],$data);
			
		//print_r($lobbyGoal);die;
		return $lobbyGoal;

	}



	public function showAndHideInLobby($user_id,$data)
	{
		/*echo "<pre/>";
		print_r($data);die;*/
		$result = DB::table("tbl_goals")->where("auto_save_id",$data['task_id'])->where("user_id",$user_id)->update(["is_show_lobby"=>$data['type']]);
		//print_r($result);die;
		return $result;
	}


public static function getAllActiveSheet($id)
	{
		$attributes = array("status"=>"STATUS - What is your current situation?","improvement"=>"IMPROVEMENT - Do you want to improve the situation? why?","risk"=>"RISK - What effect will it have if you don't improve/change the situation? ","benefits"=>"BENEFIT - What effect will it have if you improve the situation? ","vision"=>"VISION-YEARS - How do you want your situation to be in 1,2,3 years from now? ","vision_decades"=>"VISION - DECADES - How do you want your situation to be in 10 years, 20 years etc? ","barriers"=>"BARRIERS - What might stop you from achieving your goals? ","priority"=>"ACTIONS - What actions do you need to start achieving your goals?","initiative"=>"INITIATIVE - What are you confident about doing yourself? ","help"=>"HELP - What might you want help with? ","support"=>"SUPPORT - Which individuals or groups can help you? ","environment"=>"ENVIRONMENT - Do you plan on using tools, distance, data/statistics or a rewards system to help you reach your goal? If so explain how.","imagery"=>"IMAGERY - Create a list of habits and tasks associated to this goal. Imagine your self executing them successfully. Write down your thoughts.");
		
		$activeSheet = array();
		foreach ($attributes as $key => $value)
		 {
			$attributesValue = GoalsMeta::get_by_attr($id, $key);			
			foreach ($attributesValue as $k => $v) {
				$v['title'] = $value;
				if($v['is_active'] == 1)
				{
					$activeSheet[$key] = $v;
				}				
			}

		}
		return $activeSheet;
	}
}
	    
	  
	   
	   
	   
	

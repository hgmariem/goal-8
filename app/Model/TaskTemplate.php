<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Config;
use Carbon\Carbon;
use App\Model\Goals;
use DB;

class TaskTemplate extends Model
{
	
	protected $table = 'tbl_task_templates';

	protected $guarded = [
        'id',
    ];



    public static function process_task(){

    	$model=new Goals();
    	$crons = Self::where("is_repeat_done",0)->where("status",1)->get();

    	$current_date = Carbon::now();

    	foreach ($crons as $key => $cron) {
    		
    		$skip = $cron->repeat_frequency=='date' && $cron->end_on_value<=date("Y-m-d")?true:false;

    		$skip = $cron->repeat_frequency=='occurrence' && $cron->end_on_value <= Self::get_total_occurrences($cron->id)?true:$skip;
    		

    		$go=false;
    		$task_name=$cron->task_name;

    		if($cron->repeat_frequency=='weeks' && !$skip){
    			
    			$weeks_days  = explode(",", $cron->repeat_on);
    			
    			if(in_array($current_date->dayOfWeek, $weeks_days)){

    				$task_name.=" - week".$current_date->weekOfMonth;
    				$go=true;
    			}
    			
    			# code...

    		}else if(($cron->repeat_frequency=='months' || $cron->repeat_frequency=='years') && !$skip){

    			if($cron->repeat_on_date==date("d")){

    				if($cron->repeat_frequency=='months' && $cron->add_suffix==1){
    					$task_name.=" - ".$current_date->format("F");
    				}else{
    					$task_name.=" - ".$current_date->year;
    				}

    				$go=true;
    			}

    			# code...
    		}

    		if($go){
	    		$_task=Goals::where("id", $cron->task_id)->first();
	    		
	    		$user_id=$_task->user_id;

	    		$new_task=$_task->replicate();
	    		
	    		$new_task->name=$task_name;
	    		$new_task->auto_save_id=$model->generate_autosaveid();
	    		$new_task->parent_id=$_task->id;
	    		$new_task->task_template_id=$cron->id;
	    		$new_task->has_sub=0;

	    		$new_task->list_order=$model->get_max_list_order($user_id)+1; // default max number
	    		$new_task->self_order=$model->get_max_self_order($user_id)+1; // default max number
		    	$new_task->created_at=date("Y-m-d H:i:s");
	    		$new_task->updated_at=date("Y-m-d H:i:s");
	    		$new_task->save();
    		}
    	}
    }


    public static function clone_task($id, $data){
    	$model=new Goals();
    	$_task=$model->where("id", $id)->first();
	    		
		$user_id=$_task->user_id;

		$new_task=$_task->replicate();
		
		$new_task->name=$data['task_name'];
		$new_task->auto_save_id=$model->generate_autosaveid();
		$new_task->parent_id=$_task->id;
		$new_task->task_template_id=$data['template_id'];
		$new_task->due_date=$data['due_date'];
		$new_task->has_sub=0;

		$new_task->list_order=$model->get_max_list_order($user_id)+1; // default max number
		$new_task->self_order=$model->get_max_self_order($user_id)+1; // default max number
    	$new_task->created_at=date("Y-m-d H:i:s");
		$new_task->updated_at=date("Y-m-d H:i:s");
		$new_task->save();
    }

    public function get_total_occurrences($template_id){
    	
    	$goal = new Goals();

    	$total=$goal->where("task_template_id",$template_id)->get()->count();

    	return $total;
    }

    public static function process_by_id($id){

    	$task = Self::where("id", $id)->where("is_repeat_done",0)->where("status",1)->first();


    	$current_date = Carbon::now();

    	$total_repeats = 0;

    	$deadlines=array();

    	$task_name=$task->task_name;

    	$model=new Goals();
    	$_goal=$model->where("id", $task->task_id)->first();

    	$repeat_tasks=array();


    	if($task->repeat_frequency=='weeks'){
    		Carbon::setWeekStartsAt(Carbon::SUNDAY); 
    		if($task->ends_on=='date'){
    			$current_date = ($task->begin_on=='now')?Carbon::now():Carbon::parse($task->begin_on_value);
	    		$end = Carbon::parse($task->end_on_value);
	    		$total_repeats = $end->diffInWeeks($current_date)+2; // 2 weeks addition to lapsing days in the first and last week
	    	}else{
	    		$total_repeats = $task->end_on_value;
	    	}
	    	//echo "<pre/>";
	    	//echo "Weeks";
	    	//print_r($total_repeats);die;

	    	$week_counter=1;
	    	for ($i=0; $i < $total_repeats; $i++) {

	    		$current_date = ($task->begin_on=='now')?Carbon::now():Carbon::parse($task->begin_on_value);

	    		$every = $i*$task->repeat_qty;
	  
	    		$current_date->addWeek($every);
	    		
	    		$date = $current_date->startOfWeek();

	    		$current_date->addDays($task->repeat_on);
	    		
	    		$task_date=$current_date->format('Y-m-d');

	    		if($task->begin_on == 'date' && $task->begin_on_value){
	    			$begin_on_date = Carbon::parse($task->begin_on_value);
	    			if($task_date < $begin_on_date){
	    				continue;
	    			}	
	    		}

	    		if($task->ends_on=='date' && $task->end_on_value){  // check in case end on date defined and loop is exeeding...
	    			
	    			$end_on_date = Carbon::parse($task->end_on_value);
	    			
	    			if($task_date > $end_on_date){
	    				continue;
	    			}
	    		}

	    		$task_name=($task->add_suffix)?$task->task_name." - week ".($week_counter):$task->task_name;
	    		
	    		$week_data=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$task->id);

	    		$repeat_tasks[]=$week_data;

	    		//print_r($week_data);
	    		
	    		/*
	    		if($task->ends_on=='date' && $task->end_on_value){  // check in case end on date defined and loop is exeeding...
	    			
	    			$end_on_date = Carbon::parse($task->end_on_value);
	    			
	    			if($end_on_date > $_task_date){
	    				$task_date = $_task_date;
	    				Self::clone_task($task->task_id, $week_data);
	    			}

	    		}else{
	    			$task_date = $_task_date;
	    			
	    		}*/

	    		//Self::clone_task($task->task_id, $week_data);

	    	$week_counter++;
	    		# code...
	    	}



    	}else if($task->repeat_frequency=='months'){
    		
    		if($task->ends_on=='date'){
    			$current_date = ($task->begin_on=='now')?Carbon::now():Carbon::parse($task->begin_on_value);
	    		$end = Carbon::parse($task->end_on_value);
	    		$total_repeats = $end->diffInMonths($current_date)+2;
	    	}else{
	    		$total_repeats = $task->end_on_value;
	    	}
	    	//echo "<pre/>";
	    	//echo "Months";
	    	//print_r($total_repeats);die;
	    	for ($i=0; $i <$total_repeats; $i++) { 
	    		//$current_date = Carbon::now();
	    		 $current_date = ($task->begin_on=='now')?Carbon::now():Carbon::parse($task->begin_on_value);
	    		$every = $i*$task->repeat_qty;
	    		//var_dump($every);
	    		$current_date->addMonthsNoOverflow($every);
	    		if($task->repeat_on=='thisday'){
	    			$day = date("d");
	    			$task_date=$current_date->format('Y-m-d');
	    			//var_dump(date("Y-m-d H:i:s"),$task_date);

	    		}else if($task->repeat_on=='firstday'){
					$day = date("d");
					$start = Carbon::parse(new Carbon('first day of this month'))->day;
					if($day == $start){
						$task_date=$current_date->startOfMonth()->toDateString();
					}else{
						$task_date=$current_date->addMonths(1)->startOfMonth()->toDateString();
					}
	    			
	    		}else{
	    			$task_date=$current_date->endOfMonth()->toDateString();
	    		}
	    		// echo date("m",strtotime($task_date));

	    		if($task->begin_on == 'date' && $task->begin_on_value){
	    			$begin_on_date = Carbon::parse($task->begin_on_value);
	    			if(date("Y-m",strtotime($task_date)) < date("Y-m",strtotime($begin_on_date))){
	    				//echo date("m",strtotime($task_date));
	    				//exit();
	    				continue;
	    			}	
	    		}

	    		if($task->ends_on=='date' && $task->end_on_value){  // check in case end on date defined and loop is exeeding...
	    			
	    			 $end_on_date = Carbon::parse($task->end_on_value);
	    			
	    			if($task_date > $end_on_date){
	    				continue;
	    			}
	    		}

	    		$task_name=($task->add_suffix)?$task->task_name." - ".$current_date->format('F'):$task->task_name;

	    		
    			//$deadlines[]=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$task->id);
    			$month_data=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$task->id);
    			
    			$repeat_tasks[]=$month_data;

    			
    			//Self::clone_task($task->task_id, $month_data);
	    		# code...
	    	}

    	}else if($task->repeat_frequency=='years'){
    		
    		if($task->ends_on=='date'){
    			$current_date = ($task->begin_on=='now')?Carbon::now():Carbon::parse($task->begin_on_value);
	    		$end = Carbon::parse($task->end_on_value);
	    		$total_repeats = $end->diffInYears($current_date);
	    	}else{
	    		$total_repeats = $task->end_on_value;
	    	}
	    	/*echo "<pre/>";
	    	echo "Years";
	    	print_r($total_repeats);die;*/

	    	for ($i=0; $i < $total_repeats; $i++) { 
	    		//$current_date = Carbon::now();
	    		$current_date = ($task->begin_on=='now')?Carbon::now():Carbon::parse($task->begin_on_value);
	    		$every = $i*$task->repeat_qty;
	    		$current_date->addYear($every);
	    		if($task->repeat_on=='thisday'){
	    			$task_date=$current_date->format('Y-m-d');
	    		}else if($task->repeat_on=='firstday'){
	    			$task_date=$current_date->startOfYear()->toDateString();
	    		}else{
	    			$task_date=$current_date->endOfYear()->toDateString();
	    		}

	    		if($task->begin_on == 'date' && $task->begin_on_value){
	    			
	    			$begin_on_date = Carbon::parse($task->begin_on_value)->format("Y");
	    		
	    			if(date("Y",strtotime($task_date)) < $begin_on_date){
	    				continue;
	    			}	
	    		}

	    		if($task->ends_on=='date' && $task->end_on_value){  // check in case end on date defined and loop is exeeding...
	    			
	    			$end_on_date = Carbon::parse($task->end_on_value)->format("Y");

	    			if(date("Y",strtotime($task_date)) > $end_on_date){
	    				continue;
	    			}
	    		}

	    		$task_name=($task->add_suffix)?$task->task_name." - ".$current_date->format('Y'):$task->task_name;

    			//$deadlines[]=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$task->id);
    			$year_data=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$task->id);
    			//Self::clone_task($task->task_id, $year_data);
	    		$repeat_tasks[]=$year_data;
	    		# code...
	    	}

    	}

    	if(!empty($repeat_tasks)){

	    	$last_task = end($repeat_tasks);

	    	$_goal->due_date=$last_task['due_date'];

	    	$_goal->save();

	    	foreach ($repeat_tasks as $key => $_task) {
	    		Self::clone_task($task->task_id, $_task);
	    	}
    	}

    	return $_goal;
    }

    public static function delete_task_by_template_id($template_id){
    	$model=new Goals();
    	if($template_id >0){
    		$_task=$model->where("task_template_id", $template_id)->update(['is_delete' => 1]);
    	}
    }

    public function getTaskTemplate($id)
    {
    	$result = Self::where("task_id",$id)->first();
    	return $result;
    }
}
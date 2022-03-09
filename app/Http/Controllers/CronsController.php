<?php
namespace App\Http\Controllers;
use App\Model\Goals;
use App\Model\TaskTemplate;
use \Carbon\Carbon;

class CronsController extends Controller{
	
    public function __construct()
    {
        parent::__construct();
    }

    public function process(){

    	$task_template = new TaskTemplate();

    	$task_template->process_task();

    	#print_r($crons);
    }

    public function process_by_id($id){

    	$task_template = new TaskTemplate();

    	$task_template->process_by_id($id);

    	/*
    	$task = $task_template->where("id",$id)->where("is_repeat_done",0)->where("status",1)->first();

    	$current_date = Carbon::now();

    	if($cron->repeat_frequency=='weeks'){

    	}else if($cron->repeat_frequency=='months'){

    	}else if($cron->repeat_frequency=='years'){

    	}*/
    }

   

}
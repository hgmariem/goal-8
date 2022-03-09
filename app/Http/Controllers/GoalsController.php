<?php
namespace App\Http\Controllers;
use App\Model\Goals;
use App\Model\HabitTypes;
use App\Model\Types;
use App\Model\Trophy;
use App\Model\Def_goals;
use App\Model\GoalsMeta;
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
use PDF;

class GoalsController extends Controller{
	
    public function __construct()
    {
         
        $this->middleware('auth');
        parent::__construct();
    }

	public function index(Request $request){
		
		$model=new Goals();
		$model_def=new Def_goals();
		$post=$request->all();
		$user_id=Auth::user()->id;
	    $get_goals=$model->get_goals($user_id);
		$default_goals=$model->get_goals($user_id,1); // is_default=1
		if($get_goals){
			return view('goals.goals_list', ['goals'=>$get_goals,'default_goals'=>$default_goals]);
		}
	}
	
	public function add(){
		return view('goals.goals_add',["html"=>"", "is_default"=>0]);
	}
	
	public function default_add(){
		return view('goals.goals_add',["html"=>"", "is_default"=>1]);
	}
	
	public function change_state(Request $request){
		$model=new Goals();
		$post=$request->all();
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
		return \Response::json($response);
	}
	
	public function task_tree_state(Request $request){
		$model=new Goals();
		$post=$request->all();
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
		return \Response::json($response);
	}

	public function create_goals(Request $request){
		
		$post=$request->all();
		//echo "<pre/>";
		//print_r($post);die;
		$model=new Goals();
		
		$user_id=Auth::user()->id;

		if(isset($post['status'])){
			$status_text = trim(preg_replace('/\s+/', ' ', $post['status']));
			$post['status']=$status_text;
		}
		
		if(isset($post['improvement'])){
			$improvement_text = trim(preg_replace('/\s+/', ' ', $post['improvement']));
			$post['improvement']=$improvement_text;
		}

		if(isset($post['risk'])){
			$risk_text = trim(preg_replace('/\s+/', ' ', $post['risk']));
			$post['risk']=$risk_text;
		}

		if(isset($post['benefits'])){
			$benefits_text = trim(preg_replace('/\s+/', ' ', $post['benefits']));
			$post['benefits']=$benefits_text;
		}

		if(isset($post['vision'])){
			$vision_text = trim(preg_replace('/\s+/', ' ', $post['vision']));
			$post['vision']=$vision_text;
		}

		if(isset($post['vision_decades'])){
			$vision_decades_text = trim(preg_replace('/\s+/', ' ', $post['vision_decades']));
			$post['vision_decades']=$vision_decades_text;
		}
		
		if(isset($post['barriers'])){
			$barriers_text = trim(preg_replace('/\s+/', ' ', $post['barriers']));
			$post['barriers']=$barriers_text;
		}

		if(isset($post['priority'])){
			$priority_text = trim(preg_replace('/\s+/', ' ', $post['priority']));
			$post['priority']=$priority_text;
		}

		if(isset($post['initiative'])){
			$initiative_text = trim(preg_replace('/\s+/', ' ', $post['initiative']));
			$post['initiative']=$initiative_text;
		}

		if(isset($post['help'])){
			$help_text = trim(preg_replace('/\s+/', ' ', $post['help']));
			$post['help']=$help_text;
		}

		if(isset($post['support'])){
			$support_text = trim(preg_replace('/\s+/', ' ', $post['support']));
			$post['support']=$support_text;
		}

		if(isset($post['environment'])){
			$environment_text = trim(preg_replace('/\s+/', ' ',  $post['environment']));
			$post['environment']=$environment_text;
		}

		if(isset($post['imagery'])){
			$imagery_text = trim(preg_replace('/\s+/', ' ',  $post['imagery']));
			$post['imagery']=$imagery_text;
		}

		$post=$this->process_goal_request($post);
		
		/*
		print_r($post);

		exit();*/

		$goal=$post['goal'];

		if($post['goal'] && $goal_id=$model->add_goals($post['goal'])){
			
			if ($post['goal']['type_id'] == 1) {
				$_habit_type_data=$post['_habit_type_data'];
				$_habit_type_data['goal_id']=$goal_id;
				$types=new HabitTypes();
				$types->add_habit_types($_habit_type_data);
			}
			
			if($post['childs'] && count($post['childs'])){

				foreach($post['childs'] as $detail_order=>$child_goal){  // main children
					
					$child_goal=$this->process_goal_request($child_goal);
					$child_goal['goal']['detail_order']=$detail_order+1;
					$child_goal['goal']['parent_id']=$goal_id;
					$child_goal['goal']['top_parent_id']=$goal_id;
					
					$this->add_sub_goal($child_goal, $goal_id);
				}
			}
			 
		}

		$response = array(
				'status' => 1,
				'gid' => $goal_id,
				'msg' => 'Data Saved Successfully.',
				'referer'=> URL::previous(),
			);	
		return \Response::json($response);
	}
	
	public function add_sub_goal($post, $top_parent_id){

		$model=new Goals();
		if($goal_id=$model->add_goals($post['goal'])){
			
			if ($post['goal']['type_id'] == 1) {
				#print_r($post);
				$_habit_type_data=$post['_habit_type_data'];
				$_habit_type_data['goal_id']=$goal_id;
				$types=new HabitTypes();
				$types->add_habit_types($_habit_type_data);
			}
			
			if($post['childs'] && count($post['childs'])>0){
				foreach($post['childs'] as $detail_order=>$child_goal){
					$child_goal=$this->process_goal_request($child_goal);
					$child_goal['goal']['detail_order']=$detail_order+1;
					$child_goal['goal']['parent_id']=$goal_id;
					$child_goal['goal']['top_parent_id']=$top_parent_id;

					$this->add_sub_goal($child_goal, $top_parent_id);
				}
			}
		}
	}
	
	public function process_goal_request($post){
		
		unset($post['parent']);
		$response=array();
		$childs=(isset($post['childs'])&&!empty($post['childs']))?$post['childs']:array();
		unset($post['childs']);
		
		if ($post['type_id'] == 2 && isset($post['due_date']) && !empty($post['due_date'])) {
			$post['due_date']=Carbon::createFromFormat('j F, Y', $post['due_date'])->format('Y-m-d');
		}else if(isset($post['due_date'])){
			$post['due_date']=Carbon::createFromFormat('j F, Y', $post['due_date'])->format('Y-m-d');
		}else{
			unset($post['due_date']);
		}
		
		$_habit_type_data=array();
		
		if(isset($post['habit_start_date']) && !empty($post['habit_start_date'])){
			
			//var_dump($post['auto_save_id'].":".$post['habit_start_date']);

			$post['habit_start_date'] = Carbon::createFromFormat('j F, Y', $post['habit_start_date'])->format('Y-m-d');
			
			//var_dump("after convert");

			//var_dump($post['auto_save_id'].":".$post['habit_start_date']);
		}
		
		if ($post['type_id'] == 1) {
			

			$habit_type=$post['habit_type'];
			//echo "<pre/>"; print_r($habit_type);
				//echo "if........";die;
			if($post['scale_type'] == 1)
			{
				$_habit_type_data['is_scale'] = isset($post['scale_type'])?$post['scale_type']:0;
				$_habit_type_data['minimum']  = (isset($post['lowest']))?$post['lowest']:0;
				$_habit_type_data['maximum']  = (isset($post['highest']))?$post['highest']:0;
				$_habit_type_data['is_apply'] = (isset($post['is_apply']))?$post['is_apply']:0;
			}
			else
			{
				$_habit_type_data['is_scale'] = isset($post['scale_type'])?$post['scale_type']:0;
				$_habit_type_data['minimum']  = (isset($post['lowest']))?$post['lowest']:0;
				$_habit_type_data['maximum']  = (isset($post['highest']))?$post['highest']:0;
				$_habit_type_data['is_apply'] = (isset($post['is_apply']))?$post['is_apply']:0;
			}
			
			$arrHabitType = explode(';', $habit_type);
			//$model->habitType = new HabitType;
			$_habit_type_data['type'] = $arrHabitType[0];
			$_habit_type_data['value'] = isset($arrHabitType[1]) ? $arrHabitType[1] : ( ($_habit_type_data['type'] == 1) ? 7 : '' );
			$_habit_type_data['text']  = isset($post['add_text_type'])?$post['add_text_type']:"";
			
			
			$_habit_type_data['count_per_week'] = 7;
			if (2 == $_habit_type_data['type']) {
				$_habit_type_data['count_per_week'] = $_habit_type_data['value'];
			} else if (3 == $_habit_type_data['type']) {
				$_habit_type_data['count_per_week'] = ($arrHabitType[1] != "") ? count(explode(',', $arrHabitType[1])) : 0;
			}
		}

		unset($post['habit_type']);
		unset($post['scale_type']);
		unset($post['lowest']);
		unset($post['highest']);
		unset($post['is_apply']);
		
		unset($post['add_text_type']);
		$post['user_id']=Auth::user()->id;
		$post['has_sub']=isset($childs)&&!empty($childs)?1:0;
		$response['goal']=$post;
		$response['_habit_type_data']=$_habit_type_data;
		$response['childs']=$childs;
		
		
		return $response;
	}
	
	/*public function create_def_goals(Request $request){
		$model_def=new Def_goals();
		$post=$request->input();
		$add_def=$model_def->add_Def_goals($post);
		
		if($add_def){
			return redirect('list')->with('success','Success');
		}
	}*/
		
     
    public function edit($id){
		$user_id=Auth::user()->id;
		$model=new Goals();
		
		$goal_data=$model->_get_goal_attributes($id);
		
		//echo "<pre/>";
	    //print_r($goal_data);die;
		if(!$goal_data){
			return redirect("/list")->with("success","Goal does not exist.");
		}

		if($goal_data->is_default == 0 && $goal_data->user_id != $user_id)
		{
			return redirect("/list")->with("success","Goal does not exist.");
		}
		
		$html="";
		$is_default = 0;
		/*echo "<pre/>";
		print_r($goal_data->children);die;*/
		
		if($goal_data && $sub_goals=$goal_data->children){

			$html = View::make('goals.sub_goal', compact('sub_goals','is_default'))->render();
		}

		//echo "<pre/>";
		//print_r($html);die;
		
		$_prefill_status=get_prefill_status_data($goal_data->id);

		$prefill_status=($_prefill_status)&&isset($_prefill_status->meta_attr)?json_decode($_prefill_status->meta_attr,true):array();
		
		$goal_data->prefill_status = isset($prefill_status['status'])?nl2br($prefill_status['status']):'';	

		return view('goals.goals_add',['goals_edit'=>$goal_data, "html"=>$html, "is_default"=>0]);
	}


	
	public function generate_sub_goal_tree($sub_goals){
		
		 //$html = View::make('countries.list', compact('countries'))->render();
		return $result;
	}
	
	public function default_edit($id){

		$model=new Goals();

		$goal_data=$model->_get_goal_attributes($id);
		
		if(!$goal_data){
			return redirect("/list")->with("success","Goal does not exists.");
		}
		
		$html="";
		
		if($goal_data && $sub_goals=$goal_data->children){
			$html = View::make('goals.sub_goal', compact('sub_goals'))->render();
		}
			
		$_prefill_status=get_prefill_status_data($goal_data->id);

		$prefill_status=($_prefill_status)&&isset($_prefill_status->meta_attr)?json_decode($_prefill_status->meta_attr,true):array();
		
		$goal_data->prefill_status = isset($prefill_status['status'])?nl2br($prefill_status['status']):'';	


		return view('goals.goals_add',['goals_edit'=>$goal_data, "html"=>$html, "is_default"=>1]);
	}
	
	
     	
	public function update(Request $request){

		$model=new Goals();
		$post=$request->input();
		$add=$model->edit_goals($post);
		
		
		if($add){
			return redirect('list')->with('success','Success');
		}else{
			return back()->withErrors()->withInput();
		}
	}
	
	public function default_update(Request $request){
		
		$model_def=new Def_goals();
		
		$post=$request->input();

		$add_def=$model_def->edit_Def_goals($post);
		
		if($add_def){
			return redirect('list')->with('success','Success');
		}else{
			return back()->withErrors()->withInput();
		}
	}
	
	public function sort_list(Request $request){
		
		parse_str($request->input("data"),$post);	
		//$post=$request->input("data");	
		//print($post);
		//exit();
		if($post && isset($post['item']) && !empty($post['item'])){
		#$print_r($post[0]);
			$goal_ids=array_reverse($post["item"]);
			foreach ($goal_ids as $order => $id) {
				
				if($goal=Goals::find($id)){
					$goal->list_order=$order;
					$goal->save();
				}
				
			}

			$response = array(
				'status' => 1,
				'msg' => 'List Order Changed Successfully.',
			);	
		}else{
			$response = array(
				'status' => 0,
				'msg' => 'Unable to change list order.',
			);	
		}
		
		return \Response::json($response);
	}
	
	public function sort_self(Request $request){
		
 		parse_str($request->input("data"),$post);	
		
		//print_r($post);


		if($post && isset($post['item']) && !empty($post['item'])){
		
			$goal_ids=array_reverse($post['item']);
			
			foreach ($goal_ids as $i => $id) {
				$goal=Goals::find($id);
				$goal->self_order=$i;
				$goal->save();
			}

			$response = array(
				'status' => 1,
				'msg' => 'List Order Changed Successfully.',
			);	
		}else{
			$response = array(
				'status' => 0,
				'msg' => 'Unable to change list order.',
			);	
		}
		
		return \Response::json($response);/**/
	}

	
	public function delete(Request $request){
		$post=$request->input();

		if(isset($post['auto_save_id']) && !empty($post['auto_save_id'])){
			
			$auto_save_id=$post['auto_save_id'];
			
			$model=new Goals();
			
			$user_id=Auth::user()->id;

			$goal=Goals::where("auto_save_id", $auto_save_id)->first();
			if($goal){
				if($goal->user_id==$user_id){
					
					$add=$model->delete_goals($goal->id);
					
					if($add){
						$response = array(
							'status' => 1,
							'msg' => 'Goal Deleted Successfully.'
						);	
					}
					else{
						$response = array(
							'status' => 0,
							'msg' => 'Unable to delete goal.'
						);	
					}
				}else{
					$response = array(
						'status' => 0,
						'msg' => 'Invalid User'
					);	
				}
			}else{
				$response = array(
					'status' => 1,
					'msg' => 'Goal Deleted Successfully.'
				);	
			}
		}else{
			$response = array(
				'status' => 1,
				'msg' => 'Goal Deleted Successfully.'
			);	
		}	

		return \Response::json($response);
	}

	public function weekly_habits(Request $request){

        $post_data=$request->all();
        $user_id=Auth::user()->id;
    	
    	$filter=array();
    	
    	$filter['user_id']=$user_id;
    	$filter['is_default']=0;
    	$filter['isMobile']=$this->isMobile;
    	if($filter['isMobile']){
    		if($post_data['type']=='prev'){
    			$current = Carbon::parse($post_data['date']);
	    		$post_data['date']=$current->addDays(-1)->format("Y-m-d");
    		}else{
    			$current = Carbon::parse($post_data['date']);
	    		$post_data['date']=$current->addDays(1)->format("Y-m-d");
    		}

    	}else{
    		if($post_data['type']=='prev'){
	    		$current = Carbon::parse($post_data['date']);
	    		$post_data['date']=$current->addDays(-8)->format("Y-m-d");
	    	}else{
	    		$current = Carbon::parse($post_data['date']);
	    		$post_data['date']=$current->format("Y-m-d");
	    	}	
    	}

    	#print_r($post_data['date']);

    	$filter['start_date']=isset($post_data['date'])&&!empty($post_data['date'])?$post_data['date']:date("Y-m-d");

    	$model=new Goals();
    	//$days=$model->weekdays($filter['start_date']);
    	if($filter['isMobile']){
            $days=$model->weekday($filter['start_date']);
        }else{
            $days=$model->weekdays($filter['start_date']);
        }


    	$habits=$model->get_habits($filter);
    	//echo "<pre/>";
    	//print_r($habits);die;
    	$view=($filter['isMobile'])?'goals.partials.mobile.habits':'goals.partials.habits';

    	$html = View::make($view, compact('days','habits'))->render();
    	$response = array(
			'status' => 1,
			'html'	 => $html,
			'filter' => $filter,
			'msg' => 'Goal Deleted Successfully.'
		);	

		return \Response::json($response);
    }

    public function tasks_list(Request $request){

    	$filter=array();
    	$post_data=$request->all();
        $user_id=Auth::user()->id;
        $model=new Goals();
        
        $filter['start_date']=isset($post_data['date'])&&!empty($post_data['date'])?$post_data['date']:date("Y-m-d");
    	
    	$filter['user_id']=$user_id;
    	
    	$filter['is_default']=0;
    	
    	$filter['view_type']=$post_data['view_type'];

    	$html=$this->render_task_list($filter);
    	$response = array(
			'status' => 1,
			'html'	 => $html,
			'filter' => $filter,
			'msg' => 'Task Listed Successfully.'
		);	

    	//print_r($response);

		return \Response::json($response);
    }

    public function render_task_list($filter){
    	$model=new Goals();
    	$view_type=$filter['view_type'];

    	$tasks=$model->get_tasks($filter);
    	
    	$tasks['view_type']=$view_type;
    	$tasks['items']=$tasks;
    	$isMobile=$this->isMobile;
    	$partial_path="goals.partials";

    	$partial_path.=($isMobile)?".mobile":"";
    	
    	$view=$partial_path.".task_tree";

    	if($view_type=='list'){
    		$view=$partial_path.".task_list";
    	}else if($view_type=='leaf'){
    		$view=$partial_path.".task_leaf";
    	}

    	//print_r($view);
		//print_r($tasks);
    	$html = View::make($view, ['tasks'=>$tasks])->render();
    	return $html;
    }


    public function task_complete(Request $request){

    	$filter=array();
    	$post_data=$request->all();
        $user_id=Auth::user()->id;
        $model=new Goals();
        if($goal=$model->get_by_id($post_data['id'])){
        	$goal->percent=100;
        	$goal->save();
        }


        $filter['start_date']=isset($post_data['date'])&&!empty($post_data['date'])?$post_data['date']:date("Y-m-d");
    	
    	//$filter['data']=$goal;

    	$filter['user_id']=$user_id;
    	
    	$filter['is_default']=0;
    	
    	$filter['view_type']=$post_data['view_type'];


        $html=$this->render_task_list($filter);
        $response = array(
			'status' => 1,
			'html'	 => $html,
			'data'	 => $goal,
			'filter' => $filter,
			'msg' => 'Task Listed Successfully.'
		);	

		return \Response::json($response);

    }

    public function reactive_task(Request $request){

    	$post_data=$request->all();
        $user_id=Auth::user()->id;
        $model=new Goals();
        if($goal=$model->get_by_id($post_data['id'])){
        	
        	if($goal->type_id==Config::get('constants.goal_task')){
        		$goal->percent=0;
	        	$goal->is_end=0;
	        	$goal->is_in_trophy=0;

	        	$goal->save();
	        	$trophy=Trophy::where("item_id",$goal->id)->first();
	        	
	        	if($trophy){
	        		$trophy->deleted=1;
	        		$trophy->save();
	        	}

	        	$response = array(
					'status' => 1,
					'goal'	 => $goal,
					'msg' => 'Task Activated Successfully.'
				);	

        	}else{

        		$response = array(
					'status' => 0,
					'goal'	 => $goal,
					'msg' => 'Unable Activate Task.'
				);	
        	}
        	
        }

        

		return \Response::json($response);

    }

    public function addtomylist($goal_id){
    	
    	$response=array("status"=>0,"msg"=>"Unable to add goal to your list");

    	$user_id=Auth::user()->id;
    	$model=new Goals();
    	if($goal=$model->addtomylist($goal_id, $user_id)){
    		
    		$response['status']=1;
    		$response['msg']="Goal Added to your list.";
    		$response['data']=$goal;

    		return \Response::json($response);

    		//return redirect("/list")->with("success","Goal Added to your list.");
    	}else{
    		//return redirect("/list")->with("error","Unable to add goal to your list.");

    		$response['status']=0;
    		$response['data']=array();
    		$response['msg']="Unable to add goal to your list.";
    		return \Response::json($response);
    	}

    	return redirect("/list")->with("success","Goal Added to your list.");
    }

    public function goal_view_type($view_type){
    	
    	$user_id=Auth::user()->id;
    	
    	$model=new Goals();
    	
    	$filter=array();
    	
    	$filter['start_date']=isset($post_data['date'])&&!empty($post_data['date'])?$post_data['date']:date("Y-m-d");
    	
    	$filter['user_id']=$user_id;
    	
    	$filter['is_default']=0;

    	$filter['view_type']=$view_type;

    	$filter['isMobile']=$this->isMobile;
    	
        $_tasks=$model->get_tasks($filter);
        
        $tasks['view_type']=$filter['view_type'];
        
        $tasks['expanded']=true;

        $tasks['items']=$_tasks;

    	return view('goals.goals_view_type',["tasks"=>$tasks]);
    }

    public function get_monthly_statistics($id){

    	$response=array("status"=>0,"msg"=>"This record has been deleted.");
        
        $model = Goals::where("id",$id)->first();

        if ($model->is_delete) {
            return \Response::json($response);
        }
        
        $first_log=$model->get_first_log_date($id);
        /*echo "<pre/>";
        print_r($first_log);die;*/
	    $current_month=date("Y-m-d");

	    $date1 = ($first_log && $first_log->date)?$first_log->date:date("Y-m-d",strtotime("-6 months")); // default 6 months...
	        
	        //echo "<br>";
	        $date2 = $current_month;

	        $ts1 = strtotime($date1);
	        $ts2 = strtotime($date2);

	        $year1 = date('Y', $ts1);
	        $year2 = date('Y', $ts2);

	        $month1 = date('m', $ts1);
	        $month2 = date('m', $ts2);

	        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

	        $diff = ($diff < 0) ? -$diff : $diff;
	        
	        if ($diff <= 12) {
	            $diff = 12;
	            $total_year_event=1;
	        } else if ($diff > 12 && $diff <= 24) {
	            $diff = 24;
	            $total_year_event=2;
	        } else if ($diff > 24 && $diff <= 36) {
	            $diff = 36;
	            $total_year_event=3;
	        } else if ($diff > 36 && $diff <= 48) {
	            $diff = 48;
	            $total_year_event=4;
	        }else if ($diff > 48 && $diff <= 60) {
	        	$diff = 60;
	        	$total_year_event=5;
	        }

	        $html="";

	        //$total_year_event=1;
	        // echo $diff;

	        for ($i=$diff; $i >=0 ; $i--) {

	        	//$date=$dt->subMonths(1)->format("Y-m-01");

	        	$date = date("Y-m-01", strtotime(date("Y-m-01")." -".$i." months"));

	        	$_data = $model->get_monthly_percentage($id, $date);
	        	
	        	$month_year[$date] = $_data;

	        	$isdi=date("Y",strtotime($date));//-$year1;
	        	$isdisply = "display:block";
	            
	             $mode=(int)($i/12);

	            if ($mode >0) {
	                $isdisply = 'display:none';
	            }
	           

	        	$html.= '<span style="' . $isdisply . '" class="yrgraph_'.$mode.'">
		        			<div class="progress progress-bar-vertical " >
								<div class="progress-bar ' . $_data->badge . '"  role="progressbar" aria-valuenow="' . $_data->percentage . '" aria-valuemin="0" aria-valuemax="100" style="height:  ' . $_data->percentage . '%;">
									<span class="sr-only">' . $_data->percentage . '%</span>
								</div>		
							</div>
							<span class="yr-name">' . $_data->month_year['month_name'] . '</span>
							<span class="yr-year">' . $_data->month_year['year'] . '</span>
						</span>';
	        }
	        
	        $html.="<script>var total_year_event = $total_year_event</script>";

	        $response=array("status"=>1,"html"=>$html,"msg"=>"Data Found","month_year"=>$month_year);

        return \Response::json($response);
    }

    public function progress_statistics($id){

    	$response=array("status"=>0,"msg"=>"This record has been deleted.");
        
        $model = Goals::where("id",$id)->first();

        if ($model->is_delete == '1') {
            return \Response::json($response);
        }

        $sql = "SELECT * FROM tbl_logs WHERE value = 1 AND goal_id = " . $id;

        $results = DB::table('tbl_logs')->where("value",1)->where("goal_id",$id)->get()->toArray();
        #print_r($results);

        //$results = $command->queryAll();
		//$sql = "SELECT * FROM tbl_logs WHERE value = 2 AND goal_id = " . $id;
        //$command = Yii::app()->db->createCommand($sql);
        //$notappl = $command->queryAll();		
      	$notappl = DB::table('tbl_logs')->where("value",2)->where("goal_id",$id)->get()->toArray();

        if (count($results) == 0) {
            $response=array("status"=>1,"html"=>"<div>No Record Found</div>","msg"=>"No Record Found.");
            return \Response::json($response);
        }

        $date1 = $results[0]->date;
        
        #print_r($date1);

        //echo "<br>";
        $date2 = $results[count($results) - 1]->date;
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

        $diff = ($diff < 0) ? -$diff : $diff;
        
        if ($diff < 12) {
            $diff = 11;
        } else if ($diff > 12 && $diff < 24) {
            $diff = 23;
        } else if ($diff > 24 && $diff < 36) {
            $diff = 35;
        } else if ($diff > 36 && $diff < 48) {
            $diff = 47;
        }
        //$diff = ($diff < 12) ? 11 : $diff;
        //echo $diff;exit;

        $habit_dates = array();
        foreach ($results as $k => $v) {
            $habit_dates[] = $v->date;
        }
//        $habit_dates = array_column($results, 'date');

        $habit_types = HabitTypes::where('goal_id', $id)->get()->toArray();
        
        //echo "<pre>";
        $habit_types_arr[0] = $habit_types[count($habit_types) - 1];

        #print_r($habit_types_arr);
        //print_r($habit_types);exit;
        $habit_type_values = "";
        foreach ($habit_types_arr as $k => $v) {
            if ($v['type'] == '1') {
                $habit_type_values = '0,1,2,3,4,5,6';
            } else if ($v['type'] == '2') {
                $habit_type_values = $v['value'];
            } else if ($v['type'] == '3') {
                $habit_type_values = $v['value'];
            }
        }

        $html = "";
        $response_arr = array();
        $habit_end_dates_arr = array();
		 //echo "<pre>";
        //print_r($notappl);
		$not_applicble = array();
		for($i=0;$i<count($notappl);$i++)
		{
			$mon = date("Y-m",strtotime($notappl[$i]->date));
			$not_applicble[$mon][] = $notappl[$i]->date;
		}
		//print_r($not_applicble);exit;
		//echo $habit_type_values;exit;
		
		//print_r($diff);

        for ($d = 0; $d <= $diff; $d++) {
          
            if ($d == 0) {
                $habit_end_dates_arr = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-" . $d . " month"));
            } else {

                $habit_end_dates_arr = date("Y-m-t", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-" . $d . " month"));
            }

            $current_month = date('M', strtotime($habit_end_dates_arr));
            $current_year = date('Y', strtotime($habit_end_dates_arr));

            //var_dump($habit_end_dates_arr, $habit_dates, $habit_type_values, $d, $not_applicble);


            $response_arr[$current_year . "_" . $current_month] = Goals::getHabitPercent2($habit_end_dates_arr, $habit_dates, $habit_type_values, $d, $not_applicble);
        }
        //echo "<pre>";
        //$response_arr = array_reverse($response_arr);
        //echo "<pre>";
        //print_r($response_arr);exit;

        $i = 0;
        $j = 0;
        $year_habit = array();
        foreach ($response_arr as $tt => $v) {
            $year_habit[$j][$tt] = $v;
            $i = $i + 1;
            if ($i > 11) {
                ++$j;
                $i = 0;
            }
        }
        //echo "<pre>";
        //print_r($year_habit);
        //exit;
        $total_year_event = count($year_habit);
        $percentage = array("#8bc34a", "#ffc107", "#e51c23");
        for ($i = 0; $i < count($year_habit); $i++) {
            $response_arr = $year_habit[$i];
            $response_arr = array_reverse($response_arr);
            // print_r($response_arr);
            foreach ($response_arr as $tt => $v) {
                if ($v['percent'] <= 33) {
                    $prog_class = "badge-danger";
                } else if ($v['percent'] > 33 && $v['percent'] <= 66) {
                    $prog_class = "badge-warning";
                } else if ($v['percent'] > 66) {
                    $prog_class = "badge-success";
                }
                $isdisply = "display:block";
                if ($i > 0) {
                    $isdisply = 'display:none';
                }

                $tt = @explode("_", $tt);
                $html .='<span style="' . $isdisply . '" class="yrgraph_' . $i . '"><div class="progress progress-bar-vertical " >
								<div class="progress-bar ' . $prog_class . '"  role="progressbar" aria-valuenow="' . $v['percent'] . '" aria-valuemin="0" aria-valuemax="100" style="height:  ' . $v['percent'] . '%;">
									<span class="sr-only">' . $v['percent'] . '%</span>
								</div>
								
							</div><span class="yr-name">' . $tt[1] . '</span><span class="yr-year">' . $tt[0] . '</span></span>';
                //$div_index = $div_index + 1;
            }
        }
        $html.="<script>var total_year_event = $total_year_event</script>";
        $response=array("status"=>1,"html"=>$html,"msg"=>"Data Found");
        return \Response::json($response);

        exit;
    }

    public function generate_autosaveid(){

    	$model=new Goals();
    	$id=$model->generate_autosaveid();
    	$response = array(
			'status' => 1,
			'id'	 => $id
		);	

		return \Response::json($response);
    }


    public function default_import(){

	    	$user_id=Auth::user()->id;
	    	
	    	$model=new Goals();

	    	$_model=new Def_goals();

	    	$goals=$_model->get_parent_goals();

	    	
	    	if($goals){

				foreach ($goals as $key => $goal) {
					
					$_goal=$_model->_get_goal($goal['id']);
					
					if($_goal){
				    	
				    	/*if('FAMILY - SPOUSE & KIDS - (COMPLETED)'==$_goal['name']){
					    	
					    	//echo "<pre/>";
					    	
					    	//print_r($_goal);
					    	//exit();
				    	}*/

						$children=isset($_goal['children'])?$_goal['children']:array();
						
						unset($_goal['id']);
						unset($_goal['children']);
						unset($_goal['type']);
						unset($_goal['habit_types']);

			    		$_goal['user_id']=$user_id;
			    		$_goal['auto_save_id']=time()+rand(1111111111, 999999999999999);
			    		//ss$_goal['is_default']=0;
			    		$_goal['list_order']=$model->get_max_list_order($user_id)+1; // default max number
			    		$_goal['self_order']=$model->get_max_self_order($user_id)+1; // default max number
			    		$_goal['created_at']=date("Y-m-d H:i:s");
			    		$_goal['updated_at']=date("Y-m-d H:i:s");
			    		$_goal['parent_id']=0;
			    		$_goal['top_parent_id']=0;
			    		
			    		

			    		$new_goal = Goals::create($_goal);

			    		
			    		if($children){
			    			$parent_id=$new_goal->id;
			    			$top_parent_id=$new_goal->id;
			    			$this->addchildtomylist($children, $user_id, $parent_id, $top_parent_id);
			    		}else{
			    			echo "<br/>";
			    			echo "no children:".$_goal['name'];
			    			echo "<br/>";
			    		}
						
					}
				}

				//sreturn $goals;
    		}else{
    			echo "No goals...";
    		}

    }

    public function addchildtomylist($goals, $user_id, $parent_id, $top_parent_id){

		$model=new Goals();
		
		if($goals){

			foreach ($goals as $key => $goal) {
				
				$children=isset($goal['children'])?$goal['children']:array();

				unset($goal['id']);
				unset($goal['children']);
				unset($goal['type']);
				unset($goal['habit_types']);
	    		
	    		$goal['user_id']=$user_id;
	    		
	    		$goal['auto_save_id']=time()+rand(1111111, 999999999999);
	    		
	    		$goal['parent_id']=$parent_id;
	    		
	    		$goal['top_parent_id']=$top_parent_id;

	    		$goal['list_order']=$model->get_max_list_order($user_id)+1; // default max number
    			$goal['self_order']=$model->get_max_self_order($user_id)+1; // default max number
	    		$goal['created_at']=date("Y-m-d H:i:s");
    			$goal['updated_at']=date("Y-m-d H:i:s");
	    		
	    		$new_goal = Goals::create($goal);
	    		/*echo "<br/>";
	    		echo "newly inserted:".$new_goal->id;
	    		echo "<br/>";*/
	    		if($children){
	    			
	    			$this->addchildtomylist($children, $user_id, $new_goal->id, $top_parent_id);
    			}else{
    				echo "<br/>";
			    	echo "no children:".$goal['name'];
			    	echo "<br/>";
			    }

			}

			//return $goals;
		}
	}

	public function upadate_task_date(Request $request){
		

		if($post=$request->input()){
			
			$user_id=Auth::user()->id;

			$_task=Goals::where("id", $post['id'])->where("user_id",$user_id)->first();


			if($_task){
				$post['date']=Carbon::createFromFormat('j F, Y', $post['date'])->format('Y-m-d');
				//print_r($post);
				$_task->due_date=$post['date'];
				$_task->save();

				$expired_class=(date("Y-m-d") > $_task->due_date)?true:false;
				$data['expired']=$expired_class;
				$data['date']=date("d M, Y",strtotime($_task->due_date));
				$response = array(
					'status' => 1,
					'data' => $data,
					'msg' => 'Task updated Successfully.'
				);	
			}else{
				$response = array(
					'status' => 0,
					'data' => $post,
					'msg' => 'Invalid Task.'
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


	public function upadate_habit_date(Request $request){
		

		if($post=$request->input()){
			
			$user_id=Auth::user()->id;

			$_habit=Goals::where("id", $post['id'])->where("user_id",$user_id)->first();


			if($_habit){
				
				$post['date']=$post['date'];
				$_habit->habit_start_date=date("Y-m-d",strtotime($post['date']));
				$_habit->save();

				$data['date']=$_habit->habit_start_date;

				$response = array(
					'status' => 1,
					'data' => $data,
					'msg' => 'Habit updated Successfully.'
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

	
	public function save_attributes(Request $request){
		
		if($post=$request->input()){

			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				
				$post_data=array();
				
				$post_data['meta_key']=$post['attr']."-".$post['sheet_id'];
				
				GoalsMeta::deactivate_sheets($_habit->id, $post['attr']);

				$html = clean_html($post['html']);

				$post['html']=$html;
				
				$post_data['meta_attr']=json_encode($post);
				
				$post_data['meta_value']=$html;
				
				$post_data['goal_id']=$_habit['id'];
				
				$post_data['is_active']=1;

				GoalsMeta::add($post_data);

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

	public function attribute_delete_sheet(Request $request){

		if($post=$request->input()){

			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=GoalsMeta::delete_meta($_habit->id, $meta_key);

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
				
				GoalsMeta::deactivate_sheets($_habit->id, $post['attr']);

				$meta=GoalsMeta::get($_habit->id, $meta_key);
				
				$meta->is_active=1;

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


	public function attribute_duplicate_sheet(Request $request){

		if($post=$request->input()){

			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				
				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=GoalsMeta::get($_habit->id, $meta_key);
				
				$_meta=$meta->replicate();

				$meta_attr = json_decode($_meta->meta_attr);
				
				#print_r($meta_attr);

				$new_sheet_id = rand(1111111111,9999999999);
				
				$meta_attr->sheet_id = $new_sheet_id;
				
				$meta_attr->sheet_number = rand(1111,9999);
				
				$meta_attr->is_active = $_meta->is_active;

				$_meta->meta_key = $meta_attr->attr."-".$meta_attr->sheet_id;

				$_meta->meta_attr = json_encode($meta_attr);

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

				$meta=GoalsMeta::get($_habit->id, $meta_key);

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

	public function save_task_template(Request $request){
		
		$errors=array();

        /*$data = $request->all();
        echo "<pre/>";
		print_r($data);die;*/
		if($post=$request->input()){

			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['task_id'])->where("user_id",$user_id)->first();
			
			if($_habit){

				$data['task_id']=$_habit->id;
				$data['repeat_qty']=!empty($post['repeat_qty'])?$post['repeat_qty']:1;
				$data['repeat_frequency']=!empty($post['repeat_frequency'])?$post['repeat_frequency']:"weeks";
				
				if($data['repeat_frequency']=='weeks'){
					//echo "<pre/>";
					//print_r($post['week_days']);die;
					if(isset($post['week_days']) && empty($post['week_days'])){
						$errors[]="Please select a day.";
					}else if(!isset($post['week_days'])){
						$errors[]="Please select a day.";
					}

					if(isset($post['week_days']) && !empty($post['week_days'])){
						$data['repeat_on']=implode(",", $post['week_days']);
						$data['repeat_on_date']=null;
					}
				}else{
					if($post['repeat_on']=='thisday'){
						$start = date("d");//new Carbon('last day of this month');
					}else if($post['repeat_on']=='firstday'){
						$start = Carbon::parse(new Carbon('first day of this month'))->day;
					}else{
						$start = Carbon::parse(new Carbon('last day of this month'))->day;
					}
					
					/*if(empty($post['repeat_on'])){
						$errors[]="Please select a day.";
					}*/

					$data['repeat_on']=$post['repeat_on'];
					$data['repeat_on_date']=$start;
				}
				
				$data['task_name']=!empty($post['task_name'])?$post['task_name']:"";
				$data['template_name']=!empty($post['template_name'])?$post['template_name']:"";
				$data['add_suffix']=!empty($post['add_suffix'])?$post['add_suffix']:0;
				$data['ends_on']=!empty($post['ends_on'])?$post['ends_on']:"never";
				$data['begin_on']=!empty($post['begin_on'])?$post['begin_on']:"now";
				
				if(empty($post['task_name'])){
					$errors[]="Please enter Task name.";
				}

				if(empty($post['repeat_qty'])){
					$errors[]="Please enter Repeat qty.";
				}

				if($data['ends_on']=='date'){
					
					if(empty($post['ends_on_date'])){
						$errors[]="Please enter ends on date.";
					}

					$ends_on_date=!empty($post['ends_on_date'])?$post['ends_on_date']:$_habit->due_date;
					$data['end_on_value']=date("Y-m-d",strtotime($ends_on_date));
				}else if($data['ends_on']=='occurrences'){
					if(empty($post['occurrences'])){
						$errors[]="Please enter no of occurrences.";
					}

					$data['end_on_value']=!empty($post['occurrences'])?$post['occurrences']:"";
				}else{
					$data['end_on_value']=-1;
				}
				
				if($data['begin_on']=='date'){
					
					if(empty($post['begin_on_date'])){
						$errors[]="Please enter begin on date.";
					}

					$begin_on_date=!empty($post['begin_on_date'])?$post['begin_on_date']:'';
					$data['begin_on_value']=date("Y-m-d",strtotime($begin_on_date));
				}


				$data['is_repeat_done']=0;
				
				//var_dump($errors);

				if(!empty($errors)){
					
					$response = array(
						'status' => 0,
						'data' => $errors,
						'msg' => 'Invalid Request.'
					);

					return \Response::json($response);		
				}

				//$_habit->name=$data['task_name'];

				//$_habit->save();

				//Goals::where("id", $data['task_id'])->update(['name'=>$data['task_name']]);
				//print_r($data);

				$task_template=TaskTemplate::updateOrCreate(['task_id'=>$data['task_id']], $data);
				
				TaskTemplate::delete_task_by_template_id($task_template->id);
				
				$_goal=TaskTemplate::process_by_id($task_template->id);

				$post['task']=$_goal;

				$response = array(
					'status' => 1,
					'data' => $post,
					'msg' => 'Task Added.'
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
	}

	public function get_task_template(Request $request){
		
		if($post=$request->input()){

			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['task_id'])->where("user_id",$user_id)->first();

			if($post['task_id'] && $_habit){

				$task_id=$_habit->id; //['task_id'];
				
				if($template = TaskTemplate::where("task_id",$task_id)->first()){
					
					if($template->ends_on=='date' && $template->end_on_value){
						$template->end_on_value = Carbon::parse($template->end_on_value)->format('m/d/Y');
					}

					if($template->begin_on=='date' && $template->begin_on_value){
						$template->begin_on_value = Carbon::parse($template->begin_on_value)->format('m/d/Y');
					}

					$response = array(
						'status' => 1,
						'data' => $template,
						'msg' => 'Template Data.'
					);	


				}else{
					$response = array(
						'status' => 0,
						'data' => array(),
						'msg' => 'Invalid Template.'
					);	
				}
			}else{

				$response = array(
					'status' => 0,
					'data' => array(),
					'msg' => 'Invalid Parameter.'
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

	public function addLobby(Request $request)
	{	
		  $post = $request->all();
		  $user_id=Auth::user()->id;
		 	
		  $lobbyGoal = new Goals();
		  $goalLobby = $lobbyGoal->addLobby($post,$user_id);
		  /*echo "<pre/>";
		  print_r($goalLobby);die;*/
		  $post['goal_id'] = $goalLobby->id;
		  $post['task_id'] = $goalLobby->auto_save_id;
		  	if(isset($post['type']) && $post['type'] == 1)
		  	{
		  		 $this->addHabitSchedule($post);
		  	}

		  	if(isset($post['type']) && $post['type'] == 2 && isset($post['task_name']) && !empty($post['task_name']))
		  	{
		  		$this->saveTaskTemplate($post);
		  	}
		 
		  	if($post['type'] == 1)
		  	{
		  		$response = array(
					'status' => 1,
					'data' => $goalLobby,
					'msg' => 'Habit added.'
				);
		  	}
		  	else if($post['type'] == 2)
		  	{
		  		$response = array(
					'status' => 1,
					'data' => $goalLobby,
					'msg' => 'Task added.'
				);
		  	}
		  	else
		  	{
		  		$response = array(
					'status' => 1,
					'data' => $goalLobby,
					'msg' => 'Character added.'
				);	
		  	}
			
			//print_r($response);die;
			return \Response::json($response);
		  /*//print_r($goalLobby);die;
		  return \Response::json($goalLobby);*/	
	}


	public function saveTaskTemplate($post){
		
		$errors=array();

        /*$data = $request->all();
        echo "<pre/>";
		print_r($data);die;*/
		if($post){

			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['task_id'])->where("user_id",$user_id)->first();
			
			if($_habit){

				$data['task_id']=$_habit->id;
				$data['repeat_qty']=!empty($post['repeat_qty'])?$post['repeat_qty']:1;
				$data['repeat_frequency']=!empty($post['repeat_frequency'])?$post['repeat_frequency']:"weeks";
				
				if($data['repeat_frequency']=='weeks'){
					//echo "<pre/>";
					//print_r($post['week_days']);die;
					if(isset($post['week_days']) && empty($post['week_days'])){
						$errors[]="Please select a day.";
					}else if(!isset($post['week_days'])){
						$errors[]="Please select a day.";
					}

					if(isset($post['week_days']) && !empty($post['week_days'])){
						$data['repeat_on']=implode(",", $post['week_days']);
						$data['repeat_on_date']=null;
					}
				}else{
					if($post['repeat_on']=='thisday'){
						$start = date("d");//new Carbon('last day of this month');
					}else if($post['repeat_on']=='firstday'){
						$start = Carbon::parse(new Carbon('first day of this month'))->day;
					}else{
						$start = Carbon::parse(new Carbon('last day of this month'))->day;
					}
					
					/*if(empty($post['repeat_on'])){
						$errors[]="Please select a day.";
					}*/

					$data['repeat_on']=$post['repeat_on'];
					$data['repeat_on_date']=$start;
				}
				
				$data['task_name']=!empty($post['task_name'])?$post['task_name']:"";
				$data['template_name']=!empty($post['template_name'])?$post['template_name']:"";
				$data['add_suffix']=!empty($post['add_suffix'])?$post['add_suffix']:0;
				$data['ends_on']=!empty($post['ends_on'])?$post['ends_on']:"never";
				$data['begin_on']=!empty($post['begin_on'])?$post['begin_on']:"now";
				
				if(empty($post['task_name'])){
					$errors[]="Please enter Task name.";
				}

				if(empty($post['repeat_qty'])){
					$errors[]="Please enter Repeat qty.";
				}

				if($data['ends_on']=='date'){
					
					if(empty($post['ends_on_date'])){
						$errors[]="Please enter ends on date.";
					}

					$ends_on_date=!empty($post['ends_on_date'])?$post['ends_on_date']:$_habit->due_date;
					$data['end_on_value']=date("Y-m-d",strtotime($ends_on_date));
				}else if($data['ends_on']=='occurrences'){
					if(empty($post['occurrences'])){
						$errors[]="Please enter no of occurrences.";
					}

					$data['end_on_value']=!empty($post['occurrences'])?$post['occurrences']:"";
				}else{
					$data['end_on_value']=-1;
				}
				
				if($data['begin_on']=='date'){
					
					if(empty($post['begin_on_date'])){
						$errors[]="Please enter begin on date.";
					}

					$begin_on_date=!empty($post['begin_on_date'])?$post['begin_on_date']:'';
					$data['begin_on_value']=date("Y-m-d",strtotime($begin_on_date));
				}


				$data['is_repeat_done']=0;
				
				//var_dump($errors);

				if(!empty($errors)){
					
					$response = array(
						'status' => 0,
						'data' => $errors,
						'msg' => 'Invalid Request.'
					);

					return \Response::json($response);		
				}

				//$_habit->name=$data['task_name'];

				//$_habit->save();

				//Goals::where("id", $data['task_id'])->update(['name'=>$data['task_name']]);
				//print_r($data);

				$task_template=TaskTemplate::updateOrCreate(['task_id'=>$data['task_id']], $data);

				TaskTemplate::delete_task_by_template_id($task_template->id);
				
				$_goal=TaskTemplate::process_by_id($task_template->id);

				$post['task']=$_goal;

				$response = array(
					'status' => 1,
					'data' => $post,
					'msg' => 'Task Added.'
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
	}



	public function validateTaskTemplate(Request $request){
		
		$errors=array();

        /*$data = $request->all();
        echo "<pre/>";
		print_r($data);die;*/
		if($post = $request->input()){

	        /*echo "<pre/>";
			print_r($post);die;*/
			$user_id=Auth::user()->id;

				$data['repeat_qty']=!empty($post['repeat_qty'])?$post['repeat_qty']:1;
				$data['repeat_frequency']=!empty($post['repeat_frequency'])?$post['repeat_frequency']:"weeks";
				
				if($data['repeat_frequency']=='weeks'){
					/*echo "<pre/>";
					print_r($post['week_days']);die;*/
					if(isset($post['week_days']) && empty($post['week_days'])){
						$errors[]="Please select a day.";
					}else if(!isset($post['week_days'])){
						$errors[]="Please select a day.";
					}

					if(isset($post['week_days']) && !empty($post['week_days'])){
						$data['repeat_on']=implode(",", $post['week_days']);
						$data['repeat_on_date']=null;
						
					}
				}else{
					if(isset($post['repeat_on']) && $post['repeat_on']=='thisday'){
						$start = date("d");//new Carbon('last day of this month');
					}else if($post['repeat_on']=='firstday'){
						$start = Carbon::parse(new Carbon('first day of this month'))->day;
					}else{
						$start = Carbon::parse(new Carbon('last day of this month'))->day;
					}
					
					/*if(empty($post['repeat_on'])){
						$errors[]="Please select a day.";
					}*/

					$data['repeat_on']=$post['repeat_on'];
					$data['repeat_on_date']=$start;
				}
				
				$data['task_name']=!empty($post['task_name'])?$post['task_name']:"";
				$data['template_name']=!empty($post['template_name'])?$post['template_name']:"";
				$data['add_suffix']=!empty($post['add_suffix'])?$post['add_suffix']:0;
				$data['ends_on']=!empty($post['ends_on'])?$post['ends_on']:"never";
				$data['begin_on']=!empty($post['begin_on'])?$post['begin_on']:"now";
				
				if(empty($post['task_name'])){
					$errors[]="Please enter subtask name.";
				}

				if(empty($post['repeat_qty'])){
					$errors[]="Please enter Repeat qty.";
				}

				if($data['ends_on']=='date'){
					
					if(empty($post['ends_on_date'])){
						$errors[]="Please enter ends on date.";
					}

					$ends_on_date=!empty($post['ends_on_date'])?$post['ends_on_date']:"";
					$data['end_on_value']=date("Y-m-d",strtotime($ends_on_date));
				}else if($data['ends_on']=='occurrences'){
					if(empty($post['occurrences'])){
						$errors[]="Please enter no of occurrences.";
					}

					$data['end_on_value']=!empty($post['occurrences'])?$post['occurrences']:"";
				}else{
					$data['end_on_value']=-1;
				}
				
				if($data['begin_on']=='date'){
					
					if(empty($post['begin_on_date'])){
						$errors[]="Please enter begin on date.";
					}

					$begin_on_date=!empty($post['begin_on_date'])?$post['begin_on_date']:'';
					$data['begin_on_value']=date("Y-m-d",strtotime($begin_on_date));
				}


				$data['is_repeat_done']=0;
				
				//$_SESSION['task_data'] = $data;
				//var_dump($errors);

				if(!empty($errors)){
					
					$response = array(
						'status' => 0,
						'data' => $errors,
						'msg' => 'Invalid Request.'
					);	
				}
				else
				{
						$post_data = $data;
						$taskData = $this->getTaskEndDate($data);
						$post_data['new_due_date'] = (isset($taskData) && !empty($taskData)) ? $taskData['due_date']:"";
						$response = array(
						'status' => 1,
						'data' => json_encode($post_data),
						'msg' => 'Task Added.'
					);
				}

				

			return \Response::json($response);	
		}
	}

	public function addHabitType(Request $request)
	{
		//$count_per_week = 0; 
		$habitTypes = new HabitTypes();
		$post = $request->all();
		//echo "<pre/>";
		//print_r($post);die;
		$day = explode(",", $post['day']);
		//echo "<pre/>";
		//print_r($day);die;
		$count_per_week = count($day);
		if($post['type'] == 1)
		{
			$data['count_per_week'] = 7;
			$data['value']   =  7;
		}else
		{
			$data['value'] = $post['day'];
		    $data['count_per_week'] = $count_per_week;
		}
		$data['goal_id'] = $post['goal_id'];
		$data['type'] = $post['type'];
		$data['text'] = $post['text'];
		//echo "<pre/>";
		//print_r($data);die;
		$habitDetails = $habitTypes->add_habit_types($data);
		if(!empty($habitDetails))
		{
		$response = array(
					'status' => 1,
					'data' => $post,
					'msg' => 'Habit Added.'
				);

			}else{

				$response = array(
					'status' => 0,
					'data' => array(),
					'msg' => 'Invalid Request.'
				);	
			}

			return \Response::json($response);	
		//return \Response::json($habitDetails);

	}


	public function addHabitSchedule($post)
	{
		
		//$count_per_week = 0; 
		$habitTypes = new HabitTypes();
		
		if($post['scale'] == 0)
		{
			$data['is_scale'] = isset($post['scale'])?$post['scale']:0;
		}
		else
		{
			if(isset($post['is_apply']) && !empty($post['is_apply']))
			{
				$data['is_apply'] = $post['is_apply'];
			}
			$data['is_scale'] = $post['scale'];
			$data['maximum']  = (isset($post['highest']) && !empty($post['highest'])) ? $post['highest'] :"";
			$data['minimum']  = (isset($post['lowest']) && !empty($post['lowest'])) ? $post['lowest'] :"";
		}

		if($post['habitSchedule'] == 1)
		{
			$data['count_per_week'] = 7;
			$data['value']   =  7;
		}else
		{	if(isset($post['days']) && !empty($post['days']))
			{
				$day = implode(",", $post['days']);
			    $count_per_week = count($post['days']);
			    $data['value'] = $day;
		        $data['count_per_week'] = $count_per_week;
			}
			else
			{
				$data['value'] = 0;
		        $data['count_per_week'] = 0;
			}
		}
		$data['goal_id'] = $post['goal_id'];
		$data['type'] = $post['habitSchedule'];
		$data['text'] = $post['add_text_type'];
		//echo "<pre/>";
		//print_r($data);die;
		$habitDetails = $habitTypes->add_habit_types($data);
		if(!empty($habitDetails))
		{
		$response = array(
					'status' => 1,
					'data' => $post,
					'msg' => 'Habit Added.'
				);

			}else{

				$response = array(
					'status' => 0,
					'data' => array(),
					'msg' => 'Invalid Request.'
				);	
			}

			return \Response::json($response);	
		//return \Response::json($habitDetails);

	}

	public function getHabbitLop($id)
	{
		$habitTypes = new HabitTypes();
		$habitDetails = $habitTypes->getHabitLoop($id);
		//return json_encode($habitDetails);
		return \Response::json($habitDetails);
	}

	public function getTaskTemplate($id)
	{
		//echo $id;die;
		$task = new TaskTemplate();
		$taskDetails = $task->getTaskTemplate($id);
		//print_r($taskDetails);die;
		return \Response::json($taskDetails);
	}

	public function showAndHideInLobby(Request $request)
	{
		$user_id=Auth::user()->id;
		$post = $request->all();
		//echo "<Pre/>";
		//print_r($post);die;
		$goal = new Goals();
		$goalDetails = $goal->showAndHideInLobby($user_id,$post);
		/*echo "<Pre/>";
		print_r($goalDetails);die;
*/
		if($goalDetails)
		{
			//echo "success";die;
			$response = array(
					'status' => 1,
					'data' => $post,
					'msg' => 'Your Request Successfully Submitted.'
				);
		}
		else
		{
			//echo "error";die;
			$response = array(
					'status' => 0,
					'data' => "",
					'msg' => 'Invalid Request.'
				);
		}

		return \Response::json($response);

	}

	public function attributesAllActiveSheet($id)
	{
		$data = Goals::getAllActiveSheet($id);
		$pdf = PDF::loadView('goals.pdfTemplate',["attributes"=>$data]);
		$filename = time();
		$pdfName = $filename.".pdf";
     	return $pdf->download($pdfName);
	}


		 public  function getTaskEndDate($data){

    	$post = $data;

    	$current_date = Carbon::now();

    	$total_repeats = 0;

    	$deadlines=array();

    	$repeat_qty = isset($post['repeat_qty'])?$post['repeat_qty']:1;
    	$repeat_on = isset($post['repeat_on'])?$post['repeat_on']:"";
    	$repeat_frequency = isset($post['repeat_frequency'])?$post['repeat_frequency']:"month";

    	$id = isset($post['id'])?$post['id']:"";

    	$add_suffix = isset($post['add_suffix'])?$post['add_suffix']:"";

    	$end_on = isset($post['ends_on'])?$post['ends_on']:"";
    	$begin_on = isset($post['begin_on'])?$post['begin_on']:"";

    	$end = isset($post['end_on_value'])?$post['end_on_value']:"";

    	$begin_on_value = isset($post['begin_on_value'])?$post['begin_on_value']:"";

    	$task_name = isset($post['task_name'])?$post['task_name']:"";

    	//$task_name=$task->task_name;

    	//$model=new Goals();
    	//$_goal=$model->where("id", $task->task_id)->first();

    	$repeat_tasks=array();


    	if($repeat_frequency=='weeks'){
    		Carbon::setWeekStartsAt(Carbon::SUNDAY); 
    		if($end_on=='date'){
    			$current_date = ($begin_on=='now')?Carbon::now():Carbon::parse($begin_on_value);
	    		$end = Carbon::parse($end);
	    		$total_repeats = $end->diffInWeeks($current_date)+2; // 2 weeks addition to lapsing days in the first and last week
	    	}else{
	    		$total_repeats = $end;
	    	}
	    	//echo "<pre/>";
	    	//echo "Weeks";
	    	//print_r($total_repeats);die;

	    	$week_counter=1;
	    	for ($i=0; $i < $total_repeats; $i++) {

	    		$current_date = ($begin_on=='now')?Carbon::now():Carbon::parse($begin_on_value);

	    		$every = $i*$repeat_qty;
	  
	    		$current_date->addWeek($every);
	    		
	    		$date = $current_date->startOfWeek();

	    		$current_date->addDays($repeat_on);
	    		
	    		$task_date=$current_date->format('Y-m-d');

	    		if($begin_on == 'date' && $begin_on_value){
	    			$begin_on_date = Carbon::parse($begin_on_value);
	    			if($task_date < $begin_on_date){
	    				continue;
	    			}	
	    		}

	    		if($end_on=='date' && $end){  // check in case end on date defined and loop is exeeding...
	    			
	    			$end_on_date = Carbon::parse($end);
	    			
	    			if($task_date > $end_on_date){
	    				continue;
	    			}
	    		}

	    		$task_name=($add_suffix)?$task_name." - week ".($week_counter):$task_name;
	    		
	    		$week_data=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$id);

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



    	}else if($repeat_frequency=='months'){
    		
    		if($end_on=='date'){
    			$current_date = ($begin_on=='now')?Carbon::now():Carbon::parse($begin_on_value);
	    		$end = Carbon::parse($end);
	    		$total_repeats = $end->diffInMonths($current_date)+2;
	    	}else{
	    		$total_repeats = $end;
	    	}
	    	//echo "<pre/>";
	    	//echo "Months";
	    	//print_r($total_repeats);die;
	    	for ($i=0; $i <$total_repeats; $i++) { 
	    		//$current_date = Carbon::now();
	    		 $current_date = ($begin_on=='now')?Carbon::now():Carbon::parse($begin_on_value);
	    		$every = $i*$repeat_qty;
	    		//var_dump($every);
	    		$current_date->addMonthsNoOverflow($every);
	    		if($repeat_on=='thisday'){
	    			$day = date("d");
	    			$task_date=$current_date->format('Y-m-d');
	    			//var_dump(date("Y-m-d H:i:s"),$task_date);

	    		}else if($repeat_on=='firstday'){
	    			$task_date=$current_date->startOfMonth()->toDateString();
	    		}else{
	    			$task_date=$current_date->endOfMonth()->toDateString();
	    		}
	    		// echo date("m",strtotime($task_date));

	    		if($begin_on == 'date' && $begin_on_value){
	    			$begin_on_date = Carbon::parse($begin_on_value);
	    			if(date("Y-m",strtotime($task_date)) < date("Y-m",strtotime($begin_on_date))){
	    				//echo date("m",strtotime($task_date));
	    				//exit();
	    				continue;
	    			}	
	    		}

	    		if($end_on=='date' && $end){  // check in case end on date defined and loop is exeeding...
	    			
	    			$end_on_date = Carbon::parse($end);
	    			
	    			if($task_date > $end_on_date){
	    				continue;
	    			}
	    		}

	    		$task_name=($add_suffix)?$task_name." - ".$current_date->format('F'):$task_name;

	    		
    			//$deadlines[]=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$task->id);
    			$month_data=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$id);
    			
    			$repeat_tasks[]=$month_data;
    			//Self::clone_task($task->task_id, $month_data);
	    		# code...
	    	}

    	}else if($repeat_frequency=='years'){
    		
    		if($end_on=='date'){
    			$current_date = ($begin_on=='now')?Carbon::now():Carbon::parse($begin_on_value);
	    		$end = Carbon::parse($end);
	    		$total_repeats = $end->diffInYears($current_date);
	    	}else{
	    		$total_repeats = $end;
	    	}
	    	/*echo "<pre/>";
	    	echo "Years";
	    	print_r($total_repeats);die;*/

	    	for ($i=0; $i < $total_repeats; $i++) { 
	    		//$current_date = Carbon::now();
	    		$current_date = ($begin_on=='now')?Carbon::now():Carbon::parse($begin_on_value);
	    		$every = $i*$repeat_qty;
	    		$current_date->addYear($every);
	    		if($repeat_on=='thisday'){
	    			$task_date=$current_date->format('Y-m-d');
	    		}else if($repeat_on=='firstday'){
	    			$task_date=$current_date->startOfYear()->toDateString();
	    		}else{
	    			$task_date=$current_date->endOfYear()->toDateString();
	    		}

	    		if($begin_on == 'date' && $begin_on_value){
	    			
	    			$begin_on_date = Carbon::parse($begin_on_value)->format("Y");
	    		
	    			if(date("Y",strtotime($task_date)) < $begin_on_date){
	    				continue;
	    			}	
	    		}

	    		if($end_on=='date' && $end){  // check in case end on date defined and loop is exeeding...
	    			
	    			$end_on_date = Carbon::parse($end)->format("Y");

	    			if(date("Y",strtotime($task_date)) > $end_on_date){
	    				continue;
	    			}
	    		}

	    		$task_name=($add_suffix)?$task_name." - ".$current_date->format('Y'):$task_name;

    			//$deadlines[]=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$task->id);
    			$year_data=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$id);
    			//Self::clone_task($task->task_id, $year_data);
	    		$repeat_tasks[]=$year_data;
	    		# code...
	    	}

    	}

    	$last_task = array();

    	if(!empty($repeat_tasks)){

	    	$last_task = end($repeat_tasks);

	    	//$_goal->due_date=$last_task['due_date'];

	    	//$_goal->save();

	    	/*foreach ($repeat_tasks as $key => $_task) {
	    		Self::clone_task($task->task_id, $_task);
	    	}*/
    	}

    	return $last_task;
    }



    	public function attribute_last_sheet(Request $request){

		if($post=$request->input()){

			
			$user_id=Auth::user()->id;

			$_habit=Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id",$user_id)->first();

			if($_habit){
				$meta_key = "addtomylist_id";
				$meta=GoalsMeta::get($_habit->id, $meta_key);
				if(isset($meta) && !empty($meta))
				{
					$_meta = json_decode($meta->meta_attr,true);
					
					$meta->meta_value = $_meta['status'];
					
					$response = array(
					'status' => 1,
					'data' => $meta,
					'msg' => 'Meta Habit.'
				);
				}else{

				$meta_key=$post['attr']."-".$post['sheet_id'];

				$meta=GoalsMeta::get($_habit->id, $meta_key);

				$response = array(
					'status' => 1,
					'data' => $meta,
					'msg' => 'Meta Habit.'
				);

				}
				
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



	 public function editStatement($id){
		if(!empty($id)){
			
			$user_id=Auth::user()->id;

			$_habit=Goals::where("id", $id)->where("user_id",$user_id)->first();


			//$_habit=Goals::where("auto_save_id", $_goal->auto_save_id)->where("user_id",$user_id)->first();

			//print_r($_habit);


			if(isset($_habit) && !empty($_habit)){
			$auto_save_id = $_habit->auto_save_id;
			$goal_name = $_habit->name;
			return view('goals-statement.statements_add',["auto_save_id"=>$auto_save_id,'goal_name'=>$goal_name]);
			
		}else{
			return redirect("/list")->with("error","Goal not found.");
		}

		}

	}

}
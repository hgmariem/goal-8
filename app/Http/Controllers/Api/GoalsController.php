<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Def_goals;
use App\Model\Goals;
use App\Model\GoalsMeta;
use App\Model\HabitTypes;
use App\Model\TaskTemplate;
use App\Model\Trophy;
use App\Traits\ApiResponser;
use Auth;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use View;
use \Carbon\Carbon;

class GoalsController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {

        $model = new Goals();
        $model_def = new Def_goals();
        $post = $request->all();

        $get_goals = '';
        if (!isset($post['is_default'])) 
        {$post['is_default'] = 1;}
        if ($post['is_default']) {
            $get_goals = $model->get_goals($post['oauth_token']->user_id, $post['is_default']); // is_default=1
        } else {
            $get_goals = $model->get_goals($post['oauth_token']->user_id, $post['is_default']);
        }

        if ($get_goals) {
            return $this->success($get_goals, 'Goals Listed Successfully.');
        } else {
            return $this->error('Goals is empty!', 404);
        }
    }

    public function change_state(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'self' => 'required',
            'auto_save_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $model = new Goals();
        $post = $request->all();
        if ($status = $model->change_state($post['auto_save_id'], $post['self'])) {
            return $this->success($status, 'Successfully Changed State');

        } else {
            return $this->error('Unable to Change Status', 403);
        }

    }

    public function task_tree_state(Request $request)
    {
        $model = new Goals();
        $post = $request->all();
        if ($status = $model->change_state($post['auto_save_id'], $post['self'])) {
            return $this->success($status, 'Status Changed Successfully!');
        } else {
            return $this->error('Unable Change Status', 403);
        }
    }

    public function createApi_goals(Request $request)
    {

        $post = $request->all();
        $validator = Validator::make($post, [
            'auto_save_id' => 'required',
            'name' => 'required',
            'is_show_lobby' => 'required',
            'is_active' => 'required',
            'detail_order' => 'required',
            'has_sub' => 'required',
            'type_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }
        $model = new Goals();

        $post['user_id'] = $post['oauth_token']->user_id;
        if (isset($post['status'])) {
            $status_text = trim(preg_replace('/\s+/', ' ', $post['status']));
            $post['status'] = $status_text;
        }

        if (isset($post['improvement'])) {
            $improvement_text = trim(preg_replace('/\s+/', ' ', $post['improvement']));
            $post['improvement'] = $improvement_text;
        }

        if (isset($post['risk'])) {
            $risk_text = trim(preg_replace('/\s+/', ' ', $post['risk']));
            $post['risk'] = $risk_text;
        }

        if (isset($post['benefits'])) {
            $benefits_text = trim(preg_replace('/\s+/', ' ', $post['benefits']));
            $post['benefits'] = $benefits_text;
        }

        if (isset($post['vision'])) {
            $vision_text = trim(preg_replace('/\s+/', ' ', $post['vision']));
            $post['vision'] = $vision_text;
        }

        if (isset($post['vision_decades'])) {
            $vision_decades_text = trim(preg_replace('/\s+/', ' ', $post['vision_decades']));
            $post['vision_decades'] = $vision_decades_text;
        }

        if (isset($post['barriers'])) {
            $barriers_text = trim(preg_replace('/\s+/', ' ', $post['barriers']));
            $post['barriers'] = $barriers_text;
        }

        if (isset($post['priority'])) {
            $priority_text = trim(preg_replace('/\s+/', ' ', $post['priority']));
            $post['priority'] = $priority_text;
        }

        if (isset($post['initiative'])) {
            $initiative_text = trim(preg_replace('/\s+/', ' ', $post['initiative']));
            $post['initiative'] = $initiative_text;
        }

        if (isset($post['help'])) {
            $help_text = trim(preg_replace('/\s+/', ' ', $post['help']));
            $post['help'] = $help_text;
        }

        if (isset($post['support'])) {
            $support_text = trim(preg_replace('/\s+/', ' ', $post['support']));
            $post['support'] = $support_text;
        }

        if (isset($post['environment'])) {
            $environment_text = trim(preg_replace('/\s+/', ' ', $post['environment']));
            $post['environment'] = $environment_text;
        }

        if (isset($post['imagery'])) {
            $imagery_text = trim(preg_replace('/\s+/', ' ', $post['imagery']));
            $post['imagery'] = $imagery_text;
        }
        $post = $this->process_goal_request($post, $post['oauth_token']->user_id);
        $goal = $post['goal'];
        if ($post['goal'] && $goal_id = $model->add_goalsForApi($post['goal'])) {
            //echo "<pre/>";
            //print_r($goal_id->id);die;
            if ($post['goal']['type_id'] == 1) {
                $_habit_type_data = $post['_habit_type_data'];
                $_habit_type_data['goal_id'] = $goal_id->id;
                $types = new HabitTypes();
                $types->add_habit_types($_habit_type_data);
            }
        }
        return $this->success($goal_id->toArray(), 'Goal Saved Successfully!', 201);
        //return response()->json(['data'=>$goal_id->toArray(),'status'=>1,'msg'=>"Goal Saved Successfully.",'error'=>""]);
    }

    public function create_goals(Request $request)
    {

        $post = $request->all();
        $model=new Goals();
        $post['user_id'] = $post['oauth_token']->user_id;
        $is_active = isset($post['is_active']) ? $post['is_active'] : 1;
        if (isset($post['status'])) {
            $status_text = trim(preg_replace('/\s+/', ' ', $post['status']));
            $post['status'] = $status_text;
        }

        if (isset($post['improvement'])) {
            $improvement_text = trim(preg_replace('/\s+/', ' ', $post['improvement']));
            $post['improvement'] = $improvement_text;
        }

        if (isset($post['risk'])) {
            $risk_text = trim(preg_replace('/\s+/', ' ', $post['risk']));
            $post['risk'] = $risk_text;
        }

        if (isset($post['benefits'])) {
            $benefits_text = trim(preg_replace('/\s+/', ' ', $post['benefits']));
            $post['benefits'] = $benefits_text;
        }

        if (isset($post['vision'])) {
            $vision_text = trim(preg_replace('/\s+/', ' ', $post['vision']));
            $post['vision'] = $vision_text;
        }

        if (isset($post['vision_decades'])) {
            $vision_decades_text = trim(preg_replace('/\s+/', ' ', $post['vision_decades']));
            $post['vision_decades'] = $vision_decades_text;
        }

        if (isset($post['barriers'])) {
            $barriers_text = trim(preg_replace('/\s+/', ' ', $post['barriers']));
            $post['barriers'] = $barriers_text;
        }

        if (isset($post['priority'])) {
            $priority_text = trim(preg_replace('/\s+/', ' ', $post['priority']));
            $post['priority'] = $priority_text;
        }

        if (isset($post['initiative'])) {
            $initiative_text = trim(preg_replace('/\s+/', ' ', $post['initiative']));
            $post['initiative'] = $initiative_text;
        }

        if (isset($post['help'])) {
            $help_text = trim(preg_replace('/\s+/', ' ', $post['help']));
            $post['help'] = $help_text;
        }

        if (isset($post['support'])) {
            $support_text = trim(preg_replace('/\s+/', ' ', $post['support']));
            $post['support'] = $support_text;
        }

        if (isset($post['environment'])) {
            $environment_text = trim(preg_replace('/\s+/', ' ', $post['environment']));
            $post['environment'] = $environment_text;
        }

        if (isset($post['imagery'])) {
            $imagery_text = trim(preg_replace('/\s+/', ' ', $post['imagery']));
            $post['imagery'] = $imagery_text;
        }

        $post = $this->process_goal_request($post, $post['oauth_token']->user_id);

        $goal = $post['goal'];

        $new_goal = array();

        if ($post['goal'] && $goal_id = $model->add_goalsForApi($post['goal'])) {

            if ($post['goal']['type_id'] == 1) {
                $_habit_type_data = $post['_habit_type_data'];
                $_habit_type_data['goal_id'] = $goal_id->id;
                $types = new HabitTypes();
                $types->add_habit_types($_habit_type_data);
            }

            $habit = Goals::get_habit_types($goal_id->id);

            $goal_id['habit_type'] = isset($habit->type) ? $habit->type : 1;
            $goal_id['value'] = isset($habit->value) ? $habit->value : 7;
            $goal_id['lowest'] = isset($habit->minimum) ? $habit->minimum : "";
            $goal_id['highest'] = isset($habit->maximum) ? $habit->maximum : "";
            $goal_id['is_apply'] = isset($habit->is_apply) ? $habit->is_apply : 0;
            $goal_id['scale_type'] = isset($habit->is_scale) ? $habit->is_scale : 0;
            $goal_id['add_text_type'] = isset($habit->text) ? $habit->text : "";

            $new_goal['goal'] = $goal_id->toArray();

            $i = 0;

            if (isset($post['childs']) && !empty($post['childs'])) {

                foreach ($post['childs'] as $detail_order => $child_goal) {
                    // main children

                    $child_goal = $this->process_goal_request($child_goal, $post['oauth_token']->user_id);
                    $child_goal['goal']['detail_order'] = $child_goal['goal']['detail_order'];
                    $child_goal['goal']['parent_id'] = isset($child_goal['goal']['parent_id']) && !empty($child_goal['goal']['parent_id']) ? $child_goal['goal']['parent_id'] : $goal_id->id;
                    $child_goal['goal']['top_parent_id'] = isset($child_goal['goal']['top_parent_id']) && !empty($child_goal['goal']['top_parent_id']) ? $child_goal['goal']['top_parent_id'] : $goal_id->id;
                    $child_goal['goal']['is_active'] = $is_active;
                    $new_goal['goal']['childs'][$i] = $this->add_sub_goal($child_goal, $goal_id, $post['oauth_token']->user_id);

                    $habit = Goals::get_habit_types($new_goal['goal']['childs'][$i]['id']);

                    $new_goal['goal']['childs'][$i]['habit_type'] = isset($habit->type) ? $habit->type : 1;
                    $new_goal['goal']['childs'][$i]['value'] = isset($habit->value) ? $habit->value : 7;
                    $new_goal['goal']['childs'][$i]['lowest'] = isset($habit->minimum) ? $habit->minimum : "";
                    $new_goal['goal']['childs'][$i]['highest'] = isset($habit->maximum) ? $habit->maximum : "";
                    $new_goal['goal']['childs'][$i]['is_apply'] = isset($habit->is_apply) ? $habit->is_apply : 0;
                    $new_goal['goal']['childs'][$i]['scale_type'] = isset($habit->is_scale) ? $habit->is_scale : 0;
                    $new_goal['goal']['childs'][$i]['add_text_type'] = isset($habit->text) ? $habit->text : "";
                    //$new_goal['goal']['childs'][$i]['habit_type'] = (isset($habit) && !empty($habit))?$habit:array();

                    $i++;
                }

            } else {

                $children = $this->getSubGoal($goal_id->id);

                /*echo "<pre/>";
                print_r($childrens);die;*/
                if (isset($children) && !empty($children)) {

                    $new_goal['goal']['childs'] = $children;

                } else {
                    $new_goal['goal']['childs'] = array();
                }

                $i++;
            }

        }
        return $this->success($new_goal, 'Goal Saved Successfully!', 201);
    }

    public function add_sub_goal($post, $top_parent_id, $userDetails)
    {

        $model = new Goals();
        $data = array();
        $goal_id = $model->add_goalsForApi($post['goal']);
        if (isset($goal_id) && !empty($goal_id)) {

            if ($post['goal']['type_id'] == 1) {
                #print_r($post);
                $_habit_type_data = $post['_habit_type_data'];
                $_habit_type_data['goal_id'] = $goal_id->id;
                $types = new HabitTypes();
                $types->add_habit_types($_habit_type_data);
            }

            /*if($post['childs'] && count($post['childs'])>0){
            foreach($post['childs'] as $detail_order=>$child_goal){
            $child_goal=$this->process_goal_request($child_goal,$post['oauth_token']->user_id);
            $child_goal['goal']['detail_order']=$detail_order+1;
            $child_goal['goal']['parent_id']=$goal_id;
            $child_goal['goal']['top_parent_id']=$top_parent_id;

            $this->add_sub_goal($child_goal, $top_parent_id,$post['oauth_token']->user_id);
            }
            }*/

            $children = $this->getSubGoal($goal_id->id);
            if (isset($children) && !empty($children)) {

                $goal_id['childs'] = (isset($children) && !empty($children)) ? $children : array();

            } else {

                $goal_id['childs'] = array();
            }

            return $goal_id->toArray();

        }

        return $data;
    }

    public function process_goal_request($post, $userDetails)
    {

        if (isset($post['parent']) && !empty($post['parent'])) {

            unset($post['parent']);
        }

        $response = array();

        $childs = (isset($post['childs']) && !empty($post['childs'])) ? $post['childs'] : array();

        unset($post['childs']);

        if (isset($post['type_id']) && $post['type_id'] == 2 && isset($post['due_date']) && !empty($post['due_date'])) {
            $post['due_date'] = date('Y-m-d', strtotime($post['due_date']));
        } else if (isset($post['due_date'])) {
            $post['due_date'] = date('Y-m-d', strtotime($post['due_date']));
        } else {

            unset($post['due_date']);
        }

        $_habit_type_data = array();

        if (isset($post['habit_start_date']) && !empty($post['habit_start_date'])) {

            //var_dump($post['auto_save_id'].":".$post['habit_start_date']);

            $post['habit_start_date'] = date('Y-m-d', strtotime($post['habit_start_date']));

            //var_dump("after convert");

            //var_dump($post['auto_save_id'].":".$post['habit_start_date']);
        }

        if (isset($post['type_id']) && $post['type_id'] == 1) {

            $habit_type = $post['habit_type'];

            //echo "if........";die;
            if (isset($post['scale_type']) && $post['scale_type'] == 1) {
                $_habit_type_data['is_scale'] = isset($post['scale_type']) ? $post['scale_type'] : 0;
                $_habit_type_data['minimum'] = (isset($post['lowest']) && !empty($post['lowest'])) ? $post['lowest'] : "";
                $_habit_type_data['maximum'] = (isset($post['highest']) && !empty($post['highest'])) ? $post['highest'] : "";
                $_habit_type_data['is_apply'] = (isset($post['is_apply']) && !empty($post['is_apply'])) ? $post['is_apply'] : "";
            } else {
                $_habit_type_data['is_scale'] = isset($post['scale_type']) ? $post['scale_type'] : 0;
                $_habit_type_data['minimum'] = (isset($post['lowest']) && !empty($post['lowest'])) ? $post['lowest'] : "";
                $_habit_type_data['maximum'] = (isset($post['highest']) && !empty($post['highest'])) ? $post['highest'] : "";
                $_habit_type_data['is_apply'] = (isset($post['is_apply']) && !empty($post['is_apply'])) ? $post['is_apply'] : "";
            }

            //$arrHabitType = explode(';', $habit_type);

            //$model->habitType = new HabitType;
            $_habit_type_data['type'] = $habit_type;
            $_habit_type_data['value'] = isset($post['value']) ? $post['value'] : (($post['value'] == 1) ? 7 : '');
            $_habit_type_data['text'] = isset($post['add_text_type']) ? $post['add_text_type'] : "";

            $_habit_type_data['count_per_week'] = 7;
            if (2 == $_habit_type_data['type']) {
                $_habit_type_data['count_per_week'] = $_habit_type_data['value'];
            } else if ($_habit_type_data['type'] == 3) {
                $_habit_type_data['count_per_week'] = ($post['value'] != "") ? count(explode(',', $post['value'])) : 0;
            }

        }

        unset($post['habit_type']);
        unset($post['value']);
        unset($post['scale_type']);
        unset($post['lowest']);
        unset($post['highest']);
        unset($post['is_apply']);
        unset($post['oauth_token']);
        unset($post['add_text_type']);
        $post['parent_id'] = isset($post['parent_id']) ? $post['parent_id'] : 0;
        $post['is_active'] = isset($post['is_active']) ? $post['is_active'] : 0;
        $post['top_parent_id'] = isset($post['top_parent_id']) ? $post['top_parent_id'] : 0;

        //$post['user_id']=$post['oauth_token']->user_id;
        $post['has_sub'] = isset($post['has_sub']) && !empty($post['has_sub']) ? 1 : 0;
        $post['level'] = isset($post['level']) ? $post['level'] : 0;
        $post['detail_order'] = isset($post['detail_order']) ? $post['detail_order'] : 0;
        $post['auto_save_id'] = (isset($post['auto_save_id']) && !empty($post['auto_save_id'])) ? $post['auto_save_id'] : $this->autosaveid();
        $response['_habit_type_data'] = $_habit_type_data;
        $response['goal'] = $post;
        $response['childs'] = $childs;

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

    public function edit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $post = $request->all();

        $id = $post['id'];
        $model = new Goals();

        $goal_data = $model->_get_goal_attributes($id);

        //echo "<pre/>";
        //print_r($goal_data);die;
        if (!$goal_data) {
            return $this->error('Goal Not Found!', 404);
        }

        if ($goal_data->is_default == 0 && $goal_data->user_id != $post['oauth_token']->user_id) {

            return $this->error('Goal Not Found!', 404);
        }

        $_prefill_status = get_prefill_status_data($goal_data->id);

        $prefill_status = ($_prefill_status) && isset($_prefill_status->meta_attr) ? json_decode($_prefill_status->meta_attr, true) : array();

        $goal_data->prefill_status = isset($prefill_status['status']) ? nl2br($prefill_status['status']) : '';

        return $this->success($goal_data, 'Get Single Goal.');
    }

    public function getGoalSheet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $post = $request->all();

        $id = $post['id'];

        $goal = Goals::where(["id" => $id, "parent_id" => 0, "top_parent_id" => 0, "is_delete" => 0])->first();

        if (!empty($goal)) {
            $goals = new Goals();
            $data = $goals->get_goalSheet($id);
            return $this->success($data, 'get Goal All Sheet!');

        } else {

            return $this->error('Invalid Request', 419);

        }

    }

    public function save_GoalSheet(Request $request)
    {
        $post = $request->all();

        //$extras = $post['extras'];

        //echo "<pre/>";
        //print_r($extras);die;
        $validator = Validator::make($request->all(), [
            'sheet_id' => 'required',
            'sheet_number' => 'required',
            'sheet_name' => 'required',
            'attr' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $_habit = Goals::where("id", $post['id'])->where("is_delete", 0)
            ->where("parent_id", 0)
            ->where("top_parent_id", 0)
            ->where("user_id", $post['oauth_token']->user_id)
            ->first();
            
//return $this->success($extras);
        if (!empty($_habit)) {

            //$post = array();

            $new_post_data = array();


                    $post['meta_key'] = $post['attr'] . "-" . $post['sheet_id'];

                    GoalsMeta::deactivate_sheets($_habit->id, $post['attr']);
                    $this->updateGoalQuestions($_habit->id, $post['attr'], $post['html']);

                    //StatementValues::deactivate_sheets($_habit->id, $value['attr']);

                    $html = nl2br($post['html']);

                    $post['html'] = $html;

                    //$post['meta_type']=$value['attr'];

                    $post['meta_value'] = $html;

                    $post['goal_id'] = $_habit['id'];

                    $post['is_active'] = 1;

                    //$post['user_id']=$post['oauth_token']->user_id;

                    //$post['is_active']=1;

                    $post['meta_attr'] = json_encode($post);

                    $statement = GoalsMeta::add($post);

                    $data = json_decode($statement->meta_attr, true);
                    $data['is_active'] = $statement->is_active;
                    $data['goal_id'] = $statement->goal_id;

                    $new_post_data[$post['attr']][] = $data;
                
                return $this->success($new_post_data, 'Goal statement sheet successfully added!');
            

        } else {

            return $this->error('Invalid request', 419);
        }
    }

    public function updateGoalQuestions($goal_id, $attr, $html)
    {

        $goals = Goals::where("id", $goal_id)->where("is_delete", 0)->first();
        switch ($attr) {
            case "status":
                $goals->status = $html;
                break;
            case "improvement":
                $goals->improvement = $html;
                break;
            case "risk":
                $goals->risk = $html;
                break;
            case "help":
                $goals->help = $html;
                break;
            case "barriers":
                $goals->barriers = $html;
                break;
            case "environment":
                $goals->environment = $html;
                break;
            case "support":
                $goals->support = $html;
                break;
            case "initiative":
                $goals->initiative = $html;
                break;
            case "priority":
                $goals->priority = $html;
                break;
            case "vision_decades":
                $goals->vision_decades = $html;
                break;
            case "vision":
                $goals->vision = $html;
                break;
            case "benefits":
                $goals->benefits = $html;
                break;
            default:
                $goals->imagery = $html;
        }

        $goals->save();

        return $goals;

    }

    public function get_goal_details(Request $request)
    {

        $post = $request->all();
        //echo "<pre/>";
        //print_r($post);die;
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $post = $request->all();

        $id = $post['id'];
        $model = new Goals();

        $goal_data = $model->_get_goal_attribut($id, $post['oauth_token']->user_id);

        if (!empty($goal_data)) {
            $childrens = array();
            $children = $this->getSubGoal($id);

            /*if(isset($children) && !empty($children))
            {
            foreach ($children as $key => $val) {

            $sub_childs = $this->getSubGoal($val['id']);
            $val['childs'] = (isset($sub_childs) && !empty($sub_childs))?$sub_childs:array();
            $childrens[] = $val;
            }

            }*/
            $goal_data['childs'] = (isset($children) && !empty($children)) ? $children : array();
        } else {
            return $this->error('Not Found!', 404);
        }

        return $this->success($goal_data, 'Get Single Goal.');
    }

    public function getSubGoal($id)
    {
        $data = array();
        $children = Goals::where("parent_id", $id)->where("is_delete", 0)->get()->toArray();
        if (isset($children) && !empty($children)) {
            foreach ($children as $key => $va) {
                $sub_childs = $this->getSubGoal($va['id']);
                $habit = Goals::get_habit_types($va['id']);
                // echo "<pre/>";
                // print_r($habit);die;
                //$sub_childs[$key]['habit_types'] = (isset($habit) && !empty($habit))?$habit:array();
                //$va['habit_type'] = (isset($habit) && !empty($habit))?$habit:array();

                $va['habit_type'] = isset($habit->type) ? $habit->type : 1;
                $va['value'] = isset($habit->value) ? $habit->value : 7;
                $va['lowest'] = isset($habit->minimum) ? $habit->minimum : "";
                $va['highest'] = isset($habit->maximum) ? $habit->maximum : "";
                $va['is_apply'] = isset($habit->is_apply) ? $habit->is_apply : 0;
                $va['scale_type'] = isset($habit->is_scale) ? $habit->is_scale : 0;
                $va['add_text_type'] = isset($habit->text) ? $habit->text : "";

                $va['childs'] = (isset($sub_childs) && !empty($sub_childs)) ? $sub_childs : array();

                $data[] = $va;
            }
        }

        return $data;
    }

    public function generate_sub_goal_tree($sub_goals)
    {

        //$html = View::make('countries.list', compact('countries'))->render();
        return $result;
    }

    public function default_edit($id)
    {

        $model = new Goals();

        $goal_data = $model->_get_goal_attributes($id);

        if (!$goal_data) {
            return redirect("/list")->with("success", "Goal does not exists.");
        }

        $html = "";

        if ($goal_data && $sub_goals = $goal_data->children) {
            $html = View::make('goals.sub_goal', compact('sub_goals'))->render();
        }

        $_prefill_status = get_prefill_status_data($goal_data->id);

        $prefill_status = ($_prefill_status) && isset($_prefill_status->meta_attr) ? json_decode($_prefill_status->meta_attr, true) : array();

        $goal_data->prefill_status = isset($prefill_status['status']) ? nl2br($prefill_status['status']) : '';

        return view('goals.goals_add', ['goals_edit' => $goal_data, "html" => $html, "is_default" => 1]);
    }

    public function update(Request $request)
    {

        $model = new Goals();
        $post = $request->input();
        $add = $model->edit_goals($post);

        if ($add) {
            return redirect('list')->with('success', 'Success');
        } else {
            return back()->withErrors()->withInput();
        }
    }

    public function default_update(Request $request)
    {

        $model_def = new Def_goals();

        $post = $request->input();

        $add_def = $model_def->edit_Def_goals($post);

        if ($add_def) {
            return redirect('list')->with('success', 'Success');
        } else {
            return back()->withErrors()->withInput();
        }
    }

    public function sort_list(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'item.*' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $post = $request->all();
        //return $this->success($post['item']);
        if ($post && isset($post['item']) && !empty($post['item'])) {
            #$print_r($post[0]);
            $goal_ids = array_reverse($post["item"]);
            foreach ($goal_ids as $order => $id) {

                if ($goal = Goals::find($id)) {
                    $goal->list_order = $order;
                    $goal->save();
                }

            }
            return $this->success(null, 'List Order Changed Successfully.');

        } else {
            return $this->error('Unable to change list order.', 403);
        }

    }

    public function sort_self(Request $request)
    {

        parse_str($request->input("data"), $post);

        //print_r($post);

        if ($post && isset($post['item']) && !empty($post['item'])) {

            $goal_ids = array_reverse($post['item']);

            foreach ($goal_ids as $i => $id) {
                $goal = Goals::find($id);
                $goal->self_order = $i;
                $goal->save();
            }
            return $this->success(null, 'List Order Changed Successfully.');

        } else {
            return $this->error(null, 'Unable to change list order.');
        }
    }

    public function delete(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'auto_save_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $post = $request->input();

        if (isset($post['auto_save_id']) && !empty($post['auto_save_id'])) {

            $auto_save_id = $post['auto_save_id'];

            $model = new Goals();

            //$post['oauth_token']->user_id=Auth::user()->guid;

            $goal = Goals::where("auto_save_id", $auto_save_id)->first();
            if ($goal) {
                if ($goal->user_id == $post['oauth_token']->user_id) {

                    $add = $model->delete_goals($goal->id);

                    if ($add) {
                        return $this->success(null, 'Goal Deleted Successfully.');
                    } else {
                        return $this->error('Unable to delete goal.', 403);
                    }
                } else {
                    return $this->error('Invalid User', 403);
                }
            } else {
                return $this->success(null, 'Goal Deleted Successfully.');

            }
        } else {
            return $this->success(null, 'Goal Deleted Successfully.');
        }

    }

    public function weekly_habits(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }
        $post = $request->all();
        $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);

        //$post['oauth_token']->user_id=Auth::user()->guid;

        $filter = array();

        $filter['user_id'] = $post['oauth_token']->user_id;
        $filter['is_default'] = 0;
        $filter['isMobile'] = 1;
        if ($filter['isMobile']) {
            if ($post['type'] == 'prev') {
                $current = Carbon::parse($post['date']);
                $post['date'] = $current->addDays(-1)->format("Y-m-d");
            } else {
                $current = Carbon::parse($post['date']);
                $post['date'] = $current->addDays(1)->format("Y-m-d");
            }

        } else {
            if ($post['type'] == 'prev') {
                $current = Carbon::parse($post['date']);
                $post['date'] = $current->addDays(-8)->format("Y-m-d");
            } else {
                $current = Carbon::parse($post['date']);
                $post['date'] = $current->format("Y-m-d");
            }
        }

        #print_r($post['date']);

        $filter['start_date'] = isset($post['date']) && !empty($post['date']) ? $post['date'] : date("Y-m-d");

        $model = new Goals();
        //$days=$model->weekdays($filter['start_date']);
        if ($filter['isMobile']) {
            $days = $model->weekday($filter['start_date']);
        } else {
            $days = $model->weekdays($filter['start_date']);
        }

        $habits = $model->get_habits($filter);

        return $this->success($habits, 'Get Habits Successfully');

    }

    public function tasks_list(Request $request)
    {

        $filter = array();
        $post = $request->all();
        $post['oauth_token']->user_id = Auth::user()->guid;
        $model = new Goals();

        $filter['start_date'] = isset($post['date']) && !empty($post['date']) ? $post['date'] : date("Y-m-d");

        $filter['user_id'] = $post['oauth_token']->user_id;

        $filter['is_default'] = 0;

        $filter['view_type'] = $post['view_type'];

        $html = $this->render_task_list($filter);

        return response()->json([
            'status' => 'Success',
            'message' => 'Task Listed Successfully.',
            'html' => $html,
            'filter' => $filter,
        ], 200);
    }

    public function render_task_list($filter)
    {
        $model = new Goals();
        $view_type = $filter['view_type'];

        $tasks = $model->get_tasks($filter);

        $tasks['view_type'] = $view_type;
        $tasks['items'] = $tasks;
        $isMobile = $this->isMobile;
        $partial_path = "goals.partials";

        $partial_path .= ($isMobile) ? ".mobile" : "";

        $view = $partial_path . ".task_tree";

        if ($view_type == 'list') {
            $view = $partial_path . ".task_list";
        } else if ($view_type == 'leaf') {
            $view = $partial_path . ".task_leaf";
        }

        //print_r($view);
        //print_r($tasks);
        $html = View::make($view, ['tasks' => $tasks])->render();
        return $html;
    }

    public function task_complete(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'task_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $filter = array();
        $post = $request->all();

        $model = new Goals();
        if ($goal = $model->get_by_id($post['task_id'])) {
            $goal->percent = 100;
            $goal->save();
        }
        return $this->success(null, 'Task Listed Successfully.');

    }

    public function reactive_task(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $post = $request->all();

        $userDetails = $this->repository->getUserByParam('id', $post['oauth_token']->user_id);

        $model = new Goals();

        $goal = $model->get_by_id($post['id']);

        if (!empty($goal)) {

            if ($goal->type_id == Config::get('constants.goal_task')) {
                $goal->percent = 0;
                $goal->is_end = 0;
                $goal->is_in_trophy = 0;

                $goal->save();
                $trophy = Trophy::where("item_id", $goal->id)->first();

                if ($trophy) {
                    $trophy->deleted = 1;
                    $trophy->save();
                }
                return $this->success($goal, 'Task Activated Successfully.');

            } else {
                return $this->success($goal, 'Unable Activate Task.', 201);
            }

        } else {
            return $this->error($goal, 'Task Not Found.', 404);
        }

    }

    public function addtomylist(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'goal_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $post = $request->all();
        $goal_id = $request->input('goal_id');

        //$post['oauth_token']->user_id=Auth::user()->guid;
        $model = new Goals();
        $goal = $model->addtomylist($goal_id, $post['oauth_token']->user_id);
        if (!empty($goal)) {
            return $this->success($goal, 'Goal Added to your list.');
        } else {

            return $this->error('Unable to add goal to your list.', 403);

        }
    }

    public function goal_view_type($view_type)
    {

        //$post['oauth_token']->user_id=Auth::user()->guid;

        $model = new Goals();

        $filter = array();

        $filter['start_date'] = isset($post['date']) && !empty($post['date']) ? $post['date'] : date("Y-m-d");

        $filter['user_id'] = $post['oauth_token']->user_id;

        $filter['is_default'] = 0;

        $filter['view_type'] = $view_type;

        $filter['isMobile'] = $this->isMobile;

        $_tasks = $model->get_tasks($filter);

        $tasks['view_type'] = $filter['view_type'];

        $tasks['expanded'] = true;

        $tasks['items'] = $_tasks;

        return view('goals.goals_view_type', ["tasks" => $tasks]);
    }

    public function get_monthly_statistics(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'habit_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        } else {

            $id = $request->input('habit_id');
            $model = Goals::where("id", $id)->first();
            if (!empty($model)) {
                if ($model->is_delete) {
                    return $this->error('Failed.', 403);
                }

            }

            $first_log = $model->get_first_log_date($id);
            /*echo "<pre/>";
            print_r($first_log);die;*/
            $current_month = date("Y-m-d");

            $date1 = ($first_log && $first_log->date) ? $first_log->date : date("Y-m-d", strtotime("-6 months")); // default 6 months...

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
                $total_year_event = 1;
            } else if ($diff > 12 && $diff <= 24) {
                $diff = 24;
                $total_year_event = 3;
            } else if ($diff > 24 && $diff <= 36) {
                $diff = 36;
                $total_year_event = 3;
            } else if ($diff > 36 && $diff <= 48) {
                $diff = 48;
                $total_year_event = 4;
            } else if ($diff > 48 && $diff <= 60) {
                $diff = 60;
                $total_year_event = 5;
            }

            $html = "";

            //$total_year_event=1;
            $data = array();
            for ($i = $diff; $i >= 0; $i--) {

                //$date=$dt->subMonths(1)->format("Y-m-01");

                $date = date("Y-m-01", strtotime(date("Y-m-01") . " -" . $i . " months"));

                $_data = $model->get_monthly_percentage($id, $date);
                array_push($data, $_data);

            }

            return $this->success($data, 'Show Checkbox Graph');
        }
    }

    public function progress_statistics($id)
    {

        $response = array("status" => 0, "msg" => "This record has been deleted.");

        $model = Goals::where("id", $id)->first();

        if ($model->is_delete == '1') {
            return $this->success(null, 'This record has been deleted.');
        }

        $sql = "SELECT * FROM tbl_logs WHERE value = 1 AND goal_id = " . $id;

        $results = DB::table('tbl_logs')->where("value", 1)->where("goal_id", $id)->get()->toArray();
        $notappl = DB::table('tbl_logs')->where("value", 2)->where("goal_id", $id)->get()->toArray();

        if (count($results) == 0) {
            return $this->success("<div>No Record Found</div>", 'No Record Found.', 201);
        }

        $date1 = $results[0]->date;
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
        for ($i = 0; $i < count($notappl); $i++) {
            $mon = date("Y-m", strtotime($notappl[$i]->date));
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
                $html .= '<span style="' . $isdisply . '" class="yrgraph_' . $i . '"><div class="progress progress-bar-vertical " >
								<div class="progress-bar ' . $prog_class . '"  role="progressbar" aria-valuenow="' . $v['percent'] . '" aria-valuemin="0" aria-valuemax="100" style="height:  ' . $v['percent'] . '%;">
									<span class="sr-only">' . $v['percent'] . '%</span>
								</div>

							</div><span class="yr-name">' . $tt[1] . '</span><span class="yr-year">' . $tt[0] . '</span></span>';
                //$div_index = $div_index + 1;
            }
        }
        $html .= "<script>var total_year_event = $total_year_event</script>";
        return $this->success($html, 'Data Found.');

        exit;
    }

    public function generate_autosaveid()
    {

        $model = new Goals();
        $id = $model->generate_autosaveid();
        return $this->success($id, 'Generate auto save id');
    }

    public function autosaveid()
    {

        $model = new Goals();
        $id = $model->generate_autosaveid();

        //return response()->json(['data'=>$id,'status'=>1,'msg'=>"Generate auto save id",'error'=>""]);

        return $id;
    }

    public function default_import()
    {

        //$post['oauth_token']->user_id=Auth::user()->guid;

        $model = new Goals();

        $_model = new Def_goals();

        $goals = $_model->get_parent_goals();

        if ($goals) {

            foreach ($goals as $key => $goal) {

                $_goal = $_model->_get_goal($goal['id']);

                if ($_goal) {

                    /*if('FAMILY - SPOUSE & KIDS - (COMPLETED)'==$_goal['name']){

                    //echo "<pre/>";

                    //print_r($_goal);
                    //exit();
                    }*/

                    $children = isset($_goal['children']) ? $_goal['children'] : array();

                    unset($_goal['id']);
                    unset($_goal['children']);
                    unset($_goal['type']);
                    unset($_goal['habit_types']);

                    $_goal['user_id'] = $post['oauth_token']->user_id;
                    $_goal['auto_save_id'] = time() + rand(1111111111, 999999999999999);
                    //ss$_goal['is_default']=0;
                    $_goal['list_order'] = $model->get_max_list_order($post['oauth_token']->user_id) + 1; // default max number
                    $_goal['self_order'] = $model->get_max_self_order($post['oauth_token']->user_id) + 1; // default max number
                    $_goal['created_at'] = date("Y-m-d H:i:s");
                    $_goal['updated_at'] = date("Y-m-d H:i:s");
                    $_goal['parent_id'] = 0;
                    $_goal['top_parent_id'] = 0;

                    $new_goal = Goals::create($_goal);

                    if ($children) {
                        $parent_id = $new_goal->id;
                        $top_parent_id = $new_goal->id;
                        $this->addchildtomylist($children, $post['oauth_token']->user_id, $parent_id, $top_parent_id);
                    } else {
                        echo "<br/>";
                        echo "no children:" . $_goal['name'];
                        echo "<br/>";
                    }

                }
            }

            //sreturn $goals;
        } else {
            echo "No goals...";
        }

    }

    public function addchildtomylist($goals, $parent_id, $top_parent_id)
    {

        $model = new Goals();

        if ($goals) {

            foreach ($goals as $key => $goal) {

                $children = isset($goal['children']) ? $goal['children'] : array();

                unset($goal['id']);
                unset($goal['children']);
                unset($goal['type']);
                unset($goal['habit_types']);

                $goal['user_id'] = $post['oauth_token']->user_id;

                $goal['auto_save_id'] = time() + rand(1111111, 999999999999);

                $goal['parent_id'] = $parent_id;

                $goal['top_parent_id'] = $top_parent_id;

                $goal['list_order'] = $model->get_max_list_order($post['oauth_token']->user_id) + 1; // default max number
                $goal['self_order'] = $model->get_max_self_order($post['oauth_token']->user_id) + 1; // default max number
                $goal['created_at'] = date("Y-m-d H:i:s");
                $goal['updated_at'] = date("Y-m-d H:i:s");

                $new_goal = Goals::create($goal);
                /*echo "<br/>";
                echo "newly inserted:".$new_goal->id;
                echo "<br/>";*/
                if ($children) {

                    $this->addchildtomylist($children, $post['oauth_token']->user_id, $new_goal->id, $top_parent_id);
                } else {
                    echo "<br/>";
                    echo "no children:" . $goal['name'];
                    echo "<br/>";
                }

            }

            //return $goals;
        }
    }

    public function upadate_task_date(Request $request)
    {
        //echo "Hiiii";die;
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $post = $request->all();

        if (!empty($userDetails)) {

            $_task = Goals::where("id", $post['id'])->where("user_id", $post['oauth_token']->user_id)->where("type_id", 2)->first();
            //echo "<pre/>";
            //print_r($_task);die;

            if ($_task) {
                $post['date'] = date("Y-m-d", strtotime($post['date']));
                //print_r($post);
                $_task->due_date = $post['date'];
                $_task->save();

                $expired_class = (date("Y-m-d") > $_task->due_date) ? true : false;
                $data['task'] = $_task;
                $data['expired'] = $expired_class;
                $data['date'] = date("d M, Y", strtotime($_task->due_date));

                return $this->success($data, 'Task updated Successfully.');
            } else {
                return $this->error('Invalid Task.', 419);
            }
        } else {
            return $this->error('Invalid User', 419);
        }
    }

    public function upadate_habit_date(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }
        $post = $request->all();

        if (!empty($userDetails)) {

            $_habit = Goals::where("id", $post['id'])->where("user_id", $post['oauth_token']->user_id)->first();

            if ($_habit) {

                $post['date'] = $post['date'];
                $_habit->habit_start_date = date("Y-m-d", strtotime($post['date']));
                $_habit->save();

                $data['date'] = $_habit->habit_start_date;

                return $this->success($_habit, 'Habit updated Successfully.');
            } else {
                return $this->error('Invalid Habit.', 403);
            }
        } else {
            return $this->error('Invalid User', 419);
        }
    }

    public function save_for_status_attributes(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'auto_save_id' => 'required',
            'attr' => 'required',
            'sheet_id' => 'required',
            'sheet_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }
        if ($post = $request->input()) {

            //$post['oauth_token']->user_id=Auth::user()->guid;

            $_habit = Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id", $post['oauth_token']->user_id)->first();

            if ($_habit) {

                $post['meta_key'] = $post['attr'] . "-" . $post['sheet_id'];

                GoalsMeta::deactivate_sheets($_habit->id, $post['attr']);

                $meta_key_value = "addtomylist_id";

                $meta = GoalsMeta::get($_habit->id, $meta_key_value);

                if ($post['attr'] == "status" && isset($meta) && !empty($meta)) {

                    $html = (isset($meta->meta_value) && !empty($meta->meta_value)) ? clean_html($meta->meta_value) : "";
                } else {
                    $html = clean_html($post['html']);
                }

                $post['html'] = $html;

                $post['meta_attr'] = json_encode($post);

                $post['meta_value'] = $html;

                $post['goal_id'] = $_habit['id'];

                $post['is_active'] = 1;
                unset($request['oauth_token']);

                $data = GoalsMeta::add($post);

                $sheet_data = json_decode($data->meta_attr, true);

                unset($request['oauth_token']);
                $sheet_data['id'] = $data->id;
                $sheet_data['goal_id'] = $data->goal_id;
                $sheet_data['is_active'] = $data->is_active;

                return $this->success($sheet_data, 'Sheet Successfully Added');

            } else {
                return $this->error('Invalid Habit.', 419);
            }

        } else {
            return $this->error('Invalid Request', 419);
        }

    }

    public function save_attributes(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'auto_save_id' => 'required',
            'attr' => 'required',
            'sheet_id' => 'required',
            'sheet_name' => 'required',
        ]);

        //return $this->error($request->all(), 403);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }
        if ($post = $request->all()) {
            $_habit = Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id", $post['oauth_token']->user_id)->first();

            if ($_habit) {
                //    $post=array();

                $post['meta_key'] = $post['attr'] . "-" . $post['sheet_id'];

                GoalsMeta::deactivate_sheets($_habit->id, $post['attr']);

                if (!isset($post['html'])) {
                    $post['html'] = '';
                }

                $html = clean_html($post['html']);

                $post['html'] = $html;

                $post['meta_attr'] = json_encode($post);

                $post['meta_value'] = $html;

                $post['goal_id'] = $_habit['id'];

                $post['is_active'] = 1;

                unset($request['oauth_token']);

                $data = GoalsMeta::add($post);

                $sheet_data = json_decode($data->meta_attr, true);

                $sheet_data['id'] = $data->id;
                $sheet_data['goal_id'] = $data->goal_id;
                $sheet_data['is_active'] = $data->is_active;

                return $this->success($sheet_data, 'Sheet Successfully Added');

            } else {
                return $this->error('Invalid Habit.', 419);
            }
        } else {
            return $this->error('Invalid Request.', 419);
        }

    }

    public function attribute_delete_sheet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'auto_save_id' => 'required',
            'attr' => 'required',
            'sheet_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        if ($post = $request->input()) {

            //$post['oauth_token']->user_id=Auth::user()->guid;

            $_habit = Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id", $post['oauth_token']->user_id)->first();

            if ($_habit) {

                $meta_key = $post['attr'] . "-" . $post['sheet_id'];

                $meta = GoalsMeta::delete_meta($_habit->id, $meta_key);

                return $this->success(null, 'Successfully Deleted');
            } else {
                return $this->error('Invalid Habit.', 419);
            }
        } else {

            return $this->error('Invalid Habit.', 419);

        }

    }

    public function attribute_get_sheet(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'sheet_id' => 'required',
            'sheet_number' => 'required',
            'sheet_name' => 'required',
            'attr' => 'required',
            'auto_save_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        if ($post = $request->input()) {
            $_habit = Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id", $post['oauth_token']->user_id)->first();

            if ($_habit) {

                $meta_key = $post['attr'] . "-" . $post['sheet_id'];

                GoalsMeta::deactivate_sheets($_habit->id, $post['attr']);

                $meta = GoalsMeta::get($_habit->id, $meta_key);

                $meta->is_active = 1;

                unset($request['oauth_token']);
                $data = $meta->save();

                $sheet_data = json_decode($meta->meta_attr, true);
                $sheet_data['id'] = $meta->id;
                $sheet_data['goal_id'] = $meta->goal_id;
                $sheet_data['is_active'] = $meta->is_active;

                return $this->success($sheet_data, 'Get Sheet Successfully');

            } else {
                return $this->error('Invalid Habit.', 419);
            }
        } else {
            return $this->error('Invalid Habit.', 419);
        }

    }

    public function attribute_duplicate_sheet(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'auto_save_id' => 'required',
            'attr' => 'required',
            'sheet_id' => 'required',
            'sheet_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        if ($post = $request->input()) {

            //$post['oauth_token']->user_id=Auth::user()->guid;

            $_habit = Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id", $post['oauth_token']->user_id)->first();

            if ($_habit) {

                $meta_key = $post['attr'] . "-" . $post['sheet_id'];

                $meta = GoalsMeta::get($_habit->id, $meta_key);

                unset($request['oauth_token']);
                $_meta = $meta->replicate();

                $meta_attr = json_decode($_meta->meta_attr);

                #print_r($meta_attr);

                $sheetNumber = isset($post['sheet_number']) ? $post['sheet_number'] : rand(111, 999);

                $new_sheet_id = rand(1111111111, 9999999999);

                $meta_attr->sheet_id = $new_sheet_id;

                $meta_attr->sheet_number = $sheetNumber;

                $meta_attr->is_active = 0;

                $_meta->meta_key = $meta_attr->attr . "-" . $meta_attr->sheet_id;

                $_meta->meta_attr = json_encode($meta_attr);
                $_meta->is_active = 0;

                $data = $_meta->save();

                $sheet_data = json_decode($_meta->meta_attr, true);

                unset($request['oauth_token']);
                $sheet_data['id'] = $_meta->id;
                $sheet_data['goal_id'] = $_meta->goal_id;
                $sheet_data['is_active'] = $_meta->is_active;

                return $this->success($sheet_data, 'Sheet Duplicated Successfully');
            } else {
                return $this->error('Invalid Habit.', 419);
            }
        } else {
            return $this->error('Invalid request.', 419);
        }

    }

    public function attribute_rename_sheet(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'auto_save_id' => 'required',
            'attr' => 'required',
            'sheet_id' => 'required',
            'sheet_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }
        if ($post = $request->input()) {
            $_habit = Goals::where("auto_save_id", $post['auto_save_id'])->where("user_id", $post['oauth_token']->user_id)->first();

            if ($_habit) {

                $meta_key = $post['attr'] . "-" . $post['sheet_id'];

                $meta = GoalsMeta::get($_habit->id, $meta_key);
                $meta_attr = json_decode($meta->meta_attr);

                $meta_attr->sheet_name = $post['sheet_name'];

                $meta->meta_attr = json_encode($meta_attr);
                unset($request['oauth_token']);

                $data = $meta->save();

                $sheet_data = json_decode($meta->meta_attr, true);

                $sheet_data['id'] = $meta->id;
                $sheet_data['goal_id'] = $meta->goal_id;
                $sheet_data['is_active'] = $meta->is_active;

                return $this->success($sheet_data, 'Sheet Successfully Renamed');

            } else {
                return $this->error('Invalid Habit.', 419);
            }
        } else {
            return $this->error('Invalid request.', 419);

        }
    }

    public function save_task_template(Request $request)
    {

        $errors = array();

        /*$data = $request->all();
        echo "<pre/>";
        print_r($data);die;*/
        if ($post = $request->input()) {

            $_habit = Goals::where("auto_save_id", $post['task_id'])->where("user_id", $post['oauth_token']->user_id)->first();

            if ($_habit) {

                $data['task_id'] = $_habit->id;
                $data['repeat_qty'] = !empty($post['repeat_qty']) ? $post['repeat_qty'] : 1;
                $data['repeat_frequency'] = !empty($post['repeat_frequency']) ? $post['repeat_frequency'] : "weeks";

                if ($data['repeat_frequency'] == 'weeks') {
                    //echo "<pre/>";
                    //print_r($post['week_days']);die;
                    if (isset($post['week_days']) && empty($post['week_days'])) {
                        $errors[] = "Please select a day.";
                    } else if (!isset($post['week_days'])) {
                        $errors[] = "Please select a day.";
                    }

                    if (isset($post['week_days']) && !empty($post['week_days'])) {
                        $data['repeat_on'] = implode(",", $post['week_days']);
                        $data['repeat_on_date'] = null;
                    }
                } else {
                    if ($post['repeat_on'] == 'thisday') {
                        $start = date("d"); //new Carbon('last day of this month');
                    } else if ($post['repeat_on'] == 'firstday') {
                        $start = Carbon::parse(new Carbon('first day of this month'))->day;
                    } else {
                        $start = Carbon::parse(new Carbon('last day of this month'))->day;
                    }

                    /*if(empty($post['repeat_on'])){
                    $errors[]="Please select a day.";
                    }*/

                    $data['repeat_on'] = $post['repeat_on'];
                    $data['repeat_on_date'] = $start;
                }

                $data['task_name'] = !empty($post['task_name']) ? $post['task_name'] : "";
                $data['template_name'] = !empty($post['template_name']) ? $post['template_name'] : "";
                $data['add_suffix'] = !empty($post['add_suffix']) ? $post['add_suffix'] : 0;
                $data['ends_on'] = !empty($post['ends_on']) ? $post['ends_on'] : "never";
                $data['begin_on'] = !empty($post['begin_on']) ? $post['begin_on'] : "now";

                if (empty($post['task_name'])) {
                    $errors[] = "Please enter Task name.";
                }

                if (empty($post['repeat_qty'])) {
                    $errors[] = "Please enter Repeat qty.";
                }

                if ($data['ends_on'] == 'date') {

                    if (empty($post['end_on_value'])) {
                        $errors[] = "Please enter ends on date.";
                    }

                    $ends_on_date = !empty($post['end_on_value']) ? $post['end_on_value'] : $_habit->due_date;
                    $data['end_on_value'] = date("Y-m-d", strtotime($ends_on_date));
                } else if ($data['ends_on'] == 'occurrences') {
                    if (empty($post['occurrences'])) {
                        $errors[] = "Please enter no of occurrences.";
                    }

                    $data['end_on_value'] = !empty($post['occurrences']) ? $post['occurrences'] : "";
                } else {
                    $data['end_on_value'] = -1;
                }

                if ($data['begin_on'] == 'date') {

                    if (empty($post['begin_on_value'])) {
                        $errors[] = "Please enter begin on date.";
                    }

                    $begin_on_date = !empty($post['begin_on_value']) ? $post['begin_on_value'] : '';
                    $data['begin_on_value'] = date("Y-m-d", strtotime($begin_on_date));
                }

                $data['is_repeat_done'] = 0;

                //var_dump($errors);

                if (!empty($errors)) {

                    return $this->error($errors, 403);
                }

                //$_habit->name=$data['task_name'];

                //$_habit->save();

                //Goals::where("id", $data['task_id'])->update(['name'=>$data['task_name']]);
                //print_r($data);

                $task_template = TaskTemplate::updateOrCreate(['task_id' => $data['task_id']], $data);

                TaskTemplate::delete_task_by_template_id($task_template->id);

                $_goal = TaskTemplate::process_by_id($task_template->id);

                $post['task'] = $_goal;

                $_habit = Goals::where("auto_save_id", $post['task_id'])->where("user_id", $post['oauth_token']->user_id)->first();

                ///$_habit['child'] = Goals::where("parent_id", $_habit->id)->where("user_id",$post['oauth_token']->user_id)->get()->toArray();

                $goal_data = Goals::where("parent_id", $_habit->id)->where("user_id", $post['oauth_token']->user_id)->where('is_delete', 0)->get();

                if (!empty($goal_data)) {
                    foreach ($goal_data as $key => $value) {

                        $goal_data[$key]['childs'] = array();
                        $goal_data[$key]['habit_type'] = 1;
                        $goal_data[$key]['value'] = 7;
                        $goal_data[$key]['lowest'] = "";
                        $goal_data[$key]['highest'] = "";
                        $goal_data[$key]['is_apply'] = 0;
                        $goal_data[$key]['scale_type'] = 0;
                        $goal_data[$key]['add_text_type'] = "";
                    }
                    $_habit['has_sub'] = 1;
                    $_habit['habit_type'] = 1;
                    $_habit['value'] = 7;
                    $_habit['lowest'] = "";
                    $_habit['highest'] = "";
                    $_habit['is_apply'] = 0;
                    $_habit['scale_type'] = 0;
                    $_habit['add_text_type'] = "";
                    $_habit['childs'] = $goal_data;
                } else {
                    $_habit['childs'] = array();
                    $_habit['habit_type'] = 1;
                    $_habit['value'] = 7;
                    $_habit['lowest'] = "";
                    $_habit['highest'] = "";
                    $_habit['is_apply'] = 0;
                    $_habit['scale_type'] = 0;
                    $_habit['add_text_type'] = "";
                    $_habit['has_sub'] = 0;
                }

                return $this->success($_habit, 'Task Added Successfully.111');
            } else {
                return $this->error('Invalid request.', 419);
            }
        }
    }

    public function get_task_template(Request $request)
    {

        if ($post = $request->input()) {

            $post['oauth_token']->user_id = Auth::user()->guid;

            $_habit = Goals::where("auto_save_id", $post['task_id'])->where("user_id", $post['oauth_token']->user_id)->first();

            if ($post['task_id'] && $_habit) {

                $task_id = $_habit->id; //['task_id'];

                if ($template = TaskTemplate::where("task_id", $task_id)->first()) {

                    if ($template->ends_on == 'date' && $template->end_on_value) {
                        $template->end_on_value = Carbon::parse($template->end_on_value)->format('m/d/Y');
                    }

                    if ($template->begin_on == 'date' && $template->begin_on_value) {
                        $template->begin_on_value = Carbon::parse($template->begin_on_value)->format('m/d/Y');
                    }
                    return $this->success($template, 'Template Data.');
                } else {
                    return $this->error('Invalid Template.', 419);
                }
            } else {
                return $this->error('Invalid Parameter.', 419);
            }
        } else {
            return $this->error('Invalid Request.', 419);
        }
    }

    public function addLobby(Request $request)
    {
        $post = $request->all();
        $validator = Validator::make($request->all(), [
            'lobby_undefined' => 'required',
            'sub_habit_start_date' => 'required',
            'sub_goals' => 'required',
        ]);

        $type = $request->input('type');
        if ($type == 1) {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
            ]);
        } else if ($type == 2) {

            if (isset($post['task_name']) && !empty($post['task_name'])) {

                $validator = Validator::make($request->all(), [
                    'repeat_qty' => 'required',
                    'task_name' => 'required',
                ]);

                if (!empty($post['begin_on']) && $post['begin_on'] == "date") {
                    $validator = Validator::make($request->all(), [
                        'begin_on_date' => 'required',
                    ]);
                }

                if (!empty($post['ends_on']) && $post['ends_on'] == "date") {
                    $validator = Validator::make($request->all(), [
                        'ends_on_date' => 'required',
                    ]);
                }

                if (!empty($post['begin_on_date']) && $post['ends_on'] == "occurrences") {
                    $validator = Validator::make($request->all(), [
                        'occurrences' => 'required',
                    ]);
                }
            }
        }

        $is_apply = $request->input('is_apply');
        $scale = isset($post['scale']) ? $post['scale'] : 0;
        if ($type == 1 && isset($is_apply) && $is_apply == 0 && $scale == 1) {
            $validator = Validator::make($request->all(), [
                'lowest' => 'required',
                'highest' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        } else {

            $lobbyGoal = new Goals();
            $goalLobby = $lobbyGoal->addLobby($post, $post['oauth_token']->user_id);

            $post['goal_id'] = $goalLobby->id;
            $post['task_id'] = $goalLobby->auto_save_id;
            if ($post['type'] == 1) {
                if (isset($post['habitSchedule']) && !empty($post['habitSchedule'])) {
                    $goalLobby['habitSchedule'] = $this->addHabitSchedule($post);
                } else {
                    $post['scale'] = 0;
                    $post['is_apply'] = 0;
                    $post['highest'] = 0;
                    $post['habitSchedule'] = 1;
                    $post['add_text_type'] = "";
                    $goalLobby['habitSchedule'] = $this->addHabitSchedule($post);
                }
            }
            if ($post['type'] == 2) {

                if (isset($post['task_name']) && !empty($post['task_name'])) {

                    $goalLobby['TaskSchedule'] = $this->saveTaskTemplate($post);
                }
            }

            /*     if($post['type'] == 1)
            {
            $msg = "Goal Added Successfully.";
            return response()->json(['data'=>$goalLobby,'status'=>1,'msg'=>$msg,'error'=>""]);
            }
            else if($post['type'] == 2)
            {
            $msg = "Goal Added Successfully.";
            return response()->json(['data'=>$goalLobby,'status'=>1,'msg'=>$msg,'error'=>""]);
            }else if($post['type'] == 0){
            $msg = "Goal Added Successfully.";
            return response()->json(['data'=>$goalLobby,'status'=>1,'msg'=>$msg,'error'=>""]);
            }
            else
            {
            $msg = "Goal Added Successfully.";
            return response()->json(['data'=>$goalLobby,'status'=>1,'msg'=>$msg,'error'=>""]);
            } */
            return $this->success($goalLobby, "Goal Added Successfully.");
        }

    }

    public function saveTaskTemplate($post)
    {

        $errors = array();

        /*$data = $request->all();
        echo "<pre/>";
        print_r($data);die;*/
        if ($post) {

            $_habit = Goals::where("auto_save_id", $post['task_id'])->where("user_id", $post['oauth_token']->user_id)->first();

            if ($_habit) {

                $data['task_id'] = $_habit->id;
                $data['repeat_qty'] = !empty($post['repeat_qty']) ? $post['repeat_qty'] : 1;
                $data['repeat_frequency'] = !empty($post['repeat_frequency']) ? $post['repeat_frequency'] : "weeks";

                if ($data['repeat_frequency'] == 'weeks') {
                    //echo "<pre/>";
                    //print_r($post['week_days']);die;
                    if (isset($post['week_days']) && empty($post['week_days'])) {
                        $errors[] = "Please select a day.";
                    } else if (!isset($post['week_days'])) {
                        $errors[] = "Please select a day.";
                    }

                    if (isset($post['week_days']) && !empty($post['week_days'])) {
                        $data['repeat_on'] = implode(",", $post['week_days']);
                        $data['repeat_on_date'] = null;
                    }
                } else {
                    if ($post['repeat_on'] == 'thisday') {
                        $start = date("d"); //new Carbon('last day of this month');
                    } else if ($post['repeat_on'] == 'firstday') {
                        $start = Carbon::parse(new Carbon('first day of this month'))->day;
                    } else {
                        $start = Carbon::parse(new Carbon('last day of this month'))->day;
                    }

                    /*if(empty($post['repeat_on'])){
                    $errors[]="Please select a day.";
                    }*/

                    $data['repeat_on'] = $post['repeat_on'];
                    $data['repeat_on_date'] = $start;
                }

                $data['task_name'] = !empty($post['task_name']) ? $post['task_name'] : "";
                $data['template_name'] = !empty($post['template_name']) ? $post['template_name'] : "";
                $data['add_suffix'] = !empty($post['add_suffix']) ? $post['add_suffix'] : 0;
                $data['ends_on'] = !empty($post['ends_on']) ? $post['ends_on'] : "never";
                $data['begin_on'] = !empty($post['begin_on']) ? $post['begin_on'] : "now";

                if (empty($post['task_name'])) {
                    $errors[] = "Please enter Task name.";
                }

                if (empty($post['repeat_qty'])) {
                    $errors[] = "Please enter Repeat qty.";
                }

                if ($data['ends_on'] == 'date') {

                    if (empty($post['ends_on_date'])) {
                        $errors[] = "Please enter ends on date.";
                    }

                    $ends_on_date = !empty($post['ends_on_date']) ? $post['ends_on_date'] : $_habit->due_date;
                    $data['end_on_value'] = date("Y-m-d", strtotime($ends_on_date));
                } else if ($data['ends_on'] == 'occurrences') {
                    if (empty($post['occurrences'])) {
                        $errors[] = "Please enter no of occurrences.";
                    }

                    $data['end_on_value'] = !empty($post['occurrences']) ? $post['occurrences'] : "";
                } else {
                    $data['end_on_value'] = -1;
                }

                if ($data['begin_on'] == 'date') {

                    if (empty($post['begin_on_date'])) {
                        $errors[] = "Please enter begin on date.";
                    }

                    $begin_on_date = !empty($post['begin_on_date']) ? $post['begin_on_date'] : '';
                    $data['begin_on_value'] = date("Y-m-d", strtotime($begin_on_date));
                }

                $data['is_repeat_done'] = 0;

                //var_dump($errors);

                if (!empty($errors)) {
                    return $this->error($errors, 403);
                }
                $task_template = TaskTemplate::updateOrCreate(['task_id' => $data['task_id']], $data);

                TaskTemplate::delete_task_by_template_id($task_template->id);

                $_goal = TaskTemplate::process_by_id($task_template->id);
                $post['task'] = $_goal;
            }
            return $task_template;
        }
    }

    public function validateTaskTemplate(Request $request)
    {

        $errors = array();

        $post = $request->all();

        $validator = Validator::make($request->all(), [
            'repeat_qty' => 'required',
            'repeat_frequency' => 'required',
            'task_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        if ($post['ends_on'] == "date") {

            $validator = Validator::make($request->all(), [
                'ends_on_date' => 'required',
            ]);

        } else if ($post['ends_on'] == "occurrences") {
            $validator = Validator::make($request->all(), [
                'occurrences' => 'required',
            ]);
        } else if ($post['begin_on'] == "date") {
            $validator = Validator::make($request->all(), [
                'begin_on_date' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        if ($post = $request->input()) {

            $data['repeat_qty'] = !empty($post['repeat_qty']) ? $post['repeat_qty'] : 1;
            $data['repeat_frequency'] = !empty($post['repeat_frequency']) ? $post['repeat_frequency'] : "weeks";

            if ($data['repeat_frequency'] == 'weeks') {
                /*echo "<pre/>";
                print_r($post['week_days']);die;*/
                if (isset($post['week_days']) && empty($post['week_days'])) {
                    $errors = "Please select a day.";

                } else if (!isset($post['week_days'])) {

                    $errors = "Please select a day.";
                }

                if (!empty($errors)) {
                    return $this->error($errors, 403);
                }

                if (isset($post['week_days']) && !empty($post['week_days'])) {
                    $data['repeat_on'] = implode(",", $post['week_days']);
                    $data['repeat_on_date'] = null;

                }
            } else {
                if ($post['repeat_on'] == 'thisday') {
                    $start = date("d"); //new Carbon('last day of this month');
                } else if ($post['repeat_on'] == 'firstday') {
                    $start = Carbon::parse(new Carbon('first day of this month'))->day;
                } else {
                    $start = Carbon::parse(new Carbon('last day of this month'))->day;
                }
                $data['repeat_on'] = $post['repeat_on'];
                $data['repeat_on_date'] = $start;
            }

            $data['task_name'] = !empty($post['task_name']) ? $post['task_name'] : "";
            $data['template_name'] = !empty($post['template_name']) ? $post['template_name'] : "";
            $data['add_suffix'] = !empty($post['add_suffix']) ? $post['add_suffix'] : 0;
            $data['ends_on'] = !empty($post['ends_on']) ? $post['ends_on'] : "never";
            $data['begin_on'] = !empty($post['begin_on']) ? $post['begin_on'] : "now";

            if ($data['ends_on'] == 'date') {

                $ends_on_date = !empty($post['end_on_value']) ? $post['end_on_value'] : "";
                $data['end_on_value'] = date("Y-m-d", strtotime($ends_on_date));
            } else if ($data['ends_on'] == 'occurrences') {
                $data['end_on_value'] = !empty($post['occurrences']) ? $post['occurrences'] : "";
            } else {
                $data['end_on_value'] = -1;
            }

            if ($data['begin_on'] == 'date') {

                $begin_on_date = !empty($post['begin_on_date']) ? $post['begin_on_date'] : '';
                $data['begin_on_value'] = date("Y-m-d", strtotime($begin_on_date));
            }

            $data['is_repeat_done'] = 0;

            $post = $data;
            $taskData = $this->getTaskEndDate($data);
            $post['new_due_date'] = (isset($taskData) && !empty($taskData)) ? $taskData['due_date'] : "";

            $response = array(
                'status' => 1,
                'data' => $post,
                'msg' => 'Task Added.',
            );

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
        if ($post['type'] == 1) {
            $data['count_per_week'] = 7;
            $data['value'] = 7;
        } else {
            $data['value'] = $post['day'];
            $data['count_per_week'] = $count_per_week;
        }
        $data['goal_id'] = $post['goal_id'];
        $data['type'] = $post['type'];
        $data['text'] = $post['text'];
        //echo "<pre/>";
        //print_r($data);die;
        $habitDetails = $habitTypes->add_habit_types($data);
        if (!empty($habitDetails)) {
            $response = array(
                'status' => 1,
                'data' => $post,
                'msg' => 'Habit Added.',
            );

        } else {

            $response = array(
                'status' => 0,
                'data' => array(),
                'msg' => 'Invalid Request.',
            );
        }

        return \Response::json($response);
        //return \Response::json($habitDetails);

    }

    public function addHabitSchedule($post)
    {

        //$count_per_week = 0;
        $habitTypes = new HabitTypes();

        if ($post['scale'] == 0) {
            $data['is_scale'] = $post['scale'];
        } else {
            if (isset($post['is_apply']) && !empty($post['is_apply'])) {
                $data['is_apply'] = $post['is_apply'];
            }
            $data['is_scale'] = $post['scale'];
            $data['maximum'] = (isset($post['highest']) && !empty($post['highest'])) ? $post['highest'] : "";
            $data['minimum'] = (isset($post['lowest']) && !empty($post['lowest'])) ? $post['lowest'] : "";
        }

        if ($post['habitSchedule'] == 1) {
            $data['count_per_week'] = 7;
            $data['value'] = 7;
        } else {if (isset($post['days']) && !empty($post['days'])) {
            $day = implode(",", $post['days']);
            $count_per_week = count($post['days']);
            $data['value'] = $day;
            $data['count_per_week'] = $count_per_week;
        } else {
            $data['value'] = "";
            $data['count_per_week'] = 0;
        }
        }
        $data['goal_id'] = $post['goal_id'];
        $data['type'] = $post['habitSchedule'];
        $data['text'] = $post['add_text_type'];
        //echo "<pre/>";
        //print_r($data);die;
        $habitDetails = $habitTypes->add_habit_types($data);

        return $habitDetails;
        //return \Response::json($habitDetails);

    }

    public function saveHabitSchedule(Request $request)
    {

        //$count_per_week = 0;
        $validator = Validator::make($request->all(), [
            'scale' => 'required',
            'goal_id' => 'required',
            'token' => 'required',
        ]);
        $post = $request->all();
        /*echo "<pre/>";
        print_r($post);die;*/
        if (isset($post['scale']) && !empty($post['scale']) && $post['scale'] == 1) {
            if (isset($post['is_apply']) && $post['scale'] == 0) {
                $validator = Validator::make($request->all(), [
                    'highest' => 'required',
                    'lowest' => 'required',
                ]);
            }
        }

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $habitTypes = new HabitTypes();

        if ($post['scale'] == 0) {
            $data['is_scale'] = $post['scale'];
            $data['maximum'] = 0;
            $data['minimum'] = 0;
        } else {
            if (isset($post['is_apply']) && $post['is_apply'] == 1) {
                $data['is_apply'] = $post['is_apply'];
                $data['is_scale'] = $post['scale'];
                $data['maximum'] = 0;
                $data['minimum'] = 0;
            } else {
                $data['is_apply'] = $post['is_apply'];
                $data['is_scale'] = $post['scale'];
                $data['maximum'] = $post['highest'];
                $data['minimum'] = $post['lowest'];
            }

        }

        if ($post['type'] == 1) {
            $data['count_per_week'] = 7;
            $data['value'] = 7;
        } else {if (isset($post['days']) && !empty($post['days'])) {
            $day = implode(",", $post['days']);
            $count_per_week = count($post['days']);
            $data['value'] = $day;
            $data['count_per_week'] = $count_per_week;
        } else {
            $data['value'] = 0;
            $data['count_per_week'] = 0;
        }
        }
        $data['goal_id'] = $post['goal_id'];
        $data['type'] = $post['type'];
        $data['text'] = $post['add_text_type'];

        $habitDetails = $habitTypes->add_habit_types($data);
        /*echo "<pre/>";
        print_r($habitDetails);die;*/
        return response()->json(['data' => $habitDetails, 'status' => 0, 'msg' => "Habit Schedule Added Successfully", 'error' => ""]);

    }

    public function getHabbitLop($id)
    {
        $habitTypes = new HabitTypes();
        $habitDetails = $habitTypes->getHabitLoop($id);
        //return json_encode($habitDetails);
        return \Response::json($habitDetails);
    }

/*----------------GET TASK TEMPLATE APIs HERE----------------------------*/

    public function getTaskTemplate(Request $request)
    {

        $post = $request->all();

        $id = $post['id'];

        $task = new TaskTemplate();

        $taskDetails = $task->getTaskTemplate($id);

        if (!empty($taskDetails)) {
            //echo "success";die;
            $response = array(
                'status' => 1,
                'data' => $taskDetails,
                'msg' => 'Your Request Successfully Submitted.',
            );
        } else {
            //echo "error";die;
            $response = array(
                'status' => 0,
                'data' => "",
                'msg' => 'Invalid Request.',
            );
        }

        return \Response::json($response);

    }

/*----------------END GET TASK TEMPLATE APIs HERE----------------------------*/

    public function showAndHideInLobby(Request $request)
    {

        $post = $request->all();
        /*echo "<Pre/>";
        print_r($post);die;*/

        $goal = new Goals();
        $goalDetails = $goal->showAndHideInLobby($post['oauth_token']->user_id, $post);

        if ($goalDetails) {
            //echo "success";die;
            $response = array(
                'status' => 1,
                'data' => $goalDetails,
                'msg' => 'Your Request Successfully Submitted.',
            );
        } else {
            //echo "error";die;
            $response = array(
                'status' => 0,
                'data' => "",
                'msg' => 'Invalid Request.',
            );
        }

        return \Response::json($response);

    }

    public function activeAndInactiveGoal(Request $request)
    {

        $post = $request->all();

        $goal = new Goals();
        $goalDetails = $goal->activeAndInactiveGoal($post['oauth_token']->user_id, $post);

        if ($goalDetails) {
            //echo "success";die;
            $response = array(
                'status' => 1,
                'data' => $goalDetails,
                'msg' => 'Your Request Successfully Submitted.',
            );
        } else {
            //echo "error";die;
            $response = array(
                'status' => 0,
                'data' => "",
                'msg' => 'Invalid Request.',
            );
        }

        return \Response::json($response);

    }

    /*---------------------GET TASK END DATE AFTER TASK SETTING---------------------*/

    public function getTaskEndDate($data)
    {

        $post = $data;

        $current_date = Carbon::now();

        $total_repeats = 0;

        $deadlines = array();

        $repeat_qty = isset($post['repeat_qty']) ? $post['repeat_qty'] : 1;
        $repeat_on = isset($post['repeat_on']) ? $post['repeat_on'] : "";
        $repeat_frequency = isset($post['repeat_frequency']) ? $post['repeat_frequency'] : "month";

        $id = isset($post['task_id']) ? $post['task_id'] : "";

        $add_suffix = isset($post['add_suffix']) ? $post['add_suffix'] : "";

        $end_on = isset($post['ends_on']) ? $post['ends_on'] : "";
        $begin_on = isset($post['begin_on']) ? $post['begin_on'] : "";

        $end = isset($post['end_on_value']) ? $post['end_on_value'] : "";

        $begin_on_value = isset($post['begin_on_value']) ? $post['begin_on_value'] : "";

        $task_name = isset($post['task_name']) ? $post['task_name'] : "";

        //$task_name=$task->task_name;

        //$model=new Goals();
        //$_goal=$model->where("id", $task->task_id)->first();

        $repeat_tasks = array();

        if ($repeat_frequency == 'weeks') {
            Carbon::setWeekStartsAt(Carbon::SUNDAY);
            if ($end_on == 'date') {
                $current_date = ($begin_on == 'now') ? Carbon::now() : Carbon::parse($begin_on_value);
                $end = Carbon::parse($end);
                $total_repeats = $end->diffInWeeks($current_date) + 2; // 2 weeks addition to lapsing days in the first and last week
            } else {
                $total_repeats = $end;
            }
            //echo "<pre/>";
            //echo "Weeks";
            //print_r($total_repeats);die;

            $week_counter = 1;
            for ($i = 0; $i < $total_repeats; $i++) {

                $current_date = ($begin_on == 'now') ? Carbon::now() : Carbon::parse($begin_on_value);

                $every = $i * $repeat_qty;

                $current_date->addWeek($every);

                $date = $current_date->startOfWeek();

                $current_date->addDays($repeat_on);

                $task_date = $current_date->format('Y-m-d');

                if ($begin_on == 'date' && $begin_on_value) {
                    $begin_on_date = Carbon::parse($begin_on_value);
                    if ($task_date < $begin_on_date) {
                        continue;
                    }
                }

                if ($end_on == 'date' && $end) { // check in case end on date defined and loop is exeeding...

                    $end_on_date = Carbon::parse($end);

                    if ($task_date > $end_on_date) {
                        continue;
                    }
                }

                $task_name = ($add_suffix) ? $task_name . " - week " . ($week_counter) : $task_name;

                $week_data = array("due_date" => $task_date, "task_name" => $task_name, "template_id" => $id);

                $repeat_tasks[] = $week_data;

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

        } else if ($repeat_frequency == 'months') {

            if ($end_on == 'date') {
                $current_date = ($begin_on == 'now') ? Carbon::now() : Carbon::parse($begin_on_value);
                $end = Carbon::parse($end);
                $total_repeats = $end->diffInMonths($current_date) + 2;
            } else {
                $total_repeats = $end;
            }
            //echo "<pre/>";
            //echo "Months";
            //print_r($total_repeats);die;
            for ($i = 0; $i < $total_repeats; $i++) {
                //$current_date = Carbon::now();
                $current_date = ($begin_on == 'now') ? Carbon::now() : Carbon::parse($begin_on_value);
                $every = $i * $repeat_qty;
                //var_dump($every);
                $current_date->addMonthsNoOverflow($every);
                if ($repeat_on == 'thisday') {
                    $day = date("d");
                    $task_date = $current_date->format('Y-m-d');
                    //var_dump(date("Y-m-d H:i:s"),$task_date);

                } else if ($repeat_on == 'firstday') {
                    $task_date = $current_date->startOfMonth()->toDateString();
                } else {
                    $task_date = $current_date->endOfMonth()->toDateString();
                }
                // echo date("m",strtotime($task_date));

                if ($begin_on == 'date' && $begin_on_value) {
                    $begin_on_date = Carbon::parse($begin_on_value);
                    if (date("Y-m", strtotime($task_date)) < date("Y-m", strtotime($begin_on_date))) {
                        //echo date("m",strtotime($task_date));
                        //exit();
                        continue;
                    }
                }

                if ($end_on == 'date' && $end) { // check in case end on date defined and loop is exeeding...

                    $end_on_date = Carbon::parse($end);

                    if ($task_date > $end_on_date) {
                        continue;
                    }
                }

                $task_name = ($add_suffix) ? $task_name . " - " . $current_date->format('F') : $task_name;

                //$deadlines[]=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$task->id);
                $month_data = array("due_date" => $task_date, "task_name" => $task_name, "template_id" => $id);

                $repeat_tasks[] = $month_data;
                //Self::clone_task($task->task_id, $month_data);
                # code...
            }

        } else if ($repeat_frequency == 'years') {

            if ($end_on == 'date') {
                $current_date = ($begin_on == 'now') ? Carbon::now() : Carbon::parse($begin_on_value);
                $end = Carbon::parse($end);
                $total_repeats = $end->diffInYears($current_date);
            } else {
                $total_repeats = $end;
            }
            /*echo "<pre/>";
            echo "Years";
            print_r($total_repeats);die;*/

            for ($i = 0; $i < $total_repeats; $i++) {
                //$current_date = Carbon::now();
                $current_date = ($begin_on == 'now') ? Carbon::now() : Carbon::parse($begin_on_value);
                $every = $i * $repeat_qty;
                $current_date->addYear($every);
                if ($repeat_on == 'thisday') {
                    $task_date = $current_date->format('Y-m-d');
                } else if ($repeat_on == 'firstday') {
                    $task_date = $current_date->startOfYear()->toDateString();
                } else {
                    $task_date = $current_date->endOfYear()->toDateString();
                }

                if ($begin_on == 'date' && $begin_on_value) {

                    $begin_on_date = Carbon::parse($begin_on_value)->format("Y");

                    if (date("Y", strtotime($task_date)) < $begin_on_date) {
                        continue;
                    }
                }

                if ($end_on == 'date' && $end) { // check in case end on date defined and loop is exeeding...

                    $end_on_date = Carbon::parse($end)->format("Y");

                    if (date("Y", strtotime($task_date)) > $end_on_date) {
                        continue;
                    }
                }

                $task_name = ($add_suffix) ? $task_name . " - " . $current_date->format('Y') : $task_name;

                //$deadlines[]=array("due_date"=>$task_date, "task_name"=>$task_name, "template_id"=>$task->id);
                $year_data = array("due_date" => $task_date, "task_name" => $task_name, "template_id" => $id);
                //Self::clone_task($task->task_id, $year_data);
                $repeat_tasks[] = $year_data;
                # code...
            }

        }

        $last_task = array();

        if (!empty($repeat_tasks)) {

            $last_task = end($repeat_tasks);

            //$_goal->due_date=$last_task['due_date'];

            //$_goal->save();

            /*foreach ($repeat_tasks as $key => $_task) {
        Self::clone_task($task->task_id, $_task);
        }*/
        }

        return $last_task;
    }

/*---------------------GET TASK END DATE AFTER TASK SETTING---------------------*/

    public function getGoalwithParentAndChild(Request $request)
    {

        $post = $request->all();

        if (isset($userDetails) && !empty($userDetails)) {

            $id = $post['id'];
            $model = new Goals();
            $goal_data = $model->_get_goal_attribut($id, $post['oauth_token']->user_id);
            $goal_data['child'] = array();
            if (isset($goal_data) && !empty($goal_data)) {
                $parentGoal = $model->_get_goal_attribut($goal_data->parent_id, $post['oauth_token']->user_id);
                if (isset($parentGoal) && !empty($parentGoal)) {
                    $childGoals = $this->getSubGoal($parentGoal->id);
                    //$childGoals = $model->getChildByParentId($parentGoal->id);
                    $parentGoal['childs'] = (isset($childGoals) && !empty($childGoals)) ? $childGoals : array();

                } else {
                    $parentGoal['childs'] = array();
                }

                return response()->json(['data' => $parentGoal, 'status' => 1, 'msg' => "Get goal data with parent and child.", 'error' => ""]);

            } else {
                return response()->json(['data' => array(), 'status' => 0, 'msg' => "Failed", 'error' => "Goal Not Found"]);
            }
        } else {
            return response()->json(['data' => array(), 'status' => 0, 'msg' => "Failed", 'error' => "Invalid Token"]);
        }

    }

    public function getBreadCum(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }
        $post = $request->all();

        $goal_id = isset($post['id']) ? $post['id'] : "";
        $parent_id = isset($post['parent_id']) ? $post['parent_id'] : "";
        if (isset($userDetails) && !empty($userDetails)) {
            $data = Goals::getBreadcum($goal_id, $parent_id);
            if (isset($data) && !empty($data)) {
                return $this->success($data, 'Get breadcum');
            } else {
                return $this->error('Goal Not Found', 404);
            }
        }

    }

    public function setGoalStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goal_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $post = $request->all();

        if (isset($userDetails) && !empty($userDetails)) {
            $goal_id = isset($post['goal_id']) ? $post['goal_id'] : "";
            $status = isset($post['status']) ? $post['status'] : 1;
            $result = Goals::where('id', $goal_id)->update(['is_active' => $status]);
            if (isset($result) && !empty($result)) {
                Goals::where('top_parent_id', $goal_id)->update(['is_active' => $status]);
                return response()->json(['data' => $result, 'status' => 1, 'msg' => "Your Goal Updated.", 'error' => ""]);
            } else {
                return response()->json(['data' => array(), 'status' => 0, 'msg' => "Failed", 'error' => "Something went wrong."]);
            }
        } else {
            return response()->json(['data' => array(), 'status' => 0, 'msg' => "Failed", 'error' => "Invalid Token"]);
        }
    }

}

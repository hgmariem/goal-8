<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Goals;
use App\Model\StatementValues;
use App\Repositories\IUserRepository;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
//use Auth;
use Log;

class DashboardController extends Controller
{

    use ApiResponser;

    public function dashboard(Request $request)
    {

        $post_data = $request->all();

        Log::info("post_data", $post_data);

        
        if (empty($userDetails)) {
            return $this->error('User Not Found', 404);
        }
        $user_id = $post_data['oauth_token']->user_id;
        $filter = array();
        $filter['start_date'] = isset($post_data['start_date']) && !empty($post_data['start_date']) ? $post_data['start_date'] : date("Y-m-d");
        $filter['user_id'] = $user_id;
        $filter['is_default'] = 0;
        $filter['isMobile'] = 1;

        $filter['view_type'] = isset($post_data['view_type']) && !empty($post_data['view_type']) ? $post_data['view_type'] : "tree";
        $filter['type'] = isset($post_data['type']) && !empty($post_data['type']) ? $post_data['type'] : 1;
        $data = array();
        $task = array();
        $model = new Goals();

        if ($filter['isMobile']) {
            $days = $model->weekday($filter['start_date']);
        } else {
            $days = $model->weekdays($filter['start_date']);
        }

        $days = (isset($days) && !empty($days)) ? $days : array();

        if ($filter['type'] == 1) {
            $habits = $model->get_habits_forAPI($filter);
            $habits = (isset($habits) && !empty($habits)) ? $habits : array();
            $weekday = $model->weekdays($filter['start_date']);
            $weekday = (isset($weekday) && !empty($weekday)) ? $weekday : array();
            $data['habits'] = $habits;
            $data['weekday'] = $weekday;
            $data['days'] = $days;

            // echo "<pre/>";
            //print_r($data['habits']);die;
            foreach ($data['habits'] as $key => $v) {

                if (empty($v->days['log']) && !empty($data['habits'][$key])) {

                    $data['habits'][$key] = $data['habits'][$key]->toArray();
                    // (array)$data['habits'][$key]['days']['log'];

                    //$new_log = array();
                    if ($v->habit_type->value == 7) {
                        $new_log['id'] = "";
                        $new_log['goal_id'] = $v->habit_type->goal_id;
                        $new_log['goal_type'] = $v->habit_type->type;
                        $new_log['value'] = 0;
                        $new_log['date'] = $filter['start_date'];
                        $new_log['is_scale'] = $v->habit_type->is_scale;
                    } else if ($v->habit_type->value != 7 && $v->habit_type->value != "") {

                        $habit_days = explode(",", $v->habit_type->value);

                        if (in_array($v->days['day_of_week'], $habit_days)) {
                            $new_log['id'] = "";
                            $new_log['goal_id'] = $v->habit_type->goal_id;
                            $new_log['goal_type'] = $v->habit_type->type;
                            $new_log['value'] = 0;
                            $new_log['date'] = $filter['start_date'];
                            $new_log['is_scale'] = $v->habit_type->is_scale;
                        } else if (!in_array($v->days['day_of_week'], $habit_days) && $v->habit_type->is_scale == 0) {
                            $new_log['id'] = "";
                            $new_log['goal_id'] = $v->habit_type->goal_id;
                            $new_log['goal_type'] = $v->habit_type->type;
                            $new_log['value'] = 2;
                            $new_log['date'] = $filter['start_date'];
                            $new_log['is_scale'] = $v->habit_type->is_scale;
                        } else if (!in_array($v->days['day_of_week'], $habit_days) && $v->habit_type->is_scale == 1) {
                            $new_log['id'] = "";
                            $new_log['goal_id'] = $v->habit_type->goal_id;
                            $new_log['goal_type'] = $v->habit_type->type;
                            $new_log['value'] = -1;
                            $new_log['date'] = $filter['start_date'];
                            $new_log['is_scale'] = $v->habit_type->is_scale;
                        }
                    }
                    $data['habits'][$key]['days']['log'] = $new_log;
                }
            }

        } else if ($filter['type'] == 2) {
            $_tasks = $model->get_tasksForApi($filter);

            $_tasks = (isset($_tasks) && !empty($_tasks)) ? $_tasks : array();

            foreach ($_tasks as $key => $value) {
                $task[] = $value;
            }

            $data['task'] = $task;

            $data['view_type'] = $filter['view_type'];

        } else {
            $characters = $model->get_characters($filter);
            $characters = (isset($characters) && !empty($characters)) ? $characters : array();
            $sfilter = array();
            $sfilter['is_deleted'] = 0;
            $sfilter['show_in_lobby'] = 1;
            $sfilter['user_id'] = $user_id;
            $statements = StatementValues::get_lobby_values($sfilter);
            $statements = (isset($statements) && !empty($statements)) ? $statements : array();

            $data['statements'] = $statements;
            $data['characters'] = $characters;

        }
        return $this->success($data, 'Listed Successfully');
        //return response()->json(['data' => $data, 'status' => 1, 'msg' => $msg, 'error' => ""]);
    }

    private function objectToArray($object)
    {
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        if (is_array($object)) {
            return array_map(array($this, 'objectToArray'), $object);
        } else {
            return $object;
        }
    }

    public function getGoal(Request $request)
    {

        $post_data = $request->all();
        $userDetails = $this->repository->getUserByParam('id',$post_data['user_id']);
        $goal = new Goals();
        $goalData = $goal->getGoalTypes($post_data['oauth_token']->user_id);
        if (!empty($goalData)) {
            return $this->success($goalData, 'Lobby Goals Listed!');
        } else {
            return $this->error('Not Found', 404);

        }

    }

}

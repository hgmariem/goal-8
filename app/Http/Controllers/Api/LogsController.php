<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Goals;
use App\Model\HabitTypes;
use App\Model\Logs;
use App\Traits\ApiResponser;
use Carbon\Carbon;
//use Auth;
use Illuminate\Http\Request;
use Validator;

class LogsController extends Controller
{

    use ApiResponser;

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function add(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'id' => 'required',
            'value' => 'required',
            'scale' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $date = date("Y-m-d", strtotime($request['date']));

        $log = Logs::where("date", $date)->where("goal_id", $request['id'])->first();

        $goal = Goals::where("id", $request['id'])->first();

        $checkValue = HabitTypes::where("goal_id", $request['id'])->first();

        //echo "<pre/>";
        //print_r($checkValue);die;
        if (isset($checkValue) && !empty($checkValue)) {
            if ($checkValue->is_scale == 1 && $checkValue->is_apply == 0 && ($request['value'] > $checkValue->maximum || $request['value'] < $checkValue->minimum) && ($request['value'] > 1000 || $request['value'] < -1)) {
                return $this->error("Please Enter input 0 Or Between '" . $checkValue->minimum . "' and '" . $checkValue->maximum . "'.", 403);
            }

            if ($checkValue->is_scale == 1 && $checkValue->is_apply == 1 && ($request['value'] > 1000 || $request['value'] < -1)) {
                return $this->error("Please Enter input 0 Or Between '" . $checkValue->minimum . "' and '" . $checkValue->maximum . "'.", 403);}
        }

        if (!$log) {
            $log = new Logs();
        }
        $habit_id = $request['id'];
        $is_apply = (isset($request['is_apply']) && !empty($request['is_apply'])) ? $request['is_apply'] : "";
        //$is_apply=$request['is_apply'];
        $log->goal_id = $request['id'];
        $log->goal_type = $goal->type_id;
        //$log->value=($log->value==1 && $request['value']==1)?0:$request['value'];
        $log->value = $request['value'];
        $log->is_scale = $request['scale'];
        $log->date = date("Y-m-d", strtotime($request['date']));
        if (isset($request['month']) && !empty($request['month']) && isset($request['year']) && !empty($request['year'])) {
            $month = $request['month'];
            $month = $month + 1;
            $year = $request['year'];
        }
        if ($log->save()) {
            if (isset($request['scale']) && $request['scale'] == 1 && !empty($is_apply) && $is_apply == 1) {
                //echo "Reached here...";die;
                $habitType = new HabitTypes();
                $habitType->updateHabit($habit_id);
            }
            $msg = "Saved successfully.";
            if (!empty($month) && !empty($year)) {
                $log->monthDetails = $goal->getMonthly_averageToChangeMonth($habit_id, $month, $year, 2);
            }
            $log->percentage = $goal->get_percentage($goal->id);
        } else {
            return $this->error('Unable to save.', 403);
        }
		$msg = 'Log Value successfully updated.';
        return $this->success($log, $msg);
    }

    public function getMonthly_averageToChangeMonth(Request $request)
    {
        $gid = $request->input('gid');
        $month = $request->input('month');
        $month = $month + 1;
        $year = $request->input('year');
        $check = $request->input('check');
        $model = new Goals();
        $details = $model->getMonthly_averageToChangeMonth($gid, $month, $year, $check);
        return $this->success($details, 'LGet Monthly average to change Month');
    }

    /*public function getWeek_average(Request $request)
    {
    $response['status']=1;
    $weeksDate = $request->input('weeksDate');
    $gid = $request->input('id');
    $model = new Goals();
    $details = $model->getWeekly_average($gid,$weeksDate);
    //$response['data']= $details;
    //return \Response::json($response);
    }*/

    public function getDay_scale(Request $request)
    {
        $data = array();
        $details = '';
        $validator = Validator::make($request->all(), [
            'habit_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $weeksDate = $request->input('weeksDate');
        $habit_id = $request->input('habit_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $type = $request->input('type');

        $model = new Goals();
        if ($type == "days") {
            $details = $model->dayByHabitIdAverage($habit_id, $start_date, $end_date);
        } else if ($type == "weeks") {
            //echo "Reached Here...";die;
            $details = $model->weeklyGraphDetails($habit_id, $start_date, $end_date);

        } else if ($type == "month") {

            $details = $model->getMonthScaleAndTotal($habit_id, $start_date, $end_date);
        } else {
            $details = $model->getYearScaleAndTotal($habit_id, $start_date, $end_date);
        }
        return $this->success($details, "Graph show successfully");
    }

    public function get(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'habit_id' => 'required',
        ]);

		if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }

        $id = $request->input('habit_id');
        $habit = Goals::where("id", $id)->first();
        if (!isset($habit) && empty($habit)) {
            return $this->error('Habit Not found!', 404);
        }
        $data = array();

        //echo "<pre/>";
        //print_r($habit);die;
        $end = Carbon::parse($habit->created_at);

        $now = Carbon::now()->endOfMonth();

        //var_dump($now);
        //exit();
        $addtional_days = array();
        $length = $end->diffInDays($now) + 1;
        $type_data = Goals::get_habit_types($habit->id);
        $is_scale = isset($type_data->is_scale) ? $type_data->is_scale : 0;

        if ($is_scale == 0) {

            $data['start_date'] = Carbon::parse($habit->habit_start_date)->format("Y-m-d");
            $data['created_at'] = Carbon::parse($habit->created_at)->format("Y-m-d");

            $prev_days = $habit->get_prev_completed_dates($habit->id, $habit->created_at)->toArray();

            $data['prev_days'] = $prev_days;

            $addtional_days = $habit->get_additional_completed_dates($habit->id, date("Y-m-d"))->toArray();

            $data['addtional_days'] = $addtional_days;

            $disabled_days = $habit->get_all_disabled_dates($habit->id)->toArray();

            $data['disabled_days'] = $disabled_days;

            $disabled_dates = array();

            foreach ($disabled_days as $key => $_ddata) {
                $disabled_dates[] = Carbon::parse($_ddata['date'])->format("Y-m-d");
            }

            $allowed_days = array();

            if (isset($type_data) && $type_data->count_per_week != 7) { // going to get only selected dates

                $day_numbers = ($type_data && $type_data->value != '') ? explode(",", $type_data->value) : array();

                if ($day_numbers) {

                    $dayOfTheWeek = Carbon::parse($habit->created_at)->dayOfWeek;

                    foreach ($day_numbers as $key => $day_number) {

                        if ($dayOfTheWeek == $day_number) {
                            $allowed_days[] = Carbon::parse($habit->created_at)->format('Y-m-d');
                        }

                        $startDate = Carbon::parse($habit->created_at)->next($day_number); // Get the first friday.

                        $endDate = Carbon::now()->endOfMonth();

                        for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                            $allowed_days[] = $date->format('Y-m-d');
                        }
                    }
                }

                $period = generateDateRange(Carbon::parse($habit->created_at), Carbon::now()->endOfMonth()); // gettting all the dates between current month and start date

                $new_disables = array_diff($period, $allowed_days); // get dates other that selected of the week,

                if ($new_disables) {
                    foreach ($new_disables as $key => $new_d) {
                        $disabled_dates[] = Carbon::parse($new_d)->format("Y-m-d");
                    }
                }

            }

            //print_r($new_disables);

            $data['allowed_dates'] = $allowed_days;

            $data['disabled_dates'] = $disabled_dates;

            $completed = $habit->get_all_completed_dates($habit->id)->toArray();

            //print_r($completed->toArray());

            $total_complated = array_merge($completed, $prev_days, $addtional_days);

            $data['completed'] = $completed;

            $completed_dates = array();

            //echo "<pre/>";
            //print_r($completed);die;
            foreach ($total_complated as $key => $_data) {
                $completed_dates[] = Carbon::parse($_data['date'])->format("Y-m-d");
            }
            $data['completed_dates'] = array_unique($completed_dates);
        } else {

            $data['start_date'] = Carbon::parse($habit->habit_start_date)->format("Y-m-d");
            $data['created_at'] = Carbon::parse($habit->created_at)->format("Y-m-d");

            $prev_days = $habit->get_prev_completed_dates_scale_number($habit->id, $type_data->is_scale, $type_data->minimum, $type_data->maximum, $habit->created_at)->toArray();

            $data['prev_days'] = $prev_days;

            $addtional_days = $habit->get_additional_completed_dates_scale_number($habit->id, $type_data->is_scale, $type_data->minimum, $type_data->maximum, date("Y-m-d"))->toArray();

            //exit();
            $data['addtional_days'] = $addtional_days;

            $disabled_days = $habit->get_all_disabled_dates_scale_number($habit->id, $type_data->is_scale);

            //$disabled_days=$habit->get_all_disabled_dates($habit->id)->toArray();

            $data['disabled_days'] = $disabled_days;

            $disabled_dates = array();

            foreach ($disabled_days as $key => $_ddata) {
                $disabled_dates[] = Carbon::parse($_ddata['date'])->format("Y-m-d");
            }

            $allowed_days = array();

            if ($type_data->count_per_week != 7) { // going to get only selected dates

                $day_numbers = ($type_data && $type_data->value != '') ? explode(",", $type_data->value) : array();

                if ($day_numbers) {

                    $dayOfTheWeek = Carbon::parse($habit->created_at)->dayOfWeek;

                    foreach ($day_numbers as $key => $day_number) {

                        if ($dayOfTheWeek == $day_number) {
                            $allowed_days[] = Carbon::parse($habit->created_at)->format('Y-m-d');
                        }

                        $startDate = Carbon::parse($habit->created_at)->next($day_number); // Get the first friday.

                        $endDate = Carbon::now()->endOfMonth();

                        for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                            $allowed_days[] = $date->format('Y-m-d');
                        }
                    }
                }

                $period = generateDateRange(Carbon::parse($habit->created_at), Carbon::now()->endOfMonth()); // gettting all the dates between current month and start date

                $new_disables = array_diff($period, $allowed_days); // get dates other that selected of the week,

                if ($new_disables) {
                    foreach ($new_disables as $key => $new_d) {
                        $disabled_dates[] = Carbon::parse($new_d)->format("Y-m-d");
                    }
                }

            }

            //print_r($new_disables);

            //$completed=$habit->get_all_completed_dates($habit->id)->toArray();
            $completed = $habit->get_all_completed_dates_scale_number($habit->id, $type_data->is_scale, $type_data->minimum, $type_data->maximum)->toArray();

            $new_completed = array();
            foreach ($completed as $key => $v) {
                $new_completed[] = Carbon::parse($v['date'])->format("Y-m-d");
            }

            $disabled_dates = array_diff($disabled_dates, $new_completed);

            $data['allowed_dates'] = $allowed_days;

            $disabled_dates_new = array();

            if (!empty($disabled_dates) && sizeof($disabled_dates) > 0) {
                foreach ($disabled_dates as $key => $value) {
                    $disabled_dates_new[] = $value;
                }
            }

            //$new_addtional_days = (array)$addtional_days;

            $data['disabled_dates'] = $disabled_dates_new;
            ////var_dump($data);die;
            $total_complated = array_merge($completed, $prev_days, $addtional_days);

            $data['completed'] = $completed;

            $completed_dates = array();

            foreach ($total_complated as $key => $_data) {
                $completed_dates[] = Carbon::parse($_data['date'])->format("Y-m-d");
            }

            $currentDate = date("Y-m-d");
            $month = date("m");
            $month = (int) $month;
            $year = date("Y");
            $end_date = date('Y-m-d', strtotime('+365 days'));

            $data['completed_dates'] = array_unique($completed_dates);
            $start_date = date("Y-m-d", strtotime($habit->created_at));
            $details = $habit->getMonthScaleAndTotal($habit->id, $start_date, $end_date);
            $monthDetails = $habit->getMonthly_averageToChangeMonth($habit->id, $month, $year, 2);
            //$myhabit = $habit->getMonthly_average($id);

            $data['month_lowest'] = $monthDetails['lowest'];
            $data['month_highest'] = $monthDetails['highest'];
            $data['monthly_average'] = $monthDetails['monthly_average'];
            $data['monthly_total'] = $monthDetails['monthly_total'];
        }

        return $this->success($data);
    }

    public function delete(Request $request)
    {

    }

}

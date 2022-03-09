<?php

namespace App\Http\Controllers;

use App\Model\Goals;
use App\Model\StatementValues;
use App\Model\Types;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller {

  public function __construct() {

    $this->middleware('auth');
    parent::__construct();
  }

  public function dashboard(Request $request) {
    /*print_r("desktop:".$this->userAgent->isDesktop());
    print_r("mobile:".$this->userAgent->isMobile());
    exit();
    */
    //echo "Reached Here....";die;
    $post_data = $request->all();

    #print_r($post_data);

    $user_id = Auth::user()->id;

    $filter = [];

    $filter['start_date'] = isset($post_data['date']) && !empty($post_data['date']) ? $post_data['date'] : date(
      "Y-m-d"
    );

    $filter['user_id'] = $user_id;
    $filter['is_default'] = 0;
    $filter['isMobile'] = $this->isMobile;
    $model = new Goals();

    $characters = $model->get_characters($filter);

    if ($filter['isMobile']) {
      $days = $model->weekday($filter['start_date']);
    }
    else {
      $days = $model->weekdays($filter['start_date']);
    }

    $sfilter = [];

    $sfilter['is_deleted'] = 0;
    $sfilter['show_in_lobby'] = 1;
    $sfilter['user_id'] = $user_id;

    #print_r($sfilter);

    $statements = StatementValues::get_lobby_values($sfilter);

    #print_r($statements);

    //$weekday=$model->weekdays($filter['start_date']);
    $habits = $model->get_habits($filter);
    // echo "Hello Man.....";die;
    /*echo "<pre/>";
    print_r($habits);die;*/
    $filter['view_type'] = "tree";

    $_tasks = $model->get_tasks($filter);
    $ttt = $model->get_Alltask_list();
    $tasks['view_type'] = $filter['view_type'];
    $type = new Types();
    $typ = $type->all();
    $tasks['items'] = $_tasks;
    $reminder = $request->instance()->query('reminder');
    return view('home.dashboard',[
        'habits' => $habits,
        "tasks" => $tasks,
        "characters" => $characters,
        "days" => $days,
        "statements" => $statements,
        "reminder" => $reminder
      ]
    );
  }

  public function getGoal() {
    $user_id = Auth::user()->id;
    $goal = new Goals();
    $goalData['data'] = $goal->getGoalTypes($user_id);
    return json_encode($goalData);

  }


}

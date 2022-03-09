<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\Api\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


     
Route::group(['prefix' => 'V1'], function(){
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::get('forgotpwd', [AuthController::class, 'forgotpwd']);
    Route::post('resetpwd', [AuthController::class, 'updatePwd']); 
    Route::post('/getUsers','Api\ProfileController@getUsers');
});

Route::group(['middleware' => ['auth:api', 'CheckJWTAccessToken']], function () {
    Route::prefix('V1')->group(function () {
        
        Route::get('user', [AuthController::class, 'user']);
        Route::get('logout', [AuthController::class, 'logout']);
        
        Route::post('/dashboard','Api\DashboardController@dashboard');
        Route::post('add-from-lobby/goallist','Api\DashboardController@getGoal');

        Route::post('/getTaskList','Api\ApiController@get_task_list');
        Route::post('/userData','Api\ApiController@getUserDetail');
        Route::post('/sort_habbit','Api\ApiController@sort_habbit');
        Route::post('/movetotrophy','Api\ApiController@movetotrophy');

          /*-----------------------GOALs & HABITS  API ROUTES HERE---------------------------*/
          
        Route::post('/get/breadcum','Api\GoalsController@getBreadCum');
        Route::post('goal/generate_autosaveid','Api\GoalsController@generate_autosaveid');
        Route::post('/goal/list','Api\GoalsController@index');
        Route::post('/graph/checkbox','Api\GoalsController@get_monthly_statistics');
        Route::post('/add-lobby','Api\GoalsController@addLobby');
        Route::post('/goal/delete','Api\GoalsController@delete');
        Route::post('/drag/goal','Api\GoalsController@sort_list');
        // Goal Sheet
        Route::post('goals/getAllsheet','Api\GoalsController@getGoalSheet');
        Route::post('goals/saveGoalSheet','Api\GoalsController@save_GoalSheet');
        Route::post('/goal/sheet/delete','Api\GoalsController@attribute_delete_sheet');
        Route::post('/goal/sheet/save','Api\GoalsController@save_attributes');
        Route::post('/goal/plus/sheet','Api\GoalsController@save_for_status_attributes');
        Route::post('/goal/sheet/rename','Api\GoalsController@attribute_rename_sheet');
        Route::post('/goal/sheet/duplicate','Api\GoalsController@attribute_duplicate_sheet');
        Route::post('/get/goal/sheet','Api\GoalsController@attribute_get_sheet');
        Route::post('/add-default-goal/goal_list','Api\GoalsController@addtomylist');
        Route::post('add/goal','Api\GoalsController@create_goals');
        Route::post('goal/add','Api\GoalsController@createApi_goals');
        Route::post('edit/goal','Api\GoalsController@edit');
        Route::post('show-and-hide-lobby/goal','Api\GoalsController@showAndHideInLobby');
        Route::post('active-and-inactive/goal','Api\GoalsController@activeAndInactiveGoal');
        Route::post('get/goal','Api\GoalsController@get_goal_details');
    
        Route::post('goals/getGoalwithParentAndChild','Api\GoalsController@getGoalwithParentAndChild');
     
        Route::post('set-goal-status','Api\GoalsController@setGoalStatus');
        // Tasks
        
        Route::post('goals/task_complete','Api\GoalsController@task_complete');
        Route::post('task/change_state','Api\GoalsController@change_state');
        Route::post('goals/upadate_task_date','Api\GoalsController@upadate_task_date');
        Route::post('goal/task-template-save','Api\GoalsController@save_task_template');
        Route::post('goals/reactive_task','Api\GoalsController@reactive_task');
        Route::post('getTask','Api\GoalsController@getTaskTemplate');
        Route::post('validate-task-template','Api\GoalsController@validateTaskTemplate');
         // Habits
        Route::post('goals/upadate_habit_date','Api\GoalsController@upadate_habit_date');
        Route::post('/habit/prev-next','Api\GoalsController@weekly_habits');
        Route::post('goal/habit-template-save','Api\GoalsController@saveHabitSchedule');

         /*-----------------------Logs  API ROUTES HERE---------------------------*/

        Route::post('/log/add','Api\LogsController@add');
        Route::post('/log/numberGraph','Api\LogsController@getDay_scale');
        Route::post('/log/list','Api\LogsController@get');
  
        /*-----------------------STATEMENT AND VALUES API ROUTES HERE---------------------------*/
  
        Route::post('goal/get-statements-values','Api\StatementValuesController@get_statements_values');
        Route::post('/save/statement-values','Api\StatementValuesController@save_statementValue');
        Route::post('/goal/statement-values','Api\StatementValuesController@save_statements_values');
        Route::post('/add/statement-sheet','Api\StatementValuesController@save_statements_values');
        Route::post('/delete/statement-sheet','Api\StatementValuesController@attribute_delete_sheet');
        Route::post('/rename/statement-sheet','Api\StatementValuesController@attribute_rename_sheet');
        Route::post('/get/statement-sheet','Api\StatementValuesController@attribute_get_sheet');
        Route::post('/duplicate/statement-sheet','Api\StatementValuesController@attribute_duplicate_sheet'); 
        Route::post('/statement-values/save-statement-values','Api\StatementValuesController@save_statements_values'); 

         /*-----------------------Compass API ROUTE HERE--------------------*/
  
         Route::post('/compass/list','Api\StatementValuesController@index');
         Route::post('/statement-values/save-personal-statement-values','Api\StatementValuesController@save_personl_statements_values');
         Route::post('/add-statement/compass','Api\StatementValuesController@save_personl_statements_values');
         Route::post('/rename-statement/compass','Api\StatementValuesController@attribute_personal_rename_sheet');
         Route::post('/duplicate-statement/compass','Api\StatementValuesController@attribute_duplicate_personal_sheet');
         Route::post('/delete-statement/compass','Api\StatementValuesController@attribute_delete_personal_sheet');
         Route::post('/save-compass','Api\StatementValuesController@save_statement');
         Route::post('/get-sheet/compass','Api\StatementValuesController@personal_statement_attribute_get_sheet');
  
        /*-----------------------ASSIGNMENT API ROUTE HERE--------------------*/
  
        Route::post('/add/assignment','Api\AssignmentController@createAssignment');
        Route::post('/edit/assignment','Api\AssignmentController@edit');
        Route::get('/getSingle/assignment/{id}','Api\AssignmentController@edit'); 
        Route::post('/assignment/list','Api\AssignmentController@index');
        Route::post('/default-assignment/list','Api\AssignmentController@getDefaultAssignment');
        Route::post('/add/default-assignment','Api\AssignmentController@addtomylist');
        Route::post('/delete/assignment','Api\AssignmentController@delete');
        Route::post('/drag/assignment','Api\AssignmentController@sort_list');
        Route::post('/default/edit/assignment','Api\AssignmentController@default_edit');
  
  
       
  
  
        /*-----------------------Trophis API Route HERE---------------------*/
  
        Route::post('/trophy/add','Api\TrophyController@add');
        Route::post('/trophies/list','Api\TrophyController@index');
        Route::post('/trophy/detail','Api\TrophyController@editTrophy');
        Route::post('/delete/trophy','Api\TrophyController@delete');
        Route::post('/edit/trophy','Api\TrophyController@edit');
  
  
        /*-----------------------User Profile API Route HERE---------------------*/
        //Route::post('/getUsers','Api\UserController@getUsers');//done
        Route::post('/user/profile','Api\ProfileController@index');//done
        Route::post('/user/details','Api\ProfileController@getUserDetails');//done
        Route::post('/change/credential','Api\ProfileController@changeCredPassword');//done
        Route::post('/update/profile','Api\ProfileController@updateProfile');//done
        Route::post('/unsubscribe_user/profile','Api\ProfileController@unsubscribe_user');//done
        
      


/*
        Route::post('/update/image','Api\ProfileController@changeImage');//use restAPI
        Route::post('/change/preference','Api\ProfileController@changePreferenceOnMail');//use restAPI
        Route::post('get/loginmailboxDetails','Api\ProfileController@getLoginuserMailBoxDetails');//use restAPI
        Route::post('get/fiveMailboxchat','Api\ProfileController@getFiveChat');//use restAPI
        Route::post('add/chat/byloginuser','Api\ProfileController@addReply');//use restAPI
        Route::post('delete/chat/byloginuser','Api\ProfileController@deleteChat');//use restAPI
        Route::post('programAvailabity/sharedinfo','Api\ProfileController@programAvailabity');//use restAPI
  */
  
  
        /*******************Workshop*********************************/
      Route::post('/workshop/create','Api\WorkshopController@create');
      Route::post('/workshop/get','Api\WorkshopController@get');
      Route::post('workshop/update',["as"=>"workshop_submit", 'uses'=>'WorkshopController@process_form',"active_menu"=>"workshop"]);
      Route::get('workshop/update',["as"=>"workshop_update", 'uses'=>'WorkshopController@update',"active_menu"=>"workshop"]);
  
      Route::get('workshop/delete/{id}',["as"=>"workshop_delete", 'uses'=>'WorkshopController@delete',"active_menu"=>"workshop"]);
      Route::get('workshop/admin',["as"=>"workshop_admin", 'uses'=>'WorkshopController@admin',"active_menu"=>"workshop"]);
  
              /*-----------------------Setting API Route HERE---------------------*/
  
        Route::post('/change/setting','Api\ProfileController@changeSetting');
        Route::post('/get/setting','Api\ProfileController@getSetting');
  
        Route::post('/get/sharedinfo','Api\SharedController@getReceivedInfo');
     
        /*-----------------------Send Push Notification --------------------------*/
  
          Route::post('send-notification','Api\LoginApiController@sendPushNotification');
  
      });
  
});

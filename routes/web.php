<?php



/*function getIp(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}
*/
Route::get('/plan', "PlanController@index");
// migrate command : php artisan migrate
Route::get('/migrate', function() {
	$exitCode = Artisan::call('migrate');
	return '<h1>Migration success</h1>';
	});
Route::get('/clear-cache', function() {
$exitCode = Artisan::call('cache:clear');
return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
$exitCode = Artisan::call('optimize');
return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
$exitCode = Artisan::call('route:cache');
return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
$exitCode = Artisan::call('route:clear');
return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
$exitCode = Artisan::call('view:clear');
return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
$exitCode = Artisan::call('config:cache');
return '<h1>Clear Config cleared</h1>';
});
/*$allow_ip=array("103.106.194.118","171.48.32.94","122.177.17.42");
if(!in_array(getIp(), $allow_ip)){
	//header("Location: http://goals.keyhabits.com/pc/");
	//exit();
}*/

//use Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/pushNotification/workshop','Api\CroneController@setPushNotification');

Route::group(['middleware' => 'prevent-back-history'],function(){
	
if(Auth::user()){
	Route::get('/',['uses'=>'DashboardController@dashboard']);
}else{
	
	Route::get('/', array('as' => 'login', 'uses' => 'Auth\LoginController@login'));
}

Route::get('/register', array('as' => 'subscription', 'uses' => 'Auth\RegisterController@register'));
Route::post('/update_profile', array('as' => 'update_profile', 'uses' => 'Auth\UserController@update_profile'));
Route::post('/Change_password', array('as' => 'Change_password', 'uses' => 'Auth\UserController@Change_password'));
Route::post('/unsubscribe_user', array('as' => 'unsubscribe_user', 'uses' => 'Auth\UserController@unsubscribe_user'));
Route::get('/unsubscribe_user', array('as' => 'unsubscribe_user', 'uses' => 'Auth\UserController@unsubscribe_user'));


Route::get('/pc/login', array('as' => 'login', 'uses' => 'Auth\LoginController@login'));

Route::get('/login', array('as' => 'login', 'uses' => 'Auth\LoginController@login'));
Route::post('/login', array('as' => 'login', 'uses' => 'Auth\LoginController@login'));
Route::get('/postlogin', array('as' => 'login', 'uses' => 'Auth\LoginController@postLogin'));
Route::post('/postlogin', array('as' => 'login', 'uses' => 'Auth\LoginController@postLogin'));
//Route::get('/forgotpwd', array('as' => 'forgotpwd', 'uses' => 'Auth\LoginController@showforgotpwd'));
//Route::post('/forgotpwd', array('as' => 'forgotpwd', 'uses' => 'Auth\LoginController@forgotpwd'));
Route::get('/forgotpwd','Auth\ForgotPasswordController@showforgotpwd')->name('auth.forgotpwd');
Route::post('/forgotpwd', array('as' => 'forgotpwd', 'uses' => 'Auth\ForgotPasswordController@forgotpwd'));
Route::get('/reset/{token}','Auth\ForgotPasswordController@showUpdatePassword');
Route::post('/reset/{token}','Auth\ForgotPasswordController@updatePwd');
Route::post('/reset/{token}','Auth\ForgotPasswordController@updatePwd');

Route::get('/crons', array('as' => 'crons', 'uses' => 'CronsController@process'));
Route::get('/crons/process_by_id/{id}', array('as' => 'crons', 'uses' => 'CronsController@process_by_id'));
/*
Route::get('/forgotpwd', function () {
    return view('auth.forgot');
});
*/

//Route::get('/cheklogin', array('as' => 'login', 'uses' => 'AuthController@cheklogin'));
Route::get('/cheklogin', array('uses' => 'CheckloginController@cheklogin'));
Route::get('/chek_login', array('uses' => 'CheckloginController@checkBetalogin'));
 
Auth::routes();

// Registered and Activated User Routes
Route::group(['middleware' => ['auth', 'checkUserStatus']], function () {
	
	
	Route::get('/home',['uses'=>'DashboardController@dashboard']);

	Route::get('trophies',["as"=>"my_trophies",'uses'=>'TrophyController@index',"active_menu"=>"trophy"]);
	Route::get('trophies/sync',["as"=>"trophies_sync",'uses'=>'TrophyController@sync',"active_menu"=>"trophy"]);
	//Route::get('message',["as"=>"message",'uses'=>'MessageController@index',"active_menu"=>"mailbox"]);

	Route::post('trophy/add',["as"=>"add_trophy",'uses'=>'TrophyController@add',"active_menu"=>"trophy"]);
	Route::post('trophy/edit',["as"=>"edit_trophy",'uses'=>'TrophyController@edit',"active_menu"=>"trophy"]);
	Route::post('trophy/delete',["as"=>"delete_trophy",'uses'=>'TrophyController@delete',"active_menu"=>"trophy"]);

	Route::get('assignment/add', function () {
		return view('home.assignment_add');
	});

	Route::get('shared',['uses'=>'SharedController@index',"active_menu"=>"shared"]);
	Route::post('shared',['uses'=>'SharedController@index',"active_menu"=>"shared"]);

	Route::get('assignment/list',['uses'=>'AssignmentController@index',"active_menu"=>"assignment"]);

	Route::get('assignment/add',['uses'=>'AssignmentController@add',"active_menu"=>"assignment"]);
	
	Route::get('assignment/default/add',['uses'=>'AssignmentController@add_default',"active_menu"=>"assignment"]);
	
	Route::post('assignment/create',['uses'=>'AssignmentController@create',"active_menu"=>"assignment"]);

	/*----------------------NEW ROUTES FOR ASSIGNMENT 19 APRIL 2019-----------------------------------*/


	Route::post('assignment/create-update',['uses'=>'AssignmentController@createAssignment',"active_menu"=>"assignment"]);
	
	Route::post('assignment/default/create',['uses'=>'AssignmentController@default_create',"active_menu"=>"assignment"]);
	
	Route::get('assignment/edit/{id}',['uses'=>'AssignmentController@edit',"active_menu"=>"assignment"]);
	
	Route::post('assignment/update',['uses'=>'AssignmentController@update',"active_menu"=>"assignment"]);
	
	Route::get('assignment/addtomylist/{id}',['uses'=>'AssignmentController@addtomylist',"active_menu"=>"assignment"]);

	Route::post('assignment/delete',['uses'=>'AssignmentController@delete',"active_menu"=>"assignment"]);
	
	
	
	Route::get('assignment/default/edit/{id}',['uses'=>'AssignmentController@default_edit',"active_menu"=>"assignment"]);

	Route::post('assignment/defaultupdate',['uses'=>'AssignmentController@defupdate',"active_menu"=>"assignment"]);

	//Route::get('assignment/default/delete/{id}',['uses'=>'AssignmentController@delete_defuser',"active_menu"=>"assignment"]);
	Route::post('assignment/default/delete',['uses'=>'AssignmentController@delete_default',"active_menu"=>"assignment"]);
	Route::post('assignment/list_order',["as"=>"assignment_list_order", 'uses'=>'AssignmentController@sort_list',"active_menu"=>"assignment"]);
	Route::post('assignment/default/list_order',["as"=>"assignment_default_list_order", 'uses'=>'AssignmentController@default_sort_list',"active_menu"=>"assignment"]);
	Route::get('alltrophy',['uses'=>'TrophyController@create_data',"active_menu"=>"trophy"]);
	Route::get('assignment/trophy',['uses'=>'TrophyController@create_data',"active_menu"=>"trophy"]);
	Route::get('trophy/edit/{id}',['uses'=>'TrophyController@editTrophy',"active_menu"=>"trophy"]);
	Route::post('trophy/movetotrophy',['uses'=>'TrophyController@movetotrophy',"active_menu"=>"goals"]);
	//Route::post('',['uses'=>'TrophyController@updateTrophy']);
	
	Route::get('add',['uses'=>'GoalsController@add',"active_menu"=>"goals"]);
	Route::post('create_list',['uses'=>'GoalsController@create_goals',"active_menu"=>"goals"]);
	Route::get('edit/{id}',['uses'=>'GoalsController@edit',"active_menu"=>"goals"]);
	Route::post('update',['uses'=>'GoalsController@update',"active_menu"=>"goals"]);
	Route::get('delete/{id}',['uses'=>'GoalsController@delete_goals']);


	/*--------------------- Goal Statement Routes Here----------------------------*/ 

	Route::get('edit/statements/{id}',['uses'=>'GoalsController@editStatement',"active_menu"=>"goals"]);

	/*******************Default Goals*********************************/
	Route::get('goals/generate_autosaveid',['uses'=>'GoalsController@generate_autosaveid',"active_menu"=>"goals"]);
	Route::get('goals/default/list',['uses'=>'DefaultGoalsController@index',"active_menu"=>"goals"]);
	Route::get('goals/default/add',['uses'=>'GoalsController@default_add',"active_menu"=>"goals"]);
	Route::get('goals/default/edit/{id}',['uses'=>'GoalsController@default_edit',"active_menu"=>"goals"]);
	Route::post('goals/default/update',['uses'=>'GoalsController@Def_update',"active_menu"=>"goals"]);
	Route::get('goals/default/delete',['uses'=>'GoalsController@delete_def_goals',"active_menu"=>"goals"]);
	Route::get('goals/default/addtomylist/{id}',['uses'=>'GoalsController@addtomylist',"active_menu"=>"goals"]);

	//Route::get('goals/default/import',['uses'=>'GoalsController@default_import',"active_menu"=>"goals"]);

	/*******************Goals*********************************/
	Route::post('goals/create',["as"=>"create_goal", 'uses'=>'GoalsController@create_goals',"active_menu"=>"goals"]);
	Route::post('setdate',['uses'=>'GoalsController@getTaskEndDate']);
	Route::post('goals/state',["as"=>"change_state", 'uses'=>'GoalsController@change_state',"active_menu"=>"goals"]);
	Route::post('goals/delete',["as"=>"delete_goal", 'uses'=>'GoalsController@delete',"active_menu"=>"goals"]);
	Route::post('goals/list_order',["as"=>"goal_list_order", 'uses'=>'GoalsController@sort_list',"active_menu"=>"goals"]);
	Route::post('goals/self_order',["as"=>"goal_self_order", 'uses'=>'GoalsController@sort_self',"active_menu"=>"goals"]);
	Route::post('goals/weekly_habits',["as"=>"goal_weekly_habits", 'uses'=>'GoalsController@weekly_habits',"active_menu"=>"goals"]);
	Route::post('goals/task_list',["as"=>"goal_tasks_list", 'uses'=>'GoalsController@tasks_list',"active_menu"=>"goals"]);
	Route::post('goals/task_complete',["as"=>"goal_task_complete", 'uses'=>'GoalsController@task_complete',"active_menu"=>"goals"]);
	Route::post('goals/reactive_task',["as"=>"goal_reactive_task", 'uses'=>'GoalsController@reactive_task',"active_menu"=>"goals"]);
	Route::get('goals/progress_statistics/{id}',["as"=>"progress_statistics", 'uses'=>'GoalsController@progress_statistics']);
	Route::get('goals/monthly_statistics/{id}',["as"=>"monthly_statistics", 'uses'=>'GoalsController@get_monthly_statistics']);
	Route::get('goals/view/{type}',["as"=>"goal_view_type", 'uses'=>'GoalsController@goal_view_type']);
	Route::post('goals/upadate_task_date',["as"=>"upadate_task_date", 'uses'=>'GoalsController@upadate_task_date',"active_menu"=>"goals"]);
	Route::post('goals/upadate_habit_date',["as"=>"upadate_habit_date", 'uses'=>'GoalsController@upadate_habit_date',"active_menu"=>"goals"]);
	Route::post('goals/save_attributes',["as"=>"save_attributes", 'uses'=>'GoalsController@save_attributes',"active_menu"=>"goals"]);
	
	Route::post('goals/attr/get_sheet',["as"=>"attr_get_sheet", 'uses'=>'GoalsController@attribute_get_sheet',"active_menu"=>"goals"]);
	Route::post('goals/attr/delete_sheet',["as"=>"attr_delete_sheet", 'uses'=>'GoalsController@attribute_delete_sheet',"active_menu"=>"goals"]);
	Route::post('goals/attr/duplicate_sheet',["as"=>"attr_duplicate_sheet", 'uses'=>'GoalsController@attribute_duplicate_sheet',"active_menu"=>"goals"]);

	Route::post('goals/attr/lastsheet',['uses'=>'GoalsController@attribute_last_sheet']);

	Route::post('goals/attr/rename_sheet',["as"=>"attr_rename_sheet", 'uses'=>'GoalsController@attribute_rename_sheet',"active_menu"=>"goals"]);
	Route::post('goals/save-template',["as"=>"save-task-template", 'uses'=>'GoalsController@save_task_template',"active_menu"=>"goals"]);
	Route::get('goals/get-template',["as"=>"get-task-template", 'uses'=>'GoalsController@get_task_template',"active_menu"=>"goals"]);




	Route::post('goals/add-lobby',['uses'=>'GoalsController@addLobby']);
	Route::post('goals/add-habit-type',['uses'=>'GoalsController@addHabitType']);
	Route::get('goals/get-habit-type/{id}',['uses'=>'GoalsController@getHabbitLop']);
	Route::get('goals/get-task-loop/{id}',['uses'=>'GoalsController@getTaskTemplate']);
	Route::get('/goals/type',['uses'=>'DashboardController@getGoal']);
	Route::post('goals/show-in-lobby',['uses'=>'GoalsController@showAndHideInLobby']);


/*----------------------NEW ROUTES FOR PDF DOWNLOAD HERE 24 AUGUST 2019--------------------*/

	Route::any('getActiveSheet/{id}',['uses'=>'GoalsController@attributesAllActiveSheet']);

/*----------------------NEW ROUTES FOR TASK VALIDATION 18 APRIL 2019----------------------------*/

	Route::post('validate-task-template',['uses'=>'GoalsController@validateTaskTemplate']);

	/**************************Statement Values********************************/

	Route::post('statement-values/attr/get_sheet',["as"=>"statement-values_get_sheet", 'uses'=>'StatementValuesController@attribute_get_sheet',"active_menu"=>"goals"]);
	Route::post('statement-values/attr/delete_sheet',["as"=>"statement-values_delete_sheet", 'uses'=>'StatementValuesController@attribute_delete_sheet',"active_menu"=>"goals"]);
	Route::post('statement-values/attr/duplicate_sheet',["as"=>"statement-values_duplicate_sheet", 'uses'=>'StatementValuesController@attribute_duplicate_sheet',"active_menu"=>"goals"]);
	Route::post('statement-values/attr/rename_sheet',["as"=>"statement-values_rename_sheet", 'uses'=>'StatementValuesController@attribute_rename_sheet',"active_menu"=>"goals"]);
	Route::post('statement-values/save-statement-values',["as"=>"statement-values-save", 'uses'=>'StatementValuesController@save_statements_values',"active_menu"=>"goals"]);
	Route::get('statement-values/get-statements-values',["as"=>"get-statement-values", 'uses'=>'StatementValuesController@get_statements_values',"active_menu"=>"goals"]);
	
	Route::post('statement-values/save_statement',["as"=>"statement-save", 'uses'=>'StatementValuesController@save_statement',"active_menu"=>"statement-values"]);

	Route::get('statement-values',["as"=>"statement-values", 'uses'=>'StatementValuesController@index',"active_menu"=>"statement-values"]);

	Route::post('statement-values/addto_lobby',["as"=>"statement-addto-lobby", 'uses'=>'StatementValuesController@addto_lobby',"active_menu"=>"statement-values"]);


	
		/*-------------------New Routes Add Here 13/05/2019--------------------------------*/

		Route::get('statement-values/statement-view',['uses'=>'StatementValuesController@statement_view']);

		Route::get('statement-values/get-statements-values/byattr',["as"=>"get-statement-values", 'uses'=>'StatementValuesController@get_statements_valuesByAttr',"active_menu"=>"goals"]);
		Route::get('get-statements-values',["as"=>"get-statement-values", 'uses'=>'StatementValuesController@getStatementAndValues',"active_menu"=>"goals"]);


		/*******************NEW Logs 03/06/2019 *********************************/
		Route::post('log/dayAverage',['uses'=>'LogsController@getDay_scale']);


		/*--------------------------------------PERSONAL STATEMENT ROUTES HERE-----------------------------*/

	Route::post('statement-values/save-personal-statement-values',['uses'=>'StatementValuesController@save_personl_statements_values']);
	Route::post('statement-values/attr/personal-rename_sheet',['uses'=>'StatementValuesController@attribute_personal_rename_sheet']);
	Route::post('statement-values/attr/duplicate_personal-sheet',['uses'=>'StatementValuesController@attribute_duplicate_personal_sheet']);
	Route::get('statement-values/get-personal-statements-values',["as"=>"get-personal-statement-values", 'uses'=>'StatementValuesController@get_personal_statements_values',"active_menu"=>"goals"]);
	Route::post('personal-statement-values/attr/get_sheet',["as"=>"personal-statement-values_get_sheet", 'uses'=>'StatementValuesController@personal_statement_attribute_get_sheet',"active_menu"=>"goals"]);
	Route::post('statement-values/attr/delete_personal-sheet',['uses'=>'StatementValuesController@attribute_delete_personal_sheet']);


	/*******************Logs*********************************/
	Route::post('log/add',["as"=>"log_add", 'uses'=>'LogsController@add']);
	Route::post('log/delete',["as"=>"log_delete", 'uses'=>'LogsController@delete']);
	Route::get('log/list/{id}',["as"=>"log_list", 'uses'=>'LogsController@get']);

	/*******************NEW Logs 20/05/2019 *********************************/
	Route::post('log/monthlyAverage',['uses'=>'LogsController@getMonthly_averageToChangeMonth']);
	Route::post('log/weekAverage',['uses'=>'LogsController@getWeek_average']);

	/*******************Webinar*********************************/
	Route::post('webinar/create',["as"=>"webinar_submit", 'uses'=>'WebinarController@process_form',"active_menu"=>"webinar"]);
	Route::get('webinar/create',["as"=>"webinar_add", 'uses'=>'WebinarController@add',"active_menu"=>"webinar"]);
	Route::post('webinar/update',["as"=>"webinar_submit", 'uses'=>'WebinarController@process_form',"active_menu"=>"webinar"]);
	Route::get('webinar/update',["as"=>"webinar_update", 'uses'=>'WebinarController@update',"active_menu"=>"webinar"]);

	Route::get('webinar/delete/{id}',["as"=>"webinar_delete", 'uses'=>'WebinarController@delete',"active_menu"=>"webinar"]);
	Route::get('webinar/admin',["as"=>"webinar_admin", 'uses'=>'WebinarController@admin',"active_menu"=>"webinar"]);
	Route::get('webinar/autoregister/{id}',["as"=>"webinar_autoregister", 'uses'=>'WebinarController@autoregister',"active_menu"=>"webinar"]);
	Route::get('webinar/view/{id}',["as"=>"webinar_autoregister", 'uses'=>'WebinarController@view',"active_menu"=>"webinar"]);
	


	/*******************Workshop*********************************/
	Route::post('workshop/create',["as"=>"workshop_submit", 'uses'=>'WorkshopController@create',"active_menu"=>"workshop"]);
	Route::get('workshop/add',["as"=>"workshop_add", 'uses'=>'WorkshopController@add',"active_menu"=>"workshop"]);
	Route::get('workshop/delete/{id}',["as"=>"workshop_delete", 'uses'=>'WorkshopController@delete',"active_menu"=>"workshop"]);

	

});

});

Route::group(['middleware' => ['auth']], function () {
	Route::get('/logout', array('as' => 'logout', 'uses' => 'Auth\LoginController@logout'));
	Route::get('profile',["as"=>"profile",'uses'=>'ProfileController@index',"active_menu"=>"info"]);
	Route::get('assignment/list',['uses'=>'AssignmentController@index',"active_menu"=>"assignment"]);
	
	Route::get('assignment/default/list',['uses'=>'AssignmentController@list_default',"active_menu"=>"assignment"]);
	Route::get('list',["as"=>"goals_list","active_menu"=>"goals",'uses'=>'GoalsController@index']);


	/*******************Subscription & payement routes*********************************/
	Route::get('/subscription', array('as' => 'subscription','uses' => 'SubscriptionController@subscription'));
	Route::any('/payment', array('uses' => 'SubscriptionController@payment'));
	Route::post('/payment-success', array('uses' => 'SubscriptionController@payment_success'));
	Route::get('/payment-view', array('uses' => 'SubscriptionController@paymentView'));
	Route::post('/payment-failure', array('uses' => 'SubscriptionController@payment_failure'));
	Route::get('/failure-view', array('uses' => 'SubscriptionController@failureView'));
});

// Log Viewer Routes:
Route::get('/app-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

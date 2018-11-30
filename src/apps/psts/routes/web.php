<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
// $app->get('/api/test', function () use ($app) {
//     return config('settings.env');
// });

$app->post('/api/login','Auth\AuthController@login');
$app->get('/api/logout','Auth\AuthController@logout');

$app->group(['prefix' => 'api', 'middleware' => ['tokenauth']], function () use ($app) {   
    /** ****************************************************
    *  START PowerCoach route
    **************************************************** */
	$app->get('powercoach/mains', 'PowerCoachController@mainIndex');
	$app->get('powercoach/monitorings', 'PowerCoachController@monitoringIndex');

	$app->post('powercoach/main', 'PowerCoachController@createOrEdit');
	$app->post('powercoach/monitoring', 'PowerCoachController@storeOrUpdate');

	$app->get('powercoach/main/{id}', 'PowerCoachController@showMain');
	$app->get('powercoach/monitoring/{id}', 'PowerCoachController@showMonitoring');

	$app->put('powercoach/main/{id}', 'PowerCoachController@createOrEdit');
	$app->put('powercoach/monitoring/{id}', 'PowerCoachController@storeOrUpdate');

	$app->delete('powercoach/main/{id}', 'PowerCoachController@destroy');
	$app->delete('powercoach/monitoring/{id}', 'PowerCoachController@delete');
	
	/** ****************************************************
    *  START Skill Matrix route
    **************************************************** */
    $app->get('skills','SkillMatrixController@index');
    $app->get('skill/{id}','SkillMatrixController@show');
    // $app->put('accounts','UserController@update');
    // $app->put('accounts/password','UserController@update');
    // $app->put('accounts/reset/password', 'UserController@update');

    $app->get('schedules','ScheduleController@index');
    $app->post('schedule','ScheduleController@store');

    $app->get('dashboards','DashboardController@index');

	/** ****************************************************
    *  START User route
    **************************************************** */
    // Get current user info/same as in the authenticate response
    $app->get('account','UserController@loggedin');
    // Get current user as manager 
    $app->get('manager','ManagerController@show');
    // Get current manager not his agents
    $app->get('manager/{id}/agents','ManagerController@getAgents');
	// Get user list
    $app->get('users','UserController@index');
    // Get user's details
    $app->get('user/{id}', 'UserController@show');
    // Update user details
    $app->put('user/{id}', 'UserController@update');


    // Get rating types list
    $app->get('rating_type', 'RatingTypeController@index');
    // Get ratings list
    $app->get('ratings', 'RatingController@index');
    // Get types list
    $app->get('types', 'TypeController@index');

    // Get groups list
    $app->get('groups', 'GroupController@index');

    // Get group details
    $app->get('group/{id}', 'GroupController@show');

    /** ****************************************************
    *  START Medicine Cabinet route
    **************************************************** */
        $app->get('medicines','MedicineCabinetController@index');
        $app->post('medicine','MedicineCabinetController@store');
        $app->put('medicine/{id}','MedicineCabinetController@store');

        $app->get('departments','DepartmentController@index');
        $app->get('department/{id}','DepartmentController@show');
        
        $app->get('cabinets','CabinetController@index');
        $app->get('cabinet/{id}','CabinetController@show');
        
        $app->get('topics','TopicController@index');
        $app->get('topic/{id}','TopicController@show');

        $app->get('contents','ContentController@index');
        $app->get('content/{id}','ContentController@show');

});

    /** ****************************************************
    *  START Medicine Cabinet route for Sharepoint Online
    **************************************************** */
        $app->get('/api/mc/department/{id}','MedicineCabinetController@showDepartment');
        $app->get('/api/mc/cabinet/{id}','MedicineCabinetController@showCabinet');
        $app->get('/api/mc/topic/{id}','MedicineCabinetController@showTopic');
        $app->get('/api/mc/medicine/{id}','MedicineCabinetController@show');
    





    
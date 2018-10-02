<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

use App\MyCsv;
use App\Normalize;
use Illuminate\Support\Facades\Route;

$router->pattern('id', '[0-9]+');
$router->pattern('no', '[0-9]+');

Route::get('/', ['as' => 'root', 'uses' => 'openSearchController@index']);
Route::get('/get_year', 'openSearchController@get_year')->name('get_year');


Route::get('/signup', 'signupController@index')->name("signup");
Route::post('/signup/store', 'signupController@store')->name("signup.store");

Route::get('/welcome', ['uses' => function () {
    return redirect('/');
}
]);

Route::post('/login.verify', 'loginController@in')->name('login.verify');
Route::get('/logout', 'loginController@out')->name('logout');
Route::get('/forgot_password', 'loginController@fp_index')->name('forgot.password');
Route::post('/password/reset', 'loginController@fp_reset')->name('password.reset');
Route::get('/password/token/{token}', 'loginController@fp_reset_token')->name('password.token');
Route::get('/password/delete/{token}', 'loginController@fp_delete_token')->name('password.delete.token');
Route::post('/password/reset/submit', 'loginController@fp_reset_submit')->name('password.reset.submit');

Route::group(['prefix' => 'home', 'middleware' => 'home',"as"=>"home"], function () {
    //Route::auth();
    Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
    Route::get('/settings', 'HomeController@settings')->name('.settings');
    Route::get('/settings/oas', 'HomeController@settings_oas_index')->name('.settings.oas');
    Route::post('/settings/user/submit', 'HomeController@user_edit_submit')->name('.settings.user.submit');
    Route::post('/settings/password/submit', 'HomeController@user_password_submit')->name('.settings.password.submit');
    Route::group(['prefix' => '/faculty','as'=>'.faculty'], function () {
        Route::get('/', 'userController@index')->name('.index');
        Route::get('/create', 'userController@create')->name('.create');
        Route::post('/create/submit', 'userController@create_submit')->name('.create.submit');
        Route::get('/update/{id}', 'userController@update');
        Route::get('/update/submit', 'userController@update_submit');
        Route::get('/delete/{id}', 'userController@delete');
        Route::get('/delete/submit', 'userController@delete_submit');
    });
    Route::group(['prefix' => 'dept','as'=>".dept"], function () {
        Route::get('/', 'deptController@index')->name(".index");
        Route::get('/create', 'deptController@create')->name(".create");
        Route::post('/create/submit', 'deptController@create_submit')->name(".create.submit");
        Route::get('/update/{id}', 'deptController@update');
        Route::post('/update/submit', 'deptController@update_submit');
        Route::get('/delete/{id}', 'deptController@delete');
        Route::post('/delete/submit', 'deptController@delete_submit');
    });
    Route::group(['prefix' => '/class','as'=>'.class'], function () {
        Route::get('/', 'classController@index')->name('.index');
        Route::get('/{sub_code}', 'classController@subject')->name('.subject');
        Route::post('/submit', 'classController@subject_submit')->name('.subject.submit');
    });
    Route::group(['prefix' => '/long.term','as'=>'.long.term'], function () {
        Route::get('/', 'longtermController@index')->name('.index');
        Route::get('/view/{id}', 'longtermController@view')->name('.view');
        Route::get('/view/details/{id}', 'longtermController@view_details')->name('.view.details');
        Route::get('/create/{id}', 'longtermController@create');
        Route::post('/create/submit', 'longtermController@create_submit');
        Route::get('/update/{id}/{no}', 'longtermController@update');
        Route::post('/update/submit', 'longtermController@update_submit');
        Route::get('/delete/{id}/{no}', 'longtermController@delete');
        Route::post('/delete/submit', 'longtermController@delete_submit');
        Route::get('/normalize', 'longtermController@normalize');
        Route::post('/normalize/submit', 'longtermController@normalize_submit');
    });
    Route::group(['prefix' => '/myclass','as'=>'.myclass'], function () {
        Route::get('/', 'myclassController@index')->name('.index');
        Route::get('/update/{id}', 'myclassController@update')->name('.update');
        Route::post('/update/submit', 'myclassController@update_submit')->name('.update.submit');
        Route::get('/delete/{id}', 'myclassController@delete')->name('.delete');
        Route::post('/delete/submit', 'myclassController@delete_submit')->name('.delete.submit');
    });
    Route::group(['prefix' => '/batch' , 'as' => '.batch'], function () {
        Route::get('/', 'batchController@index')->name('.index');
        Route::get('/create', 'batchController@create');
        Route::post('/create/submit', 'batchController@create_submit');
        Route::get('/update/{id}', 'batchController@update');
        Route::post('/update/submit', 'batchController@update_submit');
        Route::get('/delete/{id}', 'batchController@delete');
        Route::post('/delete/submit', 'batchController@delete_submit');
        Route::get('/student/{id}/{code}', 'batchController@student_view')->name(".student.subject");
        Route::get('/student/{id}/{code}/longlist', 'batchController@student_longlistview');
        Route::get('/setting/index', 'batchController@setting_index')->name('settings.index');
    });
    Route::group(['prefix' => '/subject','as'=>'.subject'], function () {
        Route::get('/', 'subjectController@index')->name('.index');
        Route::get('/create', 'subjectController@create')->name('.create');
        Route::post('/create/submit', 'subjectController@create_submit');
        Route::get('/update/{code}', 'subjectController@update')->name('.update');
        Route::post('/update/submit', 'subjectController@update_submit');
        Route::get('/delete/{code}', 'subjectController@delete')->name('.delete');
        Route::post('/delete/submit', 'subjectController@delete_submit');
    });
    Route::group(['prefix' => '/student'], function () {
        Route::get('/', 'studentController@index');
        Route::get('/create', 'studentController@create');
        Route::post('/create/submit', 'studentController@create_submit');
        Route::get('/update/{id}', 'studentController@update');
        Route::post('/update/submit', 'studentController@update_submit');
        Route::get('/delete/{id}', 'studentController@delete');
        Route::post('/delete/submit', 'studentController@delete_submit');
    });
    Route::group(['prefix' => '/misc'], function () {
        Route::get('/', 'miscController@index');
        Route::post('/update/submit', 'miscController@update_submit');
    });
});

Route::get('/csv/{file}', function ($file) {
    $arr = MyCsv::csv_to_assocoative_array($file, True);
    return View::make('Csv', ['arr' => $arr, 'file_name' => $file]);
});

Route::any('/secret/pass', function () {
    Normalize::generate();
    return "Success";
}
);

Route::any('/secret/pass2', function () {
    Normalize::generate_class();
    return "Success";
}
);

Route::any('/app', function () {
    return view("error", ["msg" => "testing Hoche!!"]);
}
);
/*
Route::any('/get_csrf_token/{ver}', function($ver) {
	return ($ver == "5.5")?csrf_token():null;
}
);*/

Route::get('/app/login.verify', 'appController@app_in');
Route::get('/app/login.verify/{email}/{pass}', 'appController@app_login');
Route::get('/app/get_data_to_sync', 'appController@app_sync');
Route::get('/app/post_attn_data', 'appController@app_submit');


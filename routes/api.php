<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'auth'], function($router){
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['middleware' => 'jwt.auth'], function ($router) {
    Route::get('/files', 'EmailsController@getFiles');
    Route::get('/files/delete/{id}', 'EmailsController@deleteFile');
    Route::post('/files/add', 'EmailsController@uploadFile');
    Route::get('/files/emails/{id}', 'EmailsController@getFileEmails');

    Route::get('/campaigns', 'CampaignsController@getCampaigns');
    Route::get('/campaigns/delete/{id}', 'CampaignsController@deleteCampaign');
    Route::post('/campaigns/add', 'CampaignsController@addCampaign');
    Route::post('/campaigns/files/add', 'CampaignsController@addFile');
    Route::post('/campaigns/export/audience', 'CampaignsController@exportAudience');
    Route::post('/campaigns/status', 'CampaignsController@updateStatus');

    Route::get('/countries', 'CampaignsController@getCountries');
    Route::post('/countries/add', 'CampaignsController@addCountry');
    Route::get('/countries/delete/{id}', 'CampaignsController@deleteCountry');

    Route::get('/visitors/{view}/{id}', 'CampaignsController@getVisitors');
    Route::get('/stats/{id}', 'CampaignsController@getStats');

    Route::get('/settings', 'SettingsController@getSettings');
    Route::post('/settings/token', 'SettingsController@updateToken');
    Route::post('/settings/password', 'SettingsController@updatePassword');
});

Route::get('/pvt_fl/exprt_vis/{id}', 'EmailsController@exportVisitors');
Route::get('/pvt_c/exprt_md/{mode}/{id}', 'CampaignsController@exportMode');
Route::get('/set/stats/countries', 'CampaignsController@setCountries');
Route::get('/set/stats/refresh', 'CampaignsController@refreshStats');
Route::get('/set/stats/isps', 'CampaignsController@setIsps');
Route::get('/set/stats/os', 'CampaignsController@setOs');
Route::get('/set/stats/visitors', 'CampaignsController@setVisitors');
Route::get('/set/stats/openers', 'CampaignsController@setOpeners');
Route::get('/set/stats/clickers', 'CampaignsController@setClickers');

Route::post('/c_pvt_trck/c_c_em', 'CampaignsController@saveClick');
Route::post('/c_pvt_trck/c_o_em', 'CampaignsController@saveOpen');

Route::post('/create', 'SettingsController@createUser');
Route::get('/check', 'SettingsController@checkUser');



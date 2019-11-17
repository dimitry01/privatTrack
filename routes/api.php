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
//    Route::get('/campaigns/export/opens/{id}', 'CampaignsController@exportOpens');
//    Route::get('/campaigns/export/noopens/{id}', 'CampaignsController@exportNoOpens');
//    Route::get('/campaigns/export/clicks/{id}', 'CampaignsController@exportClicks');
    Route::post('/campaigns/export/audience', 'CampaignsController@exportAudience');

    Route::get('/countries', 'CampaignsController@getCountries');
    Route::post('/countries/add', 'CampaignsController@addCountry');
    Route::get('/countries/delete/{id}', 'CampaignsController@deleteCountry');

    Route::get('/visitors/{view}/{id}', 'CampaignsController@getVisitors');
    Route::get('/stats/{id}', 'CampaignsController@getStats');

    Route::get('/settings', 'SettingsController@getSettings');
    Route::post('/settings/token', 'SettingsController@updateToken');
});

Route::get('/files/export/{id}', 'EmailsController@exportVisitors');
Route::get('/campaigns/export/{mode}/{id}', 'CampaignsController@exportMode');
Route::get('/set/stats/countries', 'CampaignsController@setCountries');
Route::get('/set/stats/isps', 'CampaignsController@setIsps');
Route::get('/set/stats/os', 'CampaignsController@setOs');
Route::get('/set/stats/visitors', 'CampaignsController@setVisitors');
Route::get('/set/stats/openers', 'CampaignsController@setOpeners');
Route::get('/set/stats/clickers', 'CampaignsController@setClickers');

Route::post('/campaigns/click', 'CampaignsController@saveClick');
Route::post('/campaigns/open', 'CampaignsController@saveOpen');

Route::post('/create', 'SettingsController@createUser');
Route::get('/check', 'SettingsController@checkUser');



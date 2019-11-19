<?php
//Route::get('/user', 'CampaignsController@create');

Route::get('{any}', function () {
    return view('spa');
})->where('any','.*');


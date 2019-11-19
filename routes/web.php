<?php
//Route::get('/user', 'CampaignsController@create');

Route::get('/retry_jobs', function() {
    Artisan::call('queue:retry');
    return "Failed jobs restarted";
});

Route::get('{any}', function () {
    return view('spa');
})->where('any','.*');


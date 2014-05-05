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

Route::get('/', function()
{

	$entries = DB::table('entries')
	->select('liked_date as date', 'url', 'title', 'description', 'image')
    ->orderBy('date', 'desc')
    ->get();

    return $entries;

});

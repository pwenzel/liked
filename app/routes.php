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

	// example from http://stackoverflow.com/questions/20146938/laravel-group-data-by-datecreated-at-with-pagination
	// $days = Pic::select(DB::raw('DATE(created_at) as datum'))
	//     ->distinct()
	//     ->orderBy('datum','desc')
	//     ->paginate(5);

	// $lastWeek = Carbon::now()->subWeek();
	$entries = Entry::orderBy('liked_date', 'DESC')->paginate(10);
	// $entries = Entry::where('liked_date' > $lastWeek);
	// return $entries;

    return View::make('entries', compact('entries'));

});

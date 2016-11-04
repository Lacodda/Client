<?php

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | This file is where you may define all of the routes that are handled
    | by your application. Just tell Laravel the URIs it should respond
    | to using a Closure or controller method. Build something great!
    |
    */

    //Route::get('/', function () {
    //    return view('welcome');
    //});
    Route::get (
        '/',
        function ()
        {
            return view ('index');
        }
    );
    Route::get ('update', 'UpdateController@update');
    Route::get ('{alias}', ['as' => 'client', 'uses' => 'ClientsController@index']);
    Route::get ('{alias}/act/{id}', ['as' => 'act', 'uses' => 'ClientsController@getAct'])->where ('id', '[0-9]+');
    Route::get ('{alias}/act_stamp/{id}', ['as' => 'act_stamp', 'uses' => 'ClientsController@getActStamp'])->where ('id', '[0-9]+');
    Route::get ('{alias}/invoice/{id}', ['as' => 'invoice', 'uses' => 'ClientsController@getInvoice'])->where ('id', '[0-9]+');
    Route::get ('{alias}/invoice_stamp/{id}', ['as' => 'invoice_stamp', 'uses' => 'ClientsController@getInvoiceStamp'])->where ('id', '[0-9]+');
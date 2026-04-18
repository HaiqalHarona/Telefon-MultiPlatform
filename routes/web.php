<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $dbOk = false;
    $dbName = '';
    $dbError = '';

    try {
        $connection = DB::connection('mongodb');
        $connection->command(['ping' => 1]);
        $dbOk = true;
        $dbName = config('database.connections.mongodb.database');
    } catch (\Throwable $e) {
        $dbError = $e->getMessage();
    }

    return view('welcome', compact('dbOk', 'dbName', 'dbError'));
});

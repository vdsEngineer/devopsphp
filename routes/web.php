<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/s3', function () {
    Storage::disk('s3')->put('test.txt', 'Hello, S3!');

    return 'File uploaded to S3!';
});

Route::get('/queue', function () {
    dispatch(new \App\Jobs\ProcessBigData);

    return 'Job dispatched!';
});

Route::get('/check-log', function (): string {
    logger()->error('Hi from Laravel!');

    return 'Check your logs!';
});

Route::get('health', HealthCheckResultsController::class);

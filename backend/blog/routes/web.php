<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', function () {
    $result = [];

    $result['app'] = 'Laravel is running';

    // Mail
    try {
        Mail::raw('Health check email', function ($message) {
            $message->to('test@example.com')
                ->subject('Health Check');
        });

        $result['mail'] = 'Sent (Check MailHog)';
    } catch (\Exception $e) {
        $result['mail'] = 'Fail: ' . $e->getMessage();
    }


    return response()->json([
        'status'   => 'Health check completed',
        'services' => $result
    ]);
});

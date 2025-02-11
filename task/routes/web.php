<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return \App\Models\User::where('email', 'test@example.com')->first();
});

<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/login', function () {
    return response()->json('ok');
})->name('login');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request, $id, $hash) {
    $request->fulfill();

    if (!$request->user()->email_verified_at) {
        $request->user()->update(['email_verified_at' => now()]);
    }

    return response()->json('Email verificado com sucesso');
})->middleware(['auth', 'signed'])->name('verification.verify');


Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

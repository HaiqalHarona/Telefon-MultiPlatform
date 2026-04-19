<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\socialController;

Volt::route('/', 'auth')->middleware('guest')->name('auth');

//Socialite Routes
Route::get('/auth/{provider}/redirect', [socialController::class, 'RedirectToProvider'])->where('provider', 'google|github')->name('social.redirect');
Route::get('/auth/{provider}/callback', [socialController::class, 'ProviderCallback'])->where('provider', 'google|github')->name('social.callback');
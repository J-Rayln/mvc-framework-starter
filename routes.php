<?php

use JonathanRayln\UdemyClone\Controllers\AuthController;
use JonathanRayln\UdemyClone\Controllers\SiteController;
use JonathanRayln\UdemyClone\Routing\Exceptions\ExceptionViewer;
use JonathanRayln\UdemyClone\Routing\Route;

Route::get('/', [SiteController::class, 'index']);

Route::get('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'login']);


Route::get('/404', [ExceptionViewer::class, 'pageNotFound']);


// Example Routes:
Route::get('/about', [SiteController::class, 'about']);
// Route::get('/params', [SiteController::class, 'params']);
// Route::get('/params/{param}/{param2:\d+}', [SiteController::class, 'params']);
<?php

use Illuminate\Support\Facades\Route;
use Waxwink\DocGen\Http\Controller\DocumentationController;


Route::get('/responses', DocumentationController::class . '@responses');
Route::get('/routes', DocumentationController::class . '@routes');

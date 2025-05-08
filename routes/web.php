<?php

namespace SkyWebDev\routes;


use Illuminate\Support\Facades\Route;
use SkyWebDev\DbMail\Controllers\BladeTemplateController;

Route::apiResource('blade-templates', BladeTemplateController::class)->middleware(['web', 'auth']);

<?php

namespace App\Console;

use App\Http\Middleware\CheckRole;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel
{
protected $routeMiddleware = [
    // ... middleware lain
    'role' => CheckRole::class,
];

}

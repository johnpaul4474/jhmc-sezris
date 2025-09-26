<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocatorController extends Controller
{
    public function index(){
        return Inertia::render('Locator/Index', []);
    }
}

<?php

namespace App\Http\Controllers\SERVICEPROVIDER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocatorServiceProviderController extends Controller
{
     public function index(){
        return Inertia::render('ServiceProvider/Index',[]);
     }
}

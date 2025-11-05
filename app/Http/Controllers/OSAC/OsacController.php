<?php
namespace App\Http\Controllers\OSAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OsacController extends Controller
{
   public function index()
   {
      return Inertia::render('OSAC/Index');
   }
}

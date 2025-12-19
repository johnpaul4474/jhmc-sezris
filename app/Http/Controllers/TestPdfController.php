<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TestPdfController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Hello PDF',
            'message' => 'This PDF is generated in Laravel 12 (Laravel 11).'
        ];

        $pdf = Pdf::loadView('pdf.test', $data);

        //return $pdf->download('test.pdf');
         return $pdf->stream('test.pdf');
    }
}

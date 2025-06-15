<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application's landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ganti 'welcome' dengan nama view landing page-mu jika berbeda
        return view('welcome');
    }
}
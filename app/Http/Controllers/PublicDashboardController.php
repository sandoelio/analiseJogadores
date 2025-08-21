<?php

namespace App\Http\Controllers;

class PublicDashboardController extends Controller
{
    public function index()
    {
        return view('public.dashboard');
    }
}

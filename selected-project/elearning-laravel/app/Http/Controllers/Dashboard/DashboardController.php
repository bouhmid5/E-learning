<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function general(): View
    {
        return view('dashboard.general');
    }

    public function candidate(): View
    {
        return view('dashboard.candidate');
    }

    public function trainer(): View
    {
        return view('dashboard.trainer');
    }

    public function admin(): View
    {
        return view('dashboard.admin');
    }
}


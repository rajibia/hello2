<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DutyRosterController extends Controller
{
    public function index()
    {
        return view('duty_roster.index');
    }

}

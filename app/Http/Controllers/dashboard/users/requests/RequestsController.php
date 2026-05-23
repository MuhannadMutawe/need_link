<?php

namespace App\Http\Controllers\dashboard\users\requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestsController extends Controller
{
    public function index()
    {
        return view('dashboard.users.requests.request');
    }
}

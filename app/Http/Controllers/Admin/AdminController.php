<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    function index(): Response
    {
        return response()->view('admin.content.dashboard');
    }
}

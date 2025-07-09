<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $data=[
            'page' =>[
                'title'=> 'dashboard',
                'name'=> 'Dashboard'
            ]
        ];
        return view('dashboard.admin.panel', $data);
    }
}

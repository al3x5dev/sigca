<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $data=[
            'page' =>[
                'title'=> 'admin',
                'name'=> 'Panel de Administración'
            ]
        ];
        return view('dashboard.admin.home', $data);
    }
}

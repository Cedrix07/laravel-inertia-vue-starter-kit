<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){

        $users = User::with('listings')->paginate(10);

        return inertia('Admin/AdminDashboard',[
            'users' => $users
        ]);
    }
}

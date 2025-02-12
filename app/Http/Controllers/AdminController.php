<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){

        $users = User::with('listings')
            ->filter(request(['search', 'user_role'])) //scope filter from user model
            ->paginate(10)
            ->withQueryString();

        return inertia('Admin/AdminDashboard',[
            'users' => $users,
            'status' => session('status')
        ]);
    }

    public function role(Request $request, User $user){
        $request->validate([
            'role' => ['required', 'string']
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->route('admin.index')->with('status', "User role changed to {$request->role} successfully");
    }
}

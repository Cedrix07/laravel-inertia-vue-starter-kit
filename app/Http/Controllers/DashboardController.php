<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index(Request $request) {
        // grab user's listings
        $listings = $request->user()->role !== 'suspended' ? $request->user()->listings()->paginate(2) : null;
        return inertia('Dashboard', [
            "listings" => $listings,
            "status" => session('status')
        ]);
    }
}

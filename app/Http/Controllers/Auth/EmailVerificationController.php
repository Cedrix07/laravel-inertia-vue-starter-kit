<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    //
    public function notice(){
        // returning view and session message status
        return inertia('Auth/VerifyEmail',[
            'status' => session('status')
        ]);
    }

    public function handler (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('home');
    }

    public function resend (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    }
}

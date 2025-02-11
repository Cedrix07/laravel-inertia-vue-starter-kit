<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\support\Facades\Auth;
use Redirect;

class ProfileController extends Controller
{
    //
    public function edit(Request $request){
        return inertia('Profile/Edit',[
            'user' => $request->user(),
            'status' => session('status')
        ]);
    }

    public function updateInfo(Request $request){
        // Validate the request
       $fields = $request->validate([
           'name' => ['required', 'max:255'],
           'email' => ['required', 'max:255', 'email',
            Rule::unique(User::class)->ignore($request->user()->id)
        ],
       ]);

       // Fill the authenticated user's attributes with the validated input fields (name and email)
       $request->user()->fill($fields);

       // If the authenticated user's email has changed univerify it
       if($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
       }

       // Save the changes
       $request->user()->save();

       return redirect()->route('profile.edit');
    }


    public function updatePassword(Request $request)
    {
        $fields = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:3'] // new password field
        ]);

        $request->user()->update([
            'password' => Hash::make($fields['password'])
        ]);

        return redirect()->route('profile.edit');
    }

    public function destroy(Request $request){

        //validate the password
       $request->validate([
        "password" => ["required", "current_password"]
       ]);

       // Get the authenticated user
       $user = $request->user();

       // Logout the user
       Auth::logout();

       // Delete user from the database
       $user->delete();

       // Invalidate the session 
       $request->session()->invalidate();
       $request->session()->regenerateToken();

       return redirect()->route('home');
    }

}

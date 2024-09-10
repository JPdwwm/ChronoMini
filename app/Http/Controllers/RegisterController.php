<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;




class RegisterController extends Controller
{
    public function register(Request $request )
    {
        $formFields = $request->validate([
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => ['required', 'confirmed', Password::default()],
            'role_id' => 'required|integer'
            
        ]);

        $userFound = DB::table('users')->where('email', $formFields['email']);
        if($userFound->count() > 0) {
            return response()->json(['user' => null, 401]);
        }
        $user = new User();
        $user->fill($formFields);
        $user->email_verified_at = now();
        $user->save();
        return response()->json($user);
    }
}

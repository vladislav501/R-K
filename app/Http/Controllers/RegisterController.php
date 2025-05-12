<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller {
    
    public function index() {
        return view('register');
    }

    public function registerCreate(Request $request) {
        User::create($request->only(['email', 'password', 'name']));
        return redirect()->route('login.index');
    }
}

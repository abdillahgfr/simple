<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    public function index()
    {
        $user = session('user');
        if (!$user) {
            return redirect()->route('login')->withErrors(['login_error' => 'Please log in first.']);
        }
        
        return view('Frontend.Profile' , compact('user'));
    }

}
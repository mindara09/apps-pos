<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\LogController;
use Auth;

class LoginController extends Controller
{
    // index login
	public function index()
	{
		// show page login
		return view('sign-in');
	}

	// process login
	public function process(Request $request)
	{
		// prcess login
		if(Auth::attempt($request->only('username','password')))
		{
			LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has login');
			return redirect('/users');
		}

		LogController::log(0, 'User '.$request->username.' has login');
		return redirect('/login')->with([
			'message' => 'Username and Password wrong, please try again'
		]);
	}

	// logout
	public function logout()
	{
		LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has logout');
		Auth::logout();
		return redirect('/login');
	}
}

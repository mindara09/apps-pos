<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogUsers;

class LogController extends Controller
{
    // insert log users
    public static function log($user_id, $action)
    {
    	LogUsers::create([
            'user_id' => $user_id,
            'action' => $action
        ]);
    }

}

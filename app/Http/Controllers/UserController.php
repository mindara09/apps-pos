<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LogUsers;
use App\Http\Controllers\LogController;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get data all users
        $users = User::all();
        $logs = LogUsers::latest()->paginate(6);

        return view('dashboard.user-managements.index', compact('users','logs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // store data users
        $this->validate($request, [
            'role_user' => 'required',
            'name' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $post = User::create([
            'role_user' => $request->role_user,
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has add user');

        if ($post) {
            return redirect()
                ->route('users.index')
                ->with([
                    'success' => 'Item has been created successfully'
                ]);
        } else {
            return redirect()
                ->route('users.index')
                ->with([
                    'error' => 'Some problem occurred, please try again'
                ]);
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // update user account
        $this->validate($request, [
            'role_user' => 'required',
            'name' => 'required',
            'username' => 'required'
        ]);

        if ($request->password) {

            $this->validate($request, [
                'role_user' => 'required',
                'name' => 'required',
                'username' => 'required',
                'password' => 'string',
                'confirm_password' => 'same:password'
            ]);
            $post = User::findOrFail($id);
        
            $post->update([
                'role_user' => $request->role_user,
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password)
            ]);

            LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has update password user');

            if ($post) {
                return redirect()
                    ->route('users.index')
                    ->with([
                        'success' => 'Item has been created successfully'
                    ]);
            } else {
                return redirect()
                    ->route('users.index')
                    ->with([
                        'error' => 'Some problem occurred, please try again'
                    ]);
            }
        }

        $post = User::findOrFail($id);
        
        $post->update([
            'role_user' => $request->role_user,
            'name' => $request->name,
            'username' => $request->username
        ]);

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has update username/name/role user');

        if ($post) {
            return redirect()
                ->route('users.index')
                ->with([
                    'success' => 'Item has been created successfully'
                ]);
        } else {
            return redirect()
                ->route('users.index')
                ->with([
                    'error' => 'Some problem occurred, please try again'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete users
        $delete = User::findOrFail($id);
        $delete->delete();

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has delete user');

        return redirect()
                ->route('users.index')
                ->with([
                    'success' => 'User has been delete successfully'
                ]);
    }

    public function update_user(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required'
        ]);

        if ($request->password) {

            $this->validate($request, [
                'name' => 'required',
                'username' => 'required',
                'password' => 'string',
                'confirm_password' => 'same:password'
            ]);

            if($request->password != $request->confirm_password)
            {

                LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has update password user but failed');

                return redirect()
                    ->route('users.index')
                    ->with([
                        'error' => 'Password and Confirm Password not match, please try again'
                    ]);
            }

            $post = User::findOrFail(auth()->user()->id);
        
            $post->update([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password)
            ]);

            LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has update username/name/password user');

            if ($post) {
                return redirect()
                    ->route('users.index')
                    ->with([
                        'success' => 'Item has been created successfully'
                    ]);
            } else {
                return redirect()
                    ->route('users.index')
                    ->with([
                        'failed' => 'Some problem occurred, please try again'
                    ]);
            }
        }

        $post = User::findOrFail(auth()->user()->id);
        
        $post->update([
            'name' => $request->name,
            'username' => $request->username
        ]);

        LogController::log(auth()->user()->id, 'User '.auth()->user()->username.' has update username/user');

        if ($post) {
            return redirect()
                ->route('users.index')
                ->with([
                    'success' => 'Item has been created successfully'
                ]);
        } else {
            return redirect()
                ->route('users.index')
                ->with([
                    'failed' => 'Some problem occurred, please try again'
                ]);
        }
    }
}

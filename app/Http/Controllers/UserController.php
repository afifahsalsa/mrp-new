<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', [
            'title' => 'Index User',
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->name === null || $request->email === null || $request->password === null || $request->role === null){
            return back()->with([
                'swal' => [
                    'type' => 'error',
                    'title' => 'Isi semua field terlebih dahulu!'
                ]
            ]);
        }
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);

        $temp = explode(' ', $request->name);
        $user_code = '';
        foreach ($temp as $t) {
            $user_code .= substr($t, 0, 1);
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pw' => $request->password,
            'user_code' => $user_code,
            'role' => $request->role
        ]);

        return back()->with([
            'swal' => [
                'type' => 'success',
                'title' => 'User baru berhasil ditambahkan!'
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        $temp = explode(' ', $request->name);
        $user_code = '';
        foreach ($temp as $t) {
            $user_code .= substr($t, 0, 1);
        }

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pw' => $request->password,
            'user_code' => $user_code,
            'role' => $request->role
        ]);
        return back()->with([
            'swal' => [
                'type' => 'success',
                'title' => 'Berhasil update data user!'
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with([
            'swal' => [
                'type' => 'success',
                'title' => 'User berhasil dihapus!'
            ]
        ]);
    }
}

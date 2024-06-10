<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Auth::user();
        // return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']); // Encrypt the password
        return User::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         return Auth::user();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'password']));
        
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function search($name)
    {
        $user = Auth::user();
        return User::where('id', $user->id)
                    ->where(function($query) use ($name) {
                        $query->where('name', 'like', '%'.$name.'%')
                              ->orWhere('email', 'like', '%'.$name.'%');
                    })
                    ->get();
    }
}

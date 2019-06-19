<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showAllUsers()
    {
        return response()->json(User::all());
    }

    public function showOneUser($id)
    {

        return response()->json(User::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:6',
            'role'=>'required',
        ]);


        $user = new User;
        $user->name= $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password= Hash::make($request->password);

        $user->save();

        return response()->json($user, 201);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        if(Auth::id()==$id){
            $user->update($request->all());
        }else {
            return response()->json("not allowed, you can only update your own account.", 403);
        }


        return response()->json($user, 200);
    }

    public function delete($id)
    {
        if(Auth::id()==$id){
            User::findOrFail($id)->delete();
            return response('Deleted Successfully', 204);
        }else {
            return response()->json("not allowed, you can only delete your own account.", 403);
        }

    }
}

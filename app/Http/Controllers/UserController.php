<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function create(Request $request)
    {
        $rules = [
            'username' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|min:6|confirmed',
        ];
        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $request['password'] = bcrypt($request->password);

        $user = User::create($request->all());

        return response()->json($user);
    }
}

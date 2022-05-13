<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DownloadLog;
use Illuminate\Http\Request;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        //validar que los datos son correctos
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            "status" => "success",
            "msg" => "Register completed",
        ], 201); 
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where("email", "=", $request->email)->first();

        if(isset($user->id)) {
            if (Hash::check($request->password, $user->password)){
                $token = $user->createToken("auth_token")->plainTextToken;
                
                return response()->json([
                    "status" => 1,
                    "msg" => "User loged in",
                    "acces_token" => $token
                ], 201);

            } else {
                return response()->json([
                    "status" => 0,
                    "msg" => "Email or password not correct"
                ], 404);
            }
        } else {
            return response()->json([
                "status" => 0,
                "msg" => "Email or password not correct"
            ], 404);
        }
    }

    public function userProfile()
    {
        return response()->json([
            "status" => 0,
            "msg" => "About this user:",
            "data" => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => 1,
            "msg" => "Logout done",
        ], 201);
    }

    public function kills(Request $request)
    {
        return response()->json([
            "status" => 1,
            "msg" => "+1 kill",
            "data" => $request->email,
        ], 201);
        $user = User::where("email", "=", $request->email)->first();
        //$user->update(['kills' => $user->kills + 1]);

        return response()->json([
            "status" => 1,
            "msg" => "+1 kill",
            "data" => $user->kills,
        ], 201);
    }

    /*
    public function download(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $download = new DownloadLog();
        $date = Carbon::now();
        $download->email = $request->email;
        $download->date = $date;

        $download->save();

        return response()->json([
            "msg" => $download
        ], 201);
    }
    */
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::byTerm($request->term)->paginateOrNot($request->paginate, $request->per_page);
        return response()->json($users);
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
        try {
            $emailExists=User::where("email",$request->email)->get();
            if(count($emailExists)){
                return response()->json(["message"=>"El email esta registrado","success"=>false],400);
            }
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->status = "active";
            $user->save();
            return response()->json(["success" => true, "message" => "User created successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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
        try {
            $user = User::find($id);
            return response()->json(["success" => true, "data" => $user]);
        } catch (Exception $e) {
            return response()->json(["success" => false, 'message' => $e->getMessage()], 500);
        }
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
        try {
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            if (isset($request->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->role = $request->role;
            $user->save();
            return response()->json(["success" => true, "message" => "User created successfully"]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
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
        //
    }

    /**
     * @param Request 
     * @return  \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $user = $request->email;
            $pass = $request->password;
            $user = User::where('email', $user)->first();
            if ($user) {
                if (Hash::check($pass, $user->password)) {
                    if (Auth::attempt(["email" => $user->email, "password" => $pass])) {
                        $token =  $user->createToken('app')->accessToken;
                        return response()->json(['success' => true, 'user' => $user, "token" => $token], 200);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Contraseña incorrecta']);
                }
            }
            return response()->json(['status' => 'error', 'message' => 'Usuario no existe']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), "success" => false], 500);
        }
    }

    /**
     * @param Request 
     * @return  \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json(['success' => true, 'message' => 'Sesión cerrada']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), "success" => false], 500);
        }
    }

    /**
     * @param Request 
     * @return  \Illuminate\Http\Response
     */

    public function userAutenticated(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json(['success' => true, 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), "success" => false], 500);
        }
    }
}

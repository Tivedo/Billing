<?php

namespace App\Http\Controllers\Api;
use App\Models\Customer;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Exceptions\JWTException;

class CustomerController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Menyimpan data pelanggan
            Customer::create([
                'nama' => $request->nama,
                'npwp' => $request->npwp,
                'email' => $request->email,
                'username' => $request->username,
                'password' => password_hash($request->password, PASSWORD_BCRYPT),
                'telp' => $request->telepon,
            ]);
    
            return response()->json([
                'message' => 'Register Success',
            ], 201);
    
        } catch (\Exception $e) {
            // Penanganan error
            return response()->json([
                'message' => 'Register Failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function login()
    {
        return response()->json([
            'message' => 'Login Success'
        ]);
    }
    public function store()
    {
        return response()->json([
            'message' => 'Store Success'
        ]);
    }
    public function loginPost(Request $request)
    {
        $customer = Customer::where('username', $request->username)->first();
        if ($customer) {
            if (password_verify($request->password, $customer->password)) {
                try {
                    $token = JWTAuth::attempt($request->only('username', 'password'));
                    Session::put('jwt_token', $token);
                    return response()->json([
                        'message' => 'Login Success',
                        'token' => $token,
                    ]);
                } catch (JWTException $e) {
                    return response()->json(['message' => 'Could not create token'], 500);
                }
            }else{
                return response()->json(['message' => 'Username atau password salah'], 401);
            }
        }else{
            return response()->json(['message' => 'Username atau password salah'], 401);
        }
    }
}
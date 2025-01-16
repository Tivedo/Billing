<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Session;
use Tymon\JWTAuth\Exceptions\JWTException;

class CustomerController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        customer::create([
            'nama' => $request->nama,
            'npwp' => $request->npwp,
            'email' => $request->email,
            'username' => $request->username,
            'password' => password_hash($request->password, PASSWORD_DEFAULT),
            'telp' => $request->telepon,
        ]);

        return redirect()->route('login')->with('success', 'Data berhasil disimpan');
    }
    public function login()
    {
        return view('login');
    }
    public function loginPost(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
    
        $customer = Customer::where('username', $request->username)->first();
        if ($customer) {
            if (password_verify($request->password, $customer->password)) {
                // Generate JWT token
                try {
                    $token = JWTAuth::fromUser($customer);
                    Session::put('jwt_token', $token);
                    return redirect()->route('produk');
                } catch (JWTException $e) {
                    return response()->json(['error' => 'Could not create token'], 500);
                }
            }else{
                return response()->json(['error' => 'Username atau password salah'], 401);
            }
        }
    
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\CustomerController as ApiCustomerController;
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
        $request->validate([
            'nama' => 'required',
            'npwp' => 'required',
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',
            'telepon' => 'required',
        ]);
        $response = (new ApiCustomerController())->register($request);
        $responseData = json_decode($response->getContent(), true);
        if ($responseData['message'] == 'Register Success') {
            return redirect()->route('login')->with('success', 'Registrasi berhasil');
        } else {
            return redirect()->route('register')->with('error', $responseData['message'] ?? 'Registrasi gagal');
        }
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
    
        $response = (new ApiCustomerController())->loginPost($request);
        $responseData = json_decode($response->getContent(), true);
        if ($responseData['message'] == 'Login Success') {
            return redirect()->route('billing')->with('success', 'Login berhasil');
        } else {
            return redirect()->route('login')->with('error', $responseData['message'] ?? 'Login gagal');
        }
    
    }
}

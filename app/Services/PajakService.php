<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PajakService
{
    protected $url;
    protected $authToken;
    protected $email;
    protected $password;

    public function __construct()
    {
        $this->url        = env('API_URL_PAJAK');
        $this->authToken  = env('API_X_TOKEN_PAJAK');
        $this->email      = env('EMAIL_PAJAK');
        $this->password   = env('PASSWORD_PAJAK');
    }

    public function login()
    {
        //set limit
        set_time_limit(3600); // 1 hour
        try {
            $response = Http::asForm()
                ->withOptions([
                    'verify' => false,
                    'timeout' => 120,
                    'connect_timeout' => 60,
                ])
                ->withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => '*/*',
                ])
                ->post($this->url . 'auth/login', [
                    'email' => $this->email,
                    'password' => $this->password,
                ]);

            Log::info("Response Token Pajak: " . json_encode($response->json()));

            return $response->json()['data']['token'] ?? null;
        } catch (\Exception $e) {
            Log::error("Login Pajak Error: " . $e->getMessage());
            return null;
        }
    }

    public function validateNpwp(string $token, string $npwp): array
    {
        set_time_limit(3600); // 1 hour
        try {
            $response = Http::asForm()
                ->withOptions([
                    'verify' => false,
                    'timeout' => 120,
                    'connect_timeout' => 60,
                ])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => '*/*',
                    'x-token' => $this->authToken,
                ])
                ->post($this->url . 'IF_CLB_059', [
                    'tujuan' => 'validasi NPWP',
                    'npwp' => $npwp,
                ]);

            Log::info("Response VSWP: " . json_encode($response->json()));

            return [
                'nama' => $response->json()['data']['nama'] ?? null,
                'alamat' => $response->json()['data']['alamat'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error("VSWP Validation Error: " . $e->getMessage());
            return [
                'nama' => null,
                'alamat' => null,
            ];
        }
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}

<?php

namespace Yigit\Larasut\Services;

use Illuminate\Support\Facades\Http;
use Yigit\Larasut\Services\MainService;


class AuthService
{
    protected $access_token, $refresh_token;
    protected $urls;

    public function __construct(private MainService $mainService)
    {
        $this->urls = $mainService->init();
    }


    private function setAccessToken($token)
    {
        $this->access_token = $token;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }

    private function setRefreshToken($token)
    {
        $this->refresh_token = $token;
    }

    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    public function doAuth()
    {
        $parameters = array(
            'grant_type' => 'password',
            'client_id' => env('LARASUT_CLIENT_ID'),
            'client_secret' => env('LARASUT_CLIENT_SECRET'),
            'redirect_uri' => env('LARAVEL_REDIRECT_URI'),
            'username' => env('LARASUT_USERNAME'),
            'password' => env('LARASUT_PASSWORD')
        );

        $response = Http::withBody(json_encode($parameters), 'application/json')
            ->post($this->urls->auth_url);

        $this->setAccessToken($response->access_token);
        $this->setRefreshToken($response->refresh_token);

        return $response;
    }

    public function refreshToken()
    {
        $parameters = array(
            'grant_type' => 'refresh_token',
            'client_id' => env('LARASUT_CLIENT_ID'),
            'client_secret' => env('LARASUT_CLIENT_SECRET'),
            'refresh_token' => $this->refresh_token
        );

        $response = Http::withBody(json_encode($parameters), 'application/json')
            ->post($this->urls->token_url);

        $this->setAccessToken($response->access_token);
        $this->setRefreshToken($response->refresh_token);
    }
}

<?php

namespace Yigit\Larasut\Services;

class MainService
{
    public function init()
    {
        return (object)[
            "token_url" => "https://api.parasut.com/oauth/token",
            "auth_url" => "https://api.parasut.com/oauth/authorize",
            "me" => "https://api.parasut.com/v4/me"
        ];
    }
}

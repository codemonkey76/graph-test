<?php

namespace App\Services;

use App\Models\Server;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class FusionApiService
{
    private ?string $api_token = null;
    private Server $server;

    public function __construct(Server $server)
    {
            $this->server = $server;
    }

    public function login()
    {
        $response = Http::acceptJson()->post($this->server->server_url . '/api/login',[
            'email' => $this->server->api_user,
            'password' => $this->server->api_password
        ]);

        $result = json_decode($response->body());

        if (property_exists($result, 'exception') || !property_exists($result, 'token'))
        {
            dump($result);
            return false;
        }

        $this->api_token = $result->token;
        return true;
    }
//
//    public function register()
//    {
//        $response
//    }

    public function getActiveCalls(?Carbon $date = null)
    {
        $date ??= now();
        if (is_null($this->api_token)) return false;

        $response = Http::acceptJson()
            ->withToken($this->api_token)
            ->get($this->server->server_url . '/api/active_calls', [
                'date' => $date->format('Y-m-d H:i:s')
            ]);

        return json_decode($response->body());
    }
}

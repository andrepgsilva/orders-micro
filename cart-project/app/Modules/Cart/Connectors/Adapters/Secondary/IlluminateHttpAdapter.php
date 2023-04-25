<?php

namespace App\Modules\Cart\Connectors\Adapters\Secondary;

use Illuminate\Support\Facades\Http;
use App\Modules\Cart\Connectors\Adapters\Secondary\Abstractions\HttpResponse;
use App\Modules\Cart\Connectors\Adapters\Secondary\Abstractions\HttpAdapterAbstraction;

class IlluminateHttpAdapter extends HttpAdapterAbstraction
{
    public function __construct(public HttpResponse $httpResponse) {}

    function post(string $url, array $data): HttpResponse
    {
        $response = Http::post($url, $data);

        $this->httpResponse->statusCode = $response->status();
        $this->httpResponse->content = $response->body();

        return $this->httpResponse;
    }

    function get(string $url): HttpResponse
    {
        $response = Http::get($url);
        $this->httpResponse->statusCode = $response->status();
        $this->httpResponse->content = $response->body();

        return $this->httpResponse;
    }
}
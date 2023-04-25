<?php

namespace App\Modules\Cart\Connectors\Adapters\Secondary\Abstractions;

use App\Modules\Cart\Connectors\Adapters\Secondary\Abstractions\HttpResponse;
use App\Modules\Cart\Connectors\Adapters\Secondary\Helpers\CheckResponseFailed;

abstract class HttpAdapterAbstraction extends CheckResponseFailed
{
    abstract function post(string $url, array $data): HttpResponse;

    abstract function get(string $url): HttpResponse;
}
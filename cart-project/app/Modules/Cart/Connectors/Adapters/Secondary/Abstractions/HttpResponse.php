<?php

namespace App\Modules\Cart\Connectors\Adapters\Secondary\Abstractions;

class HttpResponse
{
    public int $statusCode;
    public string $content = '';
}

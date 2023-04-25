<?php

namespace App\Modules\Cart\Connectors\Adapters\Secondary;

use App\Modules\Cart\Application\Dtos\ProductOutputDto;
use App\Modules\Cart\Connectors\Ports\Outbound\GetProductByIdOutputPort;
use App\Modules\Cart\Connectors\Adapters\Secondary\Abstractions\HttpAdapterAbstraction;

class GetProductByIdProductsCatalogAdapter implements GetProductByIdOutputPort
{
    public function __construct(public HttpAdapterAbstraction $httpAdapter) {}
    
    function execute(string $uuid): ProductOutputDto|null
    {
        $response = $this->httpAdapter->get(
            config('external-routes.products_catalog.show') . $uuid
        );
        if ($this->httpAdapter->failed($response->statusCode)) return null;

        $body = json_decode($response->content);
        $outputDto = new ProductOutputDto();
        $outputDto->id = $body->id;
        $outputDto->name = $body->name;
        $outputDto->description = $body->description;
        $outputDto->quantity = $body->quantity;
        $outputDto->price = $body->price;
        $outputDto->createdAt = new \Datetime($body->created_at);
        $outputDto->updatedAt = new \Datetime($body->updated_at);

        return $outputDto;
    }
}

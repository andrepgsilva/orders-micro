<?php

namespace App\Modules\Cart\Application\Usecases;

use App\Modules\Cart\Connectors\Ports\Outbound\GetProductByIdOutputPort;

class GetProductByIdUseCase
{
    public function __construct(
        public GetProductByIdOutputPort $getProductByIdOutputAdapter
    ){}

    public function execute(string $uuid) 
    {
        return $this->getProductByIdOutputAdapter->execute($uuid);
    }
}

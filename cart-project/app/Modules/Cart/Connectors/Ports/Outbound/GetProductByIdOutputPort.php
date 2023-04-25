<?php

namespace App\Modules\Cart\Connectors\Ports\Outbound;

use App\Modules\Cart\Application\Dtos\ProductOutputDto;

interface GetProductByIdOutputPort 
{
    function execute(string $uuid): ProductOutputDto|null;
}
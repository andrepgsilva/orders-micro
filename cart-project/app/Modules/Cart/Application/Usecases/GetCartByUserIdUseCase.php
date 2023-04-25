<?php
namespace App\Modules\Cart\Application\Usecases;

use App\Modules\Cart\Connectors\Ports\Inbound\GetCartInboundPort;
use App\Modules\Cart\Domain\Entities\CartEntity;
use App\Modules\Cart\Connectors\Ports\Outbound\GetCartByUserIdOutboundPort;

class GetCartByUserIdUseCase implements GetCartInboundPort
{
    public function __construct(private GetCartByUserIdOutboundPort $getCartByIdAdapter) {}
    
    function get(string $userId): CartEntity|null
    {
        return $this->getCartByIdAdapter->getByUserId($userId);
    }
}
<?php

namespace App\Modules\Cart\Domain\Entities;

use App\Modules\Cart\Domain\Entities\Helpers\Id;
use DateTime;

class CartEntity
{
    private Id $id;
    public array $products;
    public int $quantity;
    public int $total;
    public DateTime $createdAt;
    public DateTime $updatedAt;

    public function __construct(
        array $products, 
        int $quantity, 
        int $total, 
        string $id = null,
        Datetime $createdAt = null, 
        Datetime $updatedAt = null,
    ) 
    {
        $this->blockAddingAnEmptyCart($products);
        $this->checkIfQuantityIsGreaterThanZero($quantity);
        $this->checkIfTotalIsNotNegative($total);
        $this->id = $id === null ? new Id() : new Id($id);
        $this->products = $products;
        $this->quantity = $quantity;
        $this->total = $total;

        $datetime = new DateTime('now', new \DateTimeZone('UTC'));

        $this->createdAt = $createdAt ?? $datetime;
        $this->updatedAt = $updatedAt ?? $datetime;
    }

    public function __get($propertyName) 
    {
        if ($propertyName === 'id') {
            return $this->id;
        }
    }

    public function __set($propertyName, $value) 
    {
        if ($propertyName === 'id') {
            return $this->id = $value;
        }
    }

    private function blockAddingAnEmptyCart(array $products): void
    {
        if ($products === null ||  count($products) === 0) {
            throw new \Exception('It cannot add an empty cart');
        }
    }

    private function checkIfQuantityIsGreaterThanZero(int $quantity): bool
    {
        if ($quantity < 1) {
            throw new \Exception('Quantity needs to be greater than zero', 1);
        }

        return true;
    }

    private function checkIfTotalIsNotNegative(int $total): bool
    {
        if ($total < 0) {
            throw new \Exception('Total cannot be negative', 1);
        }

        return true;
    }
}


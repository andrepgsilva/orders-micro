<?php

namespace App\Modules\Cart\Connectors\Adapters\Secondary;

use App\Modules\Cart\Domain\Entities\CartEntity;
use App\Modules\Cart\Domain\Entities\Factories\CartFactory;
use App\Modules\Cart\Connectors\Ports\Outbound\GetCartByUserIdOutboundPort;
use App\Modules\Cart\Connectors\Ports\Outbound\SaveProductToCartOutboundPort;
use App\Modules\Cart\Connectors\Ports\Outbound\RemoveProductByIdFromCartOutboundPort;

class CartInMemoryRepository implements GetCartByUserIdOutboundPort, SaveProductToCartOutboundPort, RemoveProductByIdFromCartOutboundPort
{
	public $carts = [];

	function __construct($carts = null)
	{
		if ($carts !== null) {
			$this->carts = $carts;
		}
	}

	private function searchCartIndex(string $userId): int|null
	{
		$cartindex = null;

		foreach ($this->carts as $index => $cart) {
			if ($cart['user_id'] === $userId) {
				$cartindex = $index;
				break;
			}
		}

		return $cartindex;
	}

	/**
	 * @param string $cartId
	 * @param mixed $userId
	 * @return CartEntity|null
	 */
	public function getByUserId(string $userId): CartEntity|null
	{
		$cart = array_filter($this->carts, function ($cart) use ($userId) {
			return $cart['user_id'] === $userId;
		});

		if (count($cart) === 0) return null;

		$cart = $cart[0];

		return CartFactory::create(
			$cart['products'],
			$cart['quantity'],
			$cart['total'],
			$cart['id'],
			\Datetime::createFromFormat(
				'Y-m-d H:i:s',
				$cart['createdAt']
			),
			\Datetime::createFromFormat(
				'Y-m-d H:i:s',
				$cart['updatedAt']
			)
		);
	}
	/**
	 * @param string $productId
	 * @param int $quantity
	 * @param int $price
	 * @param string $userId
	 * @return mixed
	 */
	public function save(string $productId, int $quantity, int $price, string $userId): CartEntity|null
	{
		$cart = $this->getByUserId($userId);

		$product = [
			'id' => $productId,
			'price' => $price,
			'quantity' => $quantity,
		];

		if ($cart === null) {
			$cart = CartFactory::create(
				[$product],
				$quantity,
				$quantity * $price,
			);

			$this->carts = [[
				'id' => $cart->id,
				'user_id' => $userId,
				'total' => $cart->total,
				'quantity' => $cart->quantity,
				'products' => [$product],
				'createdAt' => $cart->createdAt,
				'updatedAt' => $cart->updatedAt,
			]];

			return $cart;
		}

		$cartProducts = $cart->products;

		$productIndex = null;

		foreach ($cartProducts as $index => $productInCart) {
			if ($productInCart['id'] === $productId) {
				$productIndex = $index;
				break;
			};
		}

		if ($productIndex === null) {
			$cart->products[] = $product;
		} else {
			$product = [
				'id' => $cart->products[$productIndex]['id'],
				'price' => $cart->products[$productIndex]['price'],
				'quantity' => $cart->products[$productIndex]['quantity'],
			];

			$cart->products[$productIndex] = [
				'id' => $product['id'],
				'price' => $product['price'],
				'quantity' => $product['quantity'] + $quantity,
			];
		}

		$cartIndex = $this->searchCartIndex($userId);
		$this->carts[$cartIndex]['products'] = $cart->products;

		$this->carts[$cartIndex]['total'] = $this->carts[$cartIndex]['total'] + ($quantity * $price);
		$this->carts[$cartIndex]['quantity'] = $this->carts[$cartIndex]['quantity'] + $quantity;

		$cart = $this->carts[$cartIndex];

		return CartFactory::create(
			$cart['products'],
			$cart['quantity'],
			$cart['total'],
			$cart['id'],
			new \Datetime($cart['createdAt']),
			new \Datetime($cart['updatedAt']),
		);
	}
	/**
	 * @param string $userId
	 * @param string $productId
	 * @return bool
	 */
	public function remove(string $userId, string $productId): bool 
	{
		$cart = $this->getByUserId($userId);

		if ($cart === null) return false;

		$cartProducts = $cart->products;

		$productIndex = null;

		foreach ($cartProducts as $index => $productInCart) {
			if ($productInCart['id'] === $productId) {
				$productIndex = $index;
				break;
			};
		}

		if ($productIndex === null) return false;

		$productData = $cart->products[$productIndex];
		if ($productData['quantity'] < 1) return false;

		$productData['quantity'] -= 1;

		$cartIndex = $this->searchCartIndex($userId);

		$this->carts[$cartIndex]['products'][$productIndex] = $productData;
		$this->carts[$cartIndex]['quantity'] -= 1;
		if ($this->carts[$cartIndex]['products'][$productIndex]['quantity'] === 0) {
			array_splice($this->carts[$cartIndex]['products'], $productIndex);
		}
		$this->carts[$cartIndex]['total'] -= $productData['price'];

		return true;
	}
}

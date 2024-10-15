<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CurrencyRepository;

use App\GraphQL\Inputs\OrderProductType;
use App\GraphQL\Outputs\CreateOrder\CreatedOrder;

class OrderMutation
{
    private $createdOrderType;

    public function getCreateOrderField(): array
    {
        $this->createdOrderType = new CreatedOrder();
        return [
            'type' => Type::nonNull($this->createdOrderType),
            'args' => [
                'totalAmount' => Type::nonNull(Type::float()),
                'currencyId' => Type::nonNull(Type::string()),
                'products' => Type::listOf(new OrderProductType())
            ],
            'resolve' => function ($root, $args, $context, ResolveInfo $info) {
                try {
                    $orderRepository = new OrderRepository();
                    $productRepository = new ProductRepository();
                    $currencyRepository = new CurrencyRepository();

                    $totalAmount = $args['totalAmount'];
                    $currencyId = $args['currencyId'];
                    $products = $args['products'];

                    // Validate currency exists
                    $currency = $currencyRepository->getCurrencyById($currencyId);
                    if (!$currency) {
                        throw new \Exception("Invalid currency ID: " . $currencyId);
                    }

                    $orderId = $orderRepository->createOrder($totalAmount, $currencyId);
                    $orderedProducts = [];
                    foreach ($products as $product) {
                        $productDetails = $productRepository->getProductById($product['productId']);
                        if (!$productDetails) {
                            throw new \Exception("Product not found: " . $product['productId']);
                        }

                        // Get the matching price for the product in the correct currency
                        $matchingPrice = array_filter($productDetails->getPrices(), function ($price) use ($currencyId) {
                            return $price->currency->id === $currencyId;
                        });

                        $selectedPrice = array_values($matchingPrice)[0] ?? null;

                        if (!$selectedPrice) {
                            throw new \Exception("No price found for product {$product['productId']} with currency ID $currencyId");
                        }

                        // Build ordered product data
                        $orderedProducts[] = [
                            'productId' => $product['productId'],
                            'quantity' => $product['quantity'],
                            'price' => $selectedPrice->amount * $product['quantity'],
                            'selectedAttributes' => $product['selectedAttributes'] ?? [] // Include attributes if present
                        ];
                    }

                    $orderRepository->addOrderItems($orderId, $orderedProducts);

                    return [
                        "orderId" => $orderId,
                        "status" => "Order created successfully",
                        "error" => null
                    ];
                } catch (\Throwable $th) {
                    return [
                        "orderId" => null,
                        "status" => "Failed to create order",
                        "error" => $th->getMessage()
                    ];
                }
            }
        ];
    }

    public function updateOrderStatusField(): array
    {
        return [
            'type' => Type::nonNull(Type::string()),
            'args' => [
                'orderId' => Type::nonNull(Type::int()),
                'status' => Type::nonNull(Type::string()),
            ],
            'resolve' => function ($root, $args, $context, ResolveInfo $info) {
                $orderRepository = new OrderRepository();
                $orderId = $args['orderId'];
                $status = $args['status'];

                $result = $orderRepository->updateOrderStatus($orderId, $status);

                if ($result) {
                    return "Order ID {$orderId} has been updated to {$status}.";
                } else {
                    throw new \GraphQL\Error\UserError("Failed to update order status {$orderId}");
                }
            }
        ];
    }
}

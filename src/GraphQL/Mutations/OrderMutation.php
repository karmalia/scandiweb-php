<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\GraphQL\InputTypes\OrderProductInputType;

class OrderMutation
{
    public function getCreateOrderField(): array
    {
        return [
            'type' => Type::nonNull(Type::int()), // Return the newly created order's ID
            'args' => [
                'totalAmount' => Type::nonNull(Type::float()),
                'currencyId' => Type::nonNull(Type::string()),
                'products' => Type::listOf(
                    new OrderProductInputType()
                )
            ],
            'resolve' => function ($root, $args, $context, ResolveInfo $info) {
                $orderRepository = new OrderRepository();
                $productRepository = new ProductRepository();

                $totalAmount = $args['totalAmount'];
                $currencyId = $args['currencyId'];
                $products = $args['products'];

                // Create the order.
                $orderId = $orderRepository->createOrder($totalAmount, $currencyId);

                // Prepare product data for order items.
                $orderItems = [];
                foreach ($products as $product) {
                    $productDetails = $productRepository->getProductById($product['productId']);


                    // Find the price with the matching currency ID.
                    $matchingPrice = array_filter($productDetails->getPrices(), function ($price) use ($currencyId) {
                        return $price->currency->id === $currencyId;
                    });

                    // Get the first match
                    $selectedPrice = array_values($matchingPrice)[0] ?? null;

                    if (!$selectedPrice) {
                        throw new \Exception("No price found for product {$product['productId']} with currency ID $currencyId");
                    }

                    if (!$productDetails) {
                        throw new \Exception("Product not found: " . $product['productId']);
                    }
                    $orderItems[] = [
                        'productId' => $product['productId'],
                        'quantity' => $product['quantity'],
                        'price' => $selectedPrice->amount
                    ];
                }

                // Insert order items into the order_items table.
                $orderRepository->addOrderItems($orderId, $orderItems);

                [
                    'order_id' => $orderId,
                    'total_amount' => $totalAmount,
                    'currency_id' => $currencyId,
                    'status' => 'pending',
                    'items' => $orderItems
                ];

                return $orderId;
            }
        ];
    }

    // Similar methods for updateOrder or deleteOrder can be added here.
}

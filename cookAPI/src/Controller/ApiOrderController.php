<?php

namespace App\Controller;

use App\Entity\OrderEntity;
use App\Repository\CartRepository;
use App\Repository\OrderEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiOrderController extends AppController
{
    #[Route('/api/orders/{id}', name: 'api_order', methods: ['GET'])]
    public function getOrder(int $id, OrderEntityRepository $orderRepository, SerializerInterface $serializer): JsonResponse
    {
        $order = $orderRepository->find($id);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        $data = $serializer->serialize($order, 'json', ['groups' => ['order', 'order_items', 'order_payment']]);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/api/create-order', name: 'create_order', methods: ['POST'])]
    public function createOrder(Request $request, EntityManagerInterface $em, CartRepository $cartRepository, Security $security): JsonResponse
    {
        $user = $security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated'], 401);
        }

        $cart = $cartRepository->findOneBy(['user' => $user]);
        if (!$cart) {
            return new JsonResponse(['error' => 'Cart not found'], 404);
        }

        $order = new OrderEntity();
        $order->setUser($user);
        $order->setStatus('Pending');

        foreach ($cart->getItems() as $cartItem) {
            $order->addCartItem($cartItem);
            $cartItem->setOrderEntity($order);
        }

        $em->persist($order);
        $em->flush();

        return new JsonResponse(['order_id' => $order->getId(), 'order_number' => $order->getOrderNumber()], 201);
    }

    #[Route('/api/create-stripe-session/{orderId}', name: 'create_stripe_session', methods: ['POST'])]
    public function createStripeSession(int $orderId, OrderEntityRepository $orderRepository, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $order = $orderRepository->find($orderId);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        Stripe::setApiKey('sk_test_51PNBOvJGVVDN3k76cbiYmiuvXpYqFRikIu1shOuUcYTnO2F59gHfUr0BZSVW9Yl2dbKZuoXyToA04iiWQACKf826006fp9H5Hb');

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Votre commande',
                        ],
                        'unit_amount' => 1000, // Remplacez par le montant total de votre commande
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $urlGenerator->generate('payment_success', ['orderId' => $orderId], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $urlGenerator->generate('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return new JsonResponse(['url' => $session->url]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/payment-success/{orderId}', name: 'payment_success')]
    public function success(int $orderId, OrderEntityRepository $orderRepository): JsonResponse
    {
        $order = $orderRepository->find($orderId);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        return new JsonResponse(['message' => 'Payment was successful. Thank you!', 'order_number' => $order->getOrderNumber()], 200);
    }

    #[Route('/payment-cancel', name: 'payment_cancel')]
    public function cancel(): JsonResponse
    {
        return new JsonResponse(['message' => 'Payment was cancelled. Please try again.'], 200);
    }
}


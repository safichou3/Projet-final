<?php

namespace App\Controller;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiPaymentController extends AppController
{
    #[Route('/api/checkout', name: 'api_checkout', methods: ['POST'])]
    public function checkout(Request $request): Response
    {
        Stripe::setApiKey('sk_test_51PNBOvJGVVDN3k76cbiYmiuvXpYqFRikIu1shOuUcYTnO2F59gHfUr0BZSVW9Yl2dbKZuoXyToA04iiWQACKf826006fp9H5Hb');

        $data = json_decode($request->getContent(), true);
        $orderId = $data['orderId'];

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Test Product',
                        ],
                        'unit_amount' => 1000,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return $this->json(['url' => $session->url]);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/payment-success', name: 'payment_success')]
    public function success(): Response
    {
        return new Response('Payment was successful. Thank you!', Response::HTTP_OK);
    }

    #[Route('/payment-cancel', name: 'payment_cancel')]
    public function cancel(): Response
    {
        return new Response('Payment was cancelled. Please try again.', Response::HTTP_OK);
    }
}

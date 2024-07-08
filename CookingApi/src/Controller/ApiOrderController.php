<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiOrderController extends AppController
{
    #[Route('/api/order', name: 'app_api_order', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): JsonResponse
    {
        $orders = $orderRepository->findAll();
        $json = $this->serializer->serialize($orders, 'json', ['groups' => 'order']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/order/{id}', name: 'app_api_order_one', methods: ['GET'])]
    public function one(int $id, OrderRepository $orderRepository): JsonResponse
    {
        $order = $orderRepository->find($id);
        if (!$order) {
            return $this->jsonNotFound('order');
        }
        $json = $this->serializer->serialize($order, 'json', ['groups' => 'order']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/order', name: 'app_api_order_add', methods: ['POST'])]
    public function add(Request $request, ValidatorInterface $validator, UserRepository $userRepository): JsonResponse
    {
        $context = [
            DateTimeNormalizer::FORMAT_KEY => 'd-m-Y H:i:s',
        ];

        $data = json_decode($request->getContent(), true);

        $userId = $data['user']['id'] ?? null;
        if (!$userId) {
            return new JsonResponse(['error' => 'User ID is missing'], Response::HTTP_BAD_REQUEST);
        }

        $user = $userRepository->find($userId);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $orderDate = \DateTimeImmutable::createFromFormat('d-m-Y H:i:s', $data['orderDate']);
            $pickupTime = \DateTime::createFromFormat('d-m-Y H:i:s', $data['pickupTime']);

            if ($pickupTime < $orderDate) {
                return new JsonResponse(['error' => 'Pickup time cannot be earlier than order date'], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid date format'], Response::HTTP_BAD_REQUEST);
        }

        $order = new Order();
        $order->setUser($user);
        $order->setOrderDate($orderDate);
        $order->setPickupTime($pickupTime);
        $order->setStatus($data['status']);
        $order->setTotalPrice($data['totalPrice']);

        $errors = $validator->validate($order);
        if (count($errors) > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($order);
        $this->em->flush();

        $json = $this->serializer->serialize($order, 'json', ['groups' => 'order']);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/order/{id}', name: 'app_api_order_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request, OrderRepository $orderRepository, ValidatorInterface $validator): JsonResponse
    {
        $currentOrder = $orderRepository->find($id);
        if (!$currentOrder) {
            return $this->jsonNotFound('order');
        }

        $order = $this->serializer->deserialize($request->getContent(), Order::class, 'json', [
            'object_to_populate' => $currentOrder,
        ]);
        $errors = $validator->validate($order);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($order);
        $this->em->flush();

        $json = $this->serializer->serialize($order, 'json', ['groups' => 'order']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/order/{id}', name: 'app_api_order_delete', methods: ['DELETE'])]
    public function delete(int $id, OrderRepository $orderRepository): JsonResponse
    {
        $order = $orderRepository->find($id);
        if ($order) {
            $this->em->remove($order);
            $this->em->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return $this->jsonNotFound('order');
    }
}

<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiPaymentController extends AppController
{
    #[Route('/api/payment', name: 'app_api_payment', methods: ['GET'])]
    public function index(PaymentRepository $paymentRepository): JsonResponse
    {
        $payments = $paymentRepository->findAll();
        $json = $this->serializer->serialize($payments, 'json', ['groups' => 'payment']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/payment/{id}', name: 'app_api_payment_one', methods: ['GET'])]
    public function one(int $id, PaymentRepository $paymentRepository): JsonResponse
    {
        $payment = $paymentRepository->find($id);
        if (!$payment) {
            return $this->jsonNotFound('payment');
        }
        $json = $this->serializer->serialize($payment, 'json', ['groups' => 'payment']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/payment', name: 'app_api_payment_add', methods: ['POST'])]
    public function add(Request $request, ValidatorInterface $validator, OrderRepository $orderRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $order = $orderRepository->find($data['userOrder']);
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $payment = new Payment();
        $payment->setUserOrder($order);
        $payment->setPaymentDate(new \DateTime($data['paymentDate']));
        $payment->setAmount($data['amount']);
        $payment->setPaymentMethod($data['paymentMethod']);
        $payment->setPaymentStatus($data['paymentStatus']);

        $errors = $validator->validate($payment);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($payment);
        $this->em->flush();

        $json = $this->serializer->serialize($payment, 'json', ['groups' => 'payment']);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/payment/{id}', name: 'app_api_payment_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request, PaymentRepository $paymentRepository, ValidatorInterface $validator): JsonResponse
    {
        $currentPayment = $paymentRepository->find($id);
        if (!$currentPayment) {
            return $this->jsonNotFound('payment');
        }

        $payment = $this->serializer->deserialize($request->getContent(), Payment::class, 'json', [
            'object_to_populate' => $currentPayment,
        ]);
        $errors = $validator->validate($payment);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($payment);
        $this->em->flush();

        $json = $this->serializer->serialize($payment, 'json', ['groups' => 'payment']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/payment/{id}', name: 'app_api_payment_delete', methods: ['DELETE'])]
    public function delete(int $id, PaymentRepository $paymentRepository): JsonResponse
    {
        $payment = $paymentRepository->find($id);
        if ($payment) {
            $this->em->remove($payment);
            $this->em->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return $this->jsonNotFound('payment');
    }
}

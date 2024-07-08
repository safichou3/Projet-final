<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AppController
{
    #[Route('/api/user', name: 'app_api_all_user', methods: ['GET'])]
    public function index(UserRepository $userRepository): JsonResponse
    {
        try {
            $users = $userRepository->findAll();
            $json = $this->serializer->serialize($users, 'json', ['groups' => 'user_no_pass']);
            return new JsonResponse($json, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/user/getcurrent', name: 'app_api_user_by_token', methods: ['POST'])]
    public function ifUserExist(): JsonResponse
    {
        try {
            $user = $this->getUser();
            if ($user) {
                $json = $this->serializer->serialize($user, 'json', ['groups' => 'user_no_pass']);
                return new JsonResponse($json, Response::HTTP_OK, [], true);
            }
            return new JsonResponse(['message' => 'JWT Token not found'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/user/register', name: 'app_api_user_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher): JsonResponse
    {
        try {
            $user = new User();
            $form = $this->createForm(RegisterFormType::class, $user);
            $formData = json_decode($request->getContent(), true);
            $form->submit($formData);

            if ($form->isValid()) {
                $content = $request->toArray();
                $hashedPassword = $hasher->hashPassword($user, $content['plainPassword']);
                $user->setPassword($hashedPassword);

                $roles = $content['roles'] ?? [];
                $user->setRoles($roles);

                $this->em->persist($user);
                $this->em->flush();

                return new JsonResponse(['status' => 'success'], Response::HTTP_CREATED);
            } else {
                $errors = $this->getFormErrorsFormType($form);
                return new JsonResponse(['status' => 'failure', 'violation' => $errors], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            return new JsonResponse(['status' => 'error', 'message' => 'Email already exists'], Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function getFormErrorsFormType($form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }
        return $errors;
    }
}

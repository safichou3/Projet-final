<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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
            $json = $this->serializer->serialize($users, 'json', ['groups' => 'user']);
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
                $json = $this->serializer->serialize($user, 'json', ['groups' => 'user']);
                return new JsonResponse($json, Response::HTTP_OK, [], true);
            }
            return new JsonResponse(['message' => 'JWT Token not found'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/user/register', name: 'app_api_user_register', methods: ['POST'])]
    public function register(Request $request, UserPasswordHasherInterface $hasher, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        try {
            $user = new User();
            $form = $this->createForm(RegisterFormType::class, $user);
            $formData = json_decode($request->getContent(), true);
            $form->submit($formData);

            if ($form->isValid()) {
                $content = $request->toArray();

                if (!isset($content['agreeTerms']) || !$content['agreeTerms']) {
                    return new JsonResponse(['status' => 'failure', 'message' => 'You should agree to our terms.'], Response::HTTP_BAD_REQUEST);
                }

                $hashedPassword = $hasher->hashPassword($user, $content['plainPassword']);
                $user->setPassword($hashedPassword);

                $roles = $content['roles'] ?? [];
                $roles[] = 'ROLE_USER';
                $user->setRoles(array_unique($roles));

                if (isset($content['name'])) {
                    $user->setName($content['name']);
                }

                if (isset($content['isChef'])) {
                    $user->setIsChef($content['isChef']);
                }

                $this->em->persist($user);
                $this->em->flush();

                $token = $JWTManager->create($user);

                return new JsonResponse(['status' => 'success', 'token' => $token], Response::HTTP_CREATED);
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

    #[Route('/api/user/{id}', name: 'app_api_user_update', methods: ['PUT'])]
    public function update(Request $request, User $user, UserPasswordHasherInterface $hasher): JsonResponse
    {
        try {
            $formData = json_decode($request->getContent(), true);

            if (isset($formData['plainPassword'])) {
                $hashedPassword = $hasher->hashPassword($user, $formData['plainPassword']);
                $user->setPassword($hashedPassword);
            }

            if (isset($formData['roles'])) {
                $roles = $formData['roles'];
                $roles[] = 'ROLE_USER'; // Ensure ROLE_USER is always included
                $user->setRoles(array_unique($roles));
            }

            if (isset($formData['email'])) {
                $user->setEmail($formData['email']);
            }

            if (isset($formData['name'])) {
                $user->setName($formData['name']);
            }

            if (isset($formData['isChef'])) {
                $user->setIsChef($formData['isChef']);
            }

            $this->em->flush();

            return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/api/user/{id}', name: 'app_api_user_delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        try {
            $this->em->remove($user);
            $this->em->flush();

            return new JsonResponse(['status' => 'success'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

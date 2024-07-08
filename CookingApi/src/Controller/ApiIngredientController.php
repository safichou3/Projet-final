<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiIngredientController extends AppController
{

    #[Route('/api/ingredient', name: 'app_api_ingredient', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository): JsonResponse
    {
        $ingredients = $ingredientRepository->findAll();
        $json = $this->serializer->serialize($ingredients, 'json', ['groups' => 'test']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/ingredient/{id}', name: 'app_api_ingredient_one', methods: ['GET'])]
    public function one(int $id, IngredientRepository $ingredientRepository): JsonResponse
    {
        $ingredient = $ingredientRepository->find($id);
        if (!$ingredient) {
            return $this->jsonNotFound('ingredient');
        }
        $json = $this->serializer->serialize($ingredient, 'json', ['groups' => 'test']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/ingredient', name: 'app_api_ingredient_add', methods: ['POST'])]
    public function add(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $ingredient = $this->serializer->deserialize($request->getContent(), Ingredient::class, 'json');
        $errors = $validator->validate($ingredient);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($ingredient);
        $this->em->flush();

        $json = $this->serializer->serialize($ingredient, 'json', ['groups' => 'test']);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/ingredient/{id}', name: 'app_api_ingredient_edit', methods: ['PUT'])]
    public function edit(int $id, IngredientRepository $ingredientRepository, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $currentIngredient = $ingredientRepository->find($id);
        if (!$currentIngredient) {
            return $this->jsonNotFound('ingredient');
        }

        $ingredient = $this->serializer->deserialize($request->getContent(), Ingredient::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentIngredient]);
        $errors = $validator->validate($ingredient);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($ingredient);
        $this->em->flush();

        $json = $this->serializer->serialize($ingredient, 'json', ['groups' => 'test']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/ingredient/{id}', name: 'app_api_ingredient_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id, IngredientRepository $ingredientRepository): JsonResponse
    {
        $ingredient = $ingredientRepository->find($id);
        if ($ingredient) {
            $this->em->remove($ingredient);
            $this->em->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return $this->jsonNotFound('ingredient');
    }
}

<?php

namespace App\Controller;

use App\Entity\RecipeIngredient;
use App\Repository\IngredientRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiRecipeIngredientController extends AppController
{
    #[Route('/api/recipeingredient', name: 'app_api_recipeingredient', methods: ['GET'])]
    public function index(RecipeIngredientRepository $recipeIngredientRepository): JsonResponse
    {
        $recipeIngredients = $recipeIngredientRepository->findAll();
        $json = $this->serializer->serialize($recipeIngredients, 'json', ['groups' => 'recipeIngredient']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/recipeingredient/{id}', name: 'app_api_recipeingredient_one', methods: ['GET'])]
    public function one(int $id, RecipeIngredientRepository $recipeIngredientRepository): JsonResponse
    {
        $recipeIngredient = $recipeIngredientRepository->find($id);
        if (!$recipeIngredient) {
            return $this->jsonNotFound('recipeIngredient');
        }
        $json = $this->serializer->serialize($recipeIngredient, 'json', ['groups' => 'recipeIngredient']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/recipeingredient', name: 'app_api_recipeingredient_add', methods: ['POST'])]
    public function add(Request $request, ValidatorInterface $validator, RecipeRepository $recipeRepository, IngredientRepository $ingredientRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $recipeId = $data['recipe']['id'] ?? null;
        $ingredientId = $data['ingredient']['id'] ?? null;

        if (!$recipeId || !$ingredientId) {
            return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
        }

        $recipe = $recipeRepository->find($recipeId);
        $ingredient = $ingredientRepository->find($ingredientId);

        if (!$recipe || !$ingredient) {
            return new JsonResponse(['error' => 'Recipe or Ingredient not found'], Response::HTTP_NOT_FOUND);
        }

        $recipeIngredient = new RecipeIngredient();
        $recipeIngredient->setRecipe($recipe);
        $recipeIngredient->setIngredient($ingredient);
        $recipeIngredient->setQuantity($data['quantity']);

        $errors = $validator->validate($recipeIngredient);
        if (count($errors) > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($recipeIngredient);
        $this->em->flush();

        $json = $this->serializer->serialize($recipeIngredient, 'json', ['groups' => 'recipeingredient']);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/recipeingredient/{id}', name: 'app_api_recipeingredient_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request, RecipeIngredientRepository $recipeIngredientRepository, ValidatorInterface $validator): JsonResponse
    {
        $currentRecipeIngredient = $recipeIngredientRepository->find($id);
        if (!$currentRecipeIngredient) {
            return $this->jsonNotFound('recipeIngredient');
        }

        $recipeIngredient = $this->serializer->deserialize($request->getContent(), RecipeIngredient::class, 'json', [
            'object_to_populate' => $currentRecipeIngredient,
        ]);
        $errors = $validator->validate($recipeIngredient);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($recipeIngredient);
        $this->em->flush();

        $json = $this->serializer->serialize($recipeIngredient, 'json', ['groups' => 'recipeIngredient']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/recipeingredient/{id}', name: 'app_api_recipeingredient_delete', methods: ['DELETE'])]
    public function delete(int $id, RecipeIngredientRepository $recipeIngredientRepository): JsonResponse
    {
        $recipeIngredient = $recipeIngredientRepository->find($id);
        if ($recipeIngredient) {
            $this->em->remove($recipeIngredient);
            $this->em->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return $this->jsonNotFound('recipeIngredient');
    }
}

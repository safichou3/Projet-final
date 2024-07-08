<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use App\Service\Search\RecipeSearch;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiRecipeController extends AppController
{
    #[Route('/api/recipe', name: 'app_api_recipe', methods: ['GET'])]
    public function index(Request $request, RecipeSearch $recipeSearch): JsonResponse
    {
        $recipes = $recipeSearch->request($request);
        $json = $this->serializer->serialize($recipes, 'json', ['groups' => 'recipe']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/recipe/{id}', name: 'app_api_recipe_one', methods: ['GET'])]
    public function one(int $id, RecipeRepository $recipeRepository): JsonResponse
    {
        $recipe = $recipeRepository->find($id);
        if (!$recipe) {
            return $this->jsonNotFound('recipe');
        }
        $json = $this->serializer->serialize($recipe, 'json', ['groups' => 'recipe']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/recipe', name: 'app_api_recipe_add', methods: ['POST'])]
    public function add(Request $request, ValidatorInterface $validator, CategoryRepository $categoryRepository): JsonResponse
    {
        $recipe = $this->serializer->deserialize($request->getContent(), Recipe::class, 'json');
        $errors = $validator->validate($recipe);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }
        // category
        $content = $request->toArray();
        $idcat = $content['category'] ?? -1;
        $category = $categoryRepository->find($idcat);
        if (!empty($category)) {
            $recipe->setCategory($category);
        }
        // created_at
        $recipe->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($recipe);
        $this->em->flush();

        $json = $this->serializer->serialize($recipe, 'json', ['groups' => 'recipe']);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/recipe/{id}', name: 'app_api_recipe_edit', methods: ['PUT'])]
    public function edit(int $id, RecipeRepository $recipeRepository, Request $request, CategoryRepository $categoryRepository, ValidatorInterface $validator): JsonResponse
    {
        $currentRecipe = $recipeRepository->find($id);
        if (!$currentRecipe) {
            return $this->jsonNotFound('recipe');
        }

        $recipe = $this->serializer->deserialize($request->getContent(), Recipe::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentRecipe]);
        $errors = $validator->validate($recipe);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }
        // category
        $content = $request->toArray();
        $idcat = $content['category'] ?? -1;
        $recipe->setCategory($categoryRepository->find($idcat));

        $this->em->persist($recipe);
        $this->em->flush();

        $json = $this->serializer->serialize($recipe, 'json', ['groups' => 'recipe']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/recipe/{id}', name: 'app_api_recipe_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id, RecipeRepository $recipeRepository): JsonResponse
    {
        $recipe = $recipeRepository->find($id);
        if ($recipe) {
            $this->em->remove($recipe);
            $this->em->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return $this->jsonNotFound('recipe');
    }
}

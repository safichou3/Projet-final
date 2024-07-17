<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiRecipeController extends AppController
{
    #[Route('/api/recipe', name: 'api_recipe', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository, SerializerInterface $serializer): JsonResponse
    {
        $recipes = $recipeRepository->findAll();

        if (!$recipes) {
            return new JsonResponse(['status' => 'error', 'message' => 'No recipes found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $json = $serializer->serialize($recipes, 'json', ['groups' => 'recipe']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/recipe/{id}', name: 'api_recipe_one', methods: ['GET'])]
    public function one(int $id, RecipeRepository $recipeRepository): JsonResponse
    {
        $recipe = $recipeRepository->find($id);
        if (!$recipe) {
            return $this->jsonNotFound('recipe');
        }

        $json = $this->serializer->serialize($recipe, 'json', ['groups' => 'recipe']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/recipe', name: 'api_recipe_add', methods: ['POST'])]
    public function add(Request $request, ValidatorInterface $validator, CategoryRepository $categoryRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérification des données reçues
        if (!isset($data['title']) || !isset($data['description']) || !isset($data['difficulty']) || !isset($data['category'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Rechercher la catégorie
        $category = $categoryRepository->find($data['category']);
        if (!$category) {
            return new JsonResponse(['status' => 'error', 'message' => 'Category not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Désérialiser les données en entité Recipe
        $recipe = $this->serializer->deserialize($request->getContent(), Recipe::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => new Recipe()
        ]);

        // Assigner la catégorie à la recette
        $recipe->setCategory($category);

        // Valider l'entité Recipe
        $errors = $validator->validate($recipe);
        if ($errors->count() > 0) {
            return $this->jsonValidationErrors($errors);
        }

        // Persister et sauvegarder l'entité Recipe
        $this->em->persist($recipe);
        $this->em->flush();

        $json = $this->serializer->serialize($recipe, 'json', ['groups' => 'recipe']);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/recipe/{id}', name: 'api_recipe_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request, RecipeRepository $recipeRepository, ValidatorInterface $validator, CategoryRepository $categoryRepository): JsonResponse
    {
        $currentRecipe = $recipeRepository->find($id);
        if (!$currentRecipe) {
            return $this->jsonNotFound('recipe');
        }

        $data = json_decode($request->getContent(), true);

        // Rechercher la catégorie si elle est présente dans la requête
        if (isset($data['category'])) {
            $category = $categoryRepository->find($data['category']);
            if (!$category) {
                return new JsonResponse(['status' => 'error', 'message' => 'Category not found'], JsonResponse::HTTP_NOT_FOUND);
            }
            $currentRecipe->setCategory($category);
        }

        $recipe = $this->serializer->deserialize($request->getContent(), Recipe::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $currentRecipe
        ]);

        $errors = $validator->validate($recipe);
        if ($errors->count() > 0) {
            return $this->jsonValidationErrors($errors);
        }

        $this->em->persist($recipe);
        $this->em->flush();

        $json = $this->serializer->serialize($recipe, 'json', ['groups' => 'recipe']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/category/{id}', name: 'app_api_category_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(int $id, CategoryRepository $categoryRepository): JsonResponse
    {
        $cat = $categoryRepository->find($id);
        if (!$cat) {
            return new JsonResponse(['status' => 'error', 'message' => 'Category not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->em->remove($cat);
        $this->em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

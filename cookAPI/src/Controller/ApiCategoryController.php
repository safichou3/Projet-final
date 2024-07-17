<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiCategoryController extends AppController
{
    #[Route('/api/category', name: 'app_api_category', methods: ['GET'])]
    public function index(Request $request, CategoryRepository $categoryRepository): JsonResponse
    {
        // Définir les valeurs par défaut
        $params = array_merge([
            'orderby' => 'title',
            'order' => 'asc',
            'limit' => 10,
            'page' => 1
        ], $request->query->all());

        // Validation des valeurs des paramètres
        if (!in_array($params['orderby'], ['title', 'content', 'created_at'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid orderby value'], JsonResponse::HTTP_BAD_REQUEST);
        }
        if (!in_array($params['order'], ['asc', 'desc'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid order value'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $categories = $categoryRepository->findBy([], [$params['orderby'] => $params['order']], $params['limit'], ($params['page'] - 1) * $params['limit']);

        $json = $this->serializer->serialize($categories, 'json', ['groups' => 'category']);
        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/category/{id}', name: 'app_api_category_single', methods: ['GET'])]
    public function single(int $id, CategoryRepository $categoryRepository): JsonResponse
    {
        $cat = $categoryRepository->find($id);
        if (!$cat) {
            return $this->jsonNotFound('category');
        }
        $json = $this->serializer->serialize($cat, 'json', ['groups' => 'category']);
        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/category', name: 'app_api_category_add', methods: ['POST'])]
    public function add(Request $request, ValidatorInterface $validator, CategoryRepository $categoryRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifier que les données existent et ne sont pas nulles
        if (!isset($data['title']) || !isset($data['description'])) {
            return new JsonResponse(['status' => 'error', 'message' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Vérifier qu'il n'y a pas déjà une catégorie avec ce titre
        $existingCategory = $categoryRepository->findOneBy(['title' => $data['title']]);
        if ($existingCategory) {
            return new JsonResponse(['status' => 'error', 'message' => 'Category already exists'], JsonResponse::HTTP_CONFLICT);
        }

        $cat = $this->serializer->deserialize($request->getContent(), Category::class, 'json');

        // Définir created_at si ce n'est pas déjà fait
        if ($cat->getCreatedAt() === null) {
            $cat->setCreatedAt(new \DateTimeImmutable());
        }

        // Récupérer l'utilisateur connecté et l'ajouter à la catégorie
       /* $user = $this->getUser();
        if ($user) {
            $cat->setUser($user);
        } else {
            return new JsonResponse(['status' => 'error', 'message' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }*/

        $errors = $validator->validate($cat);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($cat);
        $this->em->flush();

        $json = $this->serializer->serialize($cat, 'json', ['groups' => 'category']);
        return new JsonResponse($json, JsonResponse::HTTP_CREATED, [], true);
    }

    #[Route('/api/category/{id}', name: 'app_api_category_update', methods: ['PUT'])]
    public function update(int $id, Request $request, CategoryRepository $categoryRepository, ValidatorInterface $validator): JsonResponse
    {
        $cat_current = $categoryRepository->find($id);
        if (!$cat_current) {
            return $this->jsonNotFound('category');
        }

        $cat = $this->serializer->deserialize($request->getContent(), Category::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $cat_current]);
        $errors = $validator->validate($cat);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($cat);
        $this->em->flush();

        $json = $this->serializer->serialize($cat, 'json', ['groups' => 'category']);
        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/category/{id}', name: 'app_api_category_delete', methods: ['DELETE'])]
    public function delete(int $id, CategoryRepository $categoryRepository): JsonResponse
    {
        $cat = $categoryRepository->find($id);
        if (!$cat) {
            return $this->jsonNotFound('category');
        }

        $this->em->remove($cat);
        $this->em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

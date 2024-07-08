<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\CategoryRepository;
use App\Repository\MenuRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiMenuController extends AppController
{
    #[Route('/api/menu', name: 'app_api_menu', methods: ['GET'])]
    public function index(MenuRepository $menuRepository): JsonResponse
    {
        $menus = $menuRepository->findAll();
        $json = $this->serializer->serialize($menus, 'json', ['groups' => 'menu']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/menu/{id}', name: 'app_api_menu_one', methods: ['GET'])]
    public function one(int $id, MenuRepository $menuRepository): JsonResponse
    {
        $menu = $menuRepository->find($id);
        if (!$menu) {
            return $this->jsonNotFound('menu');
        }
        $json = $this->serializer->serialize($menu, 'json', ['groups' => 'menu']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/menu', name: 'app_api_menu_add', methods: ['POST'])]
    public function add(Request $request, ValidatorInterface $validator, CategoryRepository $categoryRepository, UserRepository $userRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Deserialize menu without setting category and chef directly
        $menu = new Menu();
        $menu->setName($data['name']);
        $menu->setDescription($data['description']);
        $menu->setPrice($data['price']);
        $menu->setAvailability($data['availability']);

        // Validate the menu fields
        $errors = $validator->validate($menu);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }

        // Retrieve and set the category
        $category = $categoryRepository->find($data['category']);
        if (!$category) {
            return new JsonResponse(['status' => 404, 'message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
        $menu->setCategory($category);

        // Retrieve and set the chef
        $chef = $userRepository->find($data['chef']);
        if (!$chef) {
            return new JsonResponse(['status' => 404, 'message' => 'Chef not found'], Response::HTTP_NOT_FOUND);
        }
        $menu->setChef($chef);

        // Persist and flush the menu
        $this->em->persist($menu);
        $this->em->flush();

        // Serialize and return the response
        $json = $this->serializer->serialize($menu, 'json', ['groups' => 'menu']);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/menu/{id}', name: 'app_api_menu_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request, MenuRepository $menuRepository, ValidatorInterface $validator): JsonResponse
    {
        $currentMenu = $menuRepository->find($id);
        if (!$currentMenu) {
            return $this->jsonNotFound('menu');
        }

        $menu = $this->serializer->deserialize($request->getContent(), Menu::class, 'json', [
            'object_to_populate' => $currentMenu,
        ]);
        $errors = $validator->validate($menu);
        if ($errors->count() > 0) {
            return $this->jsonBadRequest($errors);
        }

        $this->em->persist($menu);
        $this->em->flush();

        $json = $this->serializer->serialize($menu, 'json', ['groups' => 'menu']);
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/menu/{id}', name: 'app_api_menu_delete', methods: ['DELETE'])]
    public function delete(int $id, MenuRepository $menuRepository): JsonResponse
    {
        $menu = $menuRepository->find($id);
        if ($menu) {
            $this->em->remove($menu);
            $this->em->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return $this->jsonNotFound('menu');
    }
}

<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiCartController extends AppController
{
    #[Route('/api/cart', name: 'add_to_cart', methods: ['POST'])]
    public function addToCart(Request $request, RecipeRepository $recipeRepository, CartRepository $cartRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $recipeId = $data['recipe_id'];
        $quantity = $data['quantity'];

        $user = $this->getUser();

        // Find the user's cart or create a new one
        $cart = $cartRepository->findOneBy(['user' => $user]);
        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $this->em->persist($cart);
        }

        // Find the recipe
        $recipe = $recipeRepository->find($recipeId);
        if (!$recipe) {
            return new Response('Recipe not found', Response::HTTP_NOT_FOUND);
        }

        // Check if the item is already in the cart
        $cartItem = $cart->getItems()->filter(function (CartItem $item) use ($recipe) {
            return $item->getRecipe()->getId() === $recipe->getId();
        })->first();

        if ($cartItem) {
            $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
        } else {
            $cartItem = new CartItem();
            $cartItem->setCart($cart);
            $cartItem->setRecipe($recipe);
            $cartItem->setQuantity($quantity);
            $cart->addItem($cartItem);
        }

        $this->em->persist($cart);
        $this->em->flush();

        return new Response('Item added to cart', Response::HTTP_OK);
    }

    #[Route('/api/cart', name: 'api_get_cart', methods: ['GET'])]
    public function getCart(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $cart = $em->getRepository(Cart::class)->findOneBy(['user' => $user]);
        if (!$cart) {
            return new JsonResponse(['items' => []]);
        }

        $items = [];
        foreach ($cart->getItems() as $item) {
            $items[] = [
                'id' => $item->getId(),
                'recipe' => [
                    'id' => $item->getRecipe()->getId(),
                    'title' => $item->getRecipe()->getTitle(),
                    'description' => $item->getRecipe()->getDescription(),
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        return new JsonResponse(['items' => $items]);
    }

    #[Route('/api/cart/item/{itemId}', name: 'remove_item_from_cart', methods: ['DELETE'])]
    public function removeItemFromCart(int $itemId, CartItemRepository $cartItemRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $cartItem = $cartItemRepository->find($itemId);
        if (!$cartItem) {
            return new JsonResponse(['message' => 'Item not found in cart'], Response::HTTP_NOT_FOUND);
        }

        if ($cartItem->getCart()->getUser()->getId() !== $user->getId()) {
            return new JsonResponse(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $em->remove($cartItem);
        $em->flush();

        return new JsonResponse(['message' => 'Item removed from cart'], Response::HTTP_OK);
    }


    #[Route('/api/cart/clear', name: 'clear_cart', methods: ['DELETE'])]
    public function clearCart(CartRepository $cartRepository, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $cart = $cartRepository->findOneBy(['user' => $user]);
        if (!$cart) {
            return new JsonResponse(['message' => 'Cart is already empty'], Response::HTTP_OK);
        }

        foreach ($cart->getItems() as $item) {
            $em->remove($item);
        }

        $em->flush();

        return new JsonResponse(['message' => 'Cart cleared'], Response::HTTP_OK);
    }
}

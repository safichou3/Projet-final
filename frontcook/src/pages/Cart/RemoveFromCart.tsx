/*
import React, { createContext, useContext, useState, useEffect } from 'react';
import axios from 'axios';
import {AuthContext} from "../../context/AuthContext";

const CartContext = createContext({
    cart: [],
    addToCart: () => {},
    removeFromCart: () => {},
    clearCart: () => {}
});

export const useCart = () => {
    return useContext(CartContext);
};

const CartProvider = ({ children }) => {
    const [cart, setCart] = useState([]);
    const { auth } = useContext(AuthContext);

    useEffect(() => {
        const fetchCart = async () => {
            try {
                const response = await axios.get('http://localhost:8000/api/cart', {
                    headers: {
                        Authorization: `Bearer ${auth}`
                    }
                });
                setCart(response.data.items);
            } catch (error) {
                console.error('Error fetching cart:', error);
            }
        };

        if (auth) {
            fetchCart();
        }
    }, [auth]);

    const addToCart = async (recipeId) => {
        try {
            await axios.post('http://localhost:8000/api/cart', { recipe_id: recipeId, quantity: 1 }, {
                headers: {
                    Authorization: `Bearer ${auth}`
                }
            });
            // Mise à jour locale du panier
            const updatedCart = [...cart];
            const itemIndex = updatedCart.findIndex(item => item.recipe.id === recipeId);
            if (itemIndex >= 0) {
                updatedCart[itemIndex].quantity += 1;
            } else {
                updatedCart.push({ recipe: { id: recipeId }, quantity: 1 });
            }
            setCart(updatedCart);
        } catch (error) {
            console.error('Error adding to cart:', error);
        }
    };

    const removeFromCart = async (itemId) => {
        try {
            await axios.delete(`http://localhost:8000/api/cart/${itemId}`, {
                headers: {
                    Authorization: `Bearer ${auth}`
                }
            });
            setCart(cart.filter(item => item.id !== itemId));
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    };

    const clearCart = () => {
        setCart([]);
    };

    return (
        <CartContext.Provider value={{ cart, addToCart, removeFromCart, clearCart }}>
            {children}
        </CartContext.Provider>
    );
};

export default CartProvider;
*/

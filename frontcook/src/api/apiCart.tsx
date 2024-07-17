// apiCart.ts

import axios from 'axios';

const apiCart = {
    addToCart: async (recipeId, quantity, token) => {
        try {
            const response = await axios.post('http://localhost:8000/api/cart', {
                recipe_id: recipeId,
                quantity: quantity
            }, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            return response.data;
        } catch (error) {
            console.error("Error adding recipe to cart:", error);
            throw error;
        }
    },
    removeFromCart: async (cartItemId, token) => {
        try {
            const response = await axios.delete(`http://localhost:8000/api/cart/item/${cartItemId}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            return response.data;
        } catch (error) {
            console.error("Error removing item from cart:", error);
            throw error;
        }
    },
    clearCart: async (token) => {
        try {
            const response = await axios.delete('http://localhost:8000/api/cart', {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            return response.data;
        } catch (error) {
            console.error("Error clearing cart:", error);
            throw error;
        }
    },
    getCart: async (token) => {
        try {
            const response = await axios.get('http://localhost:8000/api/cart', {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            return response.data;
        } catch (error) {
            console.error("Error fetching cart:", error);
            throw error;
        }
    },
};

export default apiCart;

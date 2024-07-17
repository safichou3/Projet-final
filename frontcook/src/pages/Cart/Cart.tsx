// src/pages/Cart.jsx
import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import { useCart } from '../../context/CartContext';

const Cart = () => {
    const [loading, setLoading] = useState(true);
    const { cart, setCart, removeFromCart } = useCart();
    const navigate = useNavigate();

    useEffect(() => {
        const token = localStorage.getItem('token');
        axios.get('http://localhost:8000/api/cart', {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
            .then(response => {
                if (response.headers['content-type'].includes('application/json')) {
                    setCart(response.data.items);
                    console.log(response.data.items);
                } else {
                    console.error('Unexpected response format:', response);
                }
                setLoading(false);
            })
            .catch(error => {
                console.error('There was an error fetching the cart!', error);
                setLoading(false);
            });
    }, [setCart]);

    const handleOrder = () => {
        const token = localStorage.getItem('token');
        axios.post('http://localhost:8000/api/create-order', {}, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
            .then(response => {
                const { order_id } = response.data;
                navigate(`/order-confirmation/${order_id}`);
            })
            .catch(error => {
                console.error('There was an error creating the order!', error);
            });
    };

    if (loading) {
        return <div>Loading...</div>;
    }

    if (!cart || !Array.isArray(cart)) {
        return <div>Cart data is invalid</div>;
    }

    return (
        <div>
            <h1>Your Cart</h1>
            <ul>
                {cart.map(item => (
                    <li key={item.id}>
                        <h3>{item.recipe.title}</h3>
                        <p>{item.recipe.description}</p>
                        <p>Quantity: {item.quantity}</p>
                        <button onClick={() => removeFromCart(item.id)}>Remove</button>
                    </li>
                ))}
            </ul>
            <button onClick={handleOrder}>Commander</button>
        </div>
    );
};

export default Cart;

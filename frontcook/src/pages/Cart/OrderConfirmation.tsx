import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import axios from 'axios';

const OrderConfirmation = () => {
    const { orderId } = useParams();
    const [order, setOrder] = useState(null);
    const [loading, setLoading] = useState(true);
    const navigate = useNavigate();

    useEffect(() => {
        const fetchOrder = async () => {
            const token = localStorage.getItem('token');
            try {
                const response = await axios.get(`http://localhost:8000/api/orders/${orderId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                setOrder(response.data);
                setLoading(false);
            } catch (error) {
                console.error('There was an error fetching the order!', error);
                setLoading(false);
            }
        };

        fetchOrder();
    }, [orderId]);

    const handlePayment = async () => {
        const token = localStorage.getItem('token');
        try {
            const stripeResponse = await axios.post(`http://localhost:8000/api/create-stripe-session/${orderId}`, {}, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            window.location.href = stripeResponse.data.url;
        } catch (error) {
            console.error('There was an error creating the Stripe session!', error);
        }
    };

    if (loading) {
        return <div>Loading...</div>;
    }

    if (!order) {
        return <div>Order not found</div>;
    }

    return (
        <div>
            <h1>Order Confirmation</h1>
            <p>Order Number: {order.order_number}</p>
            <h2>Order Details</h2>
            <ul>
                {order.cartItems.map((item, index) => (
                    <li key={index}>
                        <h3>{item.recipe ? item.recipe.title : 'Recipe title not available'}</h3>
                        <p>{item.recipe ? item.recipe.description : 'Recipe description not available'}</p>
                        <p>Quantity: {item.quantity}</p>
                    </li>
                ))}
            </ul>
            <button onClick={handlePayment}>Proceed to Payment</button>
        </div>
    );
};

export default OrderConfirmation;

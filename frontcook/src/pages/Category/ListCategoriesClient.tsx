import React, { useState, useEffect } from 'react';
import style from './CategoryClient.module.scss';
import axios from 'axios';
import { useCart } from '../../context/CartContext';

const ListCategoriesClient = ({ categories }) => {
    const { addToCart } = useCart();
    const [dishes, setDishes] = useState({});
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        const fetchDishes = async (categoryId) => {
            setLoading(true);
            try {
                const response = await axios.get(`http://localhost:8000/api/recipes?category_id=${categoryId}`);
                setDishes(prevState => ({ ...prevState, [categoryId]: response.data }));
            } catch (error) {
                console.error(`Error fetching dishes for category ${categoryId}:`, error);
            } finally {
                setLoading(false);
            }
        };

        categories.forEach(category => {
            if (!dishes[category.id]) {
                fetchDishes(category.id);
            }
        });
    }, [categories]);

    return (
        <div className={style.categoryList}>
            {categories?.map((category) => (
                <div key={category.id} className={style.categoryCard}>
                    <h2>{category.title}</h2>
                    <p>{category.description}</p>
                    <div className={style.dishes}>
                        {loading ? (
                            <p>Loading dishes...</p>
                        ) : (
                            dishes[category.id]?.map(dish => (
                                <div key={dish.id} className={style.dishCard}>
                                    <img src={dish.image || "https://via.placeholder.com/300"} alt={dish.title} />
                                    <h3>{dish.title}</h3>
                                    <p>{dish.description}</p>
                                    <button onClick={() => addToCart(dish.id)}>Add to Cart</button>
                                </div>
                            ))
                        )}
                    </div>
                </div>
            ))}
        </div>
    );
};

export default ListCategoriesClient;

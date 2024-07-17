import React from 'react';
import { useCart } from '../../context/CartContext'; // Make sure the path is correct
import style from './RecipeClient.module.scss';

const ListRecipesClient = ({ recipes }) => {
    const { addToCart } = useCart();

    return (
        <div className={style.recipeList}>
            {recipes.map((recipe) => (
                <div key={recipe.id} className={style.recipeCard}>
                    <img src="https://via.placeholder.com/300" alt={recipe.title} className={style.recipeImage} />
                    <h2>{recipe.title}</h2>
                    <p>{recipe.description}</p>
                    <button onClick={() => addToCart(recipe.id)}>Ajouter au panier</button>
                </div>
            ))}
        </div>
    );
};

export default ListRecipesClient;

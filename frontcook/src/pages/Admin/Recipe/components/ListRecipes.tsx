/*
import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';

const RecipeList = () => {
    const [recipes, setRecipes] = useState<any[]>([]);

    useEffect(() => {
        const fetchRecipes = async () => {
            try {
                const response = await axios.get('http://localhost:8000/api/recipe');
                setRecipes(response.data);
            } catch (error) {
                console.error(error);
            }
        };
        fetchRecipes();
    }, []);

    return (
        <div>
            <h1>Recipes</h1>
            <Link to="/recipes/add">Add Recipe</Link>
            <ul>
                {recipes.map(recipe => (
                    <li key={recipe.id}>
                        <Link to={`/recipes/edit/${recipe.id}`}>{recipe.title}</Link>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default RecipeList;
*/

import React from "react";
import {Link} from "react-router-dom";
import {apiDeleteCategory} from "../../../../api/apiCategory";

const ListRecipes = ({recipes}) => {
    async function handleRemoveRecette(evt, id) {
        evt.preventDefault();
        try {
            await apiDeleteCategory(id);
        } catch (e) {
            window.location.reload();
        }
    }

    return (
        <ul>
            {recipes.map(recipe => (
                <li key={recipe.id}>
                    <Link to={`/admin/recipe/edit/${recipe.id}`}>{recipe.title}</Link>
                    <Link to={`/admin/recipe/edit/${recipe.id}`}>Edit</Link>
                    <a onClick={(e) => {
                        if (window.confirm('Delete the item?')) {
                            handleRemoveRecette(e, recipe.id)
                        }
                    }}>Delete</a>
                </li>
            ))}
        </ul>
    );
};

export default ListRecipes;

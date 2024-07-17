import { Splide, SplideSlide, SplideTrack } from '@splidejs/react-splide';
import '@splidejs/react-splide/css/core';
import style from './HomePage.module.scss'; // Assurez-vous que ce fichier existe et est correctement importé
import React, { useContext, useEffect, useState } from "react";
import axios from "axios";
import { AuthContext } from "../../context/AuthContext";
import { useCart } from '../../context/CartContext';
import apiCart from "../../api/apiCart";

const HomePage = () => {
    const { auth } = useContext(AuthContext);
    const { addToCart } = useCart();
    const [profile, setProfile] = useState(null);
    const [recipes, setRecipes] = useState([]);
    const [searchQuery, setSearchQuery] = useState('');

    useEffect(() => {
        const fetchProfile = async () => {
            try {
                const response = await axios.post('http://localhost:8000/api/user/getcurrent', {}, {
                    headers: {
                        Authorization: `Bearer ${auth}`
                    }
                });
                setProfile(response.data);
            } catch (error) {
                console.error("Error fetching profile:", error);
            }
        };

        const fetchRecipes = async () => {
            try {
                const response = await axios.get('http://localhost:8000/api/recipe');
                console.log("Fetched recipes:", response.data);
                if (Array.isArray(response.data)) {
                    setRecipes(response.data);
                } else {
                    console.error("Invalid recipes data:", response.data);
                }
            } catch (error) {
                console.error("Error fetching recipes:", error);
            }
        };

        if (auth) {
            fetchProfile();
        }
        fetchRecipes();
    }, [auth]);

    const handleAddToCart = async (recipe) => {
        try {
            await apiCart.addToCart(recipe.id, 1, auth);
            addToCart(recipe);
            console.log("Recipe added to cart");
        } catch (error) {
            console.error("Error adding recipe to cart:", error);
        }
    };

    const filteredRecipes = recipes.filter(recipe =>
        recipe.title.toLowerCase().includes(searchQuery.toLowerCase())
    );

    return (
        <div className="wrap">
            <div className="p-50">
                <h1>Des bons plats livrés sur votre lieu de travail {profile && profile.name}</h1>
                <p>Merci de commander avant 10h30. Les plats sont livrés entre 11h30 et 12h15</p>
                <input
                    className={style.searchbar}
                    type="search"
                    placeholder="Chercher"
                    value={searchQuery}
                    onChange={e => setSearchQuery(e.target.value)}
                />
                <a href="/recipe" className={style.sliderLink}>Voir plus</a>

                <Splide hasTrack={false} options={{
                    rewind: false, arrows: false, mediaQuery: 'min', breakpoints: {600: {destroy: true,},}
                }}>
                    <div className={style.slider}>
                        <SplideTrack>
                            {
                                filteredRecipes.map(recipe => (
                                    <SplideSlide key={recipe.id} className={style.splide__slide}>
                                        <img src="https://via.placeholder.com/300" alt={recipe.title}/>
                                        <p>{recipe.title}</p>
                                        <button onClick={() => handleAddToCart(recipe)}>Ajouter au panier</button>
                                    </SplideSlide>
                                ))
                            }
                        </SplideTrack>
                    </div>
                </Splide>
            </div>
        </div>
    );
};

export default HomePage;

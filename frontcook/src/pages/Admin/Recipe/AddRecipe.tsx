import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import FormRecipe from "./components/FormRecipe";
import { apiAddRecipe } from "../../../api/apiRecipe";
import { getAllCategories, apiDeleteCategory } from "../../../api/apiCategory";

const AddRecipe = () => {
    const [dataForm, setDataForm] = useState({
        title: '',
        description: '',
        difficulty: '',
        tips: '',
        category: '' // Initialisé à une chaîne vide
    });
    const [dataError, setDataError] = useState({});
    const [isLoading, setIsLoading] = useState(false);
    const [categories, setCategories] = useState([]);
    const navigateTo = useNavigate();

    useEffect(() => {
        async function fetchCategories() {
            try {
                const response = await getAllCategories('');
                console.log("Categories fetched:", response);

                // Assurez-vous que la réponse contient des catégories uniques
                const uniqueCategories = Array.from(new Set(response.map(category => category.id)))
                    .map(id => response.find(category => category.id === id));

                setCategories(uniqueCategories);
            } catch (error) {
                console.error("Failed to fetch categories", error);
                setCategories([]); // En cas d'erreur, définissez categories comme un tableau vide
            }
        }

        fetchCategories();
    }, []);

    const handleSubmit = async (evt) => {
        evt.preventDefault();
        setIsLoading(true);

        const formData = {
            ...dataForm,
        };

        console.log("Form data to submit:", formData);

        try {
            const data = await apiAddRecipe(formData);
            if (data.status === 400 && !data.success) {
                setDataError(data.violations);
            } else {
                navigateTo('/admin/recipe');
            }
        } catch (e) {
            console.log(e);
        } finally {
            setIsLoading(false);
        }
    };

    const handleChange = (evt) => {
        const { name, value } = evt.target;
        setDataForm({ ...dataForm, [name]: value });
    };

    const handleDelete = async (id) => {
        try {
            const response = await apiDeleteCategory(id);
            if (response.status === 204) {
                setCategories(categories.filter(category => category.id !== id));
            } else {
                console.error("Failed to delete category", response);
            }
        } catch (error) {
            console.error("Failed to delete category", error);
        }
    };

    return (
        <div>
            <h2>Add Recipe</h2>
            <FormRecipe
                handleSubmit={handleSubmit}
                handleChange={handleChange}
                isLoading={isLoading}
                dataError={dataError}
                dataForm={dataForm}
                categories={categories} // Passer les catégories au formulaire
            />
            <ul>
                {categories.map(category => (
                    <li key={category.id}>
                        {category.title}
                        <button onClick={() => handleDelete(category.id)}>Delete</button>
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default AddRecipe;

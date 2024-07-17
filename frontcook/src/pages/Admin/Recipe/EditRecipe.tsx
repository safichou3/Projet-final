import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import FormRecipe from "./components/FormRecipe";
import { apiEditRecipe, getOneRecipe } from "../../../api/apiRecipe";
import Loader from "../../../components/Loader/Loader";

const EditRecipe = () => {
    const { id } = useParams();
    const [isOk, setIsOk] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [dataForm, setDataForm] = useState({
        title: '',
        description: '',
        difficulty: '',
        time_prepare: '',
        time_cook: '',
        tips: '',
        nutri_score: '',
        net_weight: '',
        energy_per100g: '',
        allergens: ''
    });
    const [dataError, setDataError] = useState({});
    const navigateTo = useNavigate();

    const handleSubmit = async (evt) => {
        evt.preventDefault();
        setIsLoading(true);
        try {
            const data = await apiEditRecipe(id, dataForm);
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

    useEffect(() => {
        async function fetchRecipe() {
            try {
                const data = await getOneRecipe(id);
                if (data.status === 404) {
                    navigateTo('/404', { replace: true });
                } else {
                    setDataForm(data);
                    setIsOk(true);
                }
            } catch (e) {
                console.log(e);
            }
        }
        fetchRecipe();
    }, [id, navigateTo]);

    return (
        <div>
            <h2>Edit Recipe</h2>
            { !isOk ? (
                <Loader />
            ) : (
                <FormRecipe handleSubmit={handleSubmit} handleChange={handleChange} isLoading={isLoading} dataError={dataError} dataForm={dataForm} />
            )}
        </div>
    );
};

export default EditRecipe;

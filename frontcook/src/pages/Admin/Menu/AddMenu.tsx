import React, { useState, useEffect } from 'react';
import { getAllCategories } from "../../../api/apiCategory";
import FormMenu from "./components/FormMenu";
import { useNavigate } from "react-router-dom";
import { apiAddMenu } from '../../../api/apiMenu';

const AddMenu = () => {
    const [dataForm, setDataForm] = useState({ title: '', description: '', price: '', availability: true, category: '' });
    const [dataError, setDataError] = useState({ title: '', description: '', price: '', availability: '', category: '' });
    const [isLoading, setIsLoading] = useState(false);
    const [categories, setCategories] = useState([]);
    const navigate = useNavigate();

    useEffect(() => {
        async function fetchCategories() {
            try {
                const data = await getAllCategories('');
                setCategories(data.category);
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        }

        fetchCategories();
    }, []);

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setDataForm((prevDataForm) => ({
            ...prevDataForm,
            [name]: type === 'checkbox' ? checked : value
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsLoading(true);
        try {
            const data = await apiAddMenu(dataForm);
            if (data.status === 400 && !data.success) {
                setDataError(data.violations);
            } else {
                navigate('/admin/menu');
            }
        } catch (error) {
            console.error('Error adding menu:', error);
            alert('Error adding menu. Check the console for details.');
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div>
            <h1>Ajouter un menu</h1>
            <FormMenu
                handleSubmit={handleSubmit}
                dataForm={dataForm}
                handleChange={handleChange}
                dataError={dataError}
                isLoading={isLoading}
                categories={categories}
                setDataForm={setDataForm}
            />
        </div>
    );
};

export default AddMenu;

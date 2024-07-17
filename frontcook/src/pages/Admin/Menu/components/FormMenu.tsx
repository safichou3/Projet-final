import React from 'react';
import InputForm from "../../../../components/Form/InputForm";
import TextareaForm from "../../../../components/Form/TextareaForm";

const FormMenu = ({ handleSubmit, dataForm, handleChange, dataError, isLoading, categories, setDataForm }) => {
    const handlePriceChange = (e) => {
        const { value } = e.target;
        if (/^\d*\.?\d*$/.test(value)) { // Regular expression to allow only numbers and one decimal point
            setDataForm((prevDataForm) => ({
                ...prevDataForm,
                price: value
            }));
        }
    };

    return (
        <form onSubmit={handleSubmit} className="wrapform">
            <InputForm
                id="menu_title"
                name="title"
                type="text"
                value={dataForm.title}
                handleChange={handleChange}
                label="Nom du menu"
                errormessage={dataError.title}
            />

            <TextareaForm
                id="menu_description"
                name="description"
                value={dataForm.description}
                handleChange={handleChange}
                label="Description"
                errormessage={dataError.description}
            />

            <label htmlFor="menu_price">Prix</label>
            <input
                id="menu_price"
                name="price"
                type="text"
                value={dataForm.price}
                onChange={handlePriceChange}
            />
            {dataError.price && <div className="error">{dataError.price}</div>}

            <label>Le menu est-il disponible ?</label>
            <input
                id="menu_available"
                name="availability"
                type="checkbox"
                checked={dataForm.availability}
                onChange={handleChange}
            />
            {dataError.availability && <div className="error">{dataError.availability}</div>}

            <label>Catégorie</label>
            <select name="category" value={dataForm.category} onChange={handleChange}>
                <option value="">Sélectionnez une catégorie</option>
                {categories.map((category) => (
                    <option key={category.id} value={category.id}>
                        {category.title}
                    </option>
                ))}
            </select>
            {dataError.category && <div className="error">{dataError.category}</div>}

            <button disabled={isLoading} type="submit">
                {isLoading ? 'Loading ....' : 'Ajouter'}
            </button>
        </form>
    );
};

export default FormMenu;

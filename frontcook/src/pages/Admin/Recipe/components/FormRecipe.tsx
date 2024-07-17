import React from 'react';

const FormRecipe = ({ handleSubmit, handleChange, isLoading, dataError, dataForm, categories = [] }) => {
    return (
        <form onSubmit={handleSubmit}>
            <div>
                <label>Title</label>
                <input
                    type="text"
                    name="title"
                    value={dataForm.title}
                    onChange={handleChange}
                />
                {dataError.title && <span>{dataError.title}</span>}
            </div>
            <div>
                <label>Description</label>
                <textarea
                    name="description"
                    value={dataForm.description}
                    onChange={handleChange}
                />
                {dataError.description && <span>{dataError.description}</span>}
            </div>
            <div>
                <label>Difficulty</label>
                <select
                    name="difficulty"
                    value={dataForm.difficulty}
                    onChange={handleChange}
                >
                    <option value="">Select Difficulty</option>
                    <option value="facile">Facile</option>
                    <option value="moyen">Moyen</option>
                    <option value="difficile">Difficile</option>
                </select>
                {dataError.difficulty && <span>{dataError.difficulty}</span>}
            </div>
            <div>
                <label>Tips</label>
                <textarea
                    name="tips"
                    value={dataForm.tips}
                    onChange={handleChange}
                />
                {dataError.tips && <span>{dataError.tips}</span>}
            </div>
            <div>
                <label>Category</label>
                <select
                    name="category"
                    value={dataForm.category}
                    onChange={handleChange}
                >
                    <option value="">Select Category</option>
                    {categories.map(category => (
                        <option key={category.id} value={category.id}>
                            {category.title}
                        </option>
                    ))}
                </select>
                {dataError.category && <span>{dataError.category}</span>}
            </div>
            <button type="submit" disabled={isLoading}>Add Recipe</button>
        </form>
    );
};

export default FormRecipe;

import React from 'react';
import {Link} from "react-router-dom";
import {apiDeleteCategory} from "../../../../api/apiCategory";

const ListCategories = ({categories}) => {
    async function handleRemoveRecette(evt, id) {
        evt.preventDefault();
        try {
            await apiDeleteCategory(id);
        } catch (e) {
            window.location.reload();
        }
    }

    return (
        <div>
            {categories?.map((c) => (
                <div key={c.id}>
                    <h2>{c.title}</h2>
                    <p>{c.description}</p>
                    <Link to={`/admin/category/edit/${c.id}`}>Edit</Link>

                    <a onClick={(e) => {
                        if (window.confirm('Delete the item?')) {
                            handleRemoveRecette(e, c.id)
                        }
                    }}>Delete</a>

                </div>
            ))}
        </div>
    );
};

export default ListCategories;
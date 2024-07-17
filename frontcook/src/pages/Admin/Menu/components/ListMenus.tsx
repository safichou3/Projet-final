import React from 'react';
import {Link} from "react-router-dom";
import {apiDeleteMenu} from "../../../../api/apiMenu";

const ListMenus = ({menus}) => {
    async function handleRemoveRecette(evt, id) {
        evt.preventDefault();
        try {
            await apiDeleteMenu(id);
        } catch (e) {
            window.location.reload();
        }
    }

    return (
        <div>
            {menus?.map((c) => (
                <div key={c.id}>
                    <p>{c.title}</p>
                    <Link to={`/admin/menu/edit/${c.id}`}>Edit</Link>

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

export default ListMenus;

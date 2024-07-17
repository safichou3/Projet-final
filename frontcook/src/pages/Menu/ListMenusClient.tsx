import React from 'react';


const ListMenusClient = ({menus}) => {

    return (
        <div>
            <div className="menuPage">

                {menus?.map((c) => (
                    <div key={c.id}>
                        <p>{c.title}</p>
                        <p>{c.description}</p>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default ListMenusClient;

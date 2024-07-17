import React from 'react';
import {Outlet} from "react-router-dom";
import HeaderAdmin from "../../components/Front/HeaderAdmin/HeaderAdmin";
import FooterAdmin from "../../components/Front/FooterAdmin/FooterAdmin";

const Admin = () => {
    return (
        <div>

            <HeaderAdmin/>
            {/*<h1>Admin</h1>*/}
            <Outlet/>

            <FooterAdmin/>
        </div>
    );
};

export default Admin;
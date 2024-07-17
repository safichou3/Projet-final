import React from 'react';
import { Navigate, Outlet, useLocation } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

const RedirectAuthenticatedRoute = () => {
    const { auth } = useAuth();
    const location = useLocation();

    if (auth) {
        return <Navigate to="/" replace />;
    }

    return <Outlet />;
};

export default RedirectAuthenticatedRoute;

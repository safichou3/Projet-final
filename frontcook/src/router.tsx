import {createBrowserRouter} from 'react-router-dom';
import {lazy} from "react";
import App from "./App.tsx";
import Profil from "./pages/Profil/Profil";
import HomePage from "./pages/HomePage/HomePage";

const Login = lazy(() => import ('./pages/Security/Login'));
import Admin from "./pages/Admin/Admin";
import DashBoard from "./pages/Admin/Dashboard/Dashboard";
import ErrorPage from "./pages/ErrorPage/ErrorPage";
import Category from "./pages/Admin/Category/Category";
import AddCategory from "./pages/Admin/Category/AddCategory";
import EditCategory from "./pages/Admin/Category/EditCategory";

// @ts-ignore
export const router = createBrowserRouter([
    {
        path: '/',
        element: <App/>,
        errorElement: <ErrorPage/>,
        children: [
            {
                index: true,
                element: <HomePage/>,
            },
            {
                path: '/profil',
                element: <Profil/>,
            },
            {
                path: '/login',
                element: <Login/>,
            }
        ]
    },
    {
        path: '/admin',
        element: <Admin/>,
        children: [
            {
                index: true,
                element: <DashBoard/>
            },
            {
                path: '/admin/category',
                element: <Category/>
            },
            {
                path: '/admin/category/add',
                element: <AddCategory/>
            },
            {
                path: '/admin/category/edit/:id',
                element: <EditCategory/>
            }
        ]
    }


])
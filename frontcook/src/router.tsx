// src/router.jsx
import { createBrowserRouter } from 'react-router-dom';
import React, { lazy, Suspense } from 'react';
import App from "./App.tsx";
import HomePage from "./pages/HomePage/HomePage";
import Cart from "./pages/Cart/Cart";
import StripeContainer from "./components/Stripe/StripeContainer"; // Ajouté pour Stripe
import Admin from "./pages/Admin/Admin";
import ErrorPage from "./pages/ErrorPage/ErrorPage";
import MenuClient from "./pages/Menu/MenuClient";
import RecipeClient from "./pages/Recipe/RecipeClient";
import CategoryClient from "./pages/Category/CategoryClient";
import ProtectedRoute from './components/ProtectedRoute/ProtectedRoute';
import RedirectAuthenticatedRoute from './components/RedirectIfAuthenticatedRoute/RedirectIfAuthenticatedRoute';
import Profile from "./pages/Profil/Profile";
import DashBoard from "./pages/Admin/Dashboard/Dashboard";
import Category from "./pages/Admin/Category/Category";
import AddCategory from "./pages/Admin/Category/AddCategory";
import EditCategory from "./pages/Admin/Category/EditCategory";
import Menu from "./pages/Admin/Menu/Menu";
import AddMenu from "./pages/Admin/Menu/AddMenu";
import EditMenu from "./pages/Admin/Menu/EditMenu";
import Recipe from "./pages/Admin/Recipe/Recipe";
import AddRecipe from "./pages/Admin/Recipe/AddRecipe";
import EditRecipe from "./pages/Admin/Recipe/EditRecipe";
import OrderConfirmation from "./pages/Cart/OrderConfirmation";
import PaymentSuccess from "./pages/Cart/PaymentSuccess"; // Ajouté pour la page de succès du paiement

const Login = lazy(() => import('./pages/Security/Login'));
const Register = lazy(() => import('./pages/Security/Register'));

const Loading = () => <div>Loading...</div>;

export const router = createBrowserRouter([
    {
        path: '/',
        element: <App />,
        errorElement: <ErrorPage />,
        children: [
            {
                index: true,
                element: <HomePage />,
            },
            {
                path: '/login',
                element: (
                    <Suspense fallback={<Loading />}>
                        <RedirectAuthenticatedRoute />
                    </Suspense>
                ),
                children: [
                    {
                        path: '',
                        element: (
                            <Suspense fallback={<Loading />}>
                                <Login />
                            </Suspense>
                        ),
                    }
                ]
            },
            {
                path: '/register',
                element: (
                    <Suspense fallback={<Loading />}>
                        <RedirectAuthenticatedRoute />
                    </Suspense>
                ),
                children: [
                    {
                        path: '',
                        element: (
                            <Suspense fallback={<Loading />}>
                                <Register />
                            </Suspense>
                        ),
                    }
                ]
            },
            {
                path: '/',
                element: <ProtectedRoute />,
                children: [
                    {
                        path: '/profile',
                        element: <Profile />,
                    },
                    {
                        path: '/cart',
                        element: <Cart />,
                    },
                    {
                        path: '/order-confirmation/:orderId',
                        element: <OrderConfirmation />
                    },
                    {
                        path: '/checkout',
                        element: <StripeContainer />,
                    },
                    {
                        path: '/payment-success/:orderId',
                        element: <PaymentSuccess />,
                    }
                ]
            },
            {
                path: 'menu',
                element: <MenuClient />
            },
            {
                path: 'recipe',
                element: <RecipeClient />
            },
            {
                path: 'category',
                element: <CategoryClient />
            }
        ]
    },
    {
        path: '/admin',
        element: <Admin />,
        errorElement: <ErrorPage />,
        children: [
            {
                index: true,
                element: <DashBoard />
            },
            {
                path: '/admin/category',
                element: <Category />
            },
            {
                path: '/admin/category/add',
                element: <AddCategory />
            },
            {
                path: '/admin/category/edit/:id',
                element: <EditCategory />
            },
            {
                path: '/admin/menu',
                element: <Menu />
            },
            {
                path: '/admin/menu/add',
                element: <AddMenu />
            },
            {
                path: '/admin/menu/edit/:id',
                element: <EditMenu />
            },
            {
                path: '/admin/recipe',
                element: <Recipe />
            },
            {
                path: '/admin/recipe/add',
                element: <AddRecipe />
            },
            {
                path: '/admin/recipe/edit/:id',
                element: <EditRecipe />
            }
        ]
    }
]);

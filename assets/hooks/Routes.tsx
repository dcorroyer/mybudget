import React from 'react'
import {RouterProvider, createBrowserRouter} from 'react-router-dom'

import {useAuth} from '@/hooks/AuthProvider'
import {PrivateRoute} from '@/hooks/PrivateRoute'

import HomePage from '@/layout/HomePage';
import DashboardPage from '@/layout/DashboardPage';
import RegisterForm from '@/layout/RegisterForm';
import LoginForm from '@/layout/LoginForm';
import RedirectPage from '@/layout/RedirectPage';

const Routes = () => {
    const {token} = useAuth()

    const publicRoutes = [
        {
            path: '/',
            element: <HomePage/>,
        },
    ]

    const privateRoutes = [
        {
            path: '/',
            element: <PrivateRoute/>,
            children: [
                {
                    path: '/dashboard',
                    element: <DashboardPage/>,
                },
            ],
        },
    ]

    const authRoutes = [
        {
            path: '/register',
            element: <RegisterForm/>,
        },
        {
            path: '/login',
            element: <LoginForm/>,
        },
    ]

    const router = createBrowserRouter([
        ...publicRoutes,
        ...(!token ? authRoutes : [{path: '*', element: <RedirectPage />}]),
        ...privateRoutes,
    ])

    return <RouterProvider router={router}/>
}

export default Routes

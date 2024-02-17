import React from 'react'
import { RouterProvider, createBrowserRouter } from 'react-router-dom'

import { useAuth } from '@/hooks/AuthProvider'
import { PrivateRoutes } from '@/routes/PrivateRoutes'

import HomePage from '@/pages/HomePage'
import DashboardPage from '@/pages/dashboard/DashboardPage'
import RegisterPage from '@/pages/authentication/RegisterPage'
import LoginPage from '@/pages/authentication/LoginPage'
import RedirectPage from '@/pages/authentication/RedirectPage'

const Routes = () => {
    const { token } = useAuth()

    const publicRoutes = [
        {
            path: '/',
            element: <HomePage />,
        },
    ]

    const privateRoutes = [
        {
            path: '/',
            element: <PrivateRoutes />,
            children: [
                {
                    path: '/dashboard',
                    element: <DashboardPage />,
                },
            ],
        },
    ]

    const authRoutes = [
        {
            path: '/register',
            element: <RegisterPage />,
        },
        {
            path: '/login',
            element: <LoginPage />,
        },
    ]

    const router = createBrowserRouter([
        ...publicRoutes,
        ...(!token ? authRoutes : [{ path: '*', element: <RedirectPage /> }]),
        ...privateRoutes,
    ])

    return <RouterProvider router={router} />
}

export default Routes

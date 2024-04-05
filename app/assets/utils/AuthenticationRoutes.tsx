import React from 'react'
import { Navigate, Outlet } from 'react-router-dom'

import { useAuth } from '@/hooks/AuthProvider'

const AuthenticationRoutes = (): React.JSX.Element => {
    const { token } = useAuth()

    if (token) {
        return <Navigate to='/' />
    }

    return <Outlet />
}

export default AuthenticationRoutes

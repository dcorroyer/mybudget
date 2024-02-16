import React from 'react'
import { Navigate, Outlet } from 'react-router-dom'

import { useAuth } from '@/hooks/AuthProvider'

export const PrivateRoute = (): React.JSX.Element => {
    const { token } = useAuth()

    if (!token) {
        return <Navigate to='/login' />
    }

    return <Outlet />
}

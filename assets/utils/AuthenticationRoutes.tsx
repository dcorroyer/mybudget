import React, { useEffect } from 'react'
import { Navigate, Outlet } from 'react-router-dom'

import { useToast } from '@/components/ui/toasts/use-toast'
import { useAuth } from '@/hooks/AuthProvider'

const AuthenticationRoutes = (): React.JSX.Element => {
    const { token } = useAuth()
    const { toast } = useToast()

    useEffect(() => {
        if (token) {
            toast({
                title: 'You are already logged in',
                description: 'You need to be logged out to access this page',
                variant: 'default',
            })
        }
    }, [token, toast])

    if (token) {
        return <Navigate to='/dashboard' />
    }

    return <Outlet />
}

export default AuthenticationRoutes

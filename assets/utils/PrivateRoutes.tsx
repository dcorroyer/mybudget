import React, { useEffect } from 'react'
import { Navigate, Outlet } from 'react-router-dom'

import { useToast } from '@/components/ui/toasts/use-toast'
import { useAuth } from '@/hooks/AuthProvider'

const PrivateRoutes = () => {
    const { token } = useAuth()
    const { toast } = useToast()

    useEffect(() => {
        if (!token) {
            toast({
                title: 'You are not logged in',
                description: 'You need to be logged to access this page',
                variant: 'destructive',
            })
        }
    }, [token, toast])

    if (!token) {
        return <Navigate to='/login' />
    }

    return <Outlet />
}

export default PrivateRoutes

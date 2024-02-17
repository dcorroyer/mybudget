import React from 'react'
import { Navigate, Outlet } from 'react-router-dom'

import { useAuth } from '@/hooks/AuthProvider'
import { useToast } from '@/components/ui/toasts/use-toast'

export const PrivateRoutes = (): React.JSX.Element => {
    const { token } = useAuth()
    const { toast } = useToast()

    if (!token) {
        toast({
            title: 'You are not logged in',
            description: 'You need to be logged to access this page',
            variant: 'destructive',
        })

        return <Navigate to='/login' />
    }

    return <Outlet />
}

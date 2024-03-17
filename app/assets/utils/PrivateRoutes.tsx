import React, { useEffect } from 'react'
import { Navigate, Outlet } from 'react-router-dom'

import { useToast } from '@/components/hooks/UseToast'
import { useAuth } from '@/hooks/AuthProvider'

const PrivateRoutes = (): React.JSX.Element => {
    const { token, getExpireDateToken, clearToken, checkTokenValidity } = useAuth()
    const { toast } = useToast()

    useEffect(() => {
        checkTokenValidity()

        const interval = setInterval((): void => {
            if (!token) {
                toast({
                    title: 'You are not logged in',
                    description: 'You need to be logged in to access this page',
                    variant: 'destructive',
                })
            } else {
                const decodedToken = getExpireDateToken(token)
                if (decodedToken * 1000 < Date.now()) {
                    clearToken()
                    toast({
                        title: 'Session expired',
                        description: 'Your session has expired. Please log in again.',
                        variant: 'destructive',
                    })
                }
            }
        }, 1000)

        return () => clearInterval(interval)
    }, [token, toast, clearToken])

    if (!token) {
        return <Navigate to='/login' />
    }

    return <Outlet />
}

export default PrivateRoutes

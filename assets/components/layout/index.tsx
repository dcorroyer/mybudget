import React from 'react'
import { useLocation } from 'react-router-dom'

// import Header from "@/components/layout/header";
import Sidebar from '@/components/layout/sidebar'

import { useAuth } from '@/hooks/AuthProvider'

export const Layout = ({ children }: { children: React.ReactNode }) => {
    const { token } = useAuth()
    const location = useLocation()
    const isPrivateRoute = location.pathname.startsWith('/dashboard')

    return (
        <>
            {/*<Header />*/}
            <div className='flex h-screen border-collapse overflow-hidden'>
                {token && isPrivateRoute && <Sidebar />}
                <main className='flex-1 overflow-y-auto overflow-x-hidden pt-16 bg-secondary/10 pb-1'>
                    {children}
                </main>
            </div>
        </>
    )
}

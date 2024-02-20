import React from 'react'
import { BrowserRouter, Routes, Route } from 'react-router-dom'

import { ThemeProvider } from '@/components/ui/theme-provider'
import { Toaster } from '@/components/ui/toasts/toaster'
import { Layout } from '@/components/layout'

import AuthProvider from '@/hooks/AuthProvider'

import HomePage from '@/pages/HomePage'
import DashboardPage from '@/pages/dashboard/DashboardPage'
import LoginPage from '@/pages/authentication/LoginPage'

function App(): React.JSX.Element {
    return (
        <ThemeProvider defaultTheme='dark' storageKey='vite-ui-theme'>
            <AuthProvider>
                <BrowserRouter>
                    <Layout>
                        <Routes>
                            <Route path={'/'} element={<HomePage />} />
                            <Route path={'/dashboard'} element={<DashboardPage />} />
                            <Route path={'/login'} element={<LoginPage />} />
                        </Routes>
                    </Layout>
                </BrowserRouter>
            </AuthProvider>
            <Toaster />
        </ThemeProvider>
    )
}

export default App

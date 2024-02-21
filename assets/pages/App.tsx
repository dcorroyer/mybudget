import React from 'react'
import { BrowserRouter, Routes, Route } from 'react-router-dom'

import { ThemeProvider } from '@/components/ui/theme-provider'
import { Toaster } from '@/components/ui/toasts/toaster'
import { Layout } from '@/components/layout'

import AuthProvider from '@/hooks/AuthProvider'
import PrivateRoutes from '@/utils/PrivateRoutes'

import HomePage from '@/pages/HomePage'
import DashboardPage from '@/pages/dashboard/DashboardPage'
import LoginPage from '@/pages/authentication/LoginPage'
import RegisterPage from '@/pages/authentication/RegisterPage'

function App(): React.JSX.Element {
    return (
        <ThemeProvider defaultTheme='dark' storageKey='vite-ui-theme'>
            <AuthProvider>
                <BrowserRouter>
                    <Layout>
                        <Routes>
                            <Route path={'/'} element={<HomePage />} />
                            <Route element={<PrivateRoutes />}>
                                <Route path={'/dashboard'} element={<DashboardPage />} />
                            </Route>
                            <Route path={'/login'} element={<LoginPage />} />
                            <Route path={'/register'} element={<RegisterPage />} />
                        </Routes>
                    </Layout>
                    <Toaster />
                </BrowserRouter>
            </AuthProvider>
        </ThemeProvider>
    )
}

export default App

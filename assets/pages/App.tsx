import React from 'react'
import { BrowserRouter, Routes, Route } from 'react-router-dom'

import { ThemeProvider } from '@/components/ui/theme-provider'
import { Toaster } from '@/components/ui/toasts/toaster'
import { Layout } from '@/components/layout'

import AuthProvider from '@/hooks/AuthProvider'
import SidebarStateProvider from '@/hooks/SidebarStateProvider'
import PrivateRoutes from '@/utils/PrivateRoutes'
import AuthenticationRoutes from '@/utils/AuthenticationRoutes'

import HomePage from '@/pages/HomePage'
import DashboardPage from '@/pages/dashboard/DashboardPage'
import LoginPage from '@/pages/authentication/LoginPage'
import RegisterPage from '@/pages/authentication/RegisterPage'

function App(): React.JSX.Element {
    return (
        <ThemeProvider defaultTheme='dark' storageKey='vite-ui-theme'>
            <AuthProvider>
                <SidebarStateProvider>
                    <BrowserRouter>
                        <Layout>
                            <Routes>
                                <Route path={'/'} element={<HomePage />} />
                                <Route element={<PrivateRoutes />}>
                                    <Route path={'/dashboard'} element={<DashboardPage />} />
                                </Route>
                                <Route element={<AuthenticationRoutes />}>
                                    <Route path={'/login'} element={<LoginPage />} />
                                    <Route path={'/register'} element={<RegisterPage />} />
                                </Route>
                            </Routes>
                        </Layout>
                        <Toaster />
                    </BrowserRouter>
                </SidebarStateProvider>
            </AuthProvider>
        </ThemeProvider>
    )
}

export default App

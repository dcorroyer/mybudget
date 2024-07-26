import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import React from 'react'
import ReactDOM from 'react-dom/client'
import { BrowserRouter, Route, Routes } from 'react-router-dom'

import { MantineProvider } from '@mantine/core'
import { Notifications } from '@mantine/notifications'

import '@mantine/core/styles.css'
import '@mantine/notifications/styles.css'

import AuthProvider from '@/providers/AuthProvider'

import AuthenticationRoutes from '@/utils/AuthenticationRoutes'
import PrivateRoutes from '@/utils/PrivateRoutes'

import LoginPage from '@/pages/authentication/LoginPage'
import RegisterPage from '@/pages/authentication/RegisterPage'

import DashboardPage from '@/pages/dashboard/DashboardPage'

const queryClient = new QueryClient()

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <QueryClientProvider client={queryClient}>
      <MantineProvider>
        <Notifications />
        <AuthProvider>
          <BrowserRouter>
            <Routes>
              <Route element={<PrivateRoutes />}>
                <Route path={'/'} element={<DashboardPage />} />
              </Route>
              <Route element={<AuthenticationRoutes />}>
                <Route path={'/login'} element={<LoginPage />} />
                <Route path={'/register'} element={<RegisterPage />} />
              </Route>
            </Routes>
          </BrowserRouter>
        </AuthProvider>
      </MantineProvider>
    </QueryClientProvider>
  </React.StrictMode>,
)

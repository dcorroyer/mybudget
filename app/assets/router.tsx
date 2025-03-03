import React, { PropsWithChildren, useEffect, useState } from 'react'
import { createBrowserRouter, Navigate, RouterProvider } from 'react-router-dom'
import AuthenticatedLayout from './layouts/AuthenticatedLayout'

const LoginPage = React.lazy(() => import('./features/auth/pages/LoginPage'))
const RegisterPage = React.lazy(() => import('./features/auth/pages/RegisterPage'))

const BudgetIndexPage = React.lazy(() => import('./features/budgets/pages/BudgetIndexPage'))
const BudgetCreatePage = React.lazy(() => import('./features/budgets/pages/BudgetCreatePage'))
const BudgetDetailPage = React.lazy(() => import('./features/budgets/pages/BudgetDetailPage'))

const MainPage = React.lazy(() => import('./features/savings/pages'))

function ProtectedRoute({ children }: PropsWithChildren) {
  const [isAuthenticated, setIsAuthenticated] = useState<boolean | null>(null)

  useEffect(() => {
    const token = localStorage.getItem('token')
    setIsAuthenticated(!!token)
  }, [])

  if (isAuthenticated === null) {
    return null
  }

  if (!isAuthenticated) {
    return <Navigate to='/auth/login' replace />
  }

  return <AuthenticatedLayout>{children}</AuthenticatedLayout>
}

const router = createBrowserRouter([
  {
    path: '/',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <MainPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/budgets',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <BudgetIndexPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/budgets/create',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <BudgetCreatePage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/budgets/:id',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <BudgetDetailPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },

  // Auth routes
  {
    path: '/auth/login',
    element: (
      <React.Suspense fallback={null}>
        <LoginPage />
      </React.Suspense>
    ),
  },
  {
    path: '/auth/register',
    element: (
      <React.Suspense fallback={null}>
        <RegisterPage />
      </React.Suspense>
    ),
  },
])

export default function Router() {
  return <RouterProvider router={router} />
}

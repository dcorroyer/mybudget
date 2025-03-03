import React, { PropsWithChildren } from 'react'
import { createBrowserRouter, Navigate, RouterProvider } from 'react-router-dom'
import { useUser } from './features/auth/hooks/useUser'
import AuthenticatedLayout from './layouts/authenticated-layout'

const LoginPage = React.lazy(() => import('./features/auth/pages/login'))
const RegisterPage = React.lazy(() => import('./features/auth/pages/register'))

const BudgetIndexPage = React.lazy(() => import('./features/budgets/pages'))
const BudgetCreatePage = React.lazy(() => import('./features/budgets/pages/create'))
const BudgetDetailPage = React.lazy(() => import('./features/budgets/pages/detail'))

const MainPage = React.lazy(() => import('./features/savings/pages'))

function ProtectedRoute({ children }: PropsWithChildren) {
  const { user, isFetching } = useUser()

  if (isFetching) return

  if (!user) return <Navigate to='/auth/login' replace />

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

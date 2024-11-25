import React, { PropsWithChildren } from 'react'
import { createBrowserRouter, Navigate, RouterProvider } from 'react-router-dom'
import { useUser } from './features/auth/hooks/useUser'
import AuthenticatedLayout from './layouts/authenticated-layout'

const NotFoundPage = React.lazy(() => import('./components/not-found'))

const LoginPage = React.lazy(() => import('./features/auth/pages/login'))
const RegisterPage = React.lazy(() => import('./features/auth/pages/register'))

const BudgetListPage = React.lazy(() => import('./features/budgets/pages/list'))
const BudgetCreatePage = React.lazy(() => import('./features/budgets/pages/create'))
const BudgetDetailPage = React.lazy(() => import('./features/budgets/pages/detail'))

const AccountListPage = React.lazy(() => import('./features/accounts/pages/list'))
const AccountCreatePage = React.lazy(() => import('./features/accounts/pages/create'))
const AccountDetailPage = React.lazy(() => import('./features/accounts/pages/detail'))

const SavingsPage = React.lazy(() => import('./features/savings/pages/list'))

const TransactionListPage = React.lazy(() => import('./features/transactions/pages/list'))
const TransactionCreatePage = React.lazy(() => import('./features/transactions/pages/create'))
const TransactionDetailPage = React.lazy(() => import('./features/transactions/pages/detail'))

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
          <NotFoundPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/budgets',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <BudgetListPage />
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
  {
    path: '/accounts',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <AccountListPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/accounts/create',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <AccountCreatePage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/accounts/:id',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <AccountDetailPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/accounts/:accountId/transactions/:id',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <TransactionDetailPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/savings',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <SavingsPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/transactions',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <TransactionListPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/transactions/create',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={null}>
          <TransactionCreatePage />
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

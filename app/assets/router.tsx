import { Loader } from '@mantine/core'
import React, { PropsWithChildren } from 'react'
import { createBrowserRouter, Navigate, RouterProvider } from 'react-router-dom'
import { useUser } from './features/auth/hooks/useUser'
import AuthenticatedLayout from './layouts/authenticated-layout'

const NotFoundPage = React.lazy(() => import('./components/not-found'))

const LoginPage = React.lazy(() => import('./features/auth/pages/login'))
const RegisterPage = React.lazy(() => import('./features/auth/pages/register'))

const BudgetListPage = React.lazy(() => import('./features/budgets/pages/list'))
const BudgetCreatePage = React.lazy(() => import('./features/budgets/pages/create'))
// const BudgetDetailPage = React.lazy(() => import('./features/budgets/pages/detail'))

function ProtectedRoute({ children }: PropsWithChildren) {
  const { user, isFetching } = useUser()

  if (isFetching) return <Loader />
  if (!user) return <Navigate to='/auth/login' replace />

  return <AuthenticatedLayout>{children}</AuthenticatedLayout>
}

const router = createBrowserRouter([
  {
    path: '/',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={'Loading...'}>
          <NotFoundPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/budgets',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={<Loader />}>
          <BudgetListPage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  {
    path: '/budgets/create',
    element: (
      <ProtectedRoute>
        <React.Suspense fallback={<Loader />}>
          <BudgetCreatePage />
        </React.Suspense>
      </ProtectedRoute>
    ),
  },
  // {
  //   path: '/budgets/:id',
  //   element: (
  //     <ProtectedRoute>
  //       <React.Suspense fallback={<Loader />}>
  //         <BudgetDetailPage />
  //       </React.Suspense>
  //     </ProtectedRoute>
  //   ),
  // },

  // Auth routes
  {
    path: '/auth/login',
    element: (
      <React.Suspense fallback={'Loading...'}>
        <LoginPage />
      </React.Suspense>
    ),
  },
  {
    path: '/auth/register',
    element: (
      <React.Suspense fallback={'Loading...'}>
        <RegisterPage />
      </React.Suspense>
    ),
  },
])

export default function Router() {
  return <RouterProvider router={router} />
}

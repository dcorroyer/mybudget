import React, { PropsWithChildren } from 'react'
import { createBrowserRouter, RouterProvider } from 'react-router-dom'
// import { useUser } from './features/auth/hooks/useUser'
import AuthenticatedLayout from './layouts/authenticated-layout'

const NotFoundPage = React.lazy(() => import('./components/not-found'))

const LoginPage = React.lazy(() => import('./features/auth/pages/login'))
const Register = React.lazy(() => import('./features/auth/pages/register'))

function ProtectedRoute({ children }: PropsWithChildren) {
  // const { user } = useUser()
  // if (!user) return <Navigate to='/auth/login' replace />

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
        <Register />
      </React.Suspense>
    ),
  },
])

export default function () {
  return <RouterProvider router={router} />
}

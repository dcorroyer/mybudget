import React from 'react'
import { Navigate, Outlet } from 'react-router-dom'

import { useAuthProvider } from '@/providers/AuthProvider'

const AuthenticationRoutes = (): React.JSX.Element => {
  const { token } = useAuthProvider()

  if (token) {
    return <Navigate to='/' />
  }

  return <Outlet />
}

export default AuthenticationRoutes

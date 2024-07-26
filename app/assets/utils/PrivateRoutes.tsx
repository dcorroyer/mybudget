import React from 'react'
import { Navigate, Outlet } from 'react-router-dom'

import { useAuthProvider } from '@/providers/AuthProvider'

const PrivateRoutes = (): React.JSX.Element => {
  const { token, checkTokenValidity } = useAuthProvider()

  checkTokenValidity()

  if (!token) {
    return <Navigate to='/login' />
  }

  return <Outlet />
}

export default PrivateRoutes

import React from 'react'
import { Navigate, Outlet } from 'react-router-dom'

import { useAuth } from '@/hooks/AuthProvider'

const PrivateRoutes = (): React.JSX.Element => {
  const { token, checkTokenValidity } = useAuth()

  checkTokenValidity()

  if (!token) {
    return <Navigate to='/login' />
  }

  return <Outlet />
}

export default PrivateRoutes

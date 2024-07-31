import React from 'react'
import { Navigate, Outlet } from 'react-router-dom'

import { useAuthProvider } from '@/providers/AuthProvider'

const PrivateRoutes = (): React.JSX.Element => {
  const { token, checkTokenValidity } = useAuthProvider()

  // Check if token is valid 250ms after page load
  React.useEffect(() => {
    const timer = setTimeout(() => {
      checkTokenValidity()
    }, 250)

    return () => clearTimeout(timer)
  }, [])

  if (token === null) {
    return <Navigate to='/login' />
  }

  return <Outlet />
}

export default PrivateRoutes

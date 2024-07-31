import React from 'react'

import { AuthenticatedLayout } from '@/layout/authenticated-layout'
import { useAuthProvider } from '@/providers/AuthProvider'
import { UnAuthenticatedLayout } from './layout/unauthenticated-layout'

const AppRouter = () => {
  const { token, checkTokenValidity } = useAuthProvider()

  checkTokenValidity()

  if (token !== null) {
    return <AuthenticatedLayout />
  }

  return <UnAuthenticatedLayout />
}

export default AppRouter

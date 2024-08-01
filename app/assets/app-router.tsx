import React from 'react'

import { AuthenticatedLayout } from '@/layouts/authenticated-layout'
import { useAuthProvider } from '@/providers/AuthProvider'
import { UnAuthenticatedLayout } from './layouts/unauthenticated-layout'

const AppRouter = () => {
  const { auth, isAuthenticated, loading } = useAuthProvider()

  auth()

  if (loading) {
    return <div>Chargement...</div>
  }

  if (isAuthenticated === true) {
    return <AuthenticatedLayout />
  }

  return <UnAuthenticatedLayout />
}

export default AppRouter

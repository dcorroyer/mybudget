import React from 'react'

import { Center, Loader } from '@mantine/core'

import { AuthenticatedLayout } from '@/layouts/authenticated-layout'
import { UnAuthenticatedLayout } from '@/layouts/unauthenticated-layout'
import { useAuthProvider } from '@/providers/AuthProvider'

const AppRouter = () => {
  const { auth, isAuthenticated, loading } = useAuthProvider()

  auth()

  if (loading) {
    return (
      <Center>
        <Loader />
      </Center>
    )
  }

  if (isAuthenticated === true) {
    return <AuthenticatedLayout />
  }

  return <UnAuthenticatedLayout />
}

export default AppRouter

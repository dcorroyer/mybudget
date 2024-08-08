import React from 'react'

import { createFileRoute } from '@tanstack/react-router'

import AuthenticatedLayout from '@/layouts/authenticated-layout'

export const Route = createFileRoute('/_authenticated')({
  component: () => {
    // if (!isAuthenticated()) {
    //   return <Login />
    // }

    return <AuthenticatedLayout />
  },
})

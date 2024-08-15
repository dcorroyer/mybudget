import React from 'react'
import ReactDOM from 'react-dom/client'

import { MantineProvider } from '@mantine/core'
import { Notifications } from '@mantine/notifications'

import '@mantine/core/styles.css'
import '@mantine/notifications/styles.css'

import './index.module.css'

import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { createRouter, RouterProvider } from '@tanstack/react-router'

import { useAuthContext } from '@/contexts/AuthContext'
import { routeTree } from './routeTree.gen'

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      retry: false,
      refetchOnWindowFocus: false,
      refetchOnMount: false,
      refetchOnReconnect: false,
    },
  },
})

const router = createRouter({ routeTree, context: { authentication: undefined! } })

declare module '@tanstack/react-router' {
  interface Register {
    router: typeof router
  }
}

const authentication = useAuthContext()

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <QueryClientProvider client={queryClient}>
      <MantineProvider withCssVariables>
        <Notifications />
        <RouterProvider router={router} context={{ authentication }} />
      </MantineProvider>
    </QueryClientProvider>
  </React.StrictMode>,
)

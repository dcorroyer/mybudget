import React from 'react'
import ReactDOM from 'react-dom/client'

import { MantineProvider } from '@mantine/core'
import { Notifications } from '@mantine/notifications'

import { QueryClient, QueryClientProvider } from '@tanstack/react-query'

import Router from './router'

import '@mantine/carousel/styles.css';
import '@mantine/core/styles.css'
import '@mantine/dates/styles.css'
import '@mantine/notifications/styles.css'

import './index.css'

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

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <QueryClientProvider client={queryClient}>
      <MantineProvider withCssVariables>
        <Notifications />
        <Router />
      </MantineProvider>
    </QueryClientProvider>
  </React.StrictMode>,
)

import React from 'react'
import ReactDOM from 'react-dom/client'

import { MantineProvider } from '@mantine/core'
import { Notifications } from '@mantine/notifications'

import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { ReactQueryDevtools } from '@tanstack/react-query-devtools'

import '@mantine/core/styles.css'
import '@mantine/dates/styles.css'
import '@mantine/notifications/styles.css'

import Router from './router'

import Loader from './components/loader'
import './index.module.css'

const queryClient = new QueryClient()

//loader: async ({ params }) => await getBudgetDetail(params.id.toString()),

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <QueryClientProvider client={queryClient}>
      <MantineProvider withCssVariables>
        <ReactQueryDevtools initialIsOpen={false} />
        <Notifications />
        <Loader />
        <Router />
      </MantineProvider>
    </QueryClientProvider>
  </React.StrictMode>,
)

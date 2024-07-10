import React from 'react'
import ReactDOM from 'react-dom/client'

import { MantineProvider } from '@mantine/core'
import '@mantine/core/styles.css'

import { Demo } from './components/Demo'

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <MantineProvider>
      <Demo />
    </MantineProvider>
  </React.StrictMode>,
)

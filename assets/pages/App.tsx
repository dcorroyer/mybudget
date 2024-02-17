import React from 'react'

import { ThemeProvider } from '@/components/ui/theme-provider'
import { Toaster } from '@/components/ui/toasts/toaster'

import AuthProvider from '@/hooks/AuthProvider'
import Routes from '@/routes/Routes'

function App(): React.JSX.Element {
    return (
        <ThemeProvider defaultTheme='dark' storageKey='vite-ui-theme'>
            <AuthProvider>
                <Routes />
            </AuthProvider>
            <Toaster />
        </ThemeProvider>
    )
}

export default App

import React from 'react'
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom'

import HomePage from '@/layout/HomePage'
import LoginForm from '@/layout/LoginForm'
import RegisterForm from '@/layout/RegisterForm'

import { ThemeProvider } from '@/components/ui/theme-provider'

function App(): React.JSX.Element {
    return (
        <ThemeProvider defaultTheme='dark' storageKey='vite-ui-theme'>
            <Router>
                <Routes>
                    <Route path='/' element={<HomePage />} />
                    <Route path='/login' element={<LoginForm />} />
                    <Route path='/register' element={<RegisterForm />} />
                </Routes>
            </Router>
        </ThemeProvider>
    )
}

export default App

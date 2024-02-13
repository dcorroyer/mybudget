import React from 'react'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'

import HomePage from '@/layout/HomePage'
import LoginForm from '@/layout/LoginForm'
import RegisterForm from '@/layout/RegisterForm'

const App = () => {
    return (
        <Router>
            <Routes>
                <Route path='/' element={<HomePage />} />
                <Route path='/login' element={<LoginForm />} />
                <Route path='/register' element={<RegisterForm />} />
            </Routes>
        </Router>
    )
}

export default App

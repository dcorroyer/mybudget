import * as React from 'react'
import { BrowserRouter, Route, Routes } from 'react-router-dom'

import Login from '@/pages/authentication/login'
import Register from '@/pages/authentication/register'

export const UnAuthenticatedLayout = () => {
  return (
    <BrowserRouter>
      <Routes>
        <Route path='/' element={<Login />} />
        <Route path='/register' element={<Register />} />
      </Routes>
    </BrowserRouter>
  )
}

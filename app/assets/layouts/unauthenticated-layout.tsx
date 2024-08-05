import * as React from 'react'
import { BrowserRouter, Route, Routes } from 'react-router-dom'

import { Auth } from '@/pages/authentication/index'

export const UnAuthenticatedLayout = () => {
  return (
    <BrowserRouter>
      <Routes>
        <Route path='/' element={<Auth />} />
      </Routes>
    </BrowserRouter>
  )
}

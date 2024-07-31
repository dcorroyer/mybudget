import { createBrowserRouter, RouterProvider } from 'react-router-dom'

import React from 'react'

import Home from '@/pages/home'

const router = createBrowserRouter([
  {
    path: '/',
    element: <Home />,
  },
])

export function Router() {
  return <RouterProvider router={router} />
}

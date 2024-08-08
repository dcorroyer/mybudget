import React from 'react'

import { Link, Outlet, createRootRoute } from '@tanstack/react-router'

export const Route = createRootRoute({
  component: () => (
    <>
      <h1>My App</h1>
      <ul>
        <li>
          <Link to='/'>Home</Link>
        </li>
        <li>
          <Link to='/budgets'>Budgets</Link>
        </li>
      </ul>
      <Outlet />
    </>
  ),
})

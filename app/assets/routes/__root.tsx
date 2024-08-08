import { Outlet, createRootRouteWithContext } from '@tanstack/react-router'

import { AuthContext } from '@/contexts/AuthContext'

type RouterContext = {
  authentication: AuthContext
}

export const Route = createRootRouteWithContext<RouterContext>()({
  component: Outlet,
})

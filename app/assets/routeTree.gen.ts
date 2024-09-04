/* prettier-ignore-start */

/* eslint-disable */

// @ts-nocheck

// noinspection JSUnusedGlobalSymbols

// This file is auto-generated by TanStack Router

// Import Routes

import { Route as rootRoute } from './routes/__root'
import { Route as RegisterImport } from './routes/register'
import { Route as LoginImport } from './routes/login'
import { Route as AuthenticatedImport } from './routes/_authenticated'
import { Route as AuthenticatedIndexImport } from './routes/_authenticated/index'
import { Route as AuthenticatedBudgetsIndexImport } from './routes/_authenticated/budgets/index'
import { Route as AuthenticatedBudgetsCreateImport } from './routes/_authenticated/budgets/create'
import { Route as AuthenticatedBudgetsIdIndexImport } from './routes/_authenticated/budgets/$id/index'

// Create/Update Routes

const RegisterRoute = RegisterImport.update({
  path: '/register',
  getParentRoute: () => rootRoute,
} as any)

const LoginRoute = LoginImport.update({
  path: '/login',
  getParentRoute: () => rootRoute,
} as any)

const AuthenticatedRoute = AuthenticatedImport.update({
  id: '/_authenticated',
  getParentRoute: () => rootRoute,
} as any)

const AuthenticatedIndexRoute = AuthenticatedIndexImport.update({
  path: '/',
  getParentRoute: () => AuthenticatedRoute,
} as any)

const AuthenticatedBudgetsIndexRoute = AuthenticatedBudgetsIndexImport.update({
  path: '/budgets/',
  getParentRoute: () => AuthenticatedRoute,
} as any)

const AuthenticatedBudgetsCreateRoute = AuthenticatedBudgetsCreateImport.update(
  {
    path: '/budgets/create',
    getParentRoute: () => AuthenticatedRoute,
  } as any,
)

const AuthenticatedBudgetsIdIndexRoute =
  AuthenticatedBudgetsIdIndexImport.update({
    path: '/budgets/$id/',
    getParentRoute: () => AuthenticatedRoute,
  } as any)

// Populate the FileRoutesByPath interface

declare module '@tanstack/react-router' {
  interface FileRoutesByPath {
    '/_authenticated': {
      id: '/_authenticated'
      path: ''
      fullPath: ''
      preLoaderRoute: typeof AuthenticatedImport
      parentRoute: typeof rootRoute
    }
    '/login': {
      id: '/login'
      path: '/login'
      fullPath: '/login'
      preLoaderRoute: typeof LoginImport
      parentRoute: typeof rootRoute
    }
    '/register': {
      id: '/register'
      path: '/register'
      fullPath: '/register'
      preLoaderRoute: typeof RegisterImport
      parentRoute: typeof rootRoute
    }
    '/_authenticated/': {
      id: '/_authenticated/'
      path: '/'
      fullPath: '/'
      preLoaderRoute: typeof AuthenticatedIndexImport
      parentRoute: typeof AuthenticatedImport
    }
    '/_authenticated/budgets/create': {
      id: '/_authenticated/budgets/create'
      path: '/budgets/create'
      fullPath: '/budgets/create'
      preLoaderRoute: typeof AuthenticatedBudgetsCreateImport
      parentRoute: typeof AuthenticatedImport
    }
    '/_authenticated/budgets/': {
      id: '/_authenticated/budgets/'
      path: '/budgets'
      fullPath: '/budgets'
      preLoaderRoute: typeof AuthenticatedBudgetsIndexImport
      parentRoute: typeof AuthenticatedImport
    }
    '/_authenticated/budgets/$id/': {
      id: '/_authenticated/budgets/$id/'
      path: '/budgets/$id'
      fullPath: '/budgets/$id'
      preLoaderRoute: typeof AuthenticatedBudgetsIdIndexImport
      parentRoute: typeof AuthenticatedImport
    }
  }
}

// Create and export the route tree

export const routeTree = rootRoute.addChildren({
  AuthenticatedRoute: AuthenticatedRoute.addChildren({
    AuthenticatedIndexRoute,
    AuthenticatedBudgetsCreateRoute,
    AuthenticatedBudgetsIndexRoute,
    AuthenticatedBudgetsIdIndexRoute,
  }),
  LoginRoute,
  RegisterRoute,
})

/* prettier-ignore-end */

/* ROUTE_MANIFEST_START
{
  "routes": {
    "__root__": {
      "filePath": "__root.tsx",
      "children": [
        "/_authenticated",
        "/login",
        "/register"
      ]
    },
    "/_authenticated": {
      "filePath": "_authenticated.tsx",
      "children": [
        "/_authenticated/",
        "/_authenticated/budgets/create",
        "/_authenticated/budgets/",
        "/_authenticated/budgets/$id/"
      ]
    },
    "/login": {
      "filePath": "login.tsx"
    },
    "/register": {
      "filePath": "register.tsx"
    },
    "/_authenticated/": {
      "filePath": "_authenticated/index.tsx",
      "parent": "/_authenticated"
    },
    "/_authenticated/budgets/create": {
      "filePath": "_authenticated/budgets/create.tsx",
      "parent": "/_authenticated"
    },
    "/_authenticated/budgets/": {
      "filePath": "_authenticated/budgets/index.tsx",
      "parent": "/_authenticated"
    },
    "/_authenticated/budgets/$id/": {
      "filePath": "_authenticated/budgets/$id/index.tsx",
      "parent": "/_authenticated"
    }
  }
}
ROUTE_MANIFEST_END */

/* prettier-ignore-start */

/* eslint-disable */

// @ts-nocheck

// noinspection JSUnusedGlobalSymbols

// This file is auto-generated by TanStack Router

// Import Routes

import { Route as rootRoute } from './routes/__root'
import { Route as IndexImport } from './routes/index'
import { Route as BudgetsIndexImport } from './routes/budgets/index'
import { Route as BudgetsCreateImport } from './routes/budgets/create'
import { Route as AuthRegisterImport } from './routes/auth/register'
import { Route as AuthLoginImport } from './routes/auth/login'
import { Route as BudgetsIdIndexImport } from './routes/budgets/$id/index'

// Create/Update Routes

const IndexRoute = IndexImport.update({
  path: '/',
  getParentRoute: () => rootRoute,
} as any)

const BudgetsIndexRoute = BudgetsIndexImport.update({
  path: '/budgets/',
  getParentRoute: () => rootRoute,
} as any)

const BudgetsCreateRoute = BudgetsCreateImport.update({
  path: '/budgets/create',
  getParentRoute: () => rootRoute,
} as any)

const AuthRegisterRoute = AuthRegisterImport.update({
  path: '/auth/register',
  getParentRoute: () => rootRoute,
} as any)

const AuthLoginRoute = AuthLoginImport.update({
  path: '/auth/login',
  getParentRoute: () => rootRoute,
} as any)

const BudgetsIdIndexRoute = BudgetsIdIndexImport.update({
  path: '/budgets/$id/',
  getParentRoute: () => rootRoute,
} as any)

// Populate the FileRoutesByPath interface

declare module '@tanstack/react-router' {
  interface FileRoutesByPath {
    '/': {
      id: '/'
      path: '/'
      fullPath: '/'
      preLoaderRoute: typeof IndexImport
      parentRoute: typeof rootRoute
    }
    '/auth/login': {
      id: '/auth/login'
      path: '/auth/login'
      fullPath: '/auth/login'
      preLoaderRoute: typeof AuthLoginImport
      parentRoute: typeof rootRoute
    }
    '/auth/register': {
      id: '/auth/register'
      path: '/auth/register'
      fullPath: '/auth/register'
      preLoaderRoute: typeof AuthRegisterImport
      parentRoute: typeof rootRoute
    }
    '/budgets/create': {
      id: '/budgets/create'
      path: '/budgets/create'
      fullPath: '/budgets/create'
      preLoaderRoute: typeof BudgetsCreateImport
      parentRoute: typeof rootRoute
    }
    '/budgets/': {
      id: '/budgets/'
      path: '/budgets'
      fullPath: '/budgets'
      preLoaderRoute: typeof BudgetsIndexImport
      parentRoute: typeof rootRoute
    }
    '/budgets/$id/': {
      id: '/budgets/$id/'
      path: '/budgets/$id'
      fullPath: '/budgets/$id'
      preLoaderRoute: typeof BudgetsIdIndexImport
      parentRoute: typeof rootRoute
    }
  }
}

// Create and export the route tree

export interface FileRoutesByFullPath {
  '/': typeof IndexRoute
  '/auth/login': typeof AuthLoginRoute
  '/auth/register': typeof AuthRegisterRoute
  '/budgets/create': typeof BudgetsCreateRoute
  '/budgets': typeof BudgetsIndexRoute
  '/budgets/$id': typeof BudgetsIdIndexRoute
}

export interface FileRoutesByTo {
  '/': typeof IndexRoute
  '/auth/login': typeof AuthLoginRoute
  '/auth/register': typeof AuthRegisterRoute
  '/budgets/create': typeof BudgetsCreateRoute
  '/budgets': typeof BudgetsIndexRoute
  '/budgets/$id': typeof BudgetsIdIndexRoute
}

export interface FileRoutesById {
  __root__: typeof rootRoute
  '/': typeof IndexRoute
  '/auth/login': typeof AuthLoginRoute
  '/auth/register': typeof AuthRegisterRoute
  '/budgets/create': typeof BudgetsCreateRoute
  '/budgets/': typeof BudgetsIndexRoute
  '/budgets/$id/': typeof BudgetsIdIndexRoute
}

export interface FileRouteTypes {
  fileRoutesByFullPath: FileRoutesByFullPath
  fullPaths:
    | '/'
    | '/auth/login'
    | '/auth/register'
    | '/budgets/create'
    | '/budgets'
    | '/budgets/$id'
  fileRoutesByTo: FileRoutesByTo
  to:
    | '/'
    | '/auth/login'
    | '/auth/register'
    | '/budgets/create'
    | '/budgets'
    | '/budgets/$id'
  id:
    | '__root__'
    | '/'
    | '/auth/login'
    | '/auth/register'
    | '/budgets/create'
    | '/budgets/'
    | '/budgets/$id/'
  fileRoutesById: FileRoutesById
}

export interface RootRouteChildren {
  IndexRoute: typeof IndexRoute
  AuthLoginRoute: typeof AuthLoginRoute
  AuthRegisterRoute: typeof AuthRegisterRoute
  BudgetsCreateRoute: typeof BudgetsCreateRoute
  BudgetsIndexRoute: typeof BudgetsIndexRoute
  BudgetsIdIndexRoute: typeof BudgetsIdIndexRoute
}

const rootRouteChildren: RootRouteChildren = {
  IndexRoute: IndexRoute,
  AuthLoginRoute: AuthLoginRoute,
  AuthRegisterRoute: AuthRegisterRoute,
  BudgetsCreateRoute: BudgetsCreateRoute,
  BudgetsIndexRoute: BudgetsIndexRoute,
  BudgetsIdIndexRoute: BudgetsIdIndexRoute,
}

export const routeTree = rootRoute
  ._addFileChildren(rootRouteChildren)
  ._addFileTypes<FileRouteTypes>()

/* prettier-ignore-end */

/* ROUTE_MANIFEST_START
{
  "routes": {
    "__root__": {
      "filePath": "__root.tsx",
      "children": [
        "/",
        "/auth/login",
        "/auth/register",
        "/budgets/create",
        "/budgets/",
        "/budgets/$id/"
      ]
    },
    "/": {
      "filePath": "index.tsx"
    },
    "/auth/login": {
      "filePath": "auth/login.tsx"
    },
    "/auth/register": {
      "filePath": "auth/register.tsx"
    },
    "/budgets/create": {
      "filePath": "budgets/create.tsx"
    },
    "/budgets/": {
      "filePath": "budgets/index.tsx"
    },
    "/budgets/$id/": {
      "filePath": "budgets/$id/index.tsx"
    }
  }
}
ROUTE_MANIFEST_END */

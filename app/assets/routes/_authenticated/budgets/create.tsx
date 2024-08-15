import React from 'react'

import { createFileRoute } from '@tanstack/react-router'

export const Route = createFileRoute('/_authenticated/budgets/create')({
  component: () => <div>Hello /budgets/create!</div>,
})

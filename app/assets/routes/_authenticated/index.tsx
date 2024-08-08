import React from 'react'

import { createFileRoute, redirect } from '@tanstack/react-router'

import { Container, Loader } from '@mantine/core'

import { useBudgetDetail } from '@/hooks/useBudget'

export const Route = createFileRoute('/_authenticated/')({
  component: () => {
    const { data, isLoading } = useBudgetDetail(1)

    console.log(data)

    if (isLoading) {
      return (
        <Container>
          <Loader />
        </Container>
      )
    }

    return <Container>Oui</Container>
  },
})

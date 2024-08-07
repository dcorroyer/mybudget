import React from 'react'

import { Container, Loader } from '@mantine/core'

import { useBudgetDetail } from '@/hooks/useBudget'

export default function Home() {
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
}

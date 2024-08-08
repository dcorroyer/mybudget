import React from 'react'

import { createFileRoute } from '@tanstack/react-router'

import { Container } from '@mantine/core'

export const Route = createFileRoute('/_authenticated/')({
  component: () => {
    return <Container>Oui</Container>
  },
})

import React from 'react'

import { createFileRoute, Link } from '@tanstack/react-router'

import { Button, Container, Group, Text, Title } from '@mantine/core'

import classes from './index.module.css'

import { Illustration } from '@/components/illustration'

export const Route = createFileRoute('/_authenticated/')({
  component: () => {
    return (
      <Container className={classes.root}>
        <div className={classes.inner}>
          <Illustration className={classes.image} />
          <div className={classes.content}>
            <Title className={classes.title}>Nothing to see here</Title>
            <Text c='dimmed' size='lg' ta='center' className={classes.description}>
              Page you are trying to open does not exist. The page is in construction.
            </Text>
            <Group justify='center'>
              <Button size='md' component={Link} to={'/budgets'}>
                Take me back to budget page
              </Button>
            </Group>
          </div>
        </div>
      </Container>
    )
  },
})

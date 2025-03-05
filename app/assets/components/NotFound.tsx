import React from 'react'

import { Button, Container, Group, Text, Title } from '@mantine/core'

import { Link } from 'react-router-dom'

import classes from './NotFound.module.css'

import { Illustration } from './Illustration'

const NotFound: React.FC = () => {
  return (
    <Container className={classes.root}>
      <div className={classes.inner}>
        <Illustration className={classes.image} />
        <div className={classes.content}>
          <Title className={classes.title}>Nothing to see here</Title>
          <Text c='dimmed' size='lg' ta='center' className={classes.description}>
            Page you are trying to open does not exist.
          </Text>
          <Group justify='center'>
            <Button size='md' component={Link} to={'/budgets'}>
              Take me back to budget&apos;s list page
            </Button>
          </Group>
        </div>
      </div>
    </Container>
  )
}

export default NotFound

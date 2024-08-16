import React from 'react'

import { ActionIcon, Container, Text } from '@mantine/core'
import { IconChevronLeft } from '@tabler/icons-react'
import { BudgetForm } from '../components/budget-form'

import { Link } from '@tanstack/react-router'
import classes from './create.module.css'

export const BudgetCreate = () => {
  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        <ActionIcon variant='transparent' c='black' component={Link} to='/budgets'>
          <IconChevronLeft className={classes.title} />
        </ActionIcon>
        New Budget
      </Text>
      <Container size={560} my={40}>
        <BudgetForm />
      </Container>
    </>
  )
}

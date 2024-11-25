import { ActionIcon, Container, Text } from '@mantine/core'
import { IconChevronLeft } from '@tabler/icons-react'
import React from 'react'
import { Link } from 'react-router-dom'
import { TransactionForm } from '../components/transaction-form'

import classes from './create.module.css'

const TransactionCreate: React.FC = () => {
  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        <ActionIcon variant='transparent' c='black' component={Link} to='/transactions'>
          <IconChevronLeft className={classes.title} />
        </ActionIcon>
        New Transaction
      </Text>
      <Container size={560} my={40}>
        <TransactionForm />
      </Container>
    </>
  )
}

export default TransactionCreate

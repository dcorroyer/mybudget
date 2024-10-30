import React from 'react'

import { ActionIcon, Container, Text } from '@mantine/core'
import { IconChevronLeft } from '@tabler/icons-react'
import { Link } from 'react-router-dom'

import { AccountForm } from '../components/account-form'

import classes from './create.module.css'

const AccountCreate: React.FC = () => {
  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        <ActionIcon variant='transparent' c='black' component={Link} to='/accounts'>
          <IconChevronLeft className={classes.title} />
        </ActionIcon>
        New Account
      </Text>
      <Container size={560} my={40}>
        <AccountForm />
      </Container>
    </>
  )
}

export default AccountCreate

import { ActionIcon, Container, Text } from '@mantine/core'
import { IconChevronLeft } from '@tabler/icons-react'
import React from 'react'
import { Link, useParams } from 'react-router-dom'

import { CenteredLoader as Loader } from '@/components/centered-loader'
import { AccountForm } from '../components/account-form'
import { useAccount } from '../hooks/useAccount'

import NotFound from '@/components/not-found'
import classes from './detail.module.css'

const AccountDetail: React.FC = () => {
  const { id } = useParams<{ id: string }>()
  const { useAccountDetail } = useAccount()
  const { data: account, isFetching } = useAccountDetail(id!)

  if (isFetching) return <Loader />
  if (!account) return <NotFound />

  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        <ActionIcon variant='transparent' c='black' component={Link} to='/accounts'>
          <IconChevronLeft className={classes.title} />
        </ActionIcon>
        Edit Account
      </Text>
      <Container size={560} my={40}>
        {account && (
          <AccountForm
            initialValues={{
              id: account.data.id,
              name: account.data.name,
            }}
          />
        )}
      </Container>
    </>
  )
}

export default AccountDetail

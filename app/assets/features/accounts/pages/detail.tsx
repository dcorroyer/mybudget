import { ActionIcon, Card, Container, Group, Text } from '@mantine/core'
import { IconChevronLeft, IconPencil, IconPencilOff } from '@tabler/icons-react'
import React, { useState } from 'react'
import { Link, useParams } from 'react-router-dom'

import { CenteredLoader as Loader } from '@/components/centered-loader'
import { AccountForm } from '../components/account-form'
import { TransactionList } from '../components/transaction-list'
import { useAccount } from '../hooks/useAccount'
import { useTransactions } from '../hooks/useTransactions'

import NotFound from '@/components/not-found'
import classes from './detail.module.css'

const AccountDetail: React.FC = () => {
  const { id } = useParams<{ id: string }>()
  const { useAccountDetail } = useAccount()
  const { useTransactionList } = useTransactions()
  const [editMode, setEditMode] = useState(false)

  const { data: account, isFetching: isAccountFetching } = useAccountDetail(id!)
  const { data: transactions, isFetching: isTransactionsFetching } = useTransactionList(id!)

  if (isAccountFetching || isTransactionsFetching) return <Loader />
  if (!account) return <NotFound />

  const toggleEditMode = () => {
    setEditMode((prev) => !prev)
  }

  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        <ActionIcon variant='transparent' c='black' component={Link} to='/accounts'>
          <IconChevronLeft className={classes.title} />
        </ActionIcon>
        {account.data.name}
        <ActionIcon
          variant='transparent'
          c='black'
          onClick={toggleEditMode}
          ml='sm'
          className={classes.editModeButton}
        >
          {editMode ? (
            <IconPencilOff style={{ width: '1.25rem', height: '1.25rem' }} stroke={1.5} />
          ) : (
            <IconPencil style={{ width: '1.25rem', height: '1.25rem' }} stroke={1.5} />
          )}
        </ActionIcon>
      </Text>

      <Container size={560} my={40}>
        {editMode ? (
          <AccountForm
            initialValues={{
              id: account.data.id,
              name: account.data.name,
            }}
          />
        ) : (
          <Card radius='lg' pb='xl'>
            <Card.Section inheritPadding py='xs'>
              <Group justify='space-between'>
                <Text fw={500}>Account Details</Text>
              </Group>
              <Text fw={500} c='dimmed' size='sm' mt='xs'>
                Balance: {account.data.balance} €
              </Text>
            </Card.Section>
          </Card>
        )}

        {!editMode && transactions && <TransactionList transactions={transactions.data} />}
      </Container>
    </>
  )
}

export default AccountDetail

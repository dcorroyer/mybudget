import NotFound from '@/components/not-found'
import { ActionIcon, Container, Loader, Text } from '@mantine/core'
import { IconChevronLeft } from '@tabler/icons-react'
import React from 'react'
import { Link, useParams } from 'react-router-dom'
import { TransactionForm } from '../../components/transaction-form'
import { useTransactions } from '../../hooks/useTransactions'

import classes from './detail.module.css'

const TransactionDetail: React.FC = () => {
  const { accountId, id } = useParams<{ accountId: string; id: string }>()
  const { useTransaction } = useTransactions()

  const { data: transaction, isFetching } = useTransaction(Number(accountId), Number(id))

  if (isFetching) return <Loader />
  if (!transaction) return <NotFound />

  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        <ActionIcon variant='transparent' c='black' component={Link} to='/'>
          <IconChevronLeft className={classes.title} />
        </ActionIcon>
        Transaction Details
      </Text>
      <Container>
        <TransactionForm
          initialValues={{
            ...transaction.data,
          }}
        />
      </Container>
    </>
  )
}

export default TransactionDetail

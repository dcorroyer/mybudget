import { useAccount } from '@/features/accounts/hooks/useAccount'
import { TransactionTable } from '@/features/transactions/components/transaction-table'
import { ActionIcon, Container, Loader, MultiSelect, rem, Text } from '@mantine/core'
import { IconPencil } from '@tabler/icons-react'
import React, { useState } from 'react'
import { Link } from 'react-router-dom'
import { useTransactions } from '../hooks/useTransactions'

import classes from './list.module.css'

const TransactionList: React.FC = () => {
  const [selectedAccounts, setSelectedAccounts] = useState<string[]>([])

  const { useAccountList } = useAccount()
  const { useTransactionList } = useTransactions()

  const { data: accountList } = useAccountList()
  const { data: transactions, isFetching } = useTransactionList({
    accountIds: selectedAccounts.map((id) => parseInt(id)),
  })

  const accountOptions =
    accountList?.data.map((account) => ({
      value: account.id.toString(),
      label: account.name,
    })) || []

  if (isFetching) return <Loader />

  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        Transactions
        <ActionIcon
          variant='transparent'
          ml='sm'
          className={classes.linkItem}
          component={Link}
          to={'/transactions/create'}
        >
          <IconPencil className={classes.linkIcon} stroke={1.5} />
          <span style={{ padding: rem(2.5) }}>Create</span>
        </ActionIcon>
      </Text>
      <Container>
        <MultiSelect
          label='Filter by Accounts'
          placeholder={selectedAccounts.length === 0 ? 'All accounts' : ''}
          data={accountOptions}
          value={selectedAccounts}
          onChange={setSelectedAccounts}
          searchable
          clearable
          mb='xl'
        />
        {transactions && <TransactionTable transactions={transactions.data} />}
      </Container>
    </>
  )
}

export default TransactionList

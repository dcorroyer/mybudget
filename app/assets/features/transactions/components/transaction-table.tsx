import { ActionIcon, Button, Card, Group, Modal, rem, Text } from '@mantine/core'
import { useDisclosure } from '@mantine/hooks'
import { IconPencil, IconTrash } from '@tabler/icons-react'
import React, { useState } from 'react'
import { Link } from 'react-router-dom'
import { Transaction } from '../../accounts/types/transactions'
import { useTransactions } from '../hooks/useTransactions'

interface TransactionListProps {
  transactions: Transaction[]
}

export const TransactionTable: React.FC<TransactionListProps> = ({ transactions }) => {
  const { deleteTransaction } = useTransactions()

  const [openedDelete, { open: openDelete, close: closeDelete }] = useDisclosure(false)
  const [accountIdOfTransactionToDelete, setAccountIdOfTransactionToDelete] = useState<
    number | null
  >(null)
  const [transactionIdToDelete, setTransactionIdToDelete] = useState<number | null>(null)

  const handleDelete = () => {
    if (accountIdOfTransactionToDelete && transactionIdToDelete) {
      deleteTransaction({
        accountId: accountIdOfTransactionToDelete,
        transactionId: transactionIdToDelete,
      })
      closeDelete()
    }
  }
  return (
    <>
      {/* Delete Confirmation Modal */}
      <Modal
        opened={openedDelete}
        onClose={closeDelete}
        radius='lg'
        title='Delete Account'
        centered
      >
        <Text size='sm'>Are you sure you want to delete this account?</Text>
        <Group justify='flex-end' mt='lg'>
          <Button variant='subtle' onClick={closeDelete}>
            Cancel
          </Button>
          <Button color='red' onClick={handleDelete}>
            Delete
          </Button>
        </Group>
      </Modal>
      {transactions.map((transaction) => (
        <Card key={transaction.id} radius='lg' mb='md'>
          <Group justify='space-between'>
            <div>
              <Text fw={500}>{transaction.description}</Text>
              <Text size='sm' c='dimmed'>
                {transaction.account.name} - {new Date(transaction.date).toLocaleDateString()}
              </Text>
              <Text fw={500} c={transaction.type === 'CREDIT' ? 'green' : 'red'}>
                {transaction.type === 'CREDIT' ? '+' : '-'}
                {transaction.amount} €
              </Text>
            </div>
            <Group>
              <ActionIcon
                component={Link}
                to={`/accounts/${transaction.account.id}/transactions/${transaction.id}`}
                variant='subtle'
                color='blue'
              >
                <IconPencil style={{ width: '1.25rem', height: '1.25rem' }} stroke={1.5} />
              </ActionIcon>
              <ActionIcon
                onClick={() => {
                  setAccountIdOfTransactionToDelete(transaction.account.id)
                  setTransactionIdToDelete(transaction.id)
                  openDelete()
                }}
                variant='subtle'
                color='red'
              >
                <IconTrash style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
              </ActionIcon>
            </Group>
          </Group>
        </Card>
      ))}
    </>
  )
}

import { useAccount } from '@/features/savings/hooks/useAccount'
import {
  Container,
  Grid,
  Group,
  MultiSelect,
  SegmentedControl,
  Stack,
  Text,
  Title,
} from '@mantine/core'
import { useDisclosure } from '@mantine/hooks'
import React, { useState } from 'react'
import { AccountsSection } from '../components/accounts-section'
import { ModalContainer } from '../components/modal-container'
import { SavingsChartSection } from '../components/savings-chart-section'
import { TransactionsSection } from '../components/transactions-section'
import { useTransactions } from '../hooks/useTransactions'

const SavingsIndex = () => {
  const [selectedPeriod, setSelectedPeriod] = useState<string>('6')
  const [selectedAccounts, setSelectedAccounts] = useState<string[]>([])

  const [openedTransactionDelete, { open: openTransactionDelete, close: closeTransactionDelete }] =
    useDisclosure(false)
  const [openedEdit, { open: openEdit, close: closeEdit }] = useDisclosure(false)
  const [openedCreate, { open: openCreate, close: closeCreate }] = useDisclosure(false)
  const [selectedTransaction, setSelectedTransaction] = useState<any>(null)
  const [accountIdOfTransactionToDelete, setAccountIdOfTransactionToDelete] = useState<
    number | null
  >(null)
  const [transactionIdToDelete, setTransactionIdToDelete] = useState<number | null>(null)
  const [openedAccountDelete, { open: openAccountDelete, close: closeAccountDelete }] =
    useDisclosure(false)
  const [openedAccountCreate, { open: openAccountCreate, close: closeAccountCreate }] =
    useDisclosure(false)
  const [openedAccountEdit, { open: openAccountEdit, close: closeAccountEdit }] =
    useDisclosure(false)
  const [selectedAccount, setSelectedAccount] = useState<any>(null)
  const [accountIdToDelete, setAccountIdToDelete] = useState<string | null>(null)

  const { useAccountList, deleteAccount } = useAccount()
  const { data: accountList } = useAccountList()
  const { deleteTransaction } = useTransactions()

  const accountOptions =
    accountList?.data.map((account) => ({
      value: account.id.toString(),
      label: account.name,
    })) || []

  const handleTransactionDelete = () => {
    if (accountIdOfTransactionToDelete && transactionIdToDelete) {
      deleteTransaction({
        accountId: accountIdOfTransactionToDelete,
        transactionId: transactionIdToDelete,
      })
      closeTransactionDelete()
    }
  }

  const handleEdit = (transaction: any) => {
    setSelectedTransaction(transaction)
    openEdit()
  }

  const handleAccountDelete = () => {
    if (accountIdToDelete) {
      deleteAccount(accountIdToDelete)
      closeAccountDelete()
    }
  }

  const modalState = {
    transaction: {
      delete: {
        opened: openedTransactionDelete,
        onClose: closeTransactionDelete,
        onConfirm: handleTransactionDelete,
      },
      edit: {
        opened: openedEdit,
        onClose: closeEdit,
        selectedTransaction,
        onSuccess: () => {
          closeEdit()
          setSelectedTransaction(null)
        },
      },
      create: {
        opened: openedCreate,
        onClose: closeCreate,
        onSuccess: closeCreate,
      },
    },
    account: {
      delete: {
        opened: openedAccountDelete,
        onClose: closeAccountDelete,
        onConfirm: handleAccountDelete,
      },
      edit: {
        opened: openedAccountEdit,
        onClose: closeAccountEdit,
        selectedAccount,
        onSuccess: () => {
          closeAccountEdit()
          setSelectedAccount(null)
        },
      },
      create: {
        opened: openedAccountCreate,
        onClose: closeAccountCreate,
        onSuccess: closeAccountCreate,
      },
    },
  }

  return (
    <Container size='xl' py='xl'>
      <Stack gap='xl'>
        {/* Modal Container Component */}
        <ModalContainer modals={modalState} />

        <Group justify='space-between' align='flex-end'>
          <Stack gap={0}>
            <Title order={1} size='h2' fw={600} c='blue.7'>
              Suivi de l'épargne
            </Title>
            <Text c='dimmed' size='sm'>
              Visualisez l'évolution de vos économies
            </Text>
          </Stack>
        </Group>

        {/* Accounts Section */}
        <AccountsSection
          accounts={accountList}
          onEdit={(account) => {
            setSelectedAccount(account)
            openAccountEdit()
          }}
          onDelete={(accountId) => {
            setAccountIdToDelete(accountId)
            openAccountDelete()
          }}
          onCreateClick={openAccountCreate}
        />

        <Grid align='flex-end' gutter='lg'>
          <Grid.Col span={{ base: 12, sm: 6 }}>
            <MultiSelect
              label='Filtrer par compte'
              placeholder={selectedAccounts.length === 0 ? 'Tous les comptes' : ''}
              data={accountOptions}
              value={selectedAccounts}
              onChange={setSelectedAccounts}
              searchable
              clearable
              labelProps={{ mb: 'xs' }}
              radius='lg'
              variant='filled'
              styles={{
                input: {
                  backgroundColor: 'white',
                },
              }}
            />
          </Grid.Col>
          <Grid.Col span={{ base: 12, sm: 6 }}>
            <SegmentedControl
              fullWidth
              value={selectedPeriod}
              onChange={setSelectedPeriod}
              data={[
                { label: '12 Mois', value: '12' },
                { label: '6 Mois', value: '6' },
                { label: '3 Mois', value: '3' },
                { label: 'Tout', value: '' },
              ]}
              color='blue'
              styles={{
                root: {
                  backgroundColor: 'var(--mantine-color-gray-2)',
                },
              }}
            />
          </Grid.Col>
        </Grid>

        {/* Savings Chart Section */}
        <SavingsChartSection selectedPeriod={selectedPeriod} selectedAccounts={selectedAccounts} />

        {/* Transactions List Section */}
        <TransactionsSection
          selectedAccounts={selectedAccounts}
          onEdit={handleEdit}
          onDelete={(accountId, transactionId) => {
            setAccountIdOfTransactionToDelete(accountId)
            setTransactionIdToDelete(transactionId)
            openTransactionDelete()
          }}
          onCreateClick={openCreate}
        />
      </Stack>
    </Container>
  )
}

export default SavingsIndex

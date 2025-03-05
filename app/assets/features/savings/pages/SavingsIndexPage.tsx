import {
  useDeleteApiAccountsDelete,
  useGetApiAccountsList,
} from '@/api/generated/accounts/accounts'
import { useDeleteApiTransactionsDelete } from '@/api/generated/transactions/transactions'
import { AccountResponse, TransactionResponse } from '@/api/models'
import { useMutationWithInvalidation } from '@/hooks/useMutation'
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
import { AccountSection } from '../components/AccountSection'
import { ModalContainer } from '../components/ModalContainer'
import { SavingsChartSection } from '../components/SavingsChartSection'
import { TransactionSection } from '../components/TransactionSection'

const SavingsIndex = () => {
  const [selectedPeriod, setSelectedPeriod] = useState<string>('12')
  const [selectedAccounts, setSelectedAccounts] = useState<string[]>([])

  const [openedTransactionDelete, { open: openTransactionDelete, close: closeTransactionDelete }] =
    useDisclosure(false)
  const [openedEdit, { open: openEdit, close: closeEdit }] = useDisclosure(false)
  const [openedCreate, { open: openCreate, close: closeCreate }] = useDisclosure(false)
  const [selectedTransaction, setSelectedTransaction] = useState<TransactionResponse | null>(null)
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
  const [selectedAccount, setSelectedAccount] = useState<AccountResponse | null>(null)
  const [accountIdToDelete, setAccountIdToDelete] = useState<string | null>(null)

  const { data: accountList } = useGetApiAccountsList()
  const { mutate: deleteAccount } = useMutationWithInvalidation(
    useDeleteApiAccountsDelete().mutateAsync,
    {
      queryKeyToInvalidate: ['/api/accounts', '/api/accounts/balance-history'],
      successMessage: 'Compte supprimé avec succès',
      errorMessage: 'Une erreur est survenue lors de la suppression du compte',
      onSuccess: closeAccountDelete,
    },
  )
  const { mutate: deleteTransaction } = useMutationWithInvalidation(
    useDeleteApiTransactionsDelete().mutateAsync,
    {
      queryKeyToInvalidate: [
        '/api/accounts',
        '/api/accounts/transactions',
        '/api/accounts/balance-history',
      ],
      successMessage: 'Transaction supprimée avec succès',
      errorMessage: 'Une erreur est survenue lors de la suppression de la transaction',
      onSuccess: closeTransactionDelete,
    },
  )

  const accountOptions =
    accountList?.data?.map((account) => ({
      value: account.id.toString(),
      label: account.name,
    })) || []

  const handleTransactionDelete = () => {
    if (accountIdOfTransactionToDelete && transactionIdToDelete) {
      deleteTransaction({
        accountId: accountIdOfTransactionToDelete,
        id: transactionIdToDelete,
      })
      closeTransactionDelete()
    }
  }

  const handleEdit = (transaction: TransactionResponse) => {
    setSelectedTransaction(transaction)
    openEdit()
  }

  const handleAccountDelete = () => {
    if (accountIdToDelete) {
      deleteAccount({ id: parseInt(accountIdToDelete) })
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
              Suivi de l&apos;épargne
            </Title>
            <Text c='dimmed' size='sm'>
              Visualisez l&apos;évolution de vos économies
            </Text>
          </Stack>
        </Group>

        {/* Accounts Section */}
        <AccountSection
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
                { label: '24 Mois', value: '24' },
                { label: '12 Mois', value: '12' },
                { label: '6 Mois', value: '6' },
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
        <TransactionSection
          selectedAccounts={selectedAccounts}
          onEdit={handleEdit}
          onDelete={(accountId, transactionId) => {
            setAccountIdOfTransactionToDelete(accountId)
            setTransactionIdToDelete(transactionId)
            openTransactionDelete()
          }}
          onCreateClick={openCreate}
          accounts={accountList}
        />
      </Stack>
    </Container>
  )
}

export default SavingsIndex

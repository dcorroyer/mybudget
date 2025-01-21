import { useAccount } from '@/features/accounts/hooks/useAccount'
import {
  ActionIcon,
  Badge,
  Button,
  Card,
  Container,
  Grid,
  Group,
  Loader,
  Modal,
  MultiSelect,
  SegmentedControl,
  Stack,
  Table,
  Text,
  Title,
  rem,
} from '@mantine/core'
import { useDisclosure, useMediaQuery } from '@mantine/hooks'
import { IconDatabaseOff, IconEdit, IconPlus, IconReceipt, IconTrash } from '@tabler/icons-react'
import React, { useState } from 'react'
import { Link } from 'react-router-dom'
import { SavingsChart } from '../components/savings-chart'
import { useSavings } from '../hooks/useSavings'
import { useTransactions } from '../hooks/useTransactions'

const SavingsList = () => {
  const [selectedPeriod, setSelectedPeriod] = useState<string>('6')
  const [selectedAccounts, setSelectedAccounts] = useState<string[]>([])
  const [openedDelete, { open: openDelete, close: closeDelete }] = useDisclosure(false)
  const [accountIdOfTransactionToDelete, setAccountIdOfTransactionToDelete] = useState<
    number | null
  >(null)
  const [transactionIdToDelete, setTransactionIdToDelete] = useState<number | null>(null)

  const { useAccountList } = useAccount()
  const { useBalanceHistory } = useSavings()
  const { data: accountList } = useAccountList()

  const { data: savingsData, isFetching } = useBalanceHistory({
    ...(selectedPeriod && { period: selectedPeriod as '3' | '6' | '12' }),
    ...(selectedAccounts.length > 0 && { accountIds: selectedAccounts.map((id) => parseInt(id)) }),
  })

  const { useTransactionList } = useTransactions()
  const { data: transactions, isFetching: isTransactionsFetching } = useTransactionList({
    accountIds: selectedAccounts.map((id) => parseInt(id)),
  })

  const accountOptions =
    accountList?.data.map((account) => ({
      value: account.id.toString(),
      label: account.name,
    })) || []

  const { deleteTransaction } = useTransactions()

  const isMobile = useMediaQuery('(max-width: 768px)')

  const handleDelete = () => {
    if (accountIdOfTransactionToDelete && transactionIdToDelete) {
      deleteTransaction({
        accountId: accountIdOfTransactionToDelete,
        transactionId: transactionIdToDelete,
      })
      closeDelete()
    }
  }

  const TransactionList = () => (
    <Stack gap='md'>
      {transactions?.data.map((transaction) => (
        <Card key={transaction.id} radius='md'>
          <Stack gap='xs'>
            <Group justify='space-between' wrap='nowrap'>
              <Text fw={400} size='sm' style={{ flex: 1 }}>
                {transaction.description}
              </Text>
              <Text c={transaction.type === 'CREDIT' ? 'teal' : 'red'} fw={500}>
                {transaction.type === 'CREDIT' ? '+' : '-'}
                {Math.abs(transaction.amount).toLocaleString('fr-FR')} €
              </Text>
            </Group>
            <Group justify='space-between' wrap='nowrap'>
              <Stack gap={4}>
                <Badge variant='light' color='blue'>
                  {transaction.account.name}
                </Badge>
                <Text size='xs' c='dimmed'>
                  {new Date(transaction.date).toLocaleDateString('fr-FR')}
                </Text>
              </Stack>
              <Group gap='xs'>
                <ActionIcon
                  component={Link}
                  to={`/accounts/${transaction.account.id}/transactions/${transaction.id}`}
                  variant='light'
                  color='blue'
                  size='sm'
                >
                  <IconEdit style={{ width: rem(16) }} />
                </ActionIcon>
                <ActionIcon
                  variant='light'
                  color='red'
                  size='sm'
                  onClick={() => {
                    setAccountIdOfTransactionToDelete(transaction.account.id)
                    setTransactionIdToDelete(transaction.id)
                    openDelete()
                  }}
                >
                  <IconTrash style={{ width: rem(16) }} />
                </ActionIcon>
              </Group>
            </Group>
          </Stack>
        </Card>
      ))}
    </Stack>
  )

  if (isFetching || isTransactionsFetching) return <Loader />

  return (
    <Container size='xl' py='xl'>
      <Stack gap='xl'>
        <Modal
          opened={openedDelete}
          onClose={closeDelete}
          radius='lg'
          title='Supprimer la transaction'
          centered
        >
          <Text size='sm'>Êtes-vous sûr de vouloir supprimer cette transaction ?</Text>
          <Group justify='flex-end' mt='lg'>
            <Button variant='subtle' radius='md' onClick={closeDelete}>
              Annuler
            </Button>
            <Button color='red' radius='md' onClick={handleDelete}>
              Supprimer
            </Button>
          </Group>
        </Modal>
        <Group justify='space-between' align='flex-end'>
          <Stack gap={0}>
            <Title order={1} size='h2' fw={600} c='blue.7'>
              Suivi de l'épargne
            </Title>
            <Text c='dimmed' size='sm'>
              Visualisez l'évolution de vos économies
            </Text>
          </Stack>
          <Button
            component={Link}
            to='/transactions/create'
            leftSection={<IconPlus size={16} />}
            variant='light'
          >
            Nouvelle transaction
          </Button>
        </Group>

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

        {savingsData && <SavingsChart data={savingsData.data} />}

        {transactions && transactions.data.length > 0 ? (
          <Card radius='lg' py='xl' mt='sm' shadow='sm'>
            <Card.Section inheritPadding px='xl' pb='xs'>
              <Group justify='space-between' mt='md'>
                <Group gap='xs'>
                  <IconReceipt size={20} style={{ color: 'var(--mantine-color-blue-6)' }} />
                  <Text fw={500} size='md'>
                    Transactions récentes
                  </Text>
                </Group>
              </Group>
            </Card.Section>
            <Card.Section inheritPadding px='xl' mt='sm'>
              {isMobile ? (
                <TransactionList />
              ) : (
                <Table.ScrollContainer minWidth={800}>
                  <Table verticalSpacing='sm' horizontalSpacing='lg'>
                    <Table.Thead>
                      <Table.Tr>
                        <Table.Th>Description</Table.Th>
                        <Table.Th>Compte</Table.Th>
                        <Table.Th>Date</Table.Th>
                        <Table.Th>Montant</Table.Th>
                        <Table.Th>Actions</Table.Th>
                      </Table.Tr>
                    </Table.Thead>
                    <Table.Tbody>
                      {transactions.data.map((transaction) => (
                        <Table.Tr key={transaction.id}>
                          <Table.Td>
                            <Text fw={400} size='sm'>
                              {transaction.description}
                            </Text>
                          </Table.Td>
                          <Table.Td>
                            <Badge variant='light' color='blue'>
                              {transaction.account.name}
                            </Badge>
                          </Table.Td>
                          <Table.Td>
                            {new Date(transaction.date).toLocaleDateString('fr-FR')}
                          </Table.Td>
                          <Table.Td>
                            <Text c={transaction.type === 'CREDIT' ? 'teal' : 'red'} fw={500}>
                              {transaction.type === 'CREDIT' ? '+' : '-'}
                              {Math.abs(transaction.amount).toLocaleString('fr-FR')} €
                            </Text>
                          </Table.Td>
                          <Table.Td>
                            <Group gap='xs'>
                              <ActionIcon
                                component={Link}
                                to={`/accounts/${transaction.account.id}/transactions/${transaction.id}`}
                                variant='light'
                                color='blue'
                                size='sm'
                              >
                                <IconEdit style={{ width: rem(16) }} />
                              </ActionIcon>
                              <ActionIcon
                                variant='light'
                                color='red'
                                size='sm'
                                onClick={() => {
                                  setAccountIdOfTransactionToDelete(transaction.account.id)
                                  setTransactionIdToDelete(transaction.id)
                                  openDelete()
                                }}
                              >
                                <IconTrash style={{ width: rem(16) }} />
                              </ActionIcon>
                            </Group>
                          </Table.Td>
                        </Table.Tr>
                      ))}
                    </Table.Tbody>
                  </Table>
                </Table.ScrollContainer>
              )}
            </Card.Section>
          </Card>
        ) : (
          <Container h={100} display='flex'>
            <Stack justify='center' align='center' style={{ flex: 1 }} gap='xs'>
              <IconDatabaseOff
                style={{ width: rem(24), height: rem(24) }}
                stroke={1.5}
                color='gray'
              />
              <Text size='lg' fw={500} c='gray'>
                Aucune transaction trouvée
              </Text>
            </Stack>
          </Container>
        )}
      </Stack>
    </Container>
  )
}

export default SavingsList

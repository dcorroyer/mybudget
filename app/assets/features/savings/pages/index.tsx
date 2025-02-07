import { AccountForm } from '@/features/savings/components/account-form'
import { useAccount } from '@/features/savings/hooks/useAccount'
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
  Pagination,
  rem,
  SegmentedControl,
  Skeleton,
  Stack,
  Table,
  Text,
  Title,
} from '@mantine/core'
import { useDisclosure, useMediaQuery } from '@mantine/hooks'
import {
  IconDatabaseOff,
  IconEdit,
  IconMinus,
  IconPlus,
  IconReceipt,
  IconTrash,
  IconWallet,
} from '@tabler/icons-react'
import React, { useState } from 'react'
import { SavingsChart } from '../components/savings-chart'
import { TransactionForm } from '../components/transaction-form'
import { useSavings } from '../hooks/useSavings'
import { useTransactions } from '../hooks/useTransactions'
import { Transaction } from '../types/transactions'

const SavingsIndex = () => {
  const [selectedPeriod, setSelectedPeriod] = useState<string>('6')
  const [selectedAccounts, setSelectedAccounts] = useState<string[]>([])
  const [openedTransactionDelete, { open: openTransactionDelete, close: closeTransactionDelete }] =
    useDisclosure(false)
  const [openedAccountDelete, { open: openAccountDelete, close: closeAccountDelete }] =
    useDisclosure(false)
  const [accountIdOfTransactionToDelete, setAccountIdOfTransactionToDelete] = useState<
    number | null
  >(null)
  const [transactionIdToDelete, setTransactionIdToDelete] = useState<number | null>(null)
  const [openedEdit, { open: openEdit, close: closeEdit }] = useDisclosure(false)
  const [openedCreate, { open: openCreate, close: closeCreate }] = useDisclosure(false)
  const [selectedTransaction, setSelectedTransaction] = useState<any>(null)
  const [accountIdToDelete, setAccountIdToDelete] = useState<string | null>(null)
  const [openedAccountCreate, { open: openAccountCreate, close: closeAccountCreate }] =
    useDisclosure(false)
  const [openedAccountEdit, { open: openAccountEdit, close: closeAccountEdit }] =
    useDisclosure(false)
  const [selectedAccount, setSelectedAccount] = useState<any>(null)

  const { useAccountList, deleteAccount } = useAccount()

  const { data: accountList } = useAccountList()

  const accountOptions =
    accountList?.data.map((account) => ({
      value: account.id.toString(),
      label: account.name,
    })) || []

  const { deleteTransaction } = useTransactions()

  const isMobile = useMediaQuery('(max-width: 768px)')

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

  const TransactionList = ({ transactions }: { transactions: { data: Transaction[] } }) => (
    <Stack gap='md'>
      {transactions?.data.map((transaction: Transaction) => (
        <Card key={transaction.id} radius='md'>
          <Stack gap='xs'>
            <Group justify='space-between' wrap='nowrap'>
              <Text fw={400} size='sm' style={{ flex: 1 }}>
                <Group gap='xs'>
                  {transaction.type === 'CREDIT' ? (
                    <IconPlus size={16} style={{ color: 'var(--mantine-color-teal-6)' }} />
                  ) : (
                    <IconMinus size={16} style={{ color: 'var(--mantine-color-red-6)' }} />
                  )}
                  {transaction.description}
                </Group>
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
                  variant='light'
                  color='blue'
                  size='sm'
                  onClick={() => handleEdit(transaction)}
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
                    openTransactionDelete()
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

  const SavingsChartSection = ({
    selectedPeriod,
    selectedAccounts,
  }: {
    selectedPeriod: string
    selectedAccounts: string[]
  }) => {
    const { useBalanceHistory } = useSavings()
    const { data: savingsData, isFetching } = useBalanceHistory({
      ...(selectedPeriod && { period: selectedPeriod as '3' | '6' | '12' }),
      ...(selectedAccounts.length > 0 && {
        accountIds: selectedAccounts.map((id) => parseInt(id)),
      }),
    })

    if (isFetching) {
      return (
        <Card radius='lg' py='xl' mt='sm' shadow='sm'>
          <Card.Section inheritPadding px='xl' mt='sm'>
            <Skeleton height={300} radius='md' animate={true} />
          </Card.Section>
        </Card>
      )
    }

    return savingsData ? <SavingsChart data={savingsData.data} /> : null
  }

  const TransactionsSection = ({ selectedAccounts }: { selectedAccounts: string[] }) => {
    const [page, setPage] = useState(1)
    const { useTransactionList } = useTransactions()
    const { data: transactions, isFetching } = useTransactionList({
      accountIds: selectedAccounts.map((id) => parseInt(id)),
      page,
      perPage: 20,
    })

    if (isFetching) return <Loader />

    if (!transactions?.data.length) {
      return (
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
      )
    }

    return (
      <Card radius='lg' py='xl' mt='sm' shadow='sm'>
        <Card.Section inheritPadding px='xl' pb='xs'>
          <Group justify='space-between' mt='md'>
            <Group gap='xs'>
              <IconReceipt size={20} style={{ color: 'var(--mantine-color-blue-6)' }} />
              <Text fw={500} size='md'>
                Transactions récentes
              </Text>
            </Group>
            <Button onClick={openCreate} leftSection={<IconPlus size={16} />} variant='light'>
              Nouvelle transaction
            </Button>
          </Group>
        </Card.Section>
        <Card.Section inheritPadding px='xl' mt='sm'>
          {isMobile ? (
            <>
              <TransactionList transactions={transactions} />
              <Group justify='center' mt='md'>
                <Pagination
                  value={page}
                  onChange={setPage}
                  total={Math.ceil((transactions?.meta?.total || 0) / 20)}
                  color='blue'
                  withEdges
                />
              </Group>
            </>
          ) : (
            <>
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
                            <Group gap='xs'>
                              {transaction.type === 'CREDIT' ? (
                                <IconPlus
                                  size={16}
                                  style={{ color: 'var(--mantine-color-teal-6)' }}
                                />
                              ) : (
                                <IconMinus
                                  size={16}
                                  style={{ color: 'var(--mantine-color-red-6)' }}
                                />
                              )}
                              {transaction.description}
                            </Group>
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
                              variant='light'
                              color='blue'
                              size='sm'
                              onClick={() => handleEdit(transaction)}
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
                                openTransactionDelete()
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
              <Group justify='space-between' mt='md' px='md'>
                <Text size='xs' c='dimmed'>
                  {transactions?.meta?.from}-{transactions?.meta?.to} sur{' '}
                  {transactions?.meta?.total}
                </Text>
                <Group justify='center' style={{ flex: 1 }}>
                  <Pagination
                    value={page}
                    onChange={setPage}
                    total={Math.ceil((transactions?.meta?.total || 0) / 20)}
                    color='blue'
                    withEdges
                  />
                </Group>
                <div style={{ width: 100 }} />
              </Group>
            </>
          )}
        </Card.Section>
      </Card>
    )
  }

  const AccountsSection = () => (
    <Card radius='lg' py='xl' mt='sm' shadow='sm'>
      <Card.Section inheritPadding px='xl' pb='xs'>
        <Group justify='space-between'>
          <Group gap='xs'>
            <IconWallet size={20} style={{ color: 'var(--mantine-color-blue-6)' }} />
            <Text fw={500} size='md'>
              Comptes épargne
            </Text>
          </Group>
          <Button variant='light' leftSection={<IconPlus size={16} />} onClick={openAccountCreate}>
            Créer un compte
          </Button>
        </Group>
      </Card.Section>
      <Card.Section inheritPadding px='xl' mt='sm'>
        <Grid>
          {accountList?.data.map((account) => (
            <Grid.Col key={account.id} span={{ base: 12, sm: 6, md: 4 }}>
              <Card radius='md' shadow='sm' withBorder>
                <Stack gap='md'>
                  <Group justify='space-between' wrap='nowrap'>
                    <Group gap='xs'>
                      <IconWallet size={20} style={{ color: 'var(--mantine-color-blue-6)' }} />
                      <Text fw={500} size='md'>
                        {account.name}
                      </Text>
                    </Group>
                    <Group gap='xs'>
                      <ActionIcon
                        variant='light'
                        color='blue'
                        onClick={() => {
                          setSelectedAccount(account)
                          openAccountEdit()
                        }}
                      >
                        <IconEdit style={{ width: rem(16), height: rem(16) }} />
                      </ActionIcon>
                      <ActionIcon
                        variant='light'
                        color='red'
                        onClick={() => {
                          setAccountIdToDelete(account.id.toString())
                          openAccountDelete()
                        }}
                      >
                        <IconTrash style={{ width: rem(16), height: rem(16) }} />
                      </ActionIcon>
                    </Group>
                  </Group>
                  <Group justify='space-between' wrap='nowrap'>
                    <Text size='sm' c='dimmed'>
                      Solde actuel
                    </Text>
                    <Text fw={700} c='blue'>
                      {account.balance.toLocaleString('fr-FR')} €
                    </Text>
                  </Group>
                </Stack>
              </Card>
            </Grid.Col>
          ))}
        </Grid>
      </Card.Section>
    </Card>
  )

  return (
    <Container size='xl' py='xl'>
      <Stack gap='xl'>
        <Modal
          opened={openedTransactionDelete}
          onClose={closeTransactionDelete}
          radius='lg'
          title='Supprimer la transaction'
          centered
        >
          <Text size='sm'>Êtes-vous sûr de vouloir supprimer cette transaction ?</Text>
          <Group justify='flex-end' mt='lg'>
            <Button variant='subtle' radius='md' onClick={closeTransactionDelete}>
              Annuler
            </Button>
            <Button color='red' radius='md' onClick={handleTransactionDelete}>
              Supprimer
            </Button>
          </Group>
        </Modal>
        <Modal
          opened={openedEdit}
          onClose={closeEdit}
          radius='lg'
          title='Modifier la transaction'
          size='lg'
          centered
        >
          <TransactionForm
            initialValues={selectedTransaction}
            onSuccess={() => {
              closeEdit()
              setSelectedTransaction(null)
            }}
            onClose={closeEdit}
          />
        </Modal>
        <Modal
          opened={openedCreate}
          onClose={closeCreate}
          radius='lg'
          title='Nouvelle transaction'
          size='lg'
          centered
        >
          <TransactionForm
            onSuccess={() => {
              closeCreate()
            }}
            onClose={closeCreate}
          />
        </Modal>
        <Modal
          opened={openedAccountCreate}
          onClose={closeAccountCreate}
          radius='lg'
          title='Nouveau compte'
          size='lg'
          centered
        >
          <AccountForm onSuccess={closeAccountCreate} />
        </Modal>
        <Modal
          opened={openedAccountEdit}
          onClose={closeAccountEdit}
          radius='lg'
          title='Modifier le compte'
          size='lg'
          centered
        >
          <AccountForm
            initialValues={{
              id: selectedAccount?.id,
              name: selectedAccount?.name,
            }}
            onSuccess={() => {
              closeAccountEdit()
              setSelectedAccount(null)
            }}
          />
        </Modal>
        <Modal
          opened={openedAccountDelete}
          onClose={closeAccountDelete}
          radius='lg'
          title='Supprimer le compte'
          centered
        >
          <Text size='sm'>Êtes-vous sûr de vouloir supprimer ce compte ?</Text>
          <Group justify='flex-end' mt='lg'>
            <Button variant='subtle' radius='md' onClick={closeAccountDelete}>
              Annuler
            </Button>
            <Button color='red' radius='md' onClick={handleAccountDelete}>
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
        </Group>

        <AccountsSection />

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

        <SavingsChartSection selectedPeriod={selectedPeriod} selectedAccounts={selectedAccounts} />

        <TransactionsSection selectedAccounts={selectedAccounts} />
      </Stack>
    </Container>
  )
}

export default SavingsIndex

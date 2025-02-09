import {
  ActionIcon,
  Badge,
  Button,
  Card,
  Container,
  Group,
  Loader,
  Pagination,
  rem,
  Stack,
  Table,
  Text,
} from '@mantine/core'
import { useMediaQuery } from '@mantine/hooks'
import {
  IconDatabaseOff,
  IconEdit,
  IconMinus,
  IconPlus,
  IconReceipt,
  IconTrash,
} from '@tabler/icons-react'
import React, { useState } from 'react'
import { useTransactions } from '../hooks/useTransactions'
import { Account } from '../types/accounts'
import { Transaction } from '../types/transactions'

interface TransactionListProps {
  transactions: { data: Transaction[] }
  onEdit: (transaction: Transaction) => void
  onDelete: (accountId: number, transactionId: number) => void
}

const TransactionList = ({ transactions, onEdit, onDelete }: TransactionListProps) => (
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
                onClick={() => onEdit(transaction)}
              >
                <IconEdit style={{ width: rem(16) }} />
              </ActionIcon>
              <ActionIcon
                variant='light'
                color='red'
                size='sm'
                onClick={() => onDelete(transaction.account.id, transaction.id)}
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

interface TransactionsSectionProps {
  selectedAccounts: string[]
  onEdit: (transaction: Transaction) => void
  onDelete: (accountId: number, transactionId: number) => void
  onCreateClick: () => void
  accounts?: { data: Account[] }
}

export const TransactionsSection = ({
  selectedAccounts,
  onEdit,
  onDelete,
  onCreateClick,
  accounts,
}: TransactionsSectionProps) => {
  const [page, setPage] = useState(1)
  const { useTransactionList } = useTransactions()
  const { data: transactions, isFetching } = useTransactionList({
    accountIds: selectedAccounts.map((id) => parseInt(id)),
    page,
    perPage: 20,
  })

  const isMobile = useMediaQuery('(max-width: 768px)')

  if (isFetching) return <Loader />

  const hasNoAccounts = !accounts?.data.length

  if (!transactions?.data.length) {
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
            {!hasNoAccounts && (
              <Button onClick={onCreateClick} leftSection={<IconPlus size={16} />} variant='light'>
                Nouvelle transaction
              </Button>
            )}
          </Group>
        </Card.Section>
        <Card.Section inheritPadding px='xl'>
          <Container h={100} display='flex'>
            <Stack justify='center' align='center' style={{ flex: 1 }} gap='xs'>
              <IconDatabaseOff
                style={{ width: rem(24), height: rem(24) }}
                stroke={1.5}
                color='gray'
              />
              <Text size={isMobile ? 'sm' : 'lg'} fw={500} c='gray' ta='center'>
                {hasNoAccounts
                  ? "Veuillez créer un compte avant d'ajouter des transactions" // eslint-disable-line quotes
                  : 'Aucune transaction trouvée'}
              </Text>
            </Stack>
          </Container>
        </Card.Section>
      </Card>
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
          <Button onClick={onCreateClick} leftSection={<IconPlus size={16} />} variant='light'>
            Nouvelle transaction
          </Button>
        </Group>
      </Card.Section>
      <Card.Section inheritPadding px='xl' mt='sm'>
        {isMobile ? (
          <>
            <TransactionList transactions={transactions} onEdit={onEdit} onDelete={onDelete} />
            <Group justify='center' mt='md'>
              <Pagination
                value={page}
                onChange={setPage}
                total={Math.ceil((transactions?.meta?.total || 0) / 20)}
                color='blue'
                withEdges
                styles={{
                  control: {
                    padding: isMobile ? '0 0.25rem' : undefined,
                    minWidth: isMobile ? '1.5rem' : undefined,
                    height: isMobile ? '1.5rem' : undefined,
                    fontSize: isMobile ? '0.75rem' : undefined,
                  },
                  dots: {
                    display: isMobile ? 'none' : undefined,
                  },
                }}
                siblings={isMobile ? 0 : 1}
                boundaries={isMobile ? 1 : 2}
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
                      <Table.Td>{new Date(transaction.date).toLocaleDateString('fr-FR')}</Table.Td>
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
                            onClick={() => onEdit(transaction)}
                          >
                            <IconEdit style={{ width: rem(16) }} />
                          </ActionIcon>
                          <ActionIcon
                            variant='light'
                            color='red'
                            size='sm'
                            onClick={() => onDelete(transaction.account.id, transaction.id)}
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
                {transactions?.meta?.from}-{transactions?.meta?.to} sur {transactions?.meta?.total}
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

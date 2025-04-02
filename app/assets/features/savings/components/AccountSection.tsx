import { AccountResponse, GetApiAccountsList200 } from '@/api/models'
import {
  ActionIcon,
  Badge,
  Button,
  Card,
  Group,
  Pagination,
  rem,
  Stack,
  Table,
  Text,
} from '@mantine/core'
import { useMediaQuery } from '@mantine/hooks'
import { IconEdit, IconPlus, IconTrash, IconWallet } from '@tabler/icons-react'
import React, { useState } from 'react'

interface AccountListProps {
  accounts: AccountResponse[]
  onEdit: (account: AccountResponse) => void
  onDelete: (accountId: string) => void
}

const AccountList = ({ accounts, onEdit, onDelete }: AccountListProps) => (
  <Stack gap='md'>
    {accounts.map((account) => (
      <Card key={account.id} radius='md'>
        <Stack gap='xs'>
          <Group justify='space-between' wrap='nowrap'>
            <Group gap='xs'>
              <IconWallet size={16} style={{ color: 'var(--mantine-color-blue-6)' }} />
              <Text fw={500} size='sm'>
                {account.name}
              </Text>
            </Group>
            <Group gap='xs'>
              <ActionIcon variant='light' color='blue' size='sm' onClick={() => onEdit(account)}>
                <IconEdit style={{ width: rem(16) }} />
              </ActionIcon>
              <ActionIcon
                variant='light'
                color='red'
                size='sm'
                onClick={() => onDelete(account.id.toString())}
              >
                <IconTrash style={{ width: rem(16) }} />
              </ActionIcon>
            </Group>
          </Group>
          <Group justify='space-between' wrap='nowrap'>
            <Stack gap={4}>
              <Badge variant='light' color='blue'>
                {account.type}
              </Badge>
              <Text size='xs' c='dimmed'>
                Solde actuel
              </Text>
            </Stack>
            <Text fw={700} c='blue'>
              {account.balance.toLocaleString('fr-FR')} €
            </Text>
          </Group>
        </Stack>
      </Card>
    ))}
  </Stack>
)

interface AccountsSectionProps {
  accounts: GetApiAccountsList200 | undefined
  onEdit: (account: AccountResponse) => void
  onDelete: (accountId: string) => void
  onCreateClick: () => void
}

export const AccountSection = ({
  accounts,
  onEdit,
  onDelete,
  onCreateClick,
}: AccountsSectionProps) => {
  const [page, setPage] = useState(1)
  const ITEMS_PER_PAGE = 3

  const isMobile = useMediaQuery('(max-width: 750px)')

  const paginatedAccounts = React.useMemo(() => {
    if (!accounts?.data?.length) return []

    const startIndex = (page - 1) * ITEMS_PER_PAGE
    const endIndex = startIndex + ITEMS_PER_PAGE

    return accounts.data.slice(startIndex, endIndex)
  }, [accounts, page])

  const totalPages = accounts?.data?.length ? Math.ceil(accounts.data.length / ITEMS_PER_PAGE) : 0
  const showPagination = totalPages > 1

  return (
    <Card radius='lg' py='xl' mt='sm' shadow='sm'>
      <Card.Section inheritPadding px='xl' pb='xs'>
        <Group justify='space-between'>
          <Group gap='xs'>
            <IconWallet size={20} style={{ color: 'var(--mantine-color-blue-6)' }} />
            <Text fw={500} size='md'>
              Comptes épargne
            </Text>
          </Group>
          <Button variant='light' leftSection={<IconPlus size={16} />} onClick={onCreateClick}>
            Créer un compte
          </Button>
        </Group>
      </Card.Section>
      <Card.Section inheritPadding px='xl' mt='sm'>
        {isMobile ? (
          <>
            <AccountList accounts={paginatedAccounts} onEdit={onEdit} onDelete={onDelete} />
            {showPagination && (
              <Group justify='center' mt='md'>
                <Pagination
                  value={page}
                  onChange={setPage}
                  total={totalPages}
                  color='blue'
                  withEdges
                  styles={{
                    control: {
                      padding: '0 0.25rem',
                      minWidth: '1.5rem',
                      height: '1.5rem',
                      fontSize: '0.75rem',
                    },
                    dots: {
                      display: 'none',
                    },
                  }}
                  siblings={0}
                  boundaries={1}
                />
              </Group>
            )}
          </>
        ) : (
          <>
            <Table.ScrollContainer minWidth={800}>
              <Table verticalSpacing='sm' horizontalSpacing='lg' highlightOnHover>
                <Table.Thead>
                  <Table.Tr>
                    <Table.Th>Nom</Table.Th>
                    <Table.Th>Type</Table.Th>
                    <Table.Th>Solde</Table.Th>
                    <Table.Th>Actions</Table.Th>
                  </Table.Tr>
                </Table.Thead>
                <Table.Tbody>
                  {paginatedAccounts.map((account) => (
                    <Table.Tr key={account.id}>
                      <Table.Td>
                        <Text fw={500} size='sm'>
                          <Group gap='xs'>
                            <IconWallet
                              size={16}
                              style={{ color: 'var(--mantine-color-blue-6)' }}
                            />
                            {account.name}
                          </Group>
                        </Text>
                      </Table.Td>
                      <Table.Td>
                        <Badge variant='light' color='blue'>
                          {account.type}
                        </Badge>
                      </Table.Td>
                      <Table.Td>
                        <Text fw={700} c='blue'>
                          {account.balance.toLocaleString('fr-FR')} €
                        </Text>
                      </Table.Td>
                      <Table.Td>
                        <Group gap='xs'>
                          <ActionIcon
                            variant='light'
                            color='blue'
                            size='sm'
                            onClick={() => onEdit(account)}
                          >
                            <IconEdit style={{ width: rem(16) }} />
                          </ActionIcon>
                          <ActionIcon
                            variant='light'
                            color='red'
                            size='sm'
                            onClick={() => onDelete(account.id.toString())}
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

            {showPagination && (
              <Group justify='center' mt='md'>
                <Pagination
                  value={page}
                  onChange={setPage}
                  total={totalPages}
                  color='blue'
                  withEdges
                  siblings={1}
                  boundaries={1}
                />
              </Group>
            )}
          </>
        )}
      </Card.Section>
    </Card>
  )
}

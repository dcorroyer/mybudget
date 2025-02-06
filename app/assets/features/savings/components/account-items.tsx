import { ActionIcon, Card, Container, Group, SimpleGrid, Stack, Text, rem } from '@mantine/core'
import { IconDatabaseOff, IconEdit, IconTrash } from '@tabler/icons-react'
import React from 'react'
import { Link } from 'react-router-dom'

import { CenteredLoader as Loader } from '@/components/centered-loader'
import { useAccount } from '../hooks/useAccount'

export const AccountItems = ({
  openDeleteModal,
  setAccountIdToDelete,
}: {
  openDeleteModal: () => void
  setAccountIdToDelete: (id: string | null) => void
}) => {
  const { useAccountList } = useAccount()
  const { data: accountList, isFetching } = useAccountList()

  if (isFetching) return <Loader />

  if (!accountList?.data.length) {
    return (
      <Container h={100} display='flex'>
        <Stack justify='center' align='center' style={{ flex: 1 }} gap='xs'>
          <IconDatabaseOff style={{ width: rem(24), height: rem(24) }} stroke={1.5} color='gray' />
          <Text size='lg' fw={500} c='gray'>
            No accounts found
          </Text>
        </Stack>
      </Container>
    )
  }

  const accounts = accountList.data.map((account) => (
    <div key={account.id}>
      <Card radius='lg' pb='xl' shadow='sm'>
        <Card.Section inheritPadding py='xs'>
          <Group justify='space-between'>
            <Text fw={500}>{account.name}</Text>
            <div>
              <ActionIcon
                component={Link}
                to={`/accounts/${account.id}`}
                variant='subtle'
                color='blue'
              >
                <IconEdit style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
              </ActionIcon>
              <ActionIcon
                onClick={() => {
                  setAccountIdToDelete(account.id.toString())
                  openDeleteModal()
                }}
                variant='subtle'
                color='red'
              >
                <IconTrash style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
              </ActionIcon>
            </div>
          </Group>
          <Text fw={500} c='dimmed' size='sm' mt='xs'>
            Balance: {account.balance} €
          </Text>
        </Card.Section>
      </Card>
    </div>
  ))

  return <SimpleGrid cols={{ base: 1, sm: 2, lg: 3 }}>{accounts}</SimpleGrid>
}

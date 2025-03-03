import { AccountResponse, GetApiAccountsList200 } from '@/api/models'
import { ActionIcon, Button, Card, Grid, Group, rem, Stack, Text } from '@mantine/core'
import { IconEdit, IconPlus, IconTrash, IconWallet } from '@tabler/icons-react'
import React from 'react'

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
}: AccountsSectionProps) => (
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
      <Grid>
        {(accounts?.data || []).map((account) => (
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
                    <ActionIcon variant='light' color='blue' onClick={() => onEdit(account)}>
                      <IconEdit style={{ width: rem(16), height: rem(16) }} />
                    </ActionIcon>
                    <ActionIcon
                      variant='light'
                      color='red'
                      onClick={() => onDelete(account.id.toString())}
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

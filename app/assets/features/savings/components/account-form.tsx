import { Button, Card, Group, Stack, Text, TextInput, rem } from '@mantine/core'
import { useForm } from '@mantine/form'
import { IconBuildingBank, IconCheck } from '@tabler/icons-react'
import { zodResolver } from 'mantine-form-zod-resolver'
import React from 'react'

import { useAccount } from '../hooks/useAccount'
import { accountFormSchema, createAccountFormType } from '../schemas/accounts'

interface AccountFormComponentProps {
  initialValues?: {
    id?: number
    name: string
  }
  onSuccess?: () => void
}

export const AccountForm: React.FC<AccountFormComponentProps> = ({ initialValues, onSuccess }) => {
  const form = useForm<createAccountFormType>({
    initialValues: initialValues || {
      name: '',
    },
    validate: zodResolver(accountFormSchema),
  })

  const { createAccount, updateAccount, isLoading } = useAccount()
  const isEditMode = !!initialValues?.id

  const onSubmit = (values: createAccountFormType) => {
    if (!isEditMode) {
      createAccount(values, {
        onSuccess: () => {
          onSuccess?.()
        },
      })
    } else if (initialValues?.id) {
      updateAccount(
        { id: initialValues.id, values },
        {
          onSuccess: () => {
            onSuccess?.()
          },
        },
      )
    }
  }

  return (
    <form onSubmit={form.onSubmit(onSubmit)}>
      <Stack gap='md'>
        <Card radius='lg' shadow='sm'>
          <Card.Section inheritPadding px='xl' pb='xs'>
            <Group gap='xs' my='md'>
              <IconBuildingBank
                style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-blue-6)' }}
              />
              <Text fw={500} size='md'>
                Détails du compte
              </Text>
            </Group>
          </Card.Section>

          <Card.Section withBorder inheritPadding px='xl' py='md'>
            <Stack gap='md'>
              <TextInput
                label='Nom du compte'
                placeholder='ex: Livret A, PEL...'
                {...form.getInputProps('name')}
                leftSection={<IconBuildingBank style={{ width: rem(16), height: rem(16) }} />}
                styles={{
                  input: {
                    backgroundColor: 'var(--mantine-color-gray-0)',
                  },
                }}
              />
            </Stack>
          </Card.Section>

          <Card.Section inheritPadding px='xl' py='md'>
            <Group justify='flex-end' gap='sm'>
              <Button variant='light' color='gray' onClick={onSuccess}>
                Annuler
              </Button>
              <Button
                type='submit'
                loading={isLoading}
                leftSection={<IconCheck style={{ width: rem(16), height: rem(16) }} />}
              >
                {isEditMode ? 'Mettre à jour' : 'Créer'}
              </Button>
            </Group>
          </Card.Section>
        </Card>
      </Stack>
    </form>
  )
}

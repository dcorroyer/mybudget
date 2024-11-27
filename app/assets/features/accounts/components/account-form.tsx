import { Button, Card, Container, rem, TextInput } from '@mantine/core'
import { useForm } from '@mantine/form'
import { IconCheck } from '@tabler/icons-react'
import { zodResolver } from 'mantine-form-zod-resolver'
import React from 'react'

import { useAccount } from '../hooks/useAccount'
import { accountFormSchema, createAccountFormType } from '../schemas/accounts'

import classes from './account-form.module.css'

interface AccountFormComponentProps {
  initialValues?: {
    id?: number
    name: string
  }
}

export const AccountForm: React.FC<AccountFormComponentProps> = ({ initialValues }) => {
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
      createAccount(values)
    } else if (initialValues?.id) {
      updateAccount({ id: initialValues.id, values })
    }
  }

  return (
    <Container size={560} my={40}>
      <form onSubmit={form.onSubmit(onSubmit)}>
        <Card radius='lg' py='xl' shadow='sm'>
          <Card.Section inheritPadding px='xl' pb='xs'>
            <TextInput
              label='Name'
              placeholder='Account name'
              {...form.getInputProps('name')}
              classNames={{ error: classes.error }}
            />
          </Card.Section>
          <Card.Section inheritPadding mt='sm' px='xl'>
            <Button
              type='submit'
              variant='white'
              color='black'
              className={classes.formButton}
              radius='md'
              loading={isLoading}
            >
              {isEditMode ? 'Update' : 'Create'}{' '}
              <IconCheck style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
            </Button>
          </Card.Section>
        </Card>
      </form>
    </Container>
  )
}

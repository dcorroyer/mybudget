import { Button, Card, NumberInput, rem, Select, TextInput } from '@mantine/core'
import { useForm } from '@mantine/form'
import { IconCheck } from '@tabler/icons-react'
import { zodResolver } from 'mantine-form-zod-resolver'
import React, { useEffect, useState } from 'react'

import { useTransactions } from '../hooks/useTransactions'
import { createTransactionFormType, transactionFormSchema } from '../schemas/transactions'

import { DatePickerInput } from '@mantine/dates'
import { useAccount } from '../../accounts/hooks/useAccount'
import { Transaction } from '../types/transactions'

import classes from './transaction-form.module.css'

interface TransactionFormComponentProps {
  initialValues?: Transaction
  isLoading?: boolean
}

export const TransactionForm: React.FC<TransactionFormComponentProps> = ({ initialValues }) => {
  const { useAccountList } = useAccount()
  const { data: accountList, isFetching } = useAccountList()

  const form = useForm<createTransactionFormType>({
    initialValues: initialValues || {
      description: '',
      amount: 0,
      type: 'CREDIT',
      date: new Date(),
      account: {
        id: 0,
        name: '',
      },
    },
    validate: zodResolver(transactionFormSchema),
  })

  const [dateValue, setDateValue] = useState<Date>(new Date())
  const [accountIdValue, setAccountIdValue] = useState<number>(0)
  const [isEditMode, setIsEditMode] = useState<boolean>(false)

  useEffect(() => {
    if (initialValues) {
      const initialDate = initialValues.date ? new Date(initialValues.date) : null

      if (initialDate) {
        setDateValue(initialDate)
        form.setValues({ date: initialDate })
      }

      setIsEditMode(true)
    } else {
      setIsEditMode(false)
    }
  }, [initialValues, form.setValues])

  const { createTransaction, updateTransaction, isLoading } = useTransactions()

  const onSubmit = (values: createTransactionFormType) => {
    if (!isEditMode) {
      createTransaction({ accountId: values.account.id, values })
    } else if (initialValues && initialValues.id) {
      updateTransaction({
        accountId: values.account.id,
        transactionId: initialValues.id,
        values: values,
      })
    }
  }

  return (
    <form onSubmit={form.onSubmit(onSubmit)}>
      <Card radius='lg' py='xl'>
        <Card.Section inheritPadding px='xl' pb='xs'>
          <TextInput
            label='Description'
            placeholder='Transaction description'
            {...form.getInputProps('description')}
            classNames={{ error: classes.error }}
          />

          <NumberInput
            label='Amount'
            placeholder='0'
            min={0}
            {...form.getInputProps('amount')}
            classNames={{ error: classes.error }}
            mt='md'
          />

          <Select
            {...form.getInputProps('account.id')}
            label='Account'
            placeholder={
              initialValues?.account.id ? `${initialValues?.account.name}` : 'Select an account'
            }
            data={
              accountList?.data.map((account) => ({
                value: account.id.toString(),
                label: account.name,
              })) || []
            }
            value={accountIdValue?.toString()}
            onChange={(accountId) => {
              if (accountId) {
                form.setFieldValue('account.id', parseInt(accountId))
                setAccountIdValue(parseInt(accountId))
              }
            }}
            classNames={{ error: classes.error }}
            mt='md'
            disabled={isFetching || initialValues?.account?.id ? true : false}
          />

          <Select
            label='Type'
            data={[
              { value: 'CREDIT', label: 'Credit (+)' },
              { value: 'DEBIT', label: 'Debit (-)' },
            ]}
            {...form.getInputProps('type')}
            classNames={{ error: classes.error }}
            mt='md'
          />

          <DatePickerInput
            label='Date'
            {...form.getInputProps('date')}
            classNames={{ error: classes.error }}
            mt='md'
            value={dateValue}
            onChange={(date) => {
              form.setFieldValue('date', date!)
              setDateValue(date!)
            }}
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
  )
}

import React, { useEffect, useState } from 'react'
import {
  Control,
  Controller,
  useFieldArray,
  useForm,
  UseFormRegister,
  UseFormSetValue,
  UseFormWatch,
} from 'react-hook-form'

import { zodResolver } from '@hookform/resolvers/zod'

import { Button, Card, Divider, Group, rem, SimpleGrid, Tabs, TextInput } from '@mantine/core'
import { MonthPickerInput } from '@mantine/dates'
import { IconCalendar, IconCheck, IconCurrencyEuro, IconPlus, IconX } from '@tabler/icons-react'

import { budgetDataTransformer } from '@/features/budgets/helpers'
import { useBudget } from '@/features/budgets/hooks/useBudget'
import { budgetFormSchema, createBudgetFormType } from '@/features/budgets/schemas'

import { BudgetFormDetails } from '../types'
import classes from './budget-form.module.css'

interface Card {
  category: string
  items: {
    name: string
    amount: number
  }[]
}

interface BudgetFormProps {
  budgetForm: {
    control: Control<createBudgetFormType>
    register: UseFormRegister<createBudgetFormType>
    setValue?: UseFormSetValue<createBudgetFormType>
    watch?: UseFormWatch<createBudgetFormType>
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    errors: any
  }
  isEditMode?: boolean
}

interface ExpenseCardProps {
  cardIndex: number
  budgetForm: BudgetFormProps['budgetForm']
  removeCard: (cardIndex: number) => void
  totalCards: number
}

const defaultExpense = {
  category: '',
  items: [
    {
      name: '',
      amount: 0,
    },
  ],
}

interface BudgetFormComponentProps {
  initialValues?: BudgetFormDetails
}

export const BudgetForm: React.FC<BudgetFormComponentProps> = ({ initialValues }) => {
  const [isEditMode, setIsEditMode] = useState<boolean>(false)

  const budgetForm = useForm<createBudgetFormType>({
    resolver: zodResolver(budgetFormSchema),
    defaultValues: initialValues || {
      incomes: [
        {
          name: '',
          amount: 0,
        },
      ],
      expenses: [defaultExpense],
    },
  })

  const {
    handleSubmit,
    control,
    setValue,
    formState: { errors },
    reset,
  } = budgetForm

  const [monthValue, setMonthValue] = useState<Date | null>(null)
  const icon = <IconCalendar style={{ width: rem(20), height: rem(20) }} stroke={1.5} />

  const { createBudget, updateBudget } = useBudget()

  useEffect(() => {
    if (initialValues) {
      const initialDate = initialValues.date ? new Date(initialValues.date) : null

      reset(initialValues)
      setMonthValue(initialDate)

      if (initialDate) {
        setValue('date', initialDate)
      }

      setIsEditMode(true)
    } else {
      setIsEditMode(false)
    }
  }, [initialValues, reset, setValue])

  const onSubmit = (values: createBudgetFormType) => {
    const data = budgetDataTransformer({ ...values, date: new Date(values.date) })

    if (!isEditMode) {
      createBudget(data)
    } else if (initialValues && initialValues.id) {
      updateBudget(initialValues.id, data)
    }
  }

  return (
    <>
      <form onSubmit={handleSubmit(onSubmit)}>
        <Controller
          control={control}
          name='date'
          render={({ field }) => (
            <div className={classes.relative}>
              <MonthPickerInput
                {...field}
                leftSection={icon}
                leftSectionPointerEvents='none'
                label='Budget date'
                placeholder='Date'
                value={monthValue}
                onChange={(month) => {
                  setMonthValue(month)
                  field.onChange(month)
                }}
              />
              {errors.date && <span className={classes.error}>{errors.date.message}</span>}
            </div>
          )}
        />
        <Tabs defaultValue='incomes' mt='xl'>
          <Tabs.List>
            <Tabs.Tab value='incomes' color='green'>
              Incomes
            </Tabs.Tab>
            <Tabs.Tab value='expenses' color='red'>
              Expenses
            </Tabs.Tab>
          </Tabs.List>
          <Tabs.Panel value='incomes'>
            <ManageIncomes budgetForm={{ ...budgetForm, errors }} />
          </Tabs.Panel>
          <Tabs.Panel value='expenses'>
            <ManageExpenses budgetForm={{ ...budgetForm, errors }} isEditMode={isEditMode} />
          </Tabs.Panel>
        </Tabs>
      </form>
    </>
  )
}

const ManageIncomes: React.FC<BudgetFormProps> = ({ budgetForm }) => {
  const currency = <IconCurrencyEuro style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
  const { control, register, errors } = budgetForm
  const { append, remove, fields } = useFieldArray({
    control,
    name: 'incomes',
  })

  return (
    <Card radius='lg' py='xl' mt='sm'>
      <Card.Section inheritPadding px='xl' pb='xs'>
        {fields.map((income, incomeIndex) => {
          return (
            <SimpleGrid
              cols={{ base: 1, sm: 2 }}
              mb='sm'
              className={classes.budgetLine}
              key={income.id}
            >
              <div className={classes.relative}>
                <TextInput
                  label='Name'
                  placeholder='Name'
                  {...register(`incomes.${incomeIndex}.name`)}
                  rightSection={'  '}
                />
                {errors.incomes?.[incomeIndex]?.name && (
                  <span className={classes.error}>
                    {errors.incomes?.[incomeIndex]?.name?.message}
                  </span>
                )}
              </div>
              <div className={classes.relative}>
                <TextInput
                  type='number'
                  label='Amount'
                  {...register(`incomes.${incomeIndex}.amount`, { valueAsNumber: true })}
                  rightSection={currency}
                />
                {errors.incomes?.[incomeIndex]?.amount && (
                  <span className={classes.error}>
                    {errors.incomes?.[incomeIndex]?.amount?.message}
                  </span>
                )}
              </div>
              <IconX
                onClick={() => {
                  remove(incomeIndex)
                }}
                className={classes.removeBudgetLineIcon}
                style={{ width: rem(20), height: rem(20) }}
                stroke={1.5}
              />
            </SimpleGrid>
          )
        })}
      </Card.Section>
      <Card.Section inheritPadding mt='sm' px='xl'>
        <Button
          type='button'
          variant='white'
          color='black'
          className={classes.formButton}
          radius='md'
          onClick={() => {
            append({ name: '', amount: 0 })
          }}
        >
          Add an income <IconPlus style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
        </Button>
      </Card.Section>
    </Card>
  )
}

const ManageExpenses: React.FC<BudgetFormProps> = ({ budgetForm, isEditMode }) => {
  const { setValue, watch, control, errors } = budgetForm
  const cards = watch ? watch('expenses') : []
  const { remove } = useFieldArray({
    control,
    name: 'expenses',
  })

  const addCard = () => {
    if (setValue) {
      const newCard = { ...defaultExpense }
      setValue('expenses', [...cards, newCard])
    }
  }

  const removeCard = (cardIndex: number) => {
    remove(cardIndex)
  }

  return (
    <>
      <div>
        {cards.map((card: Card, cardIndex: number) => (
          <ExpenseCard
            key={cardIndex}
            cardIndex={cardIndex}
            budgetForm={{ ...budgetForm, errors }}
            removeCard={removeCard}
            totalCards={cards.length}
          />
        ))}
        <Button
          type='button'
          variant='white'
          color='black'
          className={classes.formButton}
          radius='md'
          onClick={addCard}
          mt='sm'
          fullWidth
        >
          Add category <IconPlus style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
        </Button>
        <Button
          type='submit'
          variant='white'
          color='black'
          className={classes.formButton}
          radius='md'
          mt='sm'
          style={{ float: 'right' }}
        >
          {isEditMode ? 'Update' : 'Create'}{' '}
          <IconCheck style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
        </Button>
      </div>
    </>
  )
}

const ExpenseCard: React.FC<ExpenseCardProps> = ({ cardIndex, budgetForm, removeCard }) => {
  const currency = <IconCurrencyEuro style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
  const { control, register, errors } = budgetForm
  const { append, remove, fields } = useFieldArray({
    control,
    name: `expenses.${cardIndex}.items`,
  })

  React.useEffect(() => {
    if (fields.length === 0) {
      removeCard(cardIndex)
    }
  }, [fields.length, cardIndex, removeCard])

  return (
    <Card radius='lg' py='xl' mt='sm'>
      <Card.Section inheritPadding>
        <Group justify='space-between'>
          <div className={classes.relative}>
            <TextInput
              variant='unstyled'
              placeholder='Expense category name'
              {...register(`expenses.${cardIndex}.category`)}
              className={classes.categoryName}
            />
            {errors.expenses?.[cardIndex]?.category && (
              <span className={classes.errorCategory}>
                {errors.expenses?.[cardIndex]?.category?.message}
              </span>
            )}
          </div>
        </Group>
      </Card.Section>
      <Divider mt='xl' className={classes.divider} />
      <Card.Section inheritPadding mt='lg' px='xl' pb='xs'>
        {fields.map((expense, expenseIndex) => (
          <SimpleGrid
            cols={{ base: 1, sm: 2 }}
            mb='sm'
            className={classes.budgetLine}
            key={expense.id}
          >
            <div className={classes.relative}>
              <TextInput
                label='Name'
                placeholder='Name'
                {...register(`expenses.${cardIndex}.items.${expenseIndex}.name`)}
                rightSection={'  '}
              />
              {errors.expenses?.[cardIndex]?.items?.[expenseIndex]?.name && (
                <span className={classes.error}>
                  {errors.expenses?.[cardIndex]?.items?.[expenseIndex]?.name?.message}
                </span>
              )}
            </div>
            <div className={classes.relative}>
              <TextInput
                type='number'
                label='Amount'
                {...register(`expenses.${cardIndex}.items.${expenseIndex}.amount`, {
                  valueAsNumber: true,
                })}
                rightSection={currency}
              />
              {errors.expenses?.[cardIndex]?.items?.[expenseIndex]?.amount && (
                <span className={classes.error}>
                  {errors.expenses?.[cardIndex]?.items?.[expenseIndex]?.amount?.message}
                </span>
              )}
            </div>
            <IconX
              onClick={() => remove(expenseIndex)}
              className={classes.removeBudgetLineIcon}
              style={{
                width: rem(20),
                height: rem(20),
                cursor: cardIndex === 0 && expenseIndex === 0 ? 'none' : 'pointer',
                pointerEvents: cardIndex === 0 && expenseIndex === 0 ? 'none' : 'auto',
                color: cardIndex === 0 && expenseIndex === 0 ? 'gray' : 'black',
              }}
              stroke={1.5}
            />
          </SimpleGrid>
        ))}
      </Card.Section>
      <Card.Section inheritPadding mt='sm' px='xl'>
        <Button
          type='button'
          variant='white'
          color='black'
          className={classes.formButton}
          radius='md'
          onClick={() => append({ name: '', amount: 0 })}
        >
          Add an expense <IconPlus style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
        </Button>
      </Card.Section>
    </Card>
  )
}

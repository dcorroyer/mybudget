import {
  Button,
  Card,
  Divider,
  Group,
  NumberInput,
  rem,
  SimpleGrid,
  Tabs,
  TextInput,
} from '@mantine/core'
import { MonthPickerInput } from '@mantine/dates'
import { useForm, UseFormReturnType } from '@mantine/form'
import { IconCalendar, IconCheck, IconCurrencyEuro, IconPlus, IconX } from '@tabler/icons-react'
import { zodResolver } from 'mantine-form-zod-resolver'
import React, { useState } from 'react'
import { budgetFormSchema } from '../schemas/budgets'
import classes from './budget-form.module.css'

interface Income {
  name: string
  amount: number
}

type ExpenseItem = {
  name: string
  amount: number
}

type Expense = {
  category: string
  items: ExpenseItem[]
}

interface FormInterface {
  date: Date | null
  incomes: Income[]
  expenses: Expense[]
}

interface Card {
  category: string
  items: {
    name: string
    amount: number
  }[]
}

const defaultExpense: Expense = {
  category: '',
  items: [
    {
      name: '',
      amount: 0,
    },
  ],
}

export const BudgetForm = () => {
  const form = useForm<FormInterface>({
    initialValues: {
      date: null,
      incomes: [
        {
          name: '',
          amount: 0,
        },
      ],
      expenses: [defaultExpense],
    },
    validate: zodResolver(budgetFormSchema),
  })

  const [monthValue, setMonthValue] = useState<Date | null>(null)
  const icon = <IconCalendar style={{ width: rem(20), height: rem(20) }} stroke={1.5} />

  const onSubmit = (values: FormInterface) => {
    console.log('Form submitted:', values)
  }

  return (
    <form onSubmit={form.onSubmit(onSubmit)}>
      <div className={classes.relative}>
        <MonthPickerInput
          {...form.getInputProps('date')}
          leftSection={icon}
          leftSectionPointerEvents='none'
          label='Budget date'
          placeholder='Date'
          value={monthValue}
          onChange={(month) => {
            form.setFieldValue('date', month)
            setMonthValue(month)
          }}
        />
      </div>
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
          <ManageIncomes form={form} />
        </Tabs.Panel>
        <Tabs.Panel value='expenses'>
          <ManageExpenses form={form} />
        </Tabs.Panel>
      </Tabs>
    </form>
  )
}

const ManageIncomes = ({ form }: { form: UseFormReturnType<FormInterface> }) => {
  const currency = <IconCurrencyEuro style={{ width: rem(20), height: rem(20) }} stroke={1.5} />

  const fields = form.values.incomes

  return (
    <Card radius='lg' py='xl' mt='sm'>
      <Card.Section inheritPadding px='xl' pb='xs'>
        {fields.map((income, incomeIndex) => {
          return (
            <SimpleGrid
              cols={{ base: 1, sm: 2 }}
              mb='sm'
              className={classes.budgetLine}
              key={incomeIndex}
            >
              <div className={classes.relative}>
                <TextInput
                  label='Name'
                  placeholder='Name'
                  {...form.getInputProps(`incomes.${incomeIndex}.name`)}
                  rightSection={'  '}
                />
              </div>
              <div className={classes.relative}>
                <NumberInput
                  label='Amount'
                  {...form.getInputProps(`incomes.${incomeIndex}.amount`, { valueAsNumber: true })}
                  rightSection={currency}
                />
              </div>
              <IconX
                onClick={() => form.removeListItem('incomes', incomeIndex)}
                className={classes.removeBudgetLineIcon}
                style={{
                  width: rem(20),
                  height: rem(20),
                  cursor: incomeIndex === 0 ? 'none' : 'pointer',
                  pointerEvents: incomeIndex === 0 ? 'none' : 'auto',
                  color: incomeIndex === 0 ? 'gray' : 'black',
                }}
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
          onClick={() => form.insertListItem('incomes', { name: '', amount: 0 })}
        >
          Add an income <IconPlus style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
        </Button>
      </Card.Section>
    </Card>
  )
}

const ManageExpenses = ({ form }: { form: UseFormReturnType<FormInterface> }) => {
  const currency = <IconCurrencyEuro style={{ width: rem(20), height: rem(20) }} stroke={1.5} />

  const cards = form.values.expenses

  const addCard = () => {
    form.insertListItem('expenses', { ...defaultExpense })
  }

  const removeCard = (cardIndex: number) => {
    form.removeListItem('expenses', cardIndex)
  }

  const addExpenseItem = (cardIndex: number) => {
    form.insertListItem(`expenses.${cardIndex}.items`, { name: '', amount: 0 })
  }

  const removeExpenseItem = (cardIndex: number, expenseIndex: number) => {
    form.removeListItem(`expenses.${cardIndex}.items`, expenseIndex)
    const updatedCards = form.values.expenses
    if (updatedCards[cardIndex].items.length === 0) {
      removeCard(cardIndex)
    }
  }

  return (
    <>
      <div>
        {cards.map((card: Card, cardIndex: number) => (
          <Card radius='lg' py='xl' mt='sm' key={cardIndex}>
            <Card.Section inheritPadding>
              <Group justify='space-between'>
                <div className={classes.relative}>
                  <TextInput
                    variant='unstyled'
                    placeholder='Expense category name'
                    {...form.getInputProps(`expenses.${cardIndex}.category`)}
                    className={classes.categoryName}
                  />
                </div>
              </Group>
            </Card.Section>
            <Divider mt='xl' className={classes.divider} />
            <Card.Section inheritPadding mt='lg' px='xl' pb='xs'>
              {card.items.map((expenseItem: ExpenseItem, expenseIndex: number) => (
                <SimpleGrid
                  cols={{ base: 1, sm: 2 }}
                  mb='sm'
                  className={classes.budgetLine}
                  key={expenseIndex}
                >
                  <div className={classes.relative}>
                    <TextInput
                      label='Name'
                      placeholder='Name'
                      {...form.getInputProps(`expenses.${cardIndex}.items.${expenseIndex}.name`)}
                      rightSection={'  '}
                    />
                  </div>
                  <div className={classes.relative}>
                    <NumberInput
                      label='Amount'
                      {...form.getInputProps(`expenses.${cardIndex}.items.${expenseIndex}.amount`, {
                        valueAsNumber: true,
                      })}
                      rightSection={currency}
                    />
                  </div>
                  <IconX
                    onClick={() => removeExpenseItem(cardIndex, expenseIndex)}
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
                onClick={() => addExpenseItem(cardIndex)}
              >
                Add an expense <IconPlus style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
              </Button>
            </Card.Section>
          </Card>
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
          Create
          <IconCheck style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
        </Button>
      </div>
    </>
  )
}

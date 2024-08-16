import React from 'react'
import {
  Control,
  useFieldArray,
  useForm,
  UseFormRegister,
  UseFormSetValue,
  UseFormWatch,
} from 'react-hook-form'

import { zodResolver } from '@hookform/resolvers/zod'
import { Button, Card, Divider, Group, rem, SimpleGrid, Tabs, TextInput } from '@mantine/core'

import { IconCheck, IconCurrencyEuro, IconPlus, IconX } from '@tabler/icons-react'

import { budgetFormSchema, createBudgetFormType } from '@/features/budgets/schemas'

import { budgetDataTransformer } from '../helpers'
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
  }
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

export const BudgetForm = () => {
  const budgetForm = useForm<createBudgetFormType>({
    resolver: zodResolver(budgetFormSchema),
    defaultValues: {
      incomes: [
        {
          name: '',
          amount: 0,
        },
      ],
      expenses: [
        {
          category: '',
          items: [
            {
              name: '',
              amount: 0,
            },
          ],
        },
      ],
    },
  })

  const onSubmit = (values: createBudgetFormType) => {
    const data = budgetDataTransformer(values)
    console.log(data)
  }

  return (
    <>
      <form
        onSubmit={(event) => {
          event.preventDefault()
          onSubmit(budgetForm.getValues())
        }}
      >
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
            <ManageIncomes budgetForm={budgetForm} />
          </Tabs.Panel>
          <Tabs.Panel value='expenses'>
            <ManageExpenses budgetForm={budgetForm} />
          </Tabs.Panel>
        </Tabs>
      </form>
    </>
  )
}

const ManageIncomes: React.FC<BudgetFormProps> = ({ budgetForm }) => {
  const currency = <IconCurrencyEuro style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
  const { control, register } = budgetForm
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
              <TextInput
                label='Name'
                placeholder='Name'
                {...register(`incomes.${incomeIndex}.name`)}
                rightSection={'  '}
              />
              <TextInput
                type='number'
                label='Amount'
                {...register(`incomes.${incomeIndex}.amount`, { valueAsNumber: true })}
                rightSection={currency}
              />
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

const ManageExpenses: React.FC<BudgetFormProps> = ({ budgetForm }) => {
  const { setValue, watch, control } = budgetForm
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
            budgetForm={budgetForm}
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
          Save <IconCheck style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
        </Button>
      </div>
    </>
  )
}

const ExpenseCard: React.FC<ExpenseCardProps> = ({ cardIndex, budgetForm, removeCard }) => {
  const currency = <IconCurrencyEuro style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
  const { control, register } = budgetForm
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
          <TextInput
            variant='unstyled'
            placeholder='Expense category name'
            {...register(`expenses.${cardIndex}.category`)}
            className={classes.categoryName}
          />
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
            <TextInput
              label='Name'
              placeholder='Name'
              {...register(`expenses.${cardIndex}.items.${expenseIndex}.name`)}
              rightSection={'  '}
            />
            <TextInput
              type='number'
              label='Amount'
              {...register(`expenses.${cardIndex}.items.${expenseIndex}.amount`)}
              rightSection={currency}
            />
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

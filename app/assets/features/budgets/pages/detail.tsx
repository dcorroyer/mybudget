import React, { useState } from 'react'

import { ActionIcon, Container, rem, SimpleGrid, Text } from '@mantine/core'
import {
  IconChevronLeft,
  IconCreditCard,
  IconCreditCardPay,
  IconCreditCardRefund,
  IconPencil,
  IconPencilOff,
} from '@tabler/icons-react'

import { Link, useParams } from 'react-router-dom'

import { CenteredLoader as Loader } from '@/components/centered-loader'
import { BudgetForm } from '@/features/budgets/components/budget-form'
import { BudgetTable } from '../components/budget-table'
import { groupExpensesByCategory } from '../helpers/budgetDataTransformer'
import { useBudget } from '../hooks/useBudget'

import NotFound from '@/components/not-found'
import { BudgetSummaryCards } from '../components/budget-summary-cards'
import classes from './detail.module.css'

const BudgetDetail: React.FC = () => {
  const { id } = useParams()
  const { useBudgetDetail } = useBudget()
  const { data: budget, isFetching } = useBudgetDetail(Number(id))

  const [editMode, setEditMode] = useState(false)

  if (isFetching) return <Loader />
  if (!budget) return <NotFound />

  const formattedExpenses = groupExpensesByCategory(budget.data.expenses)
  const budgetData = { ...budget?.data, expenses: formattedExpenses }

  const toggleEditMode = () => {
    setEditMode((prev) => !prev)
  }

  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        <ActionIcon variant='transparent' c='black' component={Link} to='/budgets'>
          <IconChevronLeft className={classes.title} />
        </ActionIcon>
        {budget?.data.name}
        <ActionIcon
          variant='transparent'
          c='black'
          onClick={toggleEditMode}
          ml='sm'
          className={classes.editModeButton}
        >
          {editMode ? (
            <IconPencilOff style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
          ) : (
            <IconPencil style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
          )}
        </ActionIcon>
      </Text>
      <Container>
        <SimpleGrid cols={{ base: 1, sm: 2, lg: 3 }}>
          <BudgetSummaryCards
            icon={IconCreditCard}
            color='blue'
            title='Saving Capacity'
            amount={budget?.data.savingCapacity}
          />
          <BudgetSummaryCards
            icon={IconCreditCardRefund}
            color='green'
            title='Total Incomes'
            amount={budget?.data.incomesAmount}
          />
          <BudgetSummaryCards
            icon={IconCreditCardPay}
            color='red'
            title='Total Expenses'
            amount={budget?.data.expensesAmount}
          />
        </SimpleGrid>
      </Container>

      {editMode ? (
        <BudgetForm initialValues={budgetData} />
      ) : (
        <BudgetTable budgetValues={budgetData} />
      )}
    </>
  )
}

export default BudgetDetail

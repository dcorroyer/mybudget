import React, { useState } from 'react'

import { ActionIcon, Card, Container, Group, Loader, rem, SimpleGrid, Text } from '@mantine/core'
import {
  IconChevronLeft,
  IconCreditCard,
  IconCreditCardPay,
  IconCreditCardRefund,
  IconPencil,
  IconPencilOff,
} from '@tabler/icons-react'

import { Link, useParams } from 'react-router-dom'

import { BudgetForm } from '@/features/budgets/components/budget-form'
import { BudgetTable } from '../components/budget-table'
import { groupExpensesByCategory } from '../helpers/budgetDataTransformer'
import { useBudget } from '../hooks/useBudget'

import classes from './detail.module.css'

const BudgetDetail: React.FC = () => {
  const { id } = useParams()
  const { useBudgetDetail } = useBudget()
  const { budget, isFetching } = useBudgetDetail(Number(id))

  const [editMode, setEditMode] = useState(false)

  if (isFetching) return <Loader />

  if (!budget) {
    return <Text fw={500}>Budget not found.</Text>
  }

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
        <SimpleGrid cols={3}>
          <Card radius='lg' pb='xl'>
            <Card.Section inheritPadding py='xs'>
              <Group justify='left' gap='xl' mt='xs'>
                <div className={classes.divIconBlue}>
                  <IconCreditCard className={classes.iconBlue} stroke={1.5} />
                </div>
              </Group>
            </Card.Section>
            <Card.Section inheritPadding py='xs'>
              <Group justify='space-between'>
                <Text fw={500}>Saving Capacity</Text>
              </Group>
              <Text fw={500} c='blue'>
                {budget?.data.savingCapacity} €
              </Text>
            </Card.Section>
          </Card>

          <Card radius='lg' pb='xl'>
            <Card.Section inheritPadding py='xs'>
              <Group justify='left' gap='xl' mt='xs'>
                <div className={classes.divIconGreen}>
                  <IconCreditCardRefund className={classes.iconGreen} stroke={1.5} />
                </div>
              </Group>
            </Card.Section>
            <Card.Section inheritPadding py='xs'>
              <Group justify='space-between'>
                <Text fw={500}>Total Incomes</Text>
              </Group>
              <Text fw={500} c='green'>
                {budget?.data.incomesAmount} €
              </Text>
            </Card.Section>
          </Card>

          <Card radius='lg' pb='xl'>
            <Card.Section inheritPadding py='xs'>
              <Group justify='left' gap='xl' mt='xs'>
                <div className={classes.divIconRed}>
                  <IconCreditCardPay className={classes.iconRed} stroke={1.5} />
                </div>
              </Group>
            </Card.Section>
            <Card.Section inheritPadding py='xs'>
              <Group justify='space-between'>
                <Text fw={500}>Total Expenses</Text>
              </Group>
              <Text fw={500} c='red'>
                {budget?.data.expensesAmount} €
              </Text>
            </Card.Section>
          </Card>
        </SimpleGrid>
      </Container>

      <Container size={560} my={40}>
        {editMode ? (
          <BudgetForm initialValues={budgetData} />
        ) : (
          <BudgetTable budgetValues={budgetData} />
        )}
      </Container>
    </>
  )
}

export default BudgetDetail

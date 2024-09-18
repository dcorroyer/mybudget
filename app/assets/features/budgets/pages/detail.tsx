import React from 'react'

import { ActionIcon, Card, Container, Group, SimpleGrid, Text } from '@mantine/core'
import {
  IconChevronLeft,
  IconCreditCard,
  IconCreditCardPay,
  IconCreditCardRefund,
} from '@tabler/icons-react'

import { Link } from '@tanstack/react-router'

import { ApiResponse } from '@/utils/ApiResponse'

import { BudgetDetails } from '@/features/budgets/types'
import { BudgetForm } from '../components/budget-form'

import classes from './detail.module.css'
import { reverseExpensesTransformation } from '../helpers'

export function BudgetDetail({ budget }: { budget: ApiResponse<BudgetDetails> }) {
  const formattedExpenses = reverseExpensesTransformation(budget.data.expenses)
  const updatedBudgetData = { ...budget.data, expenses: formattedExpenses }

  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        <ActionIcon variant='transparent' c='black' component={Link} to='/budgets'>
          <IconChevronLeft className={classes.title} />
        </ActionIcon>
        {budget.data.name}
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
                {budget.data.savingCapacity} €
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
                {budget.data.incomesAmount} €
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
                {budget.data.expensesAmount} €
              </Text>
            </Card.Section>
          </Card>
        </SimpleGrid>
      </Container>

      <Container size={560} my={40}>
        <BudgetForm initialValues={updatedBudgetData} />
      </Container>
    </>
  )
}

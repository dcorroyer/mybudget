import React from 'react'
import { Link } from 'react-router-dom'

import { ActionIcon, Badge, Card, Group, SimpleGrid, Text, rem } from '@mantine/core'
import { IconEye, IconTrash } from '@tabler/icons-react'

import { CenteredLoader as Loader } from '@/components/centered-loader'
import { useBudget } from '../hooks/useBudget'

import classes from './budget-items.module.css'

export const BudgetItems = ({
  selectedYear,
  openModal,
  setBudgetIdToDelete,
}: {
  selectedYear: number
  openModal: () => void
  setBudgetIdToDelete: (id: string | null) => void
}) => {
  const { useBudgetList } = useBudget()

  const { budgetList, isFetching } = useBudgetList(selectedYear)

  if (isFetching) return <Loader />

  const budgets = budgetList?.data.map((budget) => (
    <div key={budget.id}>
      <Card radius='lg' pb='xl'>
        <Card.Section inheritPadding py='xs'>
          <Group justify='space-between'>
            <Text fw={500}>{budget.name}</Text>
            <div>
              <ActionIcon
                component={Link}
                to={`/budgets/${budget.id}`}
                variant='subtle'
                color='gray'
              >
                <IconEye style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
              </ActionIcon>
              <ActionIcon
                onClick={() => {
                  setBudgetIdToDelete(budget.id.toString())
                  openModal()
                }}
                variant='subtle'
                color='red'
              >
                <IconTrash style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
              </ActionIcon>
            </div>
          </Group>
          <Text fw={500} c='blue' className={classes.subTitle}>
            <span>Saving Capacity: </span>
            {budget.savingCapacity} €
          </Text>
        </Card.Section>
        <Card.Section inheritPadding py='xs'>
          <Group justify='center' gap='xl'>
            <div className={classes.amount}>
              <Text fw={500} size='sm' c='gray'>
                Incomes
              </Text>
              <Badge className={classes.incomesBadge} size='lg' radius='md'>
                {budget.incomesAmount} €
              </Badge>
            </div>
            <div className={classes.amount}>
              <Text fw={500} size='sm' c='gray'>
                Expenses
              </Text>
              <Badge className={classes.expensesBadge} size='lg' radius='md'>
                {budget.expensesAmount} €
              </Badge>
            </div>
          </Group>
        </Card.Section>
      </Card>
    </div>
  ))

  return <SimpleGrid cols={{ base: 1, sm: 2, lg: 3 }}>{budgets}</SimpleGrid>
}

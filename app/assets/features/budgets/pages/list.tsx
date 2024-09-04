import React from 'react'

import {
  ActionIcon,
  Badge,
  Card,
  Container,
  Group,
  Loader,
  rem,
  SimpleGrid,
  Text,
} from '@mantine/core'

import { IconEdit } from '@tabler/icons-react'
import { Link } from '@tanstack/react-router'

import { useBudgetList } from '@/features/budgets/hooks/useBudget'

import classes from './list.module.css'

export const BudgetList = () => {
  const { data, isLoading } = useBudgetList()

  const budgets = data?.data.map((budget) => (
    <div key={budget.id}>
      <Card radius='lg' pb='xl'>
        <Card.Section inheritPadding py='xs'>
          <Group justify='space-between'>
            <Text fw={500}>{budget.name}</Text>
            <ActionIcon
              component={Link}
              to={'/budgets/$id'}
              params={{ id: budget.id.toString() }}
              variant='subtle'
              color='gray'
              className={classes.editButton}
            >
              <IconEdit style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
            </ActionIcon>
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

  if (isLoading) {
    return (
      <Container>
        <Loader />
      </Container>
    )
  }

  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        Budget&apos;s List
      </Text>
      <Container>
        <SimpleGrid cols={3} pb='xs'>
          <Link className={classes.link} to={'/budgets/create'}>
            <IconEdit className={classes.linkIcon} stroke={1.5} />
            <span>Create</span>
          </Link>
        </SimpleGrid>
        <SimpleGrid cols={{ base: 1, sm: 2, lg: 3 }}>{budgets}</SimpleGrid>
      </Container>
    </>
  )
}

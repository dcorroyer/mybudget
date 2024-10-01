import React, { useState } from 'react'

import { useBudget, useBudgetList } from '@/features/budgets/hooks/useBudget'
import {
  ActionIcon,
  Badge,
  Card,
  Center,
  Container,
  Group,
  Loader,
  Modal,
  rem,
  SimpleGrid,
  Text,
} from '@mantine/core'

import { useDisclosure } from '@mantine/hooks'
import { IconEdit, IconTrash } from '@tabler/icons-react'

import { Link } from 'react-router-dom'

import classes from './list.module.css'

const BudgetList: React.FC = () => {
  const { data, isLoading } = useBudgetList()
  const [opened, { open, close }] = useDisclosure(false)
  const { deleteBudget } = useBudget()
  const [budgetIdToDelete, setBudgetIdToDelete] = useState<string | null>(null)

  const handleDelete = () => {
    if (budgetIdToDelete) {
      deleteBudget(budgetIdToDelete)
      close()
    }
  }

  const budgets = data?.data.map((budget) => (
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
                <IconEdit style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
              </ActionIcon>
              <ActionIcon
                onClick={() => {
                  setBudgetIdToDelete(budget.id.toString())
                  open()
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
          <Link className={classes.linkItem} to={'/budgets/create'}>
            <IconEdit className={classes.linkIcon} stroke={1.5} />
            <span>Create</span>
          </Link>
        </SimpleGrid>
        <Modal
          opened={opened}
          onClose={close}
          radius={12.5}
          size='sm'
          title='Are you sure you want to delete this budget?'
          centered
        >
          <Center>
            <Link className={classes.deleteItem} onClick={handleDelete} to={''}>
              <IconTrash className={classes.deleteIcon} stroke={1.5} />
              <span>Delete</span>
            </Link>
          </Center>
        </Modal>
        <SimpleGrid cols={{ base: 1, sm: 2, lg: 3 }}>{budgets}</SimpleGrid>
      </Container>
    </>
  )
}

export default BudgetList

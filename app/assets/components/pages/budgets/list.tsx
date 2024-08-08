import React from 'react'

import { Link } from '@tanstack/react-router'

import { ActionIcon, Badge, Container, Group, Loader, rem, Table, Text } from '@mantine/core'
import { IconPencil, IconTrash } from '@tabler/icons-react'

import { useBudgetList } from '@/hooks/useBudget'

export const BudgetList = () => {
  const { data, isLoading } = useBudgetList()

  const budgets = data?.data.map((budget) => (
    <Table.Tr key={budget.id}>
      <Table.Td>
        <Group gap='sm'>
          <Text fz='sm' fw={500}>
            {budget.name}
          </Text>
        </Group>
      </Table.Td>
      <Table.Td>
        <Badge variant='light'>{budget.savingCapacity}</Badge>
      </Table.Td>
      <Table.Td>
        <Badge variant='light' color='green'>
          {budget.incomesAmount}
        </Badge>
      </Table.Td>
      <Table.Td>
        <Badge variant='light' color='red'>
          {budget.expensesAmount}
        </Badge>
      </Table.Td>
      <Table.Td>
        <Group gap={0} justify='flex-end'>
          <ActionIcon variant='subtle' color='gray'>
            <Link to={'/budgets/$id'} params={{ id: budget.id }}>
              <IconPencil style={{ width: rem(16), height: rem(16) }} stroke={1.5} />
            </Link>
          </ActionIcon>
          <ActionIcon variant='subtle' color='red'>
            <IconTrash style={{ width: rem(16), height: rem(16) }} stroke={1.5} />
          </ActionIcon>
        </Group>
      </Table.Td>
    </Table.Tr>
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
      <Container>
        <Table.ScrollContainer minWidth={800}>
          <Table verticalSpacing='sm'>
            <Table.Thead>
              <Table.Tr>
                <Table.Th>Name</Table.Th>
                <Table.Th>Saving Capacity</Table.Th>
                <Table.Th>Incomes</Table.Th>
                <Table.Th>Expenses</Table.Th>
                <Table.Th />
              </Table.Tr>
            </Table.Thead>
            <Table.Tbody>{budgets}</Table.Tbody>
          </Table>
        </Table.ScrollContainer>
      </Container>
    </>
  )
}

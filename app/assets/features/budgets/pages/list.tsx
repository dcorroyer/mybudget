import React, { useState } from 'react'
import { Link } from 'react-router-dom'

import { ActionIcon, Center, Container, Group, Modal, rem, Text } from '@mantine/core'
import { useDisclosure } from '@mantine/hooks'
import {
  IconChevronLeft,
  IconChevronRight,
  IconSquareRoundedPlus2,
  IconTrash,
} from '@tabler/icons-react'

import { BudgetItems } from '../components/budget-items'
import { useBudget } from '../hooks/useBudget'

import classes from './list.module.css'

const BudgetList: React.FC = () => {
  const { deleteBudget } = useBudget()

  const currentYear = new Date().getFullYear()
  const [selectedYear, setSelectedYear] = useState(currentYear)

  const [opened, { open, close }] = useDisclosure(false)
  const [budgetIdToDelete, setBudgetIdToDelete] = useState<string | null>(null)

  const handleDelete = () => {
    if (budgetIdToDelete) {
      deleteBudget(budgetIdToDelete)
      close()
    }
  }

  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        Budget&apos;s List
        <ActionIcon
          variant='transparent'
          ml='sm'
          className={classes.linkItem}
          component={Link}
          to={'/budgets/create'}
        >
          <IconSquareRoundedPlus2 className={classes.linkIcon} stroke={1.5} />
          <span style={{ padding: rem(2.5) }}>Create</span>
        </ActionIcon>
      </Text>
      <Container>
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
        <Group justify='center' gap='xl' mb='xl'>
          <ActionIcon
            variant='transparent'
            c='black'
            onClick={() => setSelectedYear(selectedYear - 1)}
          >
            <IconChevronLeft stroke={1.5} />
          </ActionIcon>
          <Text fw={500} size='lg' pb='xl' style={{ transform: 'translateY(1rem)' }}>
            {selectedYear}
          </Text>
          <ActionIcon
            variant='transparent'
            c='black'
            onClick={() => setSelectedYear(selectedYear + 1)}
          >
            <IconChevronRight stroke={1.5} />
          </ActionIcon>
        </Group>
        <BudgetItems
          selectedYear={selectedYear}
          openModal={open}
          setBudgetIdToDelete={setBudgetIdToDelete}
        />
      </Container>
    </>
  )
}

export default BudgetList

import React, { useState } from 'react'
import { Link } from 'react-router-dom'

import { ActionIcon, Center, Container, Group, Modal, rem, Text } from '@mantine/core'
import { useDisclosure } from '@mantine/hooks'
import {
  IconChevronLeft,
  IconChevronRight,
  IconCopy,
  IconSquarePlus2,
  IconTrash,
} from '@tabler/icons-react'

import { BudgetItems } from '../components/budget-items'
import { useBudget } from '../hooks/useBudget'

import classes from './list.module.css'

const BudgetList: React.FC = () => {
  const { deleteBudget, duplicateBudget } = useBudget()

  const currentYear = new Date().getFullYear()
  const [selectedYear, setSelectedYear] = useState(currentYear)

  const [openedDelete, { open: openDelete, close: closeDelete }] = useDisclosure(false)
  const [openedDuplicate, { open: openDuplicate, close: closeDuplicate }] = useDisclosure(false)
  const [budgetIdToDelete, setBudgetIdToDelete] = useState<string | null>(null)
  const [budgetIdToDuplicate, setBudgetIdToDuplicate] = useState<string | null>(null)

  const handleDelete = () => {
    if (budgetIdToDelete) {
      deleteBudget(budgetIdToDelete)
      closeDelete()
    }
  }

  const handleDuplicate = () => {
    if (budgetIdToDuplicate) {
      duplicateBudget(budgetIdToDuplicate)
      closeDuplicate()
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
          <IconSquarePlus2 className={classes.linkIcon} stroke={1.5} />
          <span style={{ padding: rem(2.5) }}>Create</span>
        </ActionIcon>
      </Text>
      <Container>
        {/* Delete Confirmation Modal */}
        <Modal
          opened={openedDelete}
          onClose={closeDelete}
          radius={12.5}
          size='sm'
          title='Are you sure you want to delete this budget?'
          centered
        >
          <Center>
            <Link className={classes.modalItem} onClick={handleDelete} to={''}>
              <IconTrash className={classes.modalIcon} stroke={1.5} />
              <span>Delete</span>
            </Link>
          </Center>
        </Modal>

        {/* Duplicate Confirmation Modal */}
        <Modal
          opened={openedDuplicate}
          onClose={closeDuplicate}
          radius={12.5}
          size='sm'
          title='Are you sure you want to duplicate this budget?'
          centered
        >
          <Center>
            <Link className={classes.modalItem} onClick={handleDuplicate} to={''}>
              <IconCopy className={classes.modalIcon} stroke={1.5} />
              <span>Duplicate</span>
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
          openDeleteModal={openDelete}
          openDuplicateModal={openDuplicate}
          setBudgetIdToDelete={setBudgetIdToDelete}
          setBudgetIdToDuplicate={setBudgetIdToDuplicate}
        />
      </Container>
    </>
  )
}

export default BudgetList

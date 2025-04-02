import { CenteredLoader as Loader } from '@/components/CenteredLoader'
import {
  ActionIcon,
  Badge,
  Button,
  Card,
  Container,
  Group,
  Modal,
  Stack,
  Table,
  Text,
  Title,
  rem,
} from '@mantine/core'
import { useDisclosure, useMediaQuery } from '@mantine/hooks'
import {
  IconArrowLeft,
  IconArrowRight,
  IconCalendar,
  IconCopy,
  IconDatabaseOff,
  IconEye,
  IconPlus,
  IconTrash,
  IconWallet,
} from '@tabler/icons-react'
import React, { useState } from 'react'
import { Link } from 'react-router-dom'

import {
  useDeleteApiBudgetsDelete,
  useGetApiBudgetsList,
  usePostApiBudgetsDuplicate,
} from '@/api/generated/budgets/budgets'
import { useMutationWithInvalidation } from '@/hooks/useMutation'

interface BudgetCardListProps {
  budgets: any[]
  onDelete: (id: string) => void
  onDuplicate: (id: string) => void
}

const BudgetCardList = ({ budgets, onDelete, onDuplicate }: BudgetCardListProps) => (
  <Stack gap='md'>
    {budgets.map((budget) => (
      <Card key={budget.id} radius='md'>
        <Stack gap='xs'>
          <Group justify='space-between' wrap='nowrap'>
            <Group gap='xs'>
              <IconCalendar size={16} style={{ color: 'var(--mantine-color-blue-6)' }} />
              <Text fw={500} size='sm'>
                {new Date(budget.date).toLocaleDateString('fr-FR', { month: 'long' })}{' '}
                {budget.date.toString().split('-')[0]}
              </Text>
            </Group>
            <Group gap='xs'>
              <ActionIcon
                component={Link}
                to={`/budgets/${budget.id}`}
                variant='light'
                color='blue'
                size='sm'
              >
                <IconEye style={{ width: rem(16) }} />
              </ActionIcon>
              <ActionIcon
                variant='light'
                color='gray'
                size='sm'
                onClick={() => onDuplicate(budget.id.toString())}
              >
                <IconCopy style={{ width: rem(16) }} />
              </ActionIcon>
              <ActionIcon
                variant='light'
                color='red'
                size='sm'
                onClick={() => onDelete(budget.id.toString())}
              >
                <IconTrash style={{ width: rem(16) }} />
              </ActionIcon>
            </Group>
          </Group>
          <Stack gap={8}>
            <Group justify='space-between' wrap='nowrap'>
              <Text size='xs' c='dimmed'>
                Capacité d&apos;épargne
              </Text>
              <Text fw={700} c='blue'>
                {budget.savingCapacity.toLocaleString('fr-FR')} €
              </Text>
            </Group>
            <Group justify='space-between' wrap='nowrap'>
              <Text size='xs' c='dimmed'>
                Revenus
              </Text>
              <Badge variant='light' color='teal'>
                {budget.incomesAmount.toLocaleString('fr-FR')} €
              </Badge>
            </Group>
            <Group justify='space-between' wrap='nowrap'>
              <Text size='xs' c='dimmed'>
                Dépenses
              </Text>
              <Badge variant='light' color='red'>
                {budget.expensesAmount.toLocaleString('fr-FR')} €
              </Badge>
            </Group>
          </Stack>
        </Stack>
      </Card>
    ))}
  </Stack>
)

const BudgetGrid = ({
  selectedYear,
  onDelete,
  onDuplicate,
}: {
  selectedYear: number
  onDelete: (id: string) => void
  onDuplicate: (id: string) => void
}) => {
  const { data: budgetList, isLoading } = useGetApiBudgetsList(
    { year: selectedYear },
    {
      query: {
        staleTime: 10000,
      },
    },
  )

  const isMobile = useMediaQuery('(max-width: 750px)')

  if (isLoading) return <Loader />

  if (!budgetList?.data || budgetList.data.length === 0) {
    return (
      <Container h={100} display='flex'>
        <Stack justify='center' align='center' style={{ flex: 1 }} gap='xs'>
          <IconDatabaseOff style={{ width: rem(24), height: rem(24) }} stroke={1.5} color='gray' />
          <Text size={isMobile ? 'sm' : 'lg'} fw={500} c='gray'>
            Aucun budget trouvé
          </Text>
        </Stack>
      </Container>
    )
  }

  return isMobile ? (
    <BudgetCardList budgets={budgetList.data} onDelete={onDelete} onDuplicate={onDuplicate} />
  ) : (
    <Table.ScrollContainer minWidth={800}>
      <Table verticalSpacing='sm' horizontalSpacing='lg' highlightOnHover>
        <Table.Thead>
          <Table.Tr>
            <Table.Th>Mois</Table.Th>
            <Table.Th>Revenus</Table.Th>
            <Table.Th>Dépenses</Table.Th>
            <Table.Th>Capacité d'épargne</Table.Th>
            <Table.Th>Actions</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>
          {budgetList.data.map((budget) => (
            <Table.Tr key={budget.id}>
              <Table.Td>
                <Text fw={500} size='sm'>
                  <Group gap='xs'>
                    <IconCalendar size={16} style={{ color: 'var(--mantine-color-blue-6)' }} />
                    {new Date(budget.date).toLocaleDateString('fr-FR', { month: 'long' })}{' '}
                    {budget.date.toString().split('-')[0]}
                  </Group>
                </Text>
              </Table.Td>
              <Table.Td>
                <Badge variant='light' color='teal'>
                  {budget.incomesAmount.toLocaleString('fr-FR')} €
                </Badge>
              </Table.Td>
              <Table.Td>
                <Badge variant='light' color='red'>
                  {budget.expensesAmount.toLocaleString('fr-FR')} €
                </Badge>
              </Table.Td>
              <Table.Td>
                <Text fw={700} c='blue'>
                  {budget.savingCapacity.toLocaleString('fr-FR')} €
                </Text>
              </Table.Td>
              <Table.Td>
                <Group gap='xs'>
                  <ActionIcon
                    component={Link}
                    to={`/budgets/${budget.id}`}
                    variant='light'
                    color='blue'
                    size='sm'
                  >
                    <IconEye style={{ width: rem(16) }} />
                  </ActionIcon>
                  <ActionIcon
                    variant='light'
                    color='gray'
                    size='sm'
                    onClick={() => onDuplicate(budget.id.toString())}
                  >
                    <IconCopy style={{ width: rem(16) }} />
                  </ActionIcon>
                  <ActionIcon
                    variant='light'
                    color='red'
                    size='sm'
                    onClick={() => onDelete(budget.id.toString())}
                  >
                    <IconTrash style={{ width: rem(16) }} />
                  </ActionIcon>
                </Group>
              </Table.Td>
            </Table.Tr>
          ))}
        </Table.Tbody>
      </Table>
    </Table.ScrollContainer>
  )
}

const BudgetIndex = () => {
  const [selectedYear, setSelectedYear] = useState(new Date().getFullYear())
  const [openedDelete, { open: openDelete, close: closeDelete }] = useDisclosure(false)
  const [openedDuplicate, { open: openDuplicate, close: closeDuplicate }] = useDisclosure(false)
  const [budgetIdToDelete, setBudgetIdToDelete] = useState<string | null>(null)
  const [budgetIdToDuplicate, setBudgetIdToDuplicate] = useState<string | null>(null)

  const { mutate: deleteBudget } = useMutationWithInvalidation(
    useDeleteApiBudgetsDelete().mutateAsync,
    {
      queryKeyToInvalidate: ['/api/budgets'],
      successMessage: 'Budget supprimé avec succès',
      errorMessage: 'Une erreur est survenue lors de la suppression du budget',
      onSuccess: closeDelete,
    },
  )

  const { mutate: duplicateBudget } = useMutationWithInvalidation(
    usePostApiBudgetsDuplicate().mutateAsync,
    {
      queryKeyToInvalidate: ['/api/budgets'],
      successMessage: 'Budget dupliqué avec succès',
      errorMessage: 'Une erreur est survenue lors de la duplication du budget',
      onSuccess: closeDuplicate,
    },
  )

  const years = Array.from({ length: 5 }, (_, i) => {
    const year = new Date().getFullYear() - 2 + i
    return year.toString()
  })

  const handleDelete = (id: string) => {
    setBudgetIdToDelete(id)
    openDelete()
  }

  const handleDuplicate = (id: string) => {
    setBudgetIdToDuplicate(id)
    openDuplicate()
  }

  const confirmDelete = () => {
    if (budgetIdToDelete) {
      deleteBudget({ id: parseInt(budgetIdToDelete) })
    }
  }

  const confirmDuplicate = () => {
    if (budgetIdToDuplicate) {
      duplicateBudget({ id: parseInt(budgetIdToDuplicate) })
    }
  }

  return (
    <Container size='xl' py='xl'>
      <Stack gap='xl'>
        <Group justify='space-between' align='flex-end'>
          <Stack gap={0}>
            <Title order={1} size='h2' fw={600} c='blue.7'>
              Gestion du budget
            </Title>
            <Text c='dimmed' size='sm'>
              Planifiez et suivez vos dépenses mensuelles
            </Text>
          </Stack>
          <Button
            component={Link}
            to='/budgets/create'
            leftSection={<IconPlus size={16} />}
            variant='light'
          >
            Nouveau budget
          </Button>
        </Group>

        <Group justify='center' mt='md'>
          <ActionIcon
            variant='light'
            color='blue'
            onClick={() => {
              const currentIndex = years.findIndex((y) => y === selectedYear.toString())
              if (currentIndex > 0) {
                setSelectedYear(parseInt(years[currentIndex - 1]))
              }
            }}
            disabled={selectedYear === parseInt(years[0])}
          >
            <IconArrowLeft style={{ width: rem(16) }} />
          </ActionIcon>
          <Text size='lg' fw={500} style={{ width: '4.5rem', textAlign: 'center' }}>
            {selectedYear}
          </Text>
          <ActionIcon
            variant='light'
            color='blue'
            onClick={() => {
              const currentIndex = years.findIndex((y) => y === selectedYear.toString())
              if (currentIndex < years.length - 1) {
                setSelectedYear(parseInt(years[currentIndex + 1]))
              }
            }}
            disabled={selectedYear === parseInt(years[years.length - 1])}
          >
            <IconArrowRight style={{ width: rem(16) }} />
          </ActionIcon>
        </Group>

        <Card radius='lg' py='xl' shadow='sm'>
          <Card.Section inheritPadding px='xl' pb='xs'>
            <Group justify='space-between' mt='md'>
              <Group gap='xs'>
                <IconWallet size={20} style={{ color: 'var(--mantine-color-blue-6)' }} />
                <Text fw={500} size='md'>
                  Budgets mensuels
                </Text>
              </Group>
            </Group>
          </Card.Section>
          <Card.Section inheritPadding px='xl' mt='sm' pb='lg'>
            <BudgetGrid
              selectedYear={selectedYear}
              onDelete={handleDelete}
              onDuplicate={handleDuplicate}
            />
          </Card.Section>
        </Card>

        <Modal
          opened={openedDelete}
          onClose={closeDelete}
          radius='lg'
          title='Supprimer le budget'
          centered
        >
          <Text size='sm'>Êtes-vous sûr de vouloir supprimer ce budget ?</Text>
          <Group justify='flex-end' mt='lg'>
            <Button variant='subtle' radius='md' onClick={closeDelete}>
              Annuler
            </Button>
            <Button color='red' radius='md' onClick={confirmDelete}>
              Supprimer
            </Button>
          </Group>
        </Modal>

        <Modal
          opened={openedDuplicate}
          onClose={closeDuplicate}
          radius='lg'
          title='Dupliquer le budget'
          centered
        >
          <Text size='sm'>Êtes-vous sûr de vouloir dupliquer ce budget ?</Text>
          <Group justify='flex-end' mt='lg'>
            <Button variant='subtle' radius='md' onClick={closeDuplicate}>
              Annuler
            </Button>
            <Button color='blue' radius='md' onClick={confirmDuplicate}>
              Dupliquer
            </Button>
          </Group>
        </Modal>
      </Stack>
    </Container>
  )
}

export default BudgetIndex

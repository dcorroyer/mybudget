import { CenteredLoader as Loader } from '@/components/CenteredLoader'
import {
  ActionIcon,
  Badge,
  Button,
  Card,
  Container,
  Grid,
  Group,
  Modal,
  Stack,
  Text,
  Title,
  rem,
} from '@mantine/core'
import { useDisclosure } from '@mantine/hooks'
import {
  IconArrowLeft,
  IconArrowRight,
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

  if (isLoading) return <Loader />

  if (!budgetList?.data || budgetList.data.length === 0) {
    return (
      <Container h={100} display='flex'>
        <Stack justify='center' align='center' style={{ flex: 1 }} gap='xs'>
          <IconDatabaseOff style={{ width: rem(24), height: rem(24) }} stroke={1.5} color='gray' />
          <Text size='lg' fw={500} c='gray'>
            Aucun budget trouvé
          </Text>
        </Stack>
      </Container>
    )
  }

  return (
    <Grid gutter='lg'>
      {budgetList.data.map((budget) => (
        <Grid.Col key={budget.id} span={{ base: 12, sm: 6, md: 4 }}>
          <Card radius='md' withBorder>
            <Stack gap='xs'>
              <Group justify='apart'>
                <Text fw={500}>
                  Budget {new Date(budget.date).toLocaleDateString('fr-FR', { month: 'long' })}{' '}
                  {budget.date.toString().split('-')[1]}-{budget.date.toString().split('-')[0]}
                </Text>
                <Group gap='xs' ml='auto'>
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
                <Group justify='apart'>
                  <Text size='sm' c='dimmed'>
                    Capacité d&apos;épargne
                  </Text>
                  <Text size='sm' c='blue' fw={500}>
                    {budget.savingCapacity.toLocaleString('fr-FR')} €
                  </Text>
                </Group>
                <Group justify='apart'>
                  <Text size='sm' c='dimmed'>
                    Revenus
                  </Text>
                  <Badge variant='light' color='teal'>
                    {budget.incomesAmount.toLocaleString('fr-FR')} €
                  </Badge>
                </Group>
                <Group justify='apart'>
                  <Text size='sm' c='dimmed'>
                    Dépenses
                  </Text>
                  <Badge variant='light' color='red'>
                    {budget.expensesAmount.toLocaleString('fr-FR')} €
                  </Badge>
                </Group>
              </Stack>
            </Stack>
          </Card>
        </Grid.Col>
      ))}
    </Grid>
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

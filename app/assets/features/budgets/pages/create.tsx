import React from 'react'
import { useNavigate } from 'react-router-dom'

import { ActionIcon, Text } from '@mantine/core'
import { IconChevronLeft } from '@tabler/icons-react'
import { Link } from 'react-router-dom'

import { BudgetForm } from '../components/budget-form'

import classes from './create.module.css'

const BudgetCreate: React.FC = () => {
  const navigate = useNavigate()

  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        <ActionIcon variant='transparent' c='black' component={Link} to='/budgets'>
          <IconChevronLeft className={classes.title} />
        </ActionIcon>
        New Budget
      </Text>
      <BudgetForm onClose={() => navigate('/budgets')} />
    </>
  )
}

export default BudgetCreate

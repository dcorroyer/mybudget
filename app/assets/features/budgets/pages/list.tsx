import React from 'react'
import { Link } from 'react-router-dom'

import { ActionIcon, rem, Text } from '@mantine/core'
import { IconEdit } from '@tabler/icons-react'

import { BudgetItems } from '../components/budget-items'

import classes from './list.module.css'

const BudgetList: React.FC = () => {
  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        Budget&apos;s List
        <ActionIcon
          variant='transparent'
          c='black'
          ml='sm'
          className={classes.linkItem}
          component={Link}
          to={'/budgets/create'}
        >
          <IconEdit className={classes.linkIcon} stroke={1.5} />
          <span style={{ padding: rem(2.5) }}>Create</span>
        </ActionIcon>
      </Text>

      <BudgetItems />
    </>
  )
}

export default BudgetList

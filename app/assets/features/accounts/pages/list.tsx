import { ActionIcon, Button, Container, Group, Modal, rem, Text } from '@mantine/core'
import { useDisclosure } from '@mantine/hooks'
import { IconPencil } from '@tabler/icons-react'
import React, { useState } from 'react'
import { Link } from 'react-router-dom'

import { AccountItems } from '../components/account-items'
import { useAccount } from '../hooks/useAccount'

import classes from './list.module.css'

const AccountList: React.FC = () => {
  const [opened, { open, close }] = useDisclosure(false)
  const [accountIdToDelete, setAccountIdToDelete] = useState<string | null>(null)
  const { deleteAccount } = useAccount()

  const handleDelete = () => {
    if (accountIdToDelete) {
      deleteAccount(accountIdToDelete)
      close()
    }
  }

  return (
    <>
      <Text fw={500} size='lg' pb='xl'>
        Accounts
        <ActionIcon
          variant='transparent'
          ml='sm'
          className={classes.linkItem}
          component={Link}
          to={'/accounts/create'}
        >
          <IconPencil className={classes.linkIcon} stroke={1.5} />
          <span style={{ padding: rem(2.5) }}>Create</span>
        </ActionIcon>
      </Text>
      <Container mt='md'>
        <AccountItems openDeleteModal={open} setAccountIdToDelete={setAccountIdToDelete} />
      </Container>

      <Modal opened={opened} onClose={close} radius='lg' title='Delete Account' centered>
        <Text size='sm'>Are you sure you want to delete this account?</Text>
        <Group justify='flex-end' mt='lg'>
          <Button variant='subtle' radius='md' onClick={close}>
            Cancel
          </Button>
          <Button color='red' radius='md' onClick={handleDelete}>
            Delete
          </Button>
        </Group>
      </Modal>
    </>
  )
}

export default AccountList

import { Button, Group, Modal, Text } from '@mantine/core'
import React from 'react'
import { AccountForm } from './account-form'
import { TransactionForm } from './transaction-form'

interface ModalContainerProps {
  modals: {
    transaction: {
      delete: {
        opened: boolean
        onClose: () => void
        onConfirm: () => void
      }
      edit: {
        opened: boolean
        onClose: () => void
        selectedTransaction: any
        onSuccess: () => void
      }
      create: {
        opened: boolean
        onClose: () => void
        onSuccess: () => void
      }
    }
    account: {
      delete: {
        opened: boolean
        onClose: () => void
        onConfirm: () => void
      }
      edit: {
        opened: boolean
        onClose: () => void
        selectedAccount: any
        onSuccess: () => void
      }
      create: {
        opened: boolean
        onClose: () => void
        onSuccess: () => void
      }
    }
  }
}

export const ModalContainer = ({ modals }: ModalContainerProps) => (
  <>
    <Modal
      opened={modals.transaction.delete.opened}
      onClose={modals.transaction.delete.onClose}
      radius='lg'
      title='Supprimer la transaction'
      centered
    >
      <Text size='sm'>Êtes-vous sûr de vouloir supprimer cette transaction ?</Text>
      <Group justify='flex-end' mt='lg'>
        <Button variant='subtle' radius='md' onClick={modals.transaction.delete.onClose}>
          Annuler
        </Button>
        <Button color='red' radius='md' onClick={modals.transaction.delete.onConfirm}>
          Supprimer
        </Button>
      </Group>
    </Modal>

    <Modal
      opened={modals.transaction.edit.opened}
      onClose={modals.transaction.edit.onClose}
      radius='lg'
      title='Modifier la transaction'
      size='lg'
      centered
    >
      <TransactionForm
        initialValues={modals.transaction.edit.selectedTransaction}
        onSuccess={modals.transaction.edit.onSuccess}
        onClose={modals.transaction.edit.onClose}
      />
    </Modal>

    <Modal
      opened={modals.transaction.create.opened}
      onClose={modals.transaction.create.onClose}
      radius='lg'
      title='Nouvelle transaction'
      size='lg'
      centered
    >
      <TransactionForm
        onSuccess={modals.transaction.create.onSuccess}
        onClose={modals.transaction.create.onClose}
      />
    </Modal>

    <Modal
      opened={modals.account.create.opened}
      onClose={modals.account.create.onClose}
      radius='lg'
      title='Nouveau compte'
      size='lg'
      centered
    >
      <AccountForm onSuccess={modals.account.create.onSuccess} />
    </Modal>

    <Modal
      opened={modals.account.edit.opened}
      onClose={modals.account.edit.onClose}
      radius='lg'
      title='Modifier le compte'
      size='lg'
      centered
    >
      <AccountForm
        initialValues={{
          id: modals.account.edit.selectedAccount?.id,
          name: modals.account.edit.selectedAccount?.name,
        }}
        onSuccess={modals.account.edit.onSuccess}
      />
    </Modal>

    <Modal
      opened={modals.account.delete.opened}
      onClose={modals.account.delete.onClose}
      radius='lg'
      title='Supprimer le compte'
      centered
    >
      <Text size='sm'>Êtes-vous sûr de vouloir supprimer ce compte ?</Text>
      <Group justify='flex-end' mt='lg'>
        <Button variant='subtle' radius='md' onClick={modals.account.delete.onClose}>
          Annuler
        </Button>
        <Button color='red' radius='md' onClick={modals.account.delete.onConfirm}>
          Supprimer
        </Button>
      </Group>
    </Modal>
  </>
)

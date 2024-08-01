import React from 'react'

import { AppShell, Burger, Group } from '@mantine/core'
import { useDisclosure } from '@mantine/hooks'
import { MantineLogo } from '@mantinex/mantine-logo'

import { Router } from '@/router'

import { Sidebar } from '@/components/shell/sidebar'

export const AuthenticatedLayout = () => {
  const [opened, { toggle }] = useDisclosure()

  return (
    <>
      <AppShell
        header={{ height: 60 }}
        navbar={{
          width: 300,
          breakpoint: 'sm',
          collapsed: { mobile: !opened },
        }}
        padding='md'
      >
        <AppShell.Header>
          <Group h='100%' px='md'>
            <Burger opened={opened} onClick={toggle} hiddenFrom='sm' size='sm' />
            <MantineLogo size={28} inverted style={{ color: 'white' }} />
          </Group>
        </AppShell.Header>
        <AppShell.Navbar>
          <Sidebar />
        </AppShell.Navbar>
        <AppShell.Main>
          <Router />
        </AppShell.Main>
      </AppShell>
    </>
  )
}

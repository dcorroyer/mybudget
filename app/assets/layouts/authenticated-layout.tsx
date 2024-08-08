import React from 'react'

import { AppShell, Burger, Group } from '@mantine/core'
import { useDisclosure, useMediaQuery } from '@mantine/hooks'
import { IconCoinEuro } from '@tabler/icons-react'

import { Outlet } from '@tanstack/react-router'

import { Sidebar } from '@/components/ui/sidebar'

export default function AuthenticatedLayout() {
  const [opened, { toggle }] = useDisclosure()

  const isMobile = useMediaQuery('(max-width: 768px)')
  const height = isMobile ? 60 : 0

  return (
    <AppShell
      header={{ height: height }}
      navbar={{
        width: 80,
        breakpoint: 'sm',
        collapsed: { mobile: !opened },
      }}
      padding='md'
    >
      <AppShell.Header withBorder={false}>
        <Group h='100%' px='md'>
          <Burger opened={opened} onClick={toggle} hiddenFrom='sm' size='sm' />
          {isMobile && <IconCoinEuro />}
        </Group>
      </AppShell.Header>
      <AppShell.Navbar withBorder={false}>
        <Sidebar />
      </AppShell.Navbar>
      <AppShell.Main>
        <Outlet />
      </AppShell.Main>
    </AppShell>
  )
}

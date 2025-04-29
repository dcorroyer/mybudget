import React, { PropsWithChildren } from 'react'

import { AppShell, Burger, em, Group } from '@mantine/core'
import { useDisclosure, useMediaQuery } from '@mantine/hooks'

import { Logo } from '@/components/Logo'
import { Sidebar } from '@/components/Sidebar'

export default function AuthenticatedLayout({ children }: PropsWithChildren) {
  const [opened, { toggle }] = useDisclosure()

  const isMobile = useMediaQuery(`(max-width: ${em(750)})`)
  const height = isMobile ? 60 : 0

  return (
    <AppShell
      header={{ height: height }}
      navbar={{
        width: 250,
        breakpoint: 'sm',
        collapsed: { mobile: !opened },
      }}
      padding='md'
    >
      <AppShell.Header withBorder={false}>
        <Group h='100%' px='md'>
          <Burger opened={opened} onClick={toggle} hiddenFrom='sm' size='sm' />
          {isMobile && <Logo />}
        </Group>
      </AppShell.Header>
      <AppShell.Navbar withBorder={false}>
        <Sidebar onNavigate={toggle} />
      </AppShell.Navbar>
      <AppShell.Main>{children}</AppShell.Main>
    </AppShell>
  )
}

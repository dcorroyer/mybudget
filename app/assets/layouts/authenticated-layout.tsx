import React from 'react'
import { BrowserRouter, Route, Routes } from 'react-router-dom'

import { AppShell, Burger, Group } from '@mantine/core'
import { useDisclosure, useMediaQuery } from '@mantine/hooks'
import { IconCoinEuro } from '@tabler/icons-react'

import { Sidebar } from '@/components/shell/sidebar'

import BudgetList from '@/pages/budgets'
import Home from '@/pages/home'

export const AuthenticatedLayout = () => {
  const [opened, { toggle }] = useDisclosure()

  const isMobile = useMediaQuery('(max-width: 768px)')
  const height = isMobile ? 60 : 0

  return (
    <BrowserRouter>
      <AppShell
        header={{ height: height }}
        navbar={{
          width: 80,
          breakpoint: 'sm',
          collapsed: { mobile: !opened },
        }}
        padding='md'
      >
        <AppShell.Header>
          <Group h='100%' px='md'>
            <Burger opened={opened} onClick={toggle} hiddenFrom='sm' size='sm' />
            {isMobile && <IconCoinEuro />}
          </Group>
        </AppShell.Header>
        <AppShell.Navbar>
          <Sidebar />
        </AppShell.Navbar>
        <AppShell.Main>
          <Routes>
            <Route path='/' element={<Home />} />
            <Route path='/budgets' element={<BudgetList />} />
          </Routes>
        </AppShell.Main>
      </AppShell>
    </BrowserRouter>
  )
}

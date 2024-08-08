import { Link } from '@tanstack/react-router'
import React, { useState } from 'react'

import { rem, Stack, Tooltip, UnstyledButton } from '@mantine/core'
import { useMediaQuery } from '@mantine/hooks'
import { IconCoinEuro, IconGauge, IconHome2, IconLogout } from '@tabler/icons-react'

import { LogoutToggle } from '@/components/ui/logout-toggle'
import { ThemeToggle } from '@/components/ui/theme-toggle'

import { useAuth } from '@/hooks/useAuth'

import classes from './sidebar.module.css'

interface NavbarLinkProps {
  icon: typeof IconHome2
  label: string
  active?: boolean
  onClick?(): void
  to: string
}

function NavbarLink({ icon: Icon, label, to, active, onClick }: NavbarLinkProps) {
  return (
    <Tooltip label={label} position='right' transitionProps={{ duration: 0 }}>
      <UnstyledButton
        onClick={onClick}
        component={Link}
        to={to}
        className={classes.link}
        data-active={active || undefined}
      >
        <Icon style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
      </UnstyledButton>
    </Tooltip>
  )
}

const mockdata = [
  { icon: IconHome2, label: 'Home', path: '/' },
  { icon: IconGauge, label: 'Budget', path: '/budgets' },
]

export function Sidebar() {
  const [active, setActive] = useState(2)
  const { logout } = useAuth()

  const isMobile = useMediaQuery('(max-width: 768px)')

  const links = mockdata.map((link, index) => (
    <NavbarLink
      {...link}
      key={link.label}
      active={index === active}
      onClick={() => setActive(index)}
      to={link.path}
    />
  ))

  return (
    <nav className={classes.navbar}>
      {!isMobile && <IconCoinEuro stroke={1.5} style={{ margin: '0 auto' }} />}
      <div className={classes.navbarMain}>
        <Stack justify='center' gap={0}>
          {links}
        </Stack>
      </div>

      <Stack justify='center' gap={0}>
        <ThemeToggle />
        <LogoutToggle icon={IconLogout} onClick={() => logout()} />
      </Stack>
    </nav>
  )
}

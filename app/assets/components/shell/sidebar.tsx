import React, { useState } from 'react'

import { rem, Stack, Tooltip, UnstyledButton } from '@mantine/core'
import { useMediaQuery } from '@mantine/hooks'
import { IconCoinEuro, IconGauge, IconHome2, IconLogout } from '@tabler/icons-react'

import { ThemeToggle } from '@/components/shell/theme-toggle'
import { useAuth } from '@/hooks/useAuth'

import classes from './sidebar.module.css'

interface NavbarLinkProps {
  icon: typeof IconHome2
  label: string
  active?: boolean
  onClick?(): void
}

function NavbarLink({ icon: Icon, label, active, onClick }: NavbarLinkProps) {
  return (
    <Tooltip label={label} position='right' transitionProps={{ duration: 0 }}>
      <UnstyledButton onClick={onClick} className={classes.link} data-active={active || undefined}>
        <Icon style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
      </UnstyledButton>
    </Tooltip>
  )
}

const mockdata = [
  { icon: IconHome2, label: 'Home' },
  { icon: IconGauge, label: 'Budget' },
]

export function Sidebar() {
  const [active, setActive] = useState(2)
  const { logout } = useAuth()

  const isMobile = useMediaQuery(`(max-width: 768px)`)

  const links = mockdata.map((link, index) => (
    <NavbarLink
      {...link}
      key={link.label}
      active={index === active}
      onClick={() => setActive(index)}
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
        <NavbarLink icon={IconLogout} label='Logout' onClick={() => logout()} />
      </Stack>
    </nav>
  )
}

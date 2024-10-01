import React, { useState } from 'react'

import { Link, useLocation } from 'react-router-dom'

import cx from 'clsx'

import { Group, useComputedColorScheme, useMantineColorScheme } from '@mantine/core'
import { useMediaQuery } from '@mantine/hooks'
import { IconHome2, IconLogout, IconMoon, IconSun, IconWallet } from '@tabler/icons-react'

import { useAuth } from '@/features/auth/hooks/useAuth'

import Logo from './logo'
import classes from './sidebar.module.css'

const data = [
  { icon: IconHome2, label: 'Dashboard', path: '/' },
  { icon: IconWallet, label: 'Budget Planner', path: '/budgets' },
]

export function Sidebar() {
  const { logout } = useAuth()

  const isMobile = useMediaQuery('(max-width: 768px)')

  const { setColorScheme } = useMantineColorScheme()
  const computedColorScheme = useComputedColorScheme('light', { getInitialValueInEffect: true })

  const { pathname } = useLocation()
  const [active, setActive] = useState(pathname)

  const links = data.map((item) => {
    const isActive = item.path === pathname

    return (
      <Link
        className={classes.link}
        data-active={isActive ? active : undefined}
        to={item.path}
        key={item.label}
        onClick={() => {
          setActive(item.path)
        }}
      >
        <item.icon className={classes.linkIcon} stroke={1.5} />
        <span>{item.label}</span>
      </Link>
    )
  })

  return (
    <nav className={classes.navbar}>
      <div className={classes.navbarMain}>
        {!isMobile && (
          <Group className={classes.header} justify='space-between'>
            <Logo />
          </Group>
        )}
        {links}
      </div>

      <div className={classes.footer}>
        <Link
          onClick={() => setColorScheme(computedColorScheme === 'light' ? 'dark' : 'light')}
          className={classes.link}
          to={''}
        >
          <IconSun className={cx(classes.light, classes.linkIcon)} stroke={1.5} />
          <IconMoon className={cx(classes.dark, classes.linkIcon)} stroke={1.5} />
          <span>Change Theme</span>
        </Link>

        <Link onClick={() => logout()} className={classes.link} to={''}>
          <IconLogout className={classes.linkIcon} stroke={1.5} />
          <span>Logout</span>
        </Link>
      </div>
    </nav>
  )
}

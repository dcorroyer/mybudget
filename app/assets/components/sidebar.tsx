import React, { useState } from 'react'

import { Link, useLocation, useNavigate } from 'react-router-dom'

import { em, Group } from '@mantine/core'
import { useMediaQuery } from '@mantine/hooks'
import { notifications } from '@mantine/notifications'
import { IconChartLine, IconLogout, IconWallet } from '@tabler/icons-react'

import Logo from './logo'
import classes from './sidebar.module.css'

const data = [
  { icon: IconChartLine, label: 'Épargne', path: '/' },
  { icon: IconWallet, label: 'Budget', path: '/budgets' },
]

interface SidebarProps {
  onNavigate?: () => void
}

export function Sidebar({ onNavigate }: SidebarProps) {
  const navigate = useNavigate()
  const isMobile = useMediaQuery(`(max-width: ${em(750)})`)

  const { pathname } = useLocation()
  const [active, setActive] = useState(pathname)

  const handleLogout = () => {
    localStorage.removeItem('token')

    notifications.show({
      title: 'Déconnexion réussie',
      message: 'Vous avez été déconnecté avec succès',
      color: 'blue',
    })

    navigate('/auth/login')
  }

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
          if (isMobile && onNavigate) {
            onNavigate()
          }
        }}
      >
        <item.icon className={classes.linkIcon} stroke={1.5} />
        <span>{item.label}</span>
      </Link>
    )
  })

  return (
    <nav className={classes.navbar}>
      <div className={classes.navbarContent}>
        <div>
          {!isMobile && (
            <Group className={classes.header} justify='space-between'>
              <Logo />
            </Group>
          )}
          {links}
        </div>

        <div className={classes.footer}>
          <Link onClick={handleLogout} className={classes.link} to={''}>
            <IconLogout className={classes.linkIcon} stroke={1.5} />
            <span>Déconnexion</span>
          </Link>
        </div>
      </div>
    </nav>
  )
}

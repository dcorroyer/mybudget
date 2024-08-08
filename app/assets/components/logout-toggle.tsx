import React from 'react'

import { rem, Tooltip, UnstyledButton } from '@mantine/core'
import { IconLogout } from '@tabler/icons-react'

import classes from './logout-toggle.module.css'

interface LogoutProps {
  icon: typeof IconLogout
  active?: boolean
  onClick?(): void
}

export function LogoutToggle({ icon: Icon, onClick, active }: LogoutProps) {
  return (
    <Tooltip label='Logout' position='right' transitionProps={{ duration: 0 }}>
      <UnstyledButton onClick={onClick} className={classes.link} data-active={active || undefined}>
        <Icon style={{ width: rem(20), height: rem(20) }} stroke={1.5} />
      </UnstyledButton>
    </Tooltip>
  )
}

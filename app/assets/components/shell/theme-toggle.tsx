import React from 'react'

import {
  Tooltip,
  UnstyledButton,
  useComputedColorScheme,
  useMantineColorScheme,
} from '@mantine/core'
import { IconMoon, IconSun } from '@tabler/icons-react'

import cx from 'clsx'

import classes from './theme-toggle.module.css'

export function ThemeToggle() {
  const { setColorScheme } = useMantineColorScheme()
  const computedColorScheme = useComputedColorScheme('light', { getInitialValueInEffect: true })

  return (
    <Tooltip label='Toggle color scheme' position='right' transitionProps={{ duration: 0 }}>
      <UnstyledButton
        onClick={() => setColorScheme(computedColorScheme === 'light' ? 'dark' : 'light')}
        className={classes.link}
      >
        <IconSun className={cx(classes.icon, classes.light)} stroke={1.5} />
        <IconMoon className={cx(classes.icon, classes.dark)} stroke={1.5} />
      </UnstyledButton>
    </Tooltip>
  )
}

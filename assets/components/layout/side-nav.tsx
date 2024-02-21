import React, { useEffect, useState } from 'react'
import { useSidebar } from '@/hooks/useSidebar'

import { NavItem } from '@/types'
import { Link } from 'react-router-dom'
import { cn } from '@/lib/utils'
import { buttonVariants } from '../ui/button'

interface SideNavProps {
    items: NavItem[]
    setOpen?: (open: boolean) => void
    className?: string
}

export function SideNav({ items, setOpen, className }: SideNavProps) {
    const { isOpen } = useSidebar()
    const [openItem, setOpenItem] = useState('')
    const [lastOpenItem, setLastOpenItem] = useState('')

    useEffect(() => {
        if (isOpen) {
            setOpenItem(lastOpenItem)
        } else {
            setLastOpenItem(openItem)
            setOpenItem('')
        }
    }, [isOpen])

    return (
        <nav className='space-y-2'>
            {items.map((item) => (
                <Link
                    key={item.title}
                    to={item.href}
                    className={cn(
                        buttonVariants({ variant: 'ghost' }),
                        'group relative flex h-12 justify-between px-4 py-2 text-base duration-200 hover:bg-muted hover:no-underline',
                    )}
                >
                    <div>
                        <item.icon className={cn('h-5 w-5', item.color)} />
                    </div>
                    <div
                        className={cn(
                            'absolute left-12 text-base duration-200 ',
                            !isOpen && className,
                        )}
                    >
                        {item.title}
                    </div>
                </Link>
            ))}
        </nav>
    )
}

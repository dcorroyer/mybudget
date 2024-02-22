import { Home } from 'lucide-react'
import { type NavItem } from '@/types'

export const NavItems: NavItem[] = [
    {
        title: 'Dashboard',
        icon: Home,
        href: '/',
        color: 'text-sky-500',
    },
]

/* {
    title: 'TitleWithChildren',
    icon: BookOpenCheck,
    href: '/children',
    color: 'text-orange-500',
    isChidren: true,
    children: [
        {
            title: 'Example-01',
            icon: BookOpenCheck,
            color: 'text-red-500',
            href: '/',
        },
    ],
}, */

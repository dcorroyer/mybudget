import { BookOpenCheck, LayoutDashboard } from 'lucide-react'
import { type NavItem } from '@/types'

export const NavItems: NavItem[] = [
    {
        title: 'Accueil',
        icon: LayoutDashboard,
        href: '/',
        color: 'text-sky-500',
    },
    {
        title: 'Dasboard',
        icon: BookOpenCheck,
        href: '/dashboard',
        color: 'text-orange-500',
        isChidren: true,
        children: [
            {
                title: 'Example-01',
                icon: BookOpenCheck,
                color: 'text-red-500',
                href: '/',
            },
            {
                title: 'Example-02',
                icon: BookOpenCheck,
                color: 'text-red-500',
                href: '/',
            },
            {
                title: 'Example-03',
                icon: BookOpenCheck,
                color: 'text-red-500',
                href: '/',
            },
        ],
    },
    {
        title: 'Dasboard2',
        icon: BookOpenCheck,
        href: '/dashboard',
        color: 'text-orange-500',
        isChidren: true,
        children: [
            {
                title: 'Example-01',
                icon: BookOpenCheck,
                color: 'text-red-500',
                href: '/',
            },
            {
                title: 'Example-02',
                icon: BookOpenCheck,
                color: 'text-red-500',
                href: '/',
            },
            {
                title: 'Example-03',
                icon: BookOpenCheck,
                color: 'text-red-500',
                href: '/',
            },
        ],
    },
]

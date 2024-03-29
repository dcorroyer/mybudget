import React from 'react'
import { Link, useNavigate } from 'react-router-dom'

import ThemeToggle from '@/components/ui/theme-toggle'
import { Button } from '@/components/ui/button'
import { toast } from '@/components/hooks/useToast'
import { MobileSidebar } from '@/components/layout/MobileSidebar'

import { Bitcoin } from 'lucide-react'

import { useAuth } from '@/hooks/AuthProvider'
import { cn } from '@/lib/utils'

export default function Header(): React.JSX.Element {
    const { token, clearToken } = useAuth()
    const navigate = useNavigate()

    const handleLogout = () => {
        clearToken()
        navigate('/login', { replace: true })
        toast({
            title: 'Logged out',
            description: 'You have successfully logged out !',
            variant: 'default',
        })
    }

    return (
        <div className='supports-backdrop-blur:bg-background/60 fixed left-0 right-0 top-0 z-20 border-b bg-background/95 backdrop-blur'>
            <nav className='flex h-16 items-center justify-between px-4'>
                <Link to={'/'} className='hidden items-center justify-between gap-2 md:flex'>
                    <Bitcoin className='h-6 w-6' />
                    <h1 className='text-lg font-semibold'>MyBudget</h1>
                </Link>

                <div className={cn('block md:!hidden')}>
                    <MobileSidebar />
                </div>
                <div className='flex items-center gap-2'>
                    <ThemeToggle />
                    {token ? (
                        <Button size='sm' onClick={handleLogout}>
                            Logout
                        </Button>
                    ) : (
                        <Button
                            size='sm'
                            onClick={() => {
                                navigate('/login')
                            }}
                        >
                            Sign In
                        </Button>
                    )}
                </div>
            </nav>
        </div>
    )
}

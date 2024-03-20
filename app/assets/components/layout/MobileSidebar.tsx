import React, { useState, useEffect } from 'react'
import { MenuIcon } from 'lucide-react'
import { Sheet, SheetContent, SheetTrigger } from '@/components/ui/sheet'
import { SideNav } from '@/components/layout/SideNav'
import { NavItems } from '@/components/constants/SideNav'

export const MobileSidebar = (): React.JSX.Element | null => {
    const [open, setOpen] = useState(false)
    const [isMounted, setIsMounted] = useState(false)

    useEffect(() => {
        setIsMounted(true)
    }, [])

    if (!isMounted) {
        return null
    }

    return (
        <>
            <Sheet open={open} onOpenChange={setOpen}>
                <SheetTrigger asChild>
                    <div className='flex items-center justify-center gap-2'>
                        <MenuIcon />
                        <h1 className='text-lg font-semibold'>MyBudget</h1>
                    </div>
                </SheetTrigger>
                <SheetContent side='left' className='w-72'>
                    <div className='px-1 py-6 pt-16'>
                        <SideNav items={NavItems} setOpen={setOpen} />
                    </div>
                </SheetContent>
            </Sheet>
        </>
    )
}

import React from 'react'

import { useToast } from '@/components/hooks/UseToast'
import { Button } from '@/components/ui/button'

import { useAuth } from '@/hooks/AuthProvider'

function DashboardPage(): React.JSX.Element {
    const { getToken } = useAuth()
    const { toast } = useToast()

    async function isConnected(): Promise<void> {
        const token = getToken()

        try {
            const response = await fetch('api/users/me', {
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
            })

            if (!response.ok) {
                throw new Error('You are not connected')
            }

            toast({
                title: 'Connected',
                description: 'You are connected !',
                variant: 'default',
            })
        } catch (error) {
            console.log('Error logging in:', error)
        }
    }

    return (
        <>
            <div className='flex flex-col items-center py-12 sm:px-6 lg:px-8'>
                <Button className='mx-auto mt-2' variant='ghost' onClick={isConnected}>
                    Verify if you are connected
                </Button>
            </div>
        </>
    )
}

export default DashboardPage

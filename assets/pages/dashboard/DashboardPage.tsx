import React from 'react'
import { Link, useNavigate } from 'react-router-dom'

import { useToast } from '@/components/ui/toasts/use-toast'
import { Button } from '@/components/ui/button'
import { useAuth } from '@/hooks/AuthProvider'

function DashboardPage(): React.JSX.Element {
    const navigate = useNavigate()
    const { clearToken, getToken } = useAuth()
    const { toast } = useToast()

    const handleLogout = () => {
        clearToken()
        navigate('/', { replace: true })
        toast({
            title: 'Logged out',
            description: 'You have successfully logged out !',
            variant: 'default',
        })
    }

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
                <Button className='mx-auto mt-2' variant='ghost' onClick={handleLogout}>
                    Logout
                </Button>

                <Button className='mx-auto mt-2' variant='ghost' onClick={isConnected}>
                    Verify if you are connected
                </Button>

                <Link to='/'>
                    <Button className='mx-auto mt-2' variant='ghost'>
                        Accueil
                    </Button>
                </Link>
            </div>
        </>
    )
}

export default DashboardPage

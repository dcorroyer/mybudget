import React, { useEffect } from 'react'
import { useNavigate } from 'react-router-dom'

import { useToast } from '@/components/ui/toasts/use-toast'
import { Button } from '@/components/ui/button'

function DashboardPage(): React.JSX.Element {
    const navigate = useNavigate()
    const { toast } = useToast()

    useEffect(() => {
        isLoggedIn().then((loggedIn) => {
            if (!loggedIn) {
                toast({
                    title: 'You are not logged in !',
                    variant: 'destructive',
                })
            }
        })
    }, [])

    async function isLoggedIn() {
        const tokenFromCookie = document.cookie.split('; ').find((row) => row.startsWith('token='))
        const token = tokenFromCookie ? JSON.parse(tokenFromCookie.split('=')[1]).token : null

        if (!token) {
            navigate('/login')
            return false
        }

        try {
            const response = await fetch('api/users/me', {
                method: 'GET',
                headers: {
                    Authorization: `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
            })

            if (!response.ok) {
                throw new Error('You are not logged in')
            }

            console.log(await response.text())
            return true
        } catch (error) {
            console.log('Error logging in:', error)
            navigate('/login')
            toast({
                title: 'Something went wrong ...',
                variant: 'destructive',
            })
        }
    }

    const handleLogout = () => {
        logout().then(() => {
            navigate('/')
            toast({
                title: 'Logged out',
                description: 'You have successfully logged out !',
                variant: 'default',
            })
        })
    }

    async function logout() {
        document.cookie =
            'token=; Secure; SameSite=None; Path=/; Expires=Thu, 01 Jan 1970 00:00:00 GMT'
    }

    return (
        <>
            <div className='flex flex-col items-center py-12 sm:px-6 lg:px-8'>
                <Button className='mx-auto mt-2' variant='ghost' onClick={handleLogout}>
                    Logout
                </Button>
            </div>
        </>
    )
}

export default DashboardPage

import React from 'react'
import { useNavigate } from 'react-router-dom'

import { useToast } from '@/components/ui/toasts/use-toast'
import { Button } from '@/components/ui/button'
import { useAuth } from '@/hooks/AuthProvider';

function DashboardPage(): React.JSX.Element {
    const navigate = useNavigate()
    const { clearToken } = useAuth();
    const { toast } = useToast()

    const handleLogout = () => {
        clearToken();
        navigate('/', { replace: true });
        toast({
            title: 'Logged out',
            description: 'You have successfully logged out !',
            variant: 'default',
        })
    };

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

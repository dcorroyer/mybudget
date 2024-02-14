import React from 'react'
import { Button } from '@/components/ui/button'
import { useNavigate } from 'react-router-dom'
import ModeToggle from '@/components/ui/mode-toggle'

function HomePage(): React.JSX.Element {
    const navigate = useNavigate()

    return (
        <div className='flex flex-col items-center py-12 sm:px-6 lg:px-8'>
            <Button className='mx-auto mt-2' variant='ghost' onClick={() => navigate('/register')}>
                Register
            </Button>
            <Button className='mx-auto mt-2' variant='ghost' onClick={() => navigate('/login')}>
                Login
            </Button>
            <div className='mt-2'>
                <ModeToggle />
            </div>

        </div>
    )
}

export default HomePage

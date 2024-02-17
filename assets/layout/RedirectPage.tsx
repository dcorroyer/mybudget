import { useNavigate } from 'react-router-dom'

import { useAuth } from '@/hooks/AuthProvider'
import { useEffect } from 'react'

const RedirectPage = () => {
    const { token } = useAuth()
    const navigate = useNavigate()

    useEffect(() => {
        token ? navigate('/dashboard') : navigate('/')
    }, [])

    return null
}

export default RedirectPage

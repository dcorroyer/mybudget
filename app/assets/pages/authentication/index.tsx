import React, { useState } from 'react'

import { Login } from '@/components/auth/login'
import { Register } from '@/components/auth/register'

export const Auth = () => {
  const [mode, setMode] = useState<'register' | 'login'>('login')

  return (
    <div>
      {mode === 'login' && (
        <>
          <Login setMode={setMode} />
        </>
      )}
      {mode === 'register' && (
        <>
          <Register setMode={setMode} />
        </>
      )}
    </div>
  )
}

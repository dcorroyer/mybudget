import { AppShell, Button, Group, Menu, Modal, Select, Text } from '@mantine/core'
import '@mantine/core/styles.css'
import { useEffect, useState } from 'react'
import { useTranslation } from 'react-i18next'
import { LoginForm } from './components/auth/LoginForm'
import { RegisterForm } from './components/auth/RegisterForm'
import { RegisterInput } from './components/auth/schemas'
import { LANGUAGES } from './constants/languages'
import { AuthProvider, useAuth } from './contexts/AuthContext'
import { useApiArticlesGetCollection } from './lib/api/article/article'

function AppContent() {
  const [count, setCount] = useState<number>(0)
  const { data } = useApiArticlesGetCollection()
  const { t, i18n } = useTranslation()
  const { user, isAuthenticated, register, logout } = useAuth()
  const [loginModalOpened, setLoginModalOpened] = useState(false)
  const [registerModalOpened, setRegisterModalOpened] = useState(false)

  useEffect(() => {
    const savedLang = localStorage.getItem('language') || 'en'
    i18n.changeLanguage(savedLang)
  }, [i18n])

  const handleLanguageChange = (value: string | null) => {
    if (value) {
      i18n.changeLanguage(value)
      localStorage.setItem('language', value)
    }
  }

  const handleRegisterSubmit = async (values: RegisterInput) => {
    await register(values)
    setRegisterModalOpened(false)
  }

  console.log('user', i18n.language)

  return (
    <AppShell header={{ height: 60 }} padding='md'>
      <AppShell.Header>
        <Group h='100%' px='md' justify='space-between'>
          <Text size='xl' fw={700}>
            MyFitness
          </Text>

          <Group>
            <Select value={i18n.language} onChange={handleLanguageChange} data={LANGUAGES} w={85} />

            {isAuthenticated ? (
              <Menu shadow='md' width={200}>
                <Menu.Target>
                  <Button variant='subtle'>{user?.username}</Button>
                </Menu.Target>
                <Menu.Dropdown>
                  <Menu.Item>Profile</Menu.Item>
                  <Menu.Item>Settings</Menu.Item>
                  <Menu.Divider />
                  <Menu.Item color='red' onClick={logout}>
                    Logout
                  </Menu.Item>
                </Menu.Dropdown>
              </Menu>
            ) : (
              <Group>
                <Button variant='subtle' onClick={() => setLoginModalOpened(true)}>
                  Login
                </Button>
                <Button onClick={() => setRegisterModalOpened(true)}>Register</Button>
              </Group>
            )}
          </Group>
        </Group>
      </AppShell.Header>

      <Modal opened={loginModalOpened} onClose={() => setLoginModalOpened(false)} title='Login'>
        <LoginForm />
      </Modal>

      <Modal
        opened={registerModalOpened}
        onClose={() => setRegisterModalOpened(false)}
        title='Register'
      >
        <RegisterForm onSubmit={handleRegisterSubmit} />
      </Modal>

      <AppShell.Main>
        <h1>{t('welcome')}</h1>
        <button onClick={() => setCount(count + 1)}>{t('clickMe')}</button>
        <p>{t('count', { count })}</p>
        <pre>{JSON.stringify(data, null, 2)}</pre>
      </AppShell.Main>
    </AppShell>
  )
}

function App() {
  return (
    <AuthProvider>
      <AppContent />
    </AuthProvider>
  )
}

export default App

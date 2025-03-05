import axios, { AxiosError, AxiosRequestConfig } from 'axios'

const getBaseUrl = () => {
  const hostname = window.location.hostname

  if (hostname === 'mybudget.dcorroyer.fr') {
    return 'https://mybudget.dcorroyer.fr'
  }

  return 'https://mybudget.web.localhost'
}

const axiosInstance = axios.create({
  baseURL: getBaseUrl(),
  headers: {
    'Content-Type': 'application/json',
  },
})

axiosInstance.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  },
)

// Intercepteur pour gérer les réponses et les erreurs
axiosInstance.interceptors.response.use(
  (response) => {
    return response
  },
  (error: AxiosError) => {
    const isLoginPage = window.location.pathname.includes('/auth/login')
    const isRegisterPage = window.location.pathname.includes('/auth/register')
    const isAuthPage = isLoginPage || isRegisterPage

    if (error.response?.status === 401 && !isAuthPage) {
      localStorage.removeItem('token')
      window.location.href = '/auth/login'
    }

    return Promise.reject(error)
  },
)

export const customInstance = <T>(config: AxiosRequestConfig): Promise<T> => {
  const source = axios.CancelToken.source()
  const promise = axiosInstance({
    ...config,
    cancelToken: source.token,
  }).then(({ data }) => data)

  // @ts-ignore
  promise.cancel = () => {
    source.cancel('Query was cancelled')
  }

  return promise
}

export default axiosInstance

import Axios, { AxiosError, AxiosRequestConfig } from 'axios'

export const AXIOS_INSTANCE = Axios.create({ baseURL: 'https://myfitness.api.localhost' }) // use your own URL here or environment variable

// add a second `options` argument here if you want to pass extra options to each generated query

export const customInstance = <T>(
  config: AxiosRequestConfig,

  options?: AxiosRequestConfig,
): Promise<T> => {
  const source = Axios.CancelToken.source()

  const headers = config.headers || {}

  const token = localStorage.getItem('token')
  if (token) {
    headers.Authorization = `Bearer ${token}`
  }

  const promise = AXIOS_INSTANCE({
    ...config,
    ...options,
    headers,
    cancelToken: source.token,
  }).then(({ data }) => data)

  promise.cancel = () => {
    source.cancel('Query was cancelled')
  }

  return promise
}

// In some case with react-query and swr you want to be able to override the return error type so you can also do it here like this

export type ErrorType<Error> = AxiosError<Error>

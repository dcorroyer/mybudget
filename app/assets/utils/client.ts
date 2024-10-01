export async function client(url: string, options: RequestInit) {
  const token = localStorage.getItem('token')

  const clientOptions: RequestInit = {
    headers: {
      'Content-Type': 'application/json',
      ...options.headers,
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
    },
    ...options,
  }

  return await fetch(url, clientOptions)
}

export function saveUser(data: { token: string }): void {
  localStorage.setItem('token', data.token)
}

export function removeUser(): void {
  localStorage.removeItem('token')
}

export function getUser(): string | null {
  return localStorage.getItem('token')
}

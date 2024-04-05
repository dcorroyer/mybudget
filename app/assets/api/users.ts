export async function getMe(): Promise<Response> {
    return await fetch('/api/users/me', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${JSON.parse(localStorage.getItem('token')).token}`,
        },
    })
}

/*import { navigateTo } from '#app'

export const useAuth = () => {
  const user = useState('user', () => null)
  const authLoaded = useState('authLoaded', () => false)

  const getToken = () => {
    if (import.meta.server) return null
    return localStorage.getItem('token')
  }

  const fetchUser = async () => {
    const token = getToken()

    if (!token) {
      user.value = null
      authLoaded.value = true
      return
    }

    try {
      user.value = await $fetch('/api/me', {
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${token}`
        }
      })
    } catch {
      // token invalid / expired
      localStorage.removeItem('token')
      user.value = null
    } finally {
      authLoaded.value = true
    }
  }

  const login = async (email, password) => {
    const res = await $fetch('/api/login', {
      method: 'POST',
      headers: { Accept: 'application/json' },
      body: { email, password }
    })

    // 🔑 STORE TOKEN (REQUIRED)
    localStorage.setItem('token', res.token)

    authLoaded.value = false
    await fetchUser()

    await navigateTo('/dashboard')
  }

  const logout = async () => {
    try {
      const token = getToken()
      if (token) {
        await $fetch('/api/logout', {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`
          }
        })
      }
    } catch {}

    // 🔥 CLEAR EVERYTHING
    localStorage.removeItem('token')
    user.value = null
    authLoaded.value = true

    await navigateTo('/login', { replace: true })
  }

  return { user, authLoaded, fetchUser,getToken, login, logout }
}
*/
import { navigateTo } from '#app'
// const { public: { apiBase } } = useRuntimeConfig();
export const useAuth = () => {
  const user = useState('user', () => null)
  const authLoaded = useState('authLoaded', () => false)

  // 🔁 Load user from session cookie
  const fetchUser = async () => {
    try {
      user.value = await $fetch('/api/me', {
        credentials: 'include', // 🔥 REQUIRED
        headers: {
          Accept: 'application/json'
        }
      })
    } catch {
      user.value = null
    } finally {
      authLoaded.value = true
    }
  }

  // 🔐 Login (cookie auto-set by Laravel)
  const login = async (email, password) => {
    authLoaded.value = false

    await $fetch('/api/login', {
      method: 'POST',
      credentials: 'include', // 🔥 REQUIRED
      headers: {
        Accept: 'application/json'
      },
      body: { email, password }
    })

    await fetchUser()
    await navigateTo('/dashboard')
  }

  // 🚪 Logout (invalidate session + cookies)
  const logout = async () => {
    // 🔥 1. Clear frontend state FIRST
    user.value = null
    authLoaded.value = true
  
    // 🔥 2. Navigate immediately
    await navigateTo('/login', { replace: true })
  
    // 🔥 3. THEN call backend (non-blocking)
    try {
      await $fetch('/api/logout', {
        method: 'POST',
        credentials: 'include'
      })
    } catch {}
  }

  return {
    user,
    authLoaded,
    fetchUser,
    login,
    logout
  }
}

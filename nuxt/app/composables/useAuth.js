import { navigateTo } from '#app'

export const useAuth = () => {
  const user = useState('user', () => null)
  const authLoaded = useState('authLoaded', () => false)

  // 🔐 REQUIRED: initialize Sanctum CSRF cookie
  const initCsrf = async () => {
    await $fetch('/sanctum/csrf-cookie', {
      credentials: 'include'
    })
  }

  // 👤 Fetch authenticated user
  const fetchUser = async () => {
    try {
      user.value = await $fetch('/me', {
        credentials: 'include',
        headers: { Accept: 'application/json' }
      })
    } catch {
      user.value = null
    } finally {
      authLoaded.value = true
    }
  }

  // 🔑 Login (session cookie auth)
  const login = async (email, password) => {
    authLoaded.value = false

    // 🔥 REQUIRED FOR SANCTUM
    await initCsrf()

    await $fetch('/login', {
      method: 'POST',
      credentials: 'include',
      headers: { Accept: 'application/json' },
      body: { email, password }
    })

    await fetchUser()
    await navigateTo('/dashboard')
  }

  // 🚪 Logout
  const logout = async () => {
    user.value = null
    authLoaded.value = true

    await navigateTo('/login', { replace: true })

    // backend cleanup (non-blocking)
    try {
      await $fetch('/logout', {
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

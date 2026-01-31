export default defineNuxtRouteMiddleware(() => {
  const { user, authLoaded } = useAuth()

  if (!authLoaded.value) return

  if (!user.value) {
    return navigateTo('/login')
  }

  if (user.value.role !== 'admin') {
    return navigateTo('/dashboard')
  }
})

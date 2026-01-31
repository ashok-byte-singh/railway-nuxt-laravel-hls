export default defineNuxtRouteMiddleware(() => {
  const { user, authLoaded } = useAuth()

  if (!authLoaded.value) return

  // only block access if already logged in
  if (user.value) {
    return navigateTo('/dashboard')
  }
})

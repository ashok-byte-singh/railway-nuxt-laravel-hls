export default defineNuxtRouteMiddleware(async () => {
  const { user, authLoaded, fetchUser } = useAuth()

  if (!authLoaded.value) {
    await fetchUser()
  }

  if (!user.value) {
    return navigateTo('/login')
  }
})

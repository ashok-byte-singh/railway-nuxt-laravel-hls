export const useApi = () => {
  const apiFetch = (url, options = {}) => {
    // 🚫 Never run auth calls during SSR
    if (import.meta.server) return

    return $fetch(url, {
      credentials: 'include', // 🔥 REQUIRED for Sanctum
      ...options,
      headers: {
        Accept: 'application/json',
        ...(options.headers || {})
      }
    })
  }

  return { apiFetch }
}

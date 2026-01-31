// export const useApi = () => {
//   const apiFetch = (url, options = {}) => {
//     if (import.meta.server) return;

//     const token = localStorage.getItem('token');

//     return $fetch(url, {
//       ...options,
//       headers: {
//         ...(token ? { Authorization: `Bearer ${token}` } : {}),
//         ...(options.headers || {})
//       }
//     });
//   };

//   return { apiFetch };
// };
export const useApi = () => {
  const apiFetch = (url, options = {}) => {
    // ⛔ Prevent running on server (Nuxt SSR)
    if (import.meta.server) return

    return $fetch(url, {
      credentials: 'include', // 🔥 THIS is the key
      ...options,
      headers: {
        Accept: 'application/json',
        ...(options.headers || {})
      }
    })
  }

  return { apiFetch }
}

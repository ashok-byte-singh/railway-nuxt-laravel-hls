// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  runtimeConfig: {
    public: {
      apiBase: 'https://local.asd.com',
    },
  },

  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  app: {
    pageTransition: { name: 'page', mode: 'out-in' },
  },

  // 🔥 ADD THIS
  vite: {
    server: {
      allowedHosts: [
        'local.asd.com',
        'api.local.asd.com',
        'cdn.local.asd.com',
      ],
    },
  },
})

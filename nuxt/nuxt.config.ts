export default defineNuxtConfig({
  ssr: true,

  nitro: {
    preset: 'node-server'
  },

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || ''
    }
  },

  devtools: { enabled: false },

  app: {
    pageTransition: { name: 'page', mode: 'out-in' }
  }
})

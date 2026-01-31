// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: true,

  nitro: {
    preset: 'node-server'
  },

  runtimeConfig: {
    public: {
      apiBase: '/api'
    }
  },

  devtools: { enabled: false },

  app: {
    pageTransition: { name: 'page', mode: 'out-in' },
  },
})

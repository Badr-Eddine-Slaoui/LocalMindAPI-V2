// https://nuxt.com/docs/api/configuration/nuxt-config
// nuxt.config.ts
import colors from 'tailwindcss/colors'

export default defineNuxtConfig({
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://app:9000/api'
    }
  },
  vite: {
    esbuild: false,
  },
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    'pinia-plugin-persistedstate/nuxt'
  ],
  css: ['~/assets/css/style.css'],
  tailwindcss: {
    config: {
      darkMode: "class",
      theme: {
          extend: {
              colors: {
                  "primary": "#fbbf24", // Electric Yellow
                  "accent-ruby": "#e11d48", // Ruby Red
                  "background-deep": "#0a0c10", // Very dark navy/grey
                  "card-dark": "#161b22", // Lighter dark shade for cards
                  "sidebar-black": "#010409", // Sleek black
                  "obsidian": "#0a0e14",
                  "deep-teal": "#042c2e",
                  "pirate-gold": "#d4af37",
                  "silver-mist": "#e2e8f0",
                  "straw-red": "#e63946",
                  "glass-dark": "rgba(10, 14, 20, 0.7)",
                  "charcoal": "#121214",
                  "grand-line-red": "#dc2626",
                  "teal-accent": "#14b8a6",
                  "purple-accent": "#7c3aed",
                  "admin-primary": "#00f2ff",
                  "marine-dark": "#0a111a",
                  "marine-steel": "#1e293b",
              },
              fontFamily: {
                  "display": ["Plus Jakarta Sans", "sans-serif"]
              },
              borderRadius: {
                  "DEFAULT": "0.5rem",
                  "lg": "1rem",
                  "xl": "1.5rem",
                  "full": "9999px"
              },
              boxShadow: {
                  'glow': '0 0 15px -3px rgba(251, 191, 36, 0.3)',
              }
          },
      },
    }
  },
  app: {
    head:{
      htmlAttrs: {
        class: 'dark',
      }
    }
  }
})

export default defineNuxtConfig({
    runtimeConfig: {
        public: {
            backUrl: process.env.BACKEND_URL
        }
    },
    compatibilityDate: '2025-07-15',
    devtools: { enabled: true },
    modules: ['@nuxtjs/tailwindcss', '@pinia/nuxt'],
    ssr: false,
    app: {
        pageTransition: { name: 'page', mode: 'out-in' },
        head: {
            title: 'Existence',
            meta: [
                { name: 'description', content: 'Existence is a little simulation game.' },
                { name: 'viewport', content: 'width=device-width, initial-scale=1' }
            ]
        }
    },
})
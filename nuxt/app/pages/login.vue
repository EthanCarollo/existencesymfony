<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:24px_24px]"></div>

        <div class="relative w-full max-w-md">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 border border-gray-200/50">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-semibold text-gray-900 mb-2">Connexion</h1>
                    <p class="text-gray-600 text-sm">Accédez à Existence</p>
                </div>

                <form @submit.prevent="handleSubmit" class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="votre@email.com"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200 text-gray-900 placeholder-gray-400"
                        />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-transparent transition duration-200 text-gray-900 placeholder-gray-400"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full bg-gray-900 text-white py-3 px-4 rounded-xl font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="!loading">Se connecter</span>
                        <span v-else class="flex items-center justify-center">
              <svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Connexion...
            </span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Pas encore de compte ?
                        <a href="#" class="text-gray-900 font-medium hover:text-gray-700 transition">S'inscrire</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '~/composables/useAuth'

const { login } = useAuth()
const router = useRouter()

const form = reactive({
    email: '',
    password: ''
})

const loading = ref(false)

const handleSubmit = async () => {
    loading.value = true
    const result = await login(form.email, form.password)
    loading.value = false

    if (!result.success) {
        alert(result.error)
    }
}
</script>

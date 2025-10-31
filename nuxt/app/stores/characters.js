import { defineStore } from 'pinia'

export const useCharactersStore = defineStore('characters', {
    state: () => ({
        characters: null
    }),

    actions: {
        async fetchCharacters(token) {
            try {
                const characters = await $fetch(useRuntimeConfig().public.backUrl + '/api/characters', {
                    headers: { Authorization: `Bearer ${token}` }
                })
                this.characters = characters.member

                return { success: true, characters: characters.member }
            } catch (error) {
                return {
                    success: false,
                    error: error.data?.message || 'Identifiants incorrects'
                }
            }
        }
    }
})
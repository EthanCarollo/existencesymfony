import { defineStore } from 'pinia'

export const useChatStore = defineStore('chat', {
    state: () => ({
        characters: null
    }),

    actions: {
        async continueConversation(token, character1Id, character2Id) {
            try {
                const response = await $fetch(useRuntimeConfig().public.backUrl + '/chat', {
                    method: 'POST',
                    headers: {
                        Authorization: `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: {
                        character1: character1Id,
                        character2: character2Id
                    }
                })


                return { success: true, "response" : response }
            } catch (error) {
                console.log(error)
                return {
                    success: false,
                    error: error.data?.message || 'Identifiants incorrects'
                }
            }
        }
    }
})
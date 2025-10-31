

export const useChat = () => {
    const chatStore = useChatStore()

    const continueConversation = async (token, ch1, ch2) => {
        return await chatStore.continueConversation(token, ch1, ch2)
    }

    return {
        continueConversation,
    }
}
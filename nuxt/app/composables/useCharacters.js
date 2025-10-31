import { useCharactersStore } from "~/stores/characters.js";

export const useCharacters = () => {
    const characterStore = useCharactersStore()

    const fetchCharacters = async (token) => {
        return await characterStore.fetchCharacters(token)
    }

    return {
        fetchCharacters,
        characters: computed(() => characterStore.characters),
    }
}
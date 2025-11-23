<template>
    <div class="flex flex-col h-[90vh]">
        <div class="p-4 flex-shrink-0">
            <h2 class="text-lg font-semibold text-gray-900">All Characters</h2>
            <p class="text-sm text-gray-500 mt-1\">{{ characters.length }} character(s) total</p>
        </div>

        <div class="flex-1 overflow-y-auto min-h-0">
            <div v-if="characters.length > 0" class="flex flex-col gap-3 p-3">
                <Character
                    v-for="character in characters"
                    :key="character['@id']"
                    :character="character"
                    :empty="false"
                    @click="openPanelCharacter(character)"
                />
            </div>

            <div v-else class="flex items-center justify-center h-full">
                <p class="text-sm text-gray-500">No characters found</p>
            </div>
        </div>
    </div>
</template>
\
<script setup lang="ts">
import { ref, onMounted, computed } from "vue"

const buildManager = Scene.getInstance().buildManager
const buildings = ref([])
const { $bus } = useNuxtApp()

onMounted(() => {
    buildings.value = buildManager?.objectOnGrid || []
})

// Flatten all characters from all buildings into a single array
const characters = computed(() => {
    const allCharacters = []

    for (const building of buildings.value) {
        if (building.dataObject?.characters?.length > 0) {
            allCharacters.push(...building.dataObject.characters)
        }
    }

    return allCharacters
})

const openPanelCharacter = (character: any) => {
    $bus.emit("active-chat", { character })
}
</script>

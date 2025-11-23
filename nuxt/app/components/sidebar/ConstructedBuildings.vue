<template>
    <div class="flex flex-col h-[90vh]">
        <div class="p-4 flex-shrink-0">
            <h2 class="text-lg font-semibold text-gray-900">Constructed Buildings</h2>
        </div>

        <div class="flex-1 overflow-y-auto min-h-0">
            <div
                v-for="(building, index) in buildings"
                :key="index"
            >
                <div
                    @click="toggleBuilding(index)"
                    class="flex flex-col items-center gap-3 p-3 transition-colors cursor-pointer hover:bg-gray-50"
                >
                    <div class="flex items-center gap-3 w-full">
                        <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded overflow-hidden">
                            <img
                                :src="useRuntimeConfig().public.backUrl + building.dataObject.building.image"
                                :alt="building.name"
                                class="w-full h-full object-cover select-none"
                            />
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900 capitalize truncate">
                                {{ building.dataObject.building.name }}
                            </h3>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    Position : X: {{ building.position.x }} | Y: {{ building.position.y }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            <svg
                                :class="['w-5 h-5 text-gray-400 transition-transform', { 'rotate-180': openBuildingIndex === index }]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    v-if="openBuildingIndex === index && building.dataObject.characters?.length > 0"
                    class="pb-3"
                >
                    <div class="flex gap-3 overflow-x-auto pb-2 flex-col">
                        <Character
                            v-for="character in building.dataObject.characters"
                            :key="character['@id']"
                            :character="character"
                            :empty="false"
                            @click="openPanelCharacter(character)"
                        />
                    </div>
                </div>

                <div
                    v-else-if="openBuildingIndex === index && (!building.dataObject.characters || building.dataObject.characters.length === 0)"
                    class="px-3 pb-3 bg-gray-50"
                >
                    <p class="text-sm text-gray-500 text-center py-2">Aucun personnage dans ce bâtiment</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const buildManager = Scene.getInstance().buildManager
const buildings = ref([])
const openBuildingIndex = ref<number | null>(null)
const { $bus } = useNuxtApp()

onMounted(() => {
    buildings.value = buildManager?.objectOnGrid || []
})

const openPanelCharacter = (character: any) => {
    $bus.emit('active-chat', { character })
}

const toggleBuilding = (index: number) => {
    if (openBuildingIndex.value === index) {
        openBuildingIndex.value = null
    } else {
        openBuildingIndex.value = index
    }
}
</script>

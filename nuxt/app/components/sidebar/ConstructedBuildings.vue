<template>
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="p-4">
            <h2 class="text-lg font-semibold text-gray-900">Constructed Buildings</h2>
        </div>

        <!-- Buildings Grid -->
        <div class="flex-1 overflow-y-auto">
            <div
                v-for="building in buildings"
                :key="building.dataObject.building.id"
                class=""
            >
                <!-- Added click handler and hover effect -->
                <div
                    @click="toggleBuilding(building.dataObject.building.id)"
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

                        <!-- Added chevron icon to indicate expandable -->
                        <div class="flex-shrink-0">
                            <svg
                                :class="['w-5 h-5 text-gray-400 transition-transform', { 'rotate-180': openBuildingId === building.dataObject.building.id }]"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Added character list dropdown -->
                <div
                    v-if="openBuildingId === building.dataObject.building.id && building.dataObject.characters?.length > 0"
                    class="pb-3 "
                >
                    <div class="flex gap-3 overflow-x-auto pb-2 flex-col">
                        <div
                            v-for="character in building.dataObject.characters"
                            :key="character['@id']"
                            class="flex-shrink-0 w-64 bg-white/20 rounded-lg p-3 border border-gray-200 shadow-sm "
                        >
                            <div class="flex items-start gap-3">
                                <img
                                    :src="character.image"
                                    :alt="character.name"
                                    class="w-12 h-12 rounded-full flex-shrink-0"
                                />
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 text-sm truncate">
                                        {{ character.name }}
                                    </h4>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                        {{ character.personality }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Added empty state when no characters -->
                <div
                    v-else-if="openBuildingId === building.dataObject.building.id && (!building.dataObject.characters || building.dataObject.characters.length === 0)"
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
const openBuildingId = ref(null)

onMounted(() => {
    buildings.value = buildManager?.objectOnGrid || []
    console.warn(buildings.value)
})

const toggleBuilding = (buildingId: string) => {
    if (openBuildingId.value === buildingId) {
        openBuildingId.value = null
    } else {
        openBuildingId.value = buildingId
    }
}
</script>

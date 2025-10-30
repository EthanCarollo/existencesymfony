<template>
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="p-4">
            <h2 class="text-lg font-semibold text-gray-900">Buildings</h2>
        </div>

        <!-- List -->
        <div class="flex-1 overflow-y-auto">
            <div
                v-for="building in buildings"
                :key="building.id"
                @mousedown.prevent="onDragStart(building, $event)"
                @mouseup="onDragEnd(building, $event)"
                @click="handleBuild(building)"
                class="flex flex-col items-center gap-3 p-3 transition-colors cursor-pointer hover:bg-gray-50/30"
            >
                <div class="flex items-center gap-3 w-full">
                    <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded overflow-hidden">
                        <img
                            :src="useRuntimeConfig().public.backUrl + building.image"
                            :alt="building.name"
                            class="w-full h-full object-cover select-none"
                        />
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="font-medium text-gray-900 capitalize truncate">
                            {{ building.name }}
                        </h3>
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                          <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/>
                            </svg>
                            {{ building.width }} × {{ building.length }}
                          </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const { buildings } = useBuildings()
let scene = Scene.getInstance()

const dragging = ref(false)
const currentBuilding = ref(null)

const handleBuild = (building) => {
    console.log('Building clicked:', building)
}

const onDragStart = (building, event) => {
    dragging.value = true
    currentBuilding.value = building
    console.log('Drag started:', building)
    scene.buildManager.setSelectedObject(building.model, event)

    // Écoute globale pour détecter le relâché de la souris
    window.addEventListener('mousemove', onDragMove)
    window.addEventListener('mouseup', onDragEnd)
}

const onDragMove = (event) => {
    if (!dragging.value || !currentBuilding.value) return
    // Ici tu peux déplacer ton objet dans la scène si tu veux
    // ex: scene.buildManager.moveSelectedObject(event.clientX, event.clientY)
}

const onDragEnd = (event) => {
    if (!dragging.value) return
    console.log('Drag ended:', currentBuilding.value)
    scene.buildManager.resetSelectedObject()
    dragging.value = false
    currentBuilding.value = null

    window.removeEventListener('mousemove', onDragMove)
    window.removeEventListener('mouseup', onDragEnd)
}
</script>


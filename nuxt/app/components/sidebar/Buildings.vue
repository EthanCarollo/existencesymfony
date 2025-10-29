<template>
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Buildings</h2>
        </div>

        <!-- List -->
        <div
            class="flex-1 overflow-y-auto"
            @dragover.prevent="onDragOver"
            @drop="onDrop"
        >
            <div
                v-for="building in buildings"
                :key="building.id"
                draggable="true"
                @dragstart="onDragStart(building)"
                @click="handleBuild(building)"
                class="flex flex-col items-center gap-3 p-3 border-b border-gray-100 transition-colors cursor-pointer hover:bg-gray-50"
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
                {{ building.width }} × {{ building.height }}
              </span>
                            <span>ID: {{ building.id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
const { buildings } = useBuildings()

const handleBuild = (building) => {
    console.log('Building clicked:', building)
}

const onDragStart = (building) => (event) => {
    console.log('Drag started:', building)
    event.dataTransfer.effectAllowed = 'move'
    event.dataTransfer.setData('building-id', building.id)
}

const onDragOver = (event) => {
    event.preventDefault()
    event.dataTransfer.dropEffect = 'move'
}

const onDrop = (event) => {
    const id = event.dataTransfer.getData('building-id')
    console.log('Building dropped:', id)
}
</script>

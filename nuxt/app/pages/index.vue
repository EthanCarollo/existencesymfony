<template>
    <div>
        <div id="canvas" ref="sceneContainer" class="w-full h-full"></div>
        <Sidebar />
        <Chat />
    </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue'
import { useGrid } from '~/composables/useGrid.js'
import { useBuildings } from '~/composables/useBuildings.js'
import { useAuth } from '~/composables/useAuth.js'
import Sidebar from "~/components/sidebar/Sidebar.vue";

const { token } = useAuth();
const { fetchGrid } = useGrid()
const { fetchBuildings } = useBuildings()
const modelLoader = new ModelLoader();

definePageMeta({
    middleware: 'auth'
})

const sceneContainer = ref(null)
let scene = Scene.getInstance()

onMounted(async () => {
    let gridResponse = await fetchGrid(token.value)
    let buildingsResponse = await fetchBuildings(token.value)

    var modelToLoad = buildingsResponse.buildings.map(building => {
        return {
            url: useRuntimeConfig().public.backUrl + '/api' + building.model,
            key: building.model,
            width: building.width,
            length: building.length,
            height: building.height,
        }
    })
    await modelLoader.loadModels(modelToLoad)

    scene.mounted(sceneContainer.value,
        gridResponse.grid,
        modelLoader
    )
})
</script>

<style scoped>
#canvas {
    width: 100%;
    height: 100vh;
    overflow: hidden;
}
</style>

<template>
    <div>
        <div id="canvas" ref="sceneContainer" class="w-full h-full"></div>
        <Sidebar />
    </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue'
import Sidebar from "~/components/Sidebar.vue";

definePageMeta({
    middleware: 'auth'
})

const sceneContainer = ref(null)
let scene = new Scene()

onMounted(async () => {
    scene.mounted(sceneContainer.value)
})
</script>

<style scoped>
#canvas {
    width: 100%;
    height: 100vh;
    overflow: hidden;
}
</style>

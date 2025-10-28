<template>
    <div ref="sceneContainer" class="w-full h-full"></div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue'

const sceneContainer = ref(null)
let scene = new Scene()

onMounted(() => {
    scene.mounted(sceneContainer.value)
})
</script>

<style scoped>
div {
    width: 100%;
    height: 100vh;
    overflow: hidden;
}
</style>

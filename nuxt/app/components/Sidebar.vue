<template>
    <div
        class="fixed left-3 top-1/2 -translate-y-1/2 flex flex-col items-center bg-black/0 backdrop-blur-[2px] p-3 rounded-3xl shadow-lg"
    >
        <div class="flex flex-col space-y-4">
            <button
                v-for="item in navigationItems"
                :key="item.id"
                @click="handleClick(item)"
                :class="[
          'p-2 rounded-full transition',
          activeItem === item.id
            ? 'bg-gray-900 text-white'
            : 'text-gray-600 hover:bg-gray-100'
        ]"
            >
                <component :is="item.icon" class="w-6 h-6" />
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { LogOut } from 'lucide-vue-next'
import { useAuth } from '~/composables/useAuth'

const { logout } = useAuth()

const activeItem = ref('accueil')

const handleLogout = () => {
    console.log('User logged out')
    logout()
}

const navigationItems = [
    { id: 'logout', icon: LogOut, callback: handleLogout },
]

const handleClick = (item) => {
    activeItem.value = item.id
    if (item.callback) item.callback()
}
</script>

<style scoped>
button {
    transition: all 0.2s ease;
}
</style>

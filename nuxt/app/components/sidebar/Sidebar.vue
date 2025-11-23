<template>
    <div
        class="fixed left-3 top-1/2 -translate-y-1/2 flex  items-center gap-8"
    >
        <div class="bg-white/90 backdrop-blur-[2px] p-3 rounded-3xl shadow-lg">
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
        <!-- <CHANGE> Added Transition component for slide-in animation from right -->
        <Transition name="slide-from-left">
            <div class="bg-white/90 backdrop-blur-[2px] py-4 px-8 rounded-3xl shadow-lg w-80" v-if="activeComponent !== null">
                <component :is="activeComponent" class="w-full" />
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { LogOut, User, Building, FileUser, Hammer } from 'lucide-vue-next'
import { useAuth } from '~/composables/useAuth.js'
const { logout } = useAuth()
import Profile from "~/components/sidebar/Profile.vue";
import BuildingList from "~/components/sidebar/Buildings.vue";
import ConstructedBuildings from "~/components/sidebar/ConstructedBuildings.vue";

const activeItem = ref('accueil')
const activeComponent = ref(null)

const handleLogout = () => {
    console.log('User logged out')
    logout()
}

const navigationItems = [
    { id: 'buildings', icon: Hammer, callback: null, component: BuildingList },
    { id: 'characters', icon: FileUser, callback: null, component: ConstructedBuildings },
    { id: 'profile', icon: User, callback: null, component: Profile },
    { id: 'logout', icon: LogOut, callback: handleLogout, component: null },
]

// <CHANGE> Modified to toggle off when clicking active item
const handleClick = (item) => {
    // If clicking the already active item, deactivate it
    if (activeItem.value === item.id) {
        activeItem.value = null
        activeComponent.value = null
        return
    }

    activeItem.value = item.id
    if (item.callback) item.callback()
    if (item.component !== null) activeComponent.value = item.component
    else activeComponent.value = null
}
</script>

<style scoped>
button {
    transition: all 0.2s ease;
}

/* <CHANGE> Added slide-from-left animation styles */
.slide-from-left-enter-active {
    transition: all 0.3s ease-out;
}

.slide-from-left-leave-active {
    transition: all 0.3s ease-in;
}

.slide-from-left-enter-from {
    transform: translateX(-30px);
    opacity: 0;
}

.slide-from-left-leave-to {
    transform: translateX(-30px);
    opacity: 0;
}
</style>
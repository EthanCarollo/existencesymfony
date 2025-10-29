<template>
    <div class="max-w-md p-6">
        <div class="flex items-center gap-4 mb-6 pb-4 border-b">
            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 text-xl font-medium">
                {{ getInitials(user.name) }}
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-semibold text-gray-900 capitalize mb-1">
                    {{ user.name }}
                </h1>
                <div class="flex flex-wrap gap-2">
          <span
              v-for="(role, index) in user.roles"
              :key="index"
              class="inline-block px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm capitalize"
          >
            {{ formatRole(role) }}
          </span>
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-xs font-medium uppercase tracking-wide text-gray-500 mb-2">
                Informations
            </h2>
            <div class="text-sm text-gray-700">
                {{ user.email }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuth } from '~/composables/useAuth'

const { user } = useAuth()

const getInitials = (name) => {
    if (!name) return '?'
    return name.charAt(0).toUpperCase()
}

const formatRole = (role) => {
    return role.replace('ROLE_', '').toLowerCase().replace('_', ' ')
}
</script>
<template>
    <div
        class="flex-shrink-0 max-w-64 bg-white rounded-lg p-3 border border-gray-200 shadow-sm cursor-pointer hover:shadow-md transition-all"
    >
        <div class="flex items-start gap-3" v-if="empty === false">
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
        <div class="flex items-start gap-3" v-else>
            <img
                src="https://api.dicebear.com/9.x/croodles/svg?seed=Sadie&beardProbability=0&topColor=000000,0fa958,699bf7,9747ff,f24e1e,ffc700,transparent"
                alt=""
                class="w-12 h-12 rounded-full flex-shrink-0"
            />
            <div class="flex-1 min-w-0">
                <h4 class="font-semibold text-gray-900 text-sm truncate">
                   ???
                </h4>
                <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                    ???
                </p>
            </div>
        </div>
    </div>

</template>

<script setup>

defineProps(["character", "empty"]);

</script>
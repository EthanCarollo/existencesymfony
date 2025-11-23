<template>
    <div
        class="fixed right-3 top-1/2 -translate-y-1/2 flex py-4 items-between h-screen gap-8"
    >
        <Transition name="slide-from-right">
            <div class="bg-white h-full backdrop-blur-[2px] py-4 px-8 rounded-3xl shadow-lg w-[512px]" v-if="isActive">
                <div class="flex justify-between items-center">
                    <div>Chat</div>
                    <div @click="unactiveComponent" class="cursor-pointer">Close</div>
                </div>
                <div class="flex py-4 gap-4 items-start">
                    <Character :empty="false" :character="leftCharacter" class="grow w-[50%]" v-if="leftCharacter" />
                    <Character :empty="true" class="grow" v-else />
                    <div class="w-[50%] relative">
                        <Character :empty="false" @click="activeSelectCharacter" :character="rightCharacter" class="grow" v-if="rightCharacter" />
                        <Character :empty="true" @click="activeSelectCharacter" class="grow h-full" v-else />
                        <Transition name="slide-from-top">
                            <div v-if="selectCharacter" class="absolute right-0 z-20">
                                <div class="flex flex-col gap-2 mt-2">
                                    <div v-for="character in allCharacters.filter(charact => charact.id !== leftCharacter.id)">
                                        <Character :character="character" :empty="false" @click="selectRightCharacter(character)" class="grow h-full" />
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </div>
                </div>
                <div class="relative h-[75%] overflow-hidden">
                    <div ref="chatContainer" class="flex flex-col w-[calc(100%+29px)]  pr-4 gap-2 mt-2 overflow-y-scroll h-full">
                        <TransitionGroup name="message-slide">
                            <div
                                class="flex"
                                :class="message.side === 'right' ? 'flex-row-reverse' : ''"
                                v-for="message in allChats"
                                :key="message.id"
                            >
                                <div
                                    class="flex-shrink-0 max-w-96 bg-white/90 rounded-lg p-3 border border-gray-200 shadow-sm hover:bg-white"
                                >
                                    <div class="flex-1 min-w-0">
                                        <div class="flex" :class="message.side === 'right' ? 'flex-row-reverse' : ''">
                                            <h4 class="font-semibold text-gray-900 text-sm truncate">
                                                {{ message.author }}
                                            </h4>
                                        </div>
                                        <p class="text-xs text-gray-600 mt-1" :class="message.side === 'right' ? 'text-end' : ''">
                                            {{ message.message }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </TransitionGroup>
                        <div class="mt-16"></div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white via-white/80 to-transparent pointer-events-none"></div>
                </div>

                <div class="absolute bottom-0 left-0 w-[100%] p-4 flex justify-center">
                    <div
                        :class="rightCharacter === null || leftCharacter === null ? 'hidden' : ''"
                        @click="continueConversationBetween"
                        class="bg-white/80 py-2 px-4 cursor-pointer hover:bg-white rounded-lg flex items-center gap-2 transition-all hover:shadow-md"
                        :disabled="isLoading"
                    >
                        <div v-if="isLoading" class="animate-spin h-4 w-4 border-2 border-gray-900 border-t-transparent rounded-full"></div>
                        <span>{{ isLoading ? 'Generating...' : 'Continue conversation' }}</span>
                    </div>
                </div>
            </div>
        </Transition>

        <Transition name="fade">
            <div v-if="isLoading" class="fixed inset-0 bg-black/60 backdrop-blur-sm my-4 rounded-3xl z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                    <div class="flex flex-col items-center gap-4">
                        <div class="animate-spin h-12 w-12 border-4 border-gray-900 border-t-transparent rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Generating conversation...</h3>
                        <p class="text-sm text-gray-600 text-center">This can take 3-4 minutes if the conversation is too big</p>

                        <div class="mt-4 w-full min-h-24 flex items-center justify-center">
                            <Transition name="joke-fade" mode="out-in">
                                <div :key="currentJokeIndex" class="text-center px-4">
                                    <p class="text-base text-gray-700 italic">{{ jokes[currentJokeIndex] }}</p>
                                </div>
                            </Transition>
                        </div>

                        <div class="flex gap-1 mt-2">
                            <div
                                v-for="(joke, index) in jokes"
                                :key="index"
                                class="h-1.5 w-1.5 rounded-full transition-all duration-300"
                                :class="index === currentJokeIndex ? 'bg-gray-900 w-6' : 'bg-gray-300'"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, onMounted, computed, watch, nextTick, onUnmounted } from 'vue';
import { useCharactersStore } from "~/stores/characters.js";
import { useGridStore } from "~/stores/grid.js";
import { useAuth } from '~/composables/useAuth.js';
import { useChat } from "~/composables/useChat.js";
import { useNuxtApp } from '#app';
import Character from './Character.vue';

const { token } = useAuth();
const { $bus } = useNuxtApp();

const isActive = ref(false);
const isLoading = ref(false);
const leftCharacter = ref(null);
const rightCharacter = ref(null);
const selectCharacter = ref(false);
const chatContainer = ref(null);
const currentJokeIndex = ref(0);
let jokeInterval = null;

const jokes = [
    "Why don't scientists trust atoms? Because they make up everything!",
    "What do you call a bear with no teeth? A gummy bear!",
    "Why did the scarecrow win an award? He was outstanding in his field!",
    "What do you call a fake noodle? An impasta!",
    "Why don't eggs tell jokes? They'd crack each other up!",
    "What did the ocean say to the beach? Nothing, it just waved!",
    "Why did the bicycle fall over? It was two-tired!",
    "What do you call cheese that isn't yours? Nacho cheese!",
    "Why couldn't the leopard play hide and seek? Because he was always spotted!",
    "What did one wall say to the other? I'll meet you at the corner!"
];

const { fetchCharacters } = useCharactersStore();
const { fetchGrid } = useGridStore();
const allCharacters = ref([]);
const { continueConversation } = useChat();

const allChats = computed(() => {
    let chats = [];
    if (leftCharacter.value !== null && rightCharacter.value !== null) {
        const leftMessages = leftCharacter.value.sendedChats.map((chat) => {
            return {
                id: chat.id,
                author: leftCharacter.value.name,
                authorId: leftCharacter.value.id,
                receiverId: chat.receiverId,
                message: chat.message,
                side: "left"
            };
        });
        const rightMessages = rightCharacter.value.sendedChats.map((chat) => {
            return {
                id: chat.id,
                author: rightCharacter.value.name,
                authorId: rightCharacter.value.id,
                receiverId: chat.receiverId,
                message: chat.message,
                side: "right"
            };
        });
        chats = chats.concat(leftMessages.filter(chat => chat.receiverId === rightCharacter.value.id));
        chats = chats.concat(rightMessages.filter(chat => chat.receiverId === leftCharacter.value.id));
        chats.sort((a, b) => a.id - b.id);
    }
    return chats;
});

const scrollToBottom = () => {
    if (chatContainer.value) {
        nextTick(() => {
            chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
        });
    }
};

const startJokeRotation = () => {
    currentJokeIndex.value = 0;
    jokeInterval = setInterval(() => {
        currentJokeIndex.value = (currentJokeIndex.value + 1) % jokes.length;
    }, 5000);
};

const stopJokeRotation = () => {
    if (jokeInterval) {
        clearInterval(jokeInterval);
        jokeInterval = null;
    }
};

watch(allChats, () => {
    scrollToBottom();
}, { deep: true });

watch(isLoading, (newVal) => {
    if (newVal) {
        startJokeRotation();
    } else {
        stopJokeRotation();
    }
});

const activeSelectCharacter = () => {
    selectCharacter.value = !selectCharacter.value;
};

const selectRightCharacter = (character) => {
    rightCharacter.value = character;
    activeSelectCharacter();
};

const unactiveComponent = () => {
    isActive.value = false;
};

const activeComponent = (payload) => {
    console.log(payload);
    leftCharacter.value = payload.character;
    rightCharacter.value = null;
    isActive.value = true;
};

onMounted(async () => {
    $bus.on('active-chat', activeComponent);
    const characters = await fetchCharacters(token.value);
    allCharacters.value = characters.characters;
});

onUnmounted(() => {
    stopJokeRotation();
});

const continueConversationBetween = async () => {
    if (isLoading.value) return;

    isLoading.value = true;
    console.warn("continue conversation here");

    try {
        const message = await continueConversation(token.value, leftCharacter.value.id, rightCharacter.value.id);
        await fetchGrid(token.value);
        if (message.response.senderId === rightCharacter.value.id) {
            rightCharacter.value.sendedChats.push(message.response);
        } else {
            leftCharacter.value.sendedChats.push(message.response);
        }

    } finally {
        isLoading.value = false;
    }
};
</script>

<style scoped>
html::-webkit-scrollbar,
body::-webkit-scrollbar {
    display: none;
}

button {
    transition: all 0.2s ease;
}

.slide-from-right-enter-active {
    transition: all 0.3s ease-out;
}

.slide-from-right-leave-active {
    transition: all 0.3s ease-in;
}

.slide-from-right-enter-from {
    transform: translateX(30px);
    opacity: 0;
}

.slide-from-right-leave-to {
    transform: translateX(30px);
    opacity: 0;
}

.slide-from-top-enter-active {
    transition: all 0.3s ease-out;
}

.slide-from-top-leave-active {
    transition: all 0.3s ease-in;
}

.slide-from-top-enter-from {
    transform: translateY(-30px);
    opacity: 0;
}

.slide-from-top-leave-to {
    transform: translateY(-30px);
    opacity: 0;
}

.message-slide-enter-active {
    transition: all 0.4s ease-out;
}

.message-slide-enter-from {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
}

.message-slide-move {
    transition: transform 0.4s ease;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

.joke-fade-enter-active {
    transition: all 0.6s ease;
}

.joke-fade-leave-active {
    transition: all 0.6s ease;
}

.joke-fade-enter-from {
    opacity: 0;
    transform: translateY(10px);
}

.joke-fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>

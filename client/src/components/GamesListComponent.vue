<template>
  <div>
    <!-- список игр -->
    <div v-if="!isGameInfoOpened" class="w-full text-second-color rounded-xl p-5 overflow-auto my-4 select-none">
      <div class="grid mb-4">
        <h1 class="font-bold text-2xl">Games</h1>
        <input
          autocomplete="gamename"
          v-model="gameSearch"
          placeholder="Search..."
          class="mt-2 w-full bg-gray-200 p-2 rounded-lg focus:outline-none"
        />
      </div>
      <div class="space-y-2">
        <div
          v-for="game in filteredGames"
          :key="game.id"
          @click="openGameInfo(game)"
          class="p-2 border border-second-color rounded-lg cursor-pointer hover:bg-gray-100"
        >
          <code class="text-sm">{{ game.id }}</code> {{ game.name }}
        </div>
      </div>
    </div>

    <!-- модалка с деталями игры -->
    <div v-if="isGameInfoOpened" class="fixed inset-0 flex items-center justify-center p-4 z-50 bg-black bg-opacity-50">
      <div class="bg-white rounded-2xl shadow-lg overflow-auto max-h-[90vh] p-6 w-full md:w-3/4 lg:w-1/2">
        <button @click="closeGameInfo" class="mb-4 text-gray-500 hover:text-gray-700">← Back</button>
        <h2 class="text-2xl font-bold mb-2">{{ selectedGame.name }}</h2>
        <p class="text-gray-600 mb-4">Game ID: {{ selectedGame.id }}</p>
        <p class="text-gray-800 mb-4 whitespace-pre-wrap">{{ selectedGame.description }}</p>
        <div class="space-y-4">
          <div
            v-for="participant in selectedGame.participants"
            :key="participant.id"
            class="flex items-center border border-second-color rounded-lg p-3"
          >
            <font-awesome-icon icon="user" />
            <div class="ml-3">
              <p class="font-semibold">{{ participant.name }}</p>
              <p class="text-sm">{{ participant.email }}</p>
              <p class="text-xs text-gray-500">ID: {{ participant.id }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

const isGameInfoOpened = ref(false)
const selectedGame = ref({})
const games = ref([])
const gameSearch = ref('')

const filteredGames = computed(() =>
  games.value.filter(g =>
    g.name.toLowerCase().includes(gameSearch.value.toLowerCase())
  )
)

function openGameInfo(game) {
  selectedGame.value = game
  isGameInfoOpened.value = true
}
function closeGameInfo() {
  isGameInfoOpened.value = false
}

onMounted(async () => {
  const { data } = await axios.get(`${import.meta.env.VITE_API_URL}/api/v1/game`, {
    headers: { Authorization: localStorage.getItem('TOKEN') },
  })
  games.value = data
})
</script>

<style scoped>
/* Tailwind */
</style>
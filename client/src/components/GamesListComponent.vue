<template>
  <div>
    <!-- Список игр -->
    <div v-if="!isGameInfoOpened" class="w-full sm:w-96 text-gray-800 bg-gray-100/90 rounded-2xl p-4 overflow-auto my-4 select-none space-y-4">
      <!-- Заголовок + поиск -->
      <div class="space-y-2">
        <h1 class="text-xl font-bold text-red-700">Games</h1>
        <input
          autocomplete="gamename"
          v-model="gameSearch"
          placeholder="Search..."
          class="w-full p-2 rounded-md bg-gray-200 focus:ring-2 focus:ring-red-700 text-sm"
        />
      </div>

      <!-- Список карточек игр -->
      <div class="space-y-2">
        <div
          v-for="game in filteredGames"
          :key="game.id"
          @click="openGameInfo(game)"
          class="p-3 rounded-md border border-red-700 bg-white hover:bg-gray-100 cursor-pointer transition flex flex-col"
        >
          <div class="flex items-center gap-2">
            <code class="text-xs text-gray-500">{{ game.id }}</code>
            <span class="text-red-700 font-semibold truncate">{{ game.name }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Модалка с деталями игры -->
    <div v-if="isGameInfoOpened" class="fixed inset-0 flex items-center justify-center bg-black/70 z-50 p-4">
      <div class="bg-gray-100 rounded-2xl shadow-2xl overflow-auto max-h-[90vh] p-6 w-full sm:w-3/4 md:w-1/2 space-y-6">
        <button @click="closeGameInfo" class="text-red-700 hover:text-red-800 transition text-sm flex items-center gap-1 mb-4">
          <font-awesome-icon icon="arrow-left" /> Back
        </button>

        <h2 class="text-2xl font-bold text-gray-800">{{ selectedGame.name }}</h2>
        <p class="text-sm text-gray-600">Game ID: {{ selectedGame.id }}</p>

        <p class="text-gray-800 whitespace-pre-wrap">{{ selectedGame.description }}</p>

        <div class="space-y-3 pt-4">
          <h3 class="text-lg font-semibold text-red-700">Participants</h3>
          <div
            v-for="participant in selectedGame.participants"
            :key="participant.id"
            class="flex items-center p-3 rounded-md border border-red-700 bg-white"
          >
            <font-awesome-icon icon="user" class="text-gray-500" />
            <div class="ml-3 text-sm">
              <p class="font-semibold text-gray-800">{{ participant.name }}</p>
              <p class="text-gray-600">{{ participant.email }}</p>
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
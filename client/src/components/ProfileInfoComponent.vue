<template>
  <div class="w-full md:max-w-lg rounded-2xl shadow-lg bg-white p-6 my-6">
    <!-- Профиль -->
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold text-second-color">{{ user.name }}</h1>
      <p class="mt-2 text-gray-700">{{ user.email }}</p>
      <p class="mt-1 text-gray-600">{{ user.bio || 'No bio provided.' }}</p>
    </div>

    <!-- Список моих игр -->
    <h2 class="text-xl font-semibold text-second-color mb-4">My Games</h2>
    <div class="overflow-auto bg-gray-100 p-4 rounded-lg h-36 mb-6">
      <div class="divide-y divide-gray-200">
        <div
          v-for="game in user.games"
          :key="game.id"
          class="flex items-center justify-between px-3 py-2 hover:bg-white hover:shadow-md transition-shadow cursor-pointer"
        >
          <!-- ID + ссылка -->
          <div class="flex items-center space-x-4">
            <span class="text-xs font-mono text-gray-500">{{ game.id }}</span>
            <RouterLink
              :to="{ name: 'game', params: { game_id: game.id } }"
              class="text-blue-600 hover:text-blue-800 font-medium"
            >
              {{ game.name }}
            </RouterLink>
          </div>

          <!-- Кнопка + «Copied!» -->
          <div class="flex items-center space-x-2">
            <button
              @click="copyId(game.id)"
              class="p-1 text-gray-400 hover:text-gray-600 
                    transition-transform duration-150 active:scale-90"
              aria-label="Copy game ID"
            >
              <font-awesome-icon :icon="copiedGameId === game.id ? 'check' : 'copy'" />
            </button>
            <span
              v-if="copiedGameId === game.id"
              class="text-green-500 text-sm opacity-0 animate-fade-in"
            >
              Copied!
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Кнопки Join / Create -->
    <div class="flex space-x-4 mb-6">
      <button
        @click="showJoinGameForm = true"
        class="flex-1 py-2 font-semibold rounded-lg bg-second-color text-white focus:outline-none"
      >
        Join
      </button>
      <button
        @click="showCreateGameForm = true"
        class="flex-1 py-2 font-semibold rounded-lg border-2 border-second-color text-second-color focus:outline-none"
      >
        Create
      </button>
    </div>

    <!-- Create Game Modal -->
    <div
      v-if="showCreateGameForm"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40"
    >
      <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Create Game</h2>
        <form @submit.prevent="createGame" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input
              autocomplete="gamename"
              v-model="gameName"
              type="text"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea
              v-model="gameDescription"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring"
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input
              autocomplete="new-password"
              v-model="createPassword"
              type="password"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Confirm Password</label>
            <input
              autocomplete="new-password"
              v-model="passwordConfirmation"
              type="password"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring"
            />
          </div>
          <div class="flex justify-end space-x-2">
            <button type="submit" class="py-2 px-4 bg-indigo-600 text-white rounded-lg">
              Create
            </button>
            <button
              type="button"
              @click="showCreateGameForm = false"
              class="py-2 px-4 bg-gray-300 rounded-lg"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Join Game Modal -->
    <div
      v-if="showJoinGameForm"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40"
    >
      <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Join Game</h2>
        <form @submit.prevent="joinGame" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Game ID</label>
            <input
              autocomplete="gameid"
              v-model="joinGameId"
              type="text"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input
              autocomplete="current-password"
              v-model="joinPassword"
              type="password"
              class="w-full p-2 border rounded-lg focus:outline-none focus:ring"
            />
          </div>
          <div class="flex justify-end space-x-2">
            <button type="submit" class="py-2 px-4 bg-second-color text-white rounded-lg">
              Join
            </button>
            <button
              type="button"
              @click="showJoinGameForm = false"
              class="py-2 px-4 bg-gray-300 rounded-lg"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Logout -->
    <button
      @click="logout"
      class="w-full py-2 bg-red-500 text-white rounded-lg mt-4"
    >
      Logout
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useUserStore } from '@/stores/user'
import { storeToRefs } from 'pinia'
import axios from 'axios'
import router from '@/router'

const userStore = useUserStore()
const { data: user } = storeToRefs(userStore)

const showCreateGameForm = ref(false)
const showJoinGameForm   = ref(false)

const gameName            = ref('')
const gameDescription     = ref('')
const createPassword      = ref('')
const passwordConfirmation= ref('')

const joinGameId   = ref('')
const joinPassword = ref('')

async function createGame() {
  try {
    // POST создаёт новую игру и возвращает объект игры
    const { data: newGame } = await axios.post(
      `${import.meta.env.VITE_API_URL}/api/v1/game`,
      {
        name: gameName.value,
        description: gameDescription.value,
        password: createPassword.value,
        password_confirmation: passwordConfirmation.value
      },
      { headers: { Authorization: localStorage.getItem('TOKEN') } }
    )

    // Добавляем игру в стор напрямую
    userStore.addGame(newGame)

    // Сбрасываем форму и закрываем попап
    gameName.value = ''
    gameDescription.value = ''
    createPassword.value = ''
    passwordConfirmation.value = ''
    showCreateGameForm.value = false

  } catch (err) {
    console.error('Create game failed:', err)
  }
}

async function joinGame() {
  try {
    const { data: joinedGame } = await axios.post(
      `${import.meta.env.VITE_API_URL}/api/v1/game/join`,
      { id: joinGameId.value, password: joinPassword.value },
      { headers: { Authorization: localStorage.getItem('TOKEN') } }
    )

    // Добавляем игру в стор
    userStore.addGame(joinedGame)

    // Сброс полей и закрытие попапа
    joinGameId.value = ''
    joinPassword.value = ''
    showJoinGameForm.value = false

  } catch (err) {
    console.error('Join game failed:', err)
  }
}

async function logout() {
  await axios.post(
    `${import.meta.env.VITE_API_URL}/api/v1/logout`,
    {},
    { headers: { Authorization: localStorage.getItem('TOKEN') } }
  )
  router.push({ name: 'login' })
}

const copiedGameId = ref(null)

function copyId(id) {
  navigator.clipboard.writeText(id).then(() => {
    copiedGameId.value = id
    setTimeout(() => (copiedGameId.value = null), 1500)
  })
}
</script>

<style scoped>
/* Простая анимация для текста «Copied!» */
@keyframes fade-in {
  from { opacity: 0 }
  to   { opacity: 1 }
}
.animate-fade-in {
  animation: fade-in 0.3s forwards;
}

</style>
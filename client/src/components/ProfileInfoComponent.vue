<template>
  <div class="w-full max-w-2xl mx-auto rounded-2xl bg-gray-100/90 p-4 sm:p-6 md:p-8 shadow-md my-6 space-y-6">
    <!-- Верхняя панель профиля -->
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <!-- Аватар -->
        <div class="w-10 h-10 rounded-md bg-gray-400 overflow-hidden"></div>

        <!-- Имя + био -->
        <div class="flex flex-col">
          <span class="text-base font-bold text-gray-800">{{ user.name }}</span>
          <span class="text-xs text-gray-500 truncate">{{ user.bio || 'No bio provided.' }}</span>
        </div>
      </div>

      <!-- Кнопка выхода -->
      <button
        @click="logout"
        class="text-red-700 hover:text-red-800 transition"
        title="Logout"
      >
        <font-awesome-icon icon="sign-out-alt" />
      </button>
    </div>

    <!-- Email -->
    <div class="text-center text-gray-500 text-xs">{{ user.email }}</div>

    <!-- Список моих игр -->
    <div>
      <h2 class="text-lg sm:text-xl font-bold text-gray-700 mb-2">My Games</h2>

      <!-- ПК версия -->
      <div class="hidden sm:block bg-gray-200 rounded-xl p-4 space-y-3 max-h-60 overflow-y-auto">
        <div
          v-for="game in user.games"
          :key="game.id"
          class="flex items-center justify-between bg-white p-3 rounded-md hover:shadow transition cursor-pointer"
        >
          <div class="flex items-center gap-3 overflow-hidden">
            <span class="text-xs font-mono text-gray-500 truncate max-w-[80px]">{{ game.id }}</span>
            <RouterLink
              :to="{ name: 'game', params: { game_id: game.id } }"
              class="text-red-700 font-semibold hover:underline truncate"
            >
              {{ game.name }}
            </RouterLink>
          </div>

          <div class="flex items-center gap-1 shrink-0">
            <button
              @click="copyId(game.id)"
              class="text-gray-500 hover:text-gray-700 transition active:scale-90"
            >
              <font-awesome-icon :icon="copiedGameId === game.id ? 'check' : 'copy'" />
            </button>
            <span v-if="copiedGameId === game.id" class="text-green-600 text-xs animate-fade-in">
              Copied!
            </span>
          </div>
        </div>
      </div>

      <!-- Мобильная версия -->
      <div class="block sm:hidden bg-gray-200 rounded-xl p-3 overflow-x-auto whitespace-nowrap flex space-x-3">
        <div
          v-for="game in user.games"
          :key="game.id"
          class="inline-flex flex-col items-center bg-white p-2 rounded-md hover:shadow transition min-w-[120px]"
        >
          <span class="text-[10px] font-mono text-gray-500 truncate">{{ game.id }}</span>
          <RouterLink
            :to="{ name: 'game', params: { game_id: game.id } }"
            class="text-red-700 font-semibold text-sm hover:underline mt-1 truncate"
          >
            {{ game.name }}
          </RouterLink>
          <button
            @click="copyId(game.id)"
            class="text-gray-500 hover:text-gray-700 mt-2"
          >
            <font-awesome-icon :icon="copiedGameId === game.id ? 'check' : 'copy'" />
          </button>
        </div>
      </div>
    </div>

    <!-- Мини-кнопки действий -->
    <div class="flex justify-center gap-4 mt-6">
      <button
        @click="showJoinGameForm = true"
        class="px-4 py-2 bg-red-700 text-white text-sm rounded-md hover:bg-red-800 transition flex items-center justify-center"
        title="Join Game"
      >
        <font-awesome-icon icon="sign-in-alt" />
      </button>
      <button
        @click="showCreateGameForm = true"
        class="px-4 py-2 border border-red-700 text-red-700 text-sm rounded-md hover:bg-red-100 transition flex items-center justify-center"
        title="Create Game"
      >
        <font-awesome-icon icon="plus" />
      </button>
    </div>

    <!-- Create Game Modal -->
    <div v-if="showCreateGameForm" class="fixed inset-0 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-100 rounded-2xl shadow-2xl p-6 w-full max-w-sm space-y-6">
        <h2 class="text-xl font-bold text-center text-gray-800">Create Game</h2>
        <form @submit.prevent="createGame" class="space-y-4">
          <input v-model="gameName" type="text" placeholder="Game name" class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-700" autocomplete="gamename" />
          <textarea v-model="gameDescription" placeholder="Game description" class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-700"></textarea>
          <input v-model="createPassword" type="password" placeholder="Password" class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-700" autocomplete="new-password" />
          <input v-model="passwordConfirmation" type="password" placeholder="Confirm password" class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-700" autocomplete="new-password" />
          <div class="flex justify-end gap-2">
            <button type="submit" class="px-6 py-2 bg-red-700 text-white rounded-md hover:bg-red-800 transition">Create</button>
            <button type="button" @click="showCreateGameForm = false" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">Cancel</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Join Game Modal -->
    <div v-if="showJoinGameForm" class="fixed inset-0 flex items-center justify-center z-50 p-4">
      <div class="bg-gray-100 rounded-2xl shadow-2xl p-6 w-full max-w-sm space-y-6">
        <h2 class="text-xl font-bold text-center text-gray-800">Join Game</h2>
        <form @submit.prevent="joinGame" class="space-y-4">
          <input v-model="joinGameId" type="text" placeholder="Game ID" class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-700" autocomplete="gameid" />
          <input v-model="joinPassword" type="password" placeholder="Password" class="w-full p-3 border rounded-md focus:ring-2 focus:ring-red-700" autocomplete="current-password" />
          <div class="flex justify-end gap-2">
            <button type="submit" class="px-6 py-2 bg-red-700 text-white rounded-md hover:bg-red-800 transition">Join</button>
            <button type="button" @click="showJoinGameForm = false" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">Cancel</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useUserStore } from '@/stores/user'
import { storeToRefs } from 'pinia'
import axios from 'axios'
import router from '@/router'
import { pushToast } from '@/stores/toast.js'

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
    if (err.response?.status === 422) {
      // Если сервер отдаёт массив ошибок:
      const msg = err.response.data.message
                || Object.values(err.response.data.errors || {})
                    .flat().join('; ')
      pushToast(msg, 'error', 5000)
    } else {
        console.error('Create game failed:', err)
    }
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
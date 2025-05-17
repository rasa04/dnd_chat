<template>
  <div class="bg-chat-background-color col-span-3 flex flex-col h-full pt-2">
    <!-- область сообщений -->
    <div
      ref="scrollArea"
      class="flex flex-col bg-cover rounded-xl h-[500px] overflow-y-auto"
      :style="{ backgroundImage: `url(${chat_bg})` }"
    >
      <MessageComponent
        v-for="msg in messages"
        :key="msg.id"
        :message="msg"
        :from-another-player="msg.from !== user.id"
      />
    </div>

  <div class="flex items-center p-4 bg-chat-background-color space-x-2">
    <input
      v-model="message"
      @keyup.enter="send"
      :disabled="isSending"
      type="text"
      placeholder="Write..."
      class="flex-1 px-4 py-2 border border-gray-300 rounded-full
             focus:outline-none focus:ring-2 focus:ring-indigo-500
             transition-shadow duration-200 shadow-sm focus:shadow-md
             disabled:opacity-50 disabled:cursor-not-allowed"
    />
    <button
      @click="send"
      :disabled="isSending || !message.trim()"
      class="p-3 bg-indigo-600 text-white rounded-full
             hover:bg-indigo-700 active:scale-90 transition duration-150 ease-out
             disabled:opacity-50 disabled:cursor-not-allowed"
    >
      <font-awesome-icon
        v-if="!isSending"
        icon="paper-plane"
      />
      <font-awesome-icon
        v-else
        icon="spinner"
        spin
      />
    </button>
  </div>

  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { useRoute } from 'vue-router'
import { useUserStore } from '@/stores/user'
import axios from 'axios'
import MessageComponent from './MessageComponent.vue'
import chat_bg from '@/assets/img/chat_background.png'

const route = useRoute()
const gameId = Number(route.params.game_id)
const userStore = useUserStore()
const user = userStore.data

const messages = ref([])
const message = ref('')
const scrollArea = ref(null)
const isSending = ref(false)
let socket = null

// утилита автоскролла
async function scrollToBottom() {
  await nextTick()
  const el = scrollArea.value
  if (el) el.scrollTop = el.scrollHeight
}

onMounted(async () => {
  // 1) Загрузка истории из бэка — бэкенд теперь отдаёт ISO-время в поле `time`
  const { data } = await axios.get(
    `${import.meta.env.VITE_API_URL}/api/v1/messages/${gameId}`,
    { headers: { Authorization: localStorage.getItem('TOKEN') } }
  )

  messages.value = data.map(m => ({
    id: m.id,
    body: m.body,
    from: Number(m.from),
    rawTime: m.time, // ISO-строка
    type: Number(m.type)
  }))

  // скроллим в конец
  scrollToBottom()

  // 2) Открываем WS
  socket = new WebSocket(`ws://localhost:8081/ws/group?id=${gameId}`)
  socket.addEventListener('message', evt => {
    const m = JSON.parse(evt.data)
    messages.value.push({
      id: m.id,
      body: m.body,
      from: Number(m.from),
      rawTime: m.time,
      type: Number(m.type)
    })
    scrollToBottom()
  })
})

async function send() {
  if (!message.value.trim() || isSending.value) return

  isSending.value = true
  try {
    await axios.post(
      `${import.meta.env.VITE_API_URL}/api/v1/messages/`,
      { body: message.value, game_id: gameId },
      { headers: { Authorization: localStorage.getItem('TOKEN') } }
    )
    message.value = ''
    // скролл если нужно
    scrollToBottom()
  } catch (err) {
    console.error(err)
  } finally {
    isSending.value = false
  }
}
</script>

<style scoped>
</style>
<template>
  <div class="m-4">
    <!-- (Type = 1) Системное сообщение для дайсов -->
    <div v-if="message.type === 1" class="w-full flex justify-center my-4">
      <div
        class="relative max-w-md w-full px-4 py-2 rounded-lg text-center text-sm text-gray-800 select-none
               bg-white/30 backdrop-blur-sm border border-white/50"
      >
        <!-- Кто кидал -->
        <div class="uppercase font-medium mb-1">User {{ message.from }} rolled</div>
        <!-- Детали бросков -->
        <div>
          <span
            v-for="(r, i) in diceResults"
            :key="i"
            class="inline-block mx-1"
          >
            🎲 {{ r.macro }} → [{{ r.rolls.join(', ') }}] = {{ r.sum }}
          </span>
        </div>
        <!-- Время -->
        <div class="text-xs mt-1">{{ formattedTime }}</div>
      </div>
    </div>

    <!-- (Type = 0) Обычное сообщение -->
    <div v-else class="m-4">
      <!-- Подпись с ID отправителя -->
      <div class="mb-1 text-xs text-gray-500 select-none">
        <span v-if="fromAnotherPlayer">
          User id: {{ message.from }}
        </span>
      </div>

      <!-- Собственно пузырёк с текстом -->
      <div v-if="fromAnotherPlayer" class="inline-block bg-white rounded-xl p-2 whitespace-normal">
        <span>
          {{ message.body }}
          <br />
          <span class="text-gray-500 text-sm select-none">
            {{ formattedTime }}
          </span>
        </span>
      </div>
      <div v-else class="flex justify-end">
        <div class="bg-teal-800 text-white p-3 rounded-lg">
          {{ message.body }}
          <br />
          <span class="text-gray-300 text-sm select-none">
            {{ formattedTime }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
  
  <script setup>
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
  
  const props = defineProps({
    message:           { type: Object, required: true },
    fromAnotherPlayer: { type: Boolean, required: true }
  })
  
  // реактивное «сейчас» для обновления меток каждую минуту
  const now = ref(Date.now())
  let timer = null
  onMounted(() => {
    timer = setInterval(() => { now.value = Date.now() }, 60_000)
  })
  onBeforeUnmount(() => clearInterval(timer))

  // если это дайсы, распарсим JSON из поля body
  const diceResults = computed(() => {
    if (props.message.type !== 1) return []
    try {
      return JSON.parse(props.message.body)
    } catch {
      return []
    }
  })

  // форматируем ISO-время в «just now», «X minutes ago» и т.д.
  const formattedTime = computed(() => {
    const parsed = Date.parse(props.message.rawTime)
    if (isNaN(parsed)) {
      // на всякий случай — если не ISO, показываем оригинал
      return props.message.rawTime
    }
    const diff = Math.floor((now.value - parsed) / 1000)
    if (diff < 60) {
         return 'just now'
    }
    if (diff < 3600) {
      const m = Math.floor(diff / 60)
      return m === 1 ? '1 minute ago' : `${m} minutes ago`
    }
    if (diff < 86400) {
      const h = Math.floor(diff / 3600)
      return h === 1 ? '1 hour ago' : `${h} hours ago`
    }
    // старше суток — просто отображаем время HH:MM
    return new Date(parsed).toLocaleTimeString('en-US', {
      hour:   '2-digit',
      minute: '2-digit'
    })
  })
  </script>
  
  <style scoped>
  /* без изменений */
  </style>
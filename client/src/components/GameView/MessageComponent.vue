<template>
    <div class="m-4">
      <div
        v-if="fromAnotherPlayer"
        class="inline-block bg-white rounded-xl p-2 whitespace-normal"
      >
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
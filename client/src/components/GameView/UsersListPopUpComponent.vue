<template>
  <div
    v-if="modelValue"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    @click.self="close"
  >
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-80 max-w-full p-4">
      <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">
        Players ({{ game.participants.length }})
      </h3>
      <ul class="space-y-2 max-h-64 overflow-y-auto">
        <li
          v-for="p in game.participants"
          :key="p.id"
          class="flex items-center space-x-3 py-2 px-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
        >
          <!-- аватар-заглушка -->
          <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex-shrink-0"></div>
          
          <!-- Контейнер с полями -->
          <div class="flex-1 text-sm text-gray-800 dark:text-gray-200 flex flex-wrap items-center">
            <!-- ID -->
            <span 
              class="font-semibold text-indigo-600 hover:underline cursor-pointer"
              @click="copyPlayerId(p.id)"
            >
              #{{ p.id }}
            </span>
            <!-- индикатор "Copied!" -->
            <span 
              v-if="copiedPlayerId === p.id" 
              class="ml-2 text-xs text-green-600 select-none"
            >
              Copied!
            </span>
            
            <!-- нормальный разделитель -->
            <span class="mx-2 text-gray-400">—</span>
            
            <!-- Name -->
            <span class="font-medium">{{ p.name }}</span>
            
            <span class="mx-2 text-gray-400">—</span>
            
            <!-- Email -->
            <span class="text-gray-500">{{ p.email }}</span>
            
            <!-- Иконка мастера -->
            <font-awesome-icon
              v-if="p.id === game.game_master_id"
              icon="crown"
              class="ml-2 text-yellow-500"
              title="Game Master"
            />
          </div>
        </li>
        <li
          v-if="!game.participants.length"
          class="text-gray-500 text-center py-2"
        >
          No players
        </li>
      </ul>
      <div class="mt-4 text-right">
        <button
          @click="close"
          class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// Принимаем v-model и game
const props = defineProps({
  modelValue: { type: Boolean, required: true },
  game:        { type: Object,  required: true }
})
const emit = defineEmits(['update:modelValue'])

const copiedPlayerId = ref(null)

function copyPlayerId(id) {
  navigator.clipboard.writeText(String(id)).then(() => {
    copiedPlayerId.value = id
    setTimeout(() => {
      copiedPlayerId.value = null
    }, 1500)
  })
}

function close() {
  emit('update:modelValue', false)
}
</script>

<style scoped>
/* по желанию добавьте transition для плавного появления/исчезания */
</style>

<!-- src/components/ToastContainer.vue -->
<template>
    <div class="fixed top-4 right-4 flex flex-col gap-2 z-50">
      <div
        v-for="t in toasts"
        :key="t.id"
        :class="[
          'px-4 py-2 rounded shadow-lg transition-all',
          t.type === 'error'   ? 'bg-red-600 text-white'
        : t.type === 'success' ? 'bg-green-600 text-white'
                                : 'bg-gray-600 text-white'
        ]"
      >
        {{ t.text }}
      </div>
    </div>
  </template>
  
  <script setup>
  import { toasts } from '@/stores/toast.js'
  </script>
  
  <style scoped>
  /* Можно добавить анимации, например fade-in/fade-out */
  </style>
  
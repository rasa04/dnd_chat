import { reactive } from 'vue'

// Массив всех сообщений
export const toasts = reactive([])

/**
 * Добавить тост
 * @param {string} text — текст сообщения
 * @param {'error'|'success'} [type='error'] — тип (стиль)
 * @param {number} [timeout=3000] — время жизни в мс
 */
export function pushToast(text, type = 'error', timeout = 3000) {
  const id = Date.now() + Math.random() // гарантированно уникальный
  toasts.push({ id, text, type })

  // Авто-удаление спустя timeout
  setTimeout(() => {
    const idx = toasts.findIndex(t => t.id === id)
    if (idx !== -1) toasts.splice(idx, 1)
  }, timeout)
}

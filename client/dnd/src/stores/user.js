import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    data: {}
  }),

  getters: {
    doubleCount: (state) => state.count * 2
  },

  actions: {
    get() {
      return this.data
    }
  },
})

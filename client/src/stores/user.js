import { defineStore } from 'pinia'
import axios from "axios";

export const useUserStore = defineStore('user', {
  state: () => ({
    data: {}
  }),

  actions: {
    get() {
      return this.data
    },
    addGame(game) {
      this.data.games.unshift(game)
    },
    refresh() {
      axios.get(`${import.meta.env.VITE_API_URL}/api/user`, {
        headers: {'Authorization': localStorage.getItem('TOKEN')}
      })
      .then(res => {
        this.data = res.data
        console.log('-------data--------')
        console.log(this.data)
      })
    }
  },
})

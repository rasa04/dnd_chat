import { defineStore } from 'pinia'
import axios from "axios";

export const useGameStore = defineStore('current_game', {
  state: () => ({
    current_game_id: null,
    data: {}
  }),

  actions: {
    get() {
      return this.data
    },
    refresh() {
      return new Promise((resolve, reject) => {
        axios.get(`${import.meta.env.VITE_API_URL}/api/v1/game/` + this.current_game_id, {
          headers: {'Authorization': localStorage.getItem('TOKEN')}
        }).then(
          res => {
            this.data = res.data
            resolve()
        }).catch(error => {
            reject(error)
        })
      })
    }
  },
})

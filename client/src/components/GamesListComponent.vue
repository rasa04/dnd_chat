<template>
  <!-- Games list -->
  <div v-if="!isGameInfoOpened" class="m-auto text-second-color rounded-xl p-5
  overflow-auto  my-4 select-none pointer-events-auto">
    <div class="grid">
      <h1 class="font-bold text-2xl">Games</h1>
      <input v-model="game_search" placeholder="search..."
             class="focus:outline-none bg-gray-200 p-1 my-1 rounded-lg">
    </div>
    <div class="w-full overflow-auto">
      <div class="border-0 border-second-color rounded-lg p-1 my-1 cursor-pointer whitespace-pre-wrap"
           @click.prevent="showGameInfo(game)" v-for="game in games">
        <code>{{ game.id }}</code> {{ game.name }}
      </div>
    </div>
  </div>
  <!-- Game Info PopUp -->
  <div v-if="isGameInfoOpened" class="m-auto overflow-auto my-4 p-5 rounded-xl">
      <div class="flex">
        <a class="p-1 font-bold rounded-lg bg-second-color text-white cursor-pointer"
           @click="showGameInfo()">
          <font-awesome-icon :icon="['fas', 'backward']" />
        </a>
      </div>
      <h2 class="text-2xl font-bold mb-2">{{ infoFormGame.name }}</h2>
      <p class="text-gray-600 mb-4">Game ID: {{ infoFormGame.id }}</p>
      <p class="text-gray-800 mb-4">{{ infoFormGame.description }}</p>
      <div class="flex flex-col">
        <div class="flex items-center border border-x-0 border-second-color"
             v-for="participant in infoFormGame.participants">
          <font-awesome-icon :icon="['fas', 'user']" />
          <div class="ml-2">
            <p class="font-semibold">{{ participant.name }}</p>
            <p class="font-semibold">{{ participant.email }}</p>
            <p class="text-sm text-gray-600">ID: {{ participant.id }}</p>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "GamesListComponent",

  data() {
    return {
      isGameInfoOpened: false,
      infoFormGame: [],
      users: [],
      games: [],
      game_search: '',
      user_search: '',
    }
  },

  methods: {
    showGameInfo(game) {
      this.isGameInfoOpened = !this.isGameInfoOpened
      this.infoFormGame = game
      console.log(this.games)
    }
  },

  created() {
    axios.get(`${import.meta.env.VITE_API_URL}/api/v1/game`, {
          headers: {Authorization: localStorage.getItem('TOKEN')}
        })
        .then(res => {
          this.games = res.data
          console.log(res.data)
        })
  }
}
</script>

<style scoped>

</style>
<template>
  <div class="rounded-lg shadow-lg p-5 text-white my-4">
    <div class="gap-4 gap-y-8">
      <div class="text-second-color">
        <h1 class="text-2xl font-bold mb-5">{{ user.name }}</h1>
        <div class="about-section">
          <label class="font-bold"></label>
          <p class="text-gray-700">{{ user.email }}</p>
          <p class="text-gray-700">Bio</p>
        </div>
        <h2 class="text-lg font-bold text-center mb-2 text-second-color">MY GAMES</h2>
        <div class="flex flex-col bg-gray-200 p-4 rounded-lg h-36 overflow-auto">
            <RouterLink v-for="game in user.games" class="text-blue-500 hover:underline decoration-sky-500"
                        :to="{name: 'home'}">{{ game.name }}</RouterLink>
        </div>
      </div>
      <div class="flex select-none pointer-events-auto text-lg bg-second-color font-semibold
      text-white rounded-lg mb-2 p-2 justify-between">
        <a @click.prevent="joinGame" class=""
        >Join Game</a>
        <a @click.prevent="createGame" class=""
        >Create Game</a>
      </div>
    </div>

    <div v-if="showCreateGameForm" class="fixed inset-0 flex items-center justify-center">
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Create Game</h2>
        <form @submit.prevent="createGame">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="gameName">Game Name</label>
            <input v-model="gameName" type="text" id="gameName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="gameDescription">Game Description</label>
            <textarea v-model="gameDescription" id="gameDescription" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
          </div>
          <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Create</button>
            <a @click.prevent="createGame" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Join Game Form Popup -->
    <div v-if="showJoinGameForm" class="fixed inset-0 flex items-center justify-center z-40">
      <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-5 text-second-color">
        <h2 class="text-lg font-bold mb-5">Join Game</h2>

        <!-- Form Inputs -->
        <div class="mb-4">
          <label class="block font-semibold mb-1">Game ID</label>
          <input v-model="gameId" type="text" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none" />
        </div>
        <div class="mb-4">
          <label class="block font-semibold mb-1">Password</label>
          <input v-model="password" type="password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none" />
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end">
          <button @click="joinGame" class="text-sm text-gray-600 hover:underline focus:outline-none mr-4">Cancel</button>
          <button class="text-sm font-semibold bg-second-color text-white px-4 py-2 rounded-lg focus:outline-none">Join</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {useUserStore} from "@/stores/user";

export default {
  name: "ProfileInfoComponent",
  data() {
    return {
      user: useUserStore().data,
      showCreateGameForm: false,
      showJoinGameForm: false,
      gameName: null,
      gameDescription: null,
      gameId: null,
      password: null,
    }
  },
  methods: {
    joinGame() {
      this.showJoinGameForm = !this.showJoinGameForm
      this.gameId = ''
      this.password = ''
      console.log('joining to the game')
    },
    createGame() {
      this.showCreateGameForm = !this.showCreateGameForm
      this.gameName = ''
      this.gameDescription = ''
      console.log('creating game')
    },
  },
}
</script>

<style scoped>

</style>
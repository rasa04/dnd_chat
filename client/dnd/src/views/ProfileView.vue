<template>
  <div class="w-screen h-screen bg-second-color grid place-content-center">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg p-5 text-second-color z-30">
      <div class="flex">
        <div class="w-1/2 pr-4">
          <div class="chats-list">
            <h2 class="text-lg font-bold text-center mb-2">GAMES</h2>
            <ul class="bg-gray-200 p-4 rounded-lg">
              <li class="mb-2">
                <RouterLink class="text-blue-500 hover:underline decoration-sky-500"
                            :to="{name: 'home'}">Game 1</RouterLink>
              </li>
              <li class="mb-2">
                <RouterLink class="text-blue-500 hover:underline decoration-sky-500"
                            :to="{name: 'home'}">Game 2</RouterLink>
              </li>
              <li class="mb-2">
                <RouterLink class="text-blue-500 hover:underline decoration-sky-500"
                            :to="{name: 'home'}">Game 3</RouterLink>
              </li>
            </ul>
          </div>
        </div>
        <div class="w-1/2 pl-4">
          <h1 class="text-2xl font-bold mb-5">{{ user.name }}</h1>
          <div class="about-section">
            <p class="text-gray-700">Email: {{ user.email }}</p>
            <p class="text-gray-700">Bio</p>
          </div>
        </div>
      </div>
      <div class="flex justify-between mt-5">
        <div class="w-1/2">
            <a @click.prevent="joinGame" class="text-lg font-semibold mb-2 bg-second-color p-2 rounded-lg text-white
            select-none pointer-events-auto"
            >Join Game</a>
        </div>
        <div class="w-1/2">
            <a @click.prevent="createGame" class="text-lg font-semibold mb-2 bg-second-color p-2 rounded-lg text-white
            select-none pointer-events-auto"
            >Create Game</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {useUserStore} from "@/stores/user";

export default {
  data() {
    return {
      user: useUserStore().data,
    }
  },

  methods: {
    joinGame() {
      console.log('joined!')
    },
    createGame() {
      console.log('created!')
    }
  }
}
</script>
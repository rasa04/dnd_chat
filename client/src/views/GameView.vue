<template>
  <main class="h-screen flex flex-col">
    <div class="bg-gradient-to-r from-indigo-500 from-10% via-teal-500 via-30% to-emerald-500 to-90%">
      <div class="grid grid-cols-3 justify-items-center content-center gap-4">
        <div>
          <router-link :to="{ name: 'profile' }" class="mr-4 text-white hover:underline">
            <font-awesome-icon :icon="['fas', 'house']" class="w-8 h-8" />
          </router-link>
        </div>
        <div class="flex space-x-8">
          <img v-if="game.photo_link" :src="game.photo_link" class="w-8 h-8 rounded-full" alt="Game Photo">
          <font-awesome-icon v-else icon="fas fa-gamepad"
            class="ml-4 w-10 h-10 text-white rounded-full bg-second-color flex items-center justify-center"></font-awesome-icon>
          <span class="font-serif text-3xl font-bold text-center">{{ game.name }}</span>
        </div>
        <div>
          <div>
              <font-awesome-icon :icon="['fas', 'users']" class="w-8 h-8"/>
              <span v-if="game.participants">{{ game.participants.length }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-5 bg-slate-500 flex-grow">
      <div class="bg-second-color text-white">
        <img src="@/assets/img/bestiary.png" width="100" height="100" alt="">
        <img src="@/assets/img/spells.png" width="100" height="100" alt="">
      </div>
      <ChatComponent />
      <div class="bg-second-color text-white">
        <img src="@/assets/img/dice.png" width="100" height="100" alt="">
      </div>
    </div>
  </main>
</template>

<script>
import ChatComponent from "@/components/GameView/ChatComponent.vue";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import axios from "axios";

export default {
  name: "GameView",

  components: {
    ChatComponent
  },


  data() {
    return {
      game: [],
    }
  },

  created() {
    // Fetching game data
    if (!this.$route.params.game_id) {
      this.$router.push('/profile');
    }
    axios.get(
      `${import.meta.env.VITE_API_URL}/api/v1/game/` + this.$route.params.game_id,
      {
        headers: {
          'Authorization': localStorage.getItem('TOKEN')
        }
      }
    ).then(res => this.game = res.data)
  },
}
</script>
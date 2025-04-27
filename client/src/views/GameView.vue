<template>
  <main class="h-screen flex flex-col">
    <!-- Шапка -->
    <div class="bg-gradient-to-r from-indigo-500 via-teal-500 to-emerald-500 p-4">
      <div class="grid grid-cols-3 justify-items-center items-center gap-4">
        <!-- Домик -->
        <router-link :to="{ name: 'profile' }" class="text-white hover:underline">
          <font-awesome-icon :icon="['fas','house']" class="w-8 h-8"/>
        </router-link>

        <!-- Название и аватар -->
        <div class="flex items-center space-x-4">
          <img
            v-if="game.photo_link"
            :src="game.photo_link"
            class="w-8 h-8 rounded-full"
            alt="Game Photo"
          />
          <font-awesome-icon
            v-else
            icon="gamepad"
            class="w-10 h-10 text-white rounded-full bg-second-color p-1"
          />
          <span class="font-serif text-3xl font-bold text-white">{{ game.name }}</span>
        </div>

        <!-- Количество игроков -->
        <div class="flex items-center space-x-2 text-white">
          <font-awesome-icon :icon="['fas','users']" class="w-8 h-8"/>
          <span>{{ game.participants?.length || 0 }}</span>
        </div>
      </div>
    </div>

    <!-- Тело: чат -->
    <div class="flex-1 overflow-hidden bg-second-color">
      <div class="h-full mx-auto px-4 md:px-20 lg:px-40">
        <!-- ChatComponent займёт всю ширину контейнера -->
        <ChatComponent class="h-full w-full" />
      </div>
    </div>

    <!-- Футер: опции -->
    <div class="bg-slate-500 py-4 px-4 md:px-8">
      <div class="max-w-6xl mx-auto flex justify-between space-x-4">
        <div class="flex-1 bg-second-color text-white p-4 rounded-lg flex flex-col items-center">
          <img src="@/assets/img/bestiary.png" alt="Bestiary" class="w-16 h-16 mb-2"/>
          <span>Bestiary</span>
        </div>
        <div class="flex-1 bg-second-color text-white p-4 rounded-lg flex flex-col items-center">
          <img src="@/assets/img/spells.png" alt="Spells" class="w-16 h-16 mb-2"/>
          <span>Spells</span>
        </div>
        <div class="flex-1 bg-second-color text-white p-4 rounded-lg flex flex-col items-center">
          <img src="@/assets/img/dice.png" alt="Dice" class="w-16 h-16 mb-2"/>
          <span>Dice</span>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import ChatComponent from "@/components/GameView/ChatComponent.vue";
import axios from "axios";

export default {
  name: "GameView",
  components: { ChatComponent },
  data() {
    return {
      game: {},
    };
  },
  async created() {
    const id = this.$route.params.game_id;
    if (!id) {
      return this.$router.push({ name: "profile" });
    }
    try {
      const { data } = await axios.get(
        `${import.meta.env.VITE_API_URL}/api/v1/game/${id}`,
        { headers: { Authorization: localStorage.getItem("TOKEN") } }
      );
      this.game = data;
    } catch (err) {
      console.error("Failed to fetch game:", err);
      this.$router.push({ name: "profile" });
    }
  },
};
</script>

<style scoped>
</style>
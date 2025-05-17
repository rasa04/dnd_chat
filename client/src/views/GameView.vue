<template>
  <main class="h-screen flex flex-col">
    <!-- Шапка -->
    <div class="bg-gradient-to-r from-purple-900 via-red-800 to-red-700 p-4">
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
            class="w-10 h-10 text-white rounded-full bg-chat-background-color p-1"
          />
          <span class="font-serif text-3xl font-bold text-white">{{ game.name }}</span>
        </div>

        <!-- Количество игроков -->
        <div
          class="flex items-center space-x-2 text-white cursor-pointer"
          @click="showParticipantsModal = true"
          title="Show all players"
        >
          <font-awesome-icon :icon="['fas','users']" class="w-8 h-8"/>
          <span>{{ game.participants?.length || 0 }}</span>
        </div>
      </div>
    </div>

    <!-- Список игроков -->
    <UsersListPopUpComponent v-model="showParticipantsModal" :game="game" />

    <!-- Тело: чат -->
    <div class="flex-1 overflow-hidden bg-chat-background-color">
      <div class="h-full mx-auto px-4 md:px-20 lg:px-40">
        <ChatComponent class="h-full w-full" />
      </div>
    </div>

    <!-- Футер -->
    <div class="bg-gray-800 py-4 px-4 md:px-8">
      <div class="max-w-6xl mx-auto flex justify-between space-x-4">
        <div class="flex-1 bg-chat-background-color text-white p-4 rounded-lg flex flex-col items-center">
            <!-- Бестиарий -->
            <div>
              <img src="@/assets/img/bestiary.png" alt="Bestiary" class="w-16 h-16 mb-2" />
              <span>Bestiary</span>
            </div>
            <!-- Заклинания -->
            <div>
              <span>Spells</span>
              <img src="@/assets/img/spells.png" alt="Spells" class="w-16 h-16 mb-2" />
            </div>
        </div>

        <!-- Кости (выбор дайса) -->
        <DicesPanelComponent />
      </div>
    </div>
  </main>
</template>

<script>
import ChatComponent from "@/components/GameView/ChatComponent.vue";
import DicesPanelComponent from "@/components/GameView/DicesPanelComponent.vue";
import UsersListPopUpComponent from "@/components/GameView/UsersListPopUpComponent.vue";
import axios from "axios";

export default {
  name: "GameView",
  components: { ChatComponent, DicesPanelComponent, UsersListPopUpComponent },

  data() {
    return {
      game: {},
      showParticipantsModal: false,
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
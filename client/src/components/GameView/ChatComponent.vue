<template>
  <div class="bg-second-color col-span-3 flex flex-col h-full pt-2">
    <div class="flex-grow bg-cover rounded-xl h-[500px] overflow-y-auto"
      style="background-image: url('./src/assets/img/background.jpg')"
    >
      <div v-for="message in messages">
        <MessageComponent :message="message" :fromAnotherPlayer="message === this.user.id" />
      </div>
    </div>
    <div class="flex justify-center items-center p-4 bg-second-color">
      <input
        v-model="message"
        @keyup.enter="send"
        type="text"
        placeholder="Write..."
        class="mr-2 px-4 py-2 border rounded-2xl focus:outline-none w-full"
      />
      <button
        @click="send"
        class="px-4 py-2 bg-second-color text-white rounded-2xl focus:outline-none hover:bg-blue-500 transition duration-200">
        <font-awesome-icon :icon="['fas', 'paper-plane']" />
      </button>
    </div>
  </div>
</template>

<script>
import MessageComponent from './MessageComponent.vue';
import axios from "axios";
import {useUserStore} from "@/stores/user";
export default {
  name: "chat",
  props: {},
  components: { MessageComponent },
  data() {
    return {
      messages: [],
      user: null,
      message: ''
    }
  },

  created() {
    this.user = useUserStore().data
    axios.get(
        `${import.meta.env.VITE_API_URL}/api/v1/messages/` + this.$route.params.game_id,
        {
          headers: {
            Authorization: localStorage.getItem('TOKEN')
          }
        }
      )
      .then(res => this.messages = res.data)
  },

  methods: {
    send() {
      axios.post(`${import.meta.env.VITE_API_URL}/api/v1/messages`,
          {
            body: this.message,
            game_id: this.$route.params.game_id
          },
          {
            headers: {
              Authorization: localStorage.getItem('TOKEN')
            }
          }
      ).then(res => {
        console.log(res)
        this.messages.shift(res.data)
      })
      this.message = ''
    },
  }
}
</script>

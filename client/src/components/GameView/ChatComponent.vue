<template>
  <div class="bg-second-color col-span-3 flex flex-col h-full pt-2">
    <div class="flex-grow bg-cover rounded-xl h-[500px] overflow-y-auto"
      style="background-image: url('./src/assets/img/background.jpg')"
    >
      <div v-for="message in messages">
        <MessageComponent :message="message" :fromAnotherPlayer="message.from !== this.user.id" />
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
      message: '',
      socket: WebSocket
    }
  },

  created() {
    // Setting up websocket connection
    this.socket = new WebSocket('ws://localhost:8081/ws/group?id=' + this.$route.params.game_id);

    this.socket.addEventListener('open', () => this.socket.send(
      'opening connection to group by id:' + this.$route.params.game_id
    ));
    this.socket.addEventListener('message', (event) => {
      let new_message = JSON.parse(event.data)
      this.messages.unshift({
        body: new_message.body,
        from: new_message.from,
        time: this.formatTime(new_message.time)
      })
    });
    this.socket.addEventListener('close', () => console.log('WebSocket connection is closed'));

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
      axios.post(
        `${import.meta.env.VITE_API_URL}/api/v1/messages/`,
        {
          body: this.message,
          game_id: this.$route.params.game_id
        },
        {headers: {Authorization: localStorage.getItem('TOKEN')}}
      ).then(response => {
        this.messages.unshift({
          body: this.message,
          from: this.user.id,
          time: response.time
        })

        this.message = ''
      })
    },

    // For now, formatting time in real time messages and messages, gotten through ajax request are different.
    // Hope in feature i fix it.
    formatTime(isoTimestamp) {
      const date = new Date(isoTimestamp);
      const now = new Date();
      const diffInSeconds = Math.floor((now - date) / 1000);

      if (diffInSeconds < 60) {
        return "just now";
      } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return minutes === 1 ? "1 minute ago" : `${minutes} minutes ago`;
      } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return hours === 1 ? "1 hour ago" : `${hours} hours ago`;
      } else {
        // Format timestamp as just time (e.g., 12:21)
        const options = {
          hour: '2-digit',
          minute: '2-digit'
        };
        return date.toLocaleTimeString('en-US', options);
      }
    }
  }
}
</script>

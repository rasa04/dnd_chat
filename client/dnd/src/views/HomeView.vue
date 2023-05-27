<template>
  <main class="h-screen flex flex-col">
    <div class="h-12 bg-gradient-to-r from-indigo-500 from-10% via-sky-500 via-30% to-emerald-500 to-90%
      text-second-color font-bold text-center text-2xl">Эпическая компания</div>
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
import ChatComponent from "@/components/ChatComponent.vue";
import axios from 'axios'
import {useUserStore} from "@/stores/user";
export default {

  components: {
    ChatComponent
  },

  created() {
    let endpoint = import.meta.env.VITE_API_URL
    let token = localStorage.getItem('TOKEN')
    let user = useUserStore()

    axios.get(`${endpoint}/api/user`, {headers: {'Authorization': token}})
    .then((r) => {
      user.data = r.data
      console.log(user.data)
    })

  }
}
</script>
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
export default {

  components: {
    ChatComponent
  },

  created() {
    axios.get('http://127.0.0.1:8000/api/user', {
      headers: {
        'Authorization': 'Bearer 2|v2Fdwc8q0mnISnTU7hklxYvyCGwOvYnWxhyJmb8y'
      }
    }).then((r) => {
      console.log(r.data);
    })
  }
}
</script>
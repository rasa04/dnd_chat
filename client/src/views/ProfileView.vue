<template>
  <div class="fixed top-0 left-0 w-screen h-screen">
    <video autoplay loop muted class="absolute z-10 min-w-full min-h-screen">
      <source :src="'./assets/img/auth_backgrounds/'+background_video+'.mp4'" type="video/mp4" />
      Your browser does not support the video tag.
    </video>
  </div>
  <div class="absolute z-30 w-screen h-screen flex space-x-4 place-content-center bg-gray-500 opacity-90">
    <games-list-component />
    <profile-info-component />
  </div>
</template>

<script>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import GamesListComponent from "@/components/GamesListComponent.vue";
import ProfileInfoComponent from "@/components/ProfileInfoComponent.vue";

export default {
  components: {
    ProfileInfoComponent,
    GamesListComponent,
    FontAwesomeIcon
  },

  data() {
    return {
      background_video: Math.floor(Math.random() * 2)+1
    }
  }
}
</script>
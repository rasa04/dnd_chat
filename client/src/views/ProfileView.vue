<template>
  <!-- фон -->
  <div class="fixed inset-0">
    <video
      autoplay
      loop
      muted
      class="absolute inset-0 object-cover"
    >
      <source
        :src="'/assets/img/auth_backgrounds/' + background_video + '.mp4'"
        type="video/mp4"
      />
    </video>
  </div>

  <!-- контейнер для UI -->
  <div
    class="absolute inset-0
           flex flex-col md:flex-row
           h-screen
           bg-gray-500 bg-opacity-90
           p-4"
  >
    <!-- Профиль -->
    <div
      class="order-1
             w-full md:w-2/3
             mb-4 md:mb-0
             pr-0 md:pr-4"
    >
      <profile-info-component />
    </div>

    <!-- Список игр -->
    <div
      class="order-2
             w-full md:w-1/3
             md:h-full
             overflow-y-auto"
    >
      <games-list-component />
    </div>
  </div>
</template>

<script>
import GamesListComponent from "@/components/GamesListComponent.vue";
import ProfileInfoComponent from "@/components/ProfileInfoComponent.vue";

export default {
  components: {
    GamesListComponent,
    ProfileInfoComponent,
  },
  data() {
    return {
      background_video: ~~(Math.random() * 3) + 1,
    };
  },
};
</script>
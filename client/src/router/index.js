import { createRouter, createWebHistory } from 'vue-router'
import RegisterView from "@/views/RegisterView.vue";
import LoginView from "@/views/LoginView.vue";
import ProfileView from "@/views/ProfileView.vue";
import axios from "axios";
import {useUserStore} from "@/stores/user";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: ProfileView,
      meta: {
        auth: true
      }
    },
    {
      path: '/game/:game_id',
      name: 'game',
      component: () => import('../views/GameView.vue'),
      meta: {
        auth: true
      },
    },
    {
      path: '/profile',
      name: 'profile',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/ProfileView.vue'),
      meta: {
        auth: true
      }
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    }
  ]
})

router.beforeEach((to, from, next) => {
  let endpoint = import.meta.env.VITE_API_URL
  let user = useUserStore()
  let token = localStorage.getItem('TOKEN')

  if (to.matched.some(record => record.meta.auth)) { // is route protected
    if(token) { // is token exist
      axios.get(`${endpoint}/api/v1/user`, {headers: {'Authorization': token}})
      .then((res) => {
        next()
        user.data = res.data
      })
      .catch(() => next({ name: 'login' }))
    } else {
      next({ name: 'login' });
    }
  } else {
    if(token) { // is token exist
      axios.get(`${endpoint}/api/v1/user`, {headers: {'Authorization': token}})
          .then(() => next({name: 'profile'}))
          .catch(() => next())
    }
    else next(); // Very important to call next() in this case!
  }
})

export default router

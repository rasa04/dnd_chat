import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from "@/views/RegisterView.vue";
import LoginView from "@/views/LoginView.vue";
import axios from "axios";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: {
        auth: true
      }
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
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
  let token = localStorage.getItem('TOKEN')
  if (to.matched.some(record => record.meta.auth)) { // is route protected
    if(token) { // is token exist
      axios.get(`${import.meta.env.VITE_API_URL}/api/user`, {headers: {'Authorization': token}})
      .then((res) => {
        next()
        console.log('authorized')
      })
      .catch(() => {
        next({ name: 'login' })
        console.log('Unauthorized')
      })
    }
    else next({ name: 'login' });
  }
  else {
    if(token) { // is token exist
      axios.get(`${import.meta.env.VITE_API_URL}/api/user`, {headers: {'Authorization': token}})
          .then(() => next({name: 'home'}))
          .catch(() => next())
    }
    else next(); // Very important to call next() in this case!
  }
})

export default router

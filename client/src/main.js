import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

// Font-awesome
import { library, dom } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// dom.watch() // если иконки не работают попробовать раскомментировать
library.add(fas)
//

const app = createApp(App)

app.use(createPinia())
    .use(router)
    .component('font-awesome-icon', FontAwesomeIcon)

app.mount('#app')

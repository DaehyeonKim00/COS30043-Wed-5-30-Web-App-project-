import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { store } from './store'

import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// Restore the logged-in user from localStorage so a page refresh
// keeps the session (Vuex state is otherwise reset on reload).
const savedUser = JSON.parse(localStorage.getItem('user'))
if (savedUser) {
  store.commit('setUser', savedUser)
}

const app = createApp(App)
app.use(router)
app.use(store)
app.mount('#app')


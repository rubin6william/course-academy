require('./bootstrap')

import { createApp } from 'vue'
import App from './views/App.vue'
import router from './router/index'
import api from './api'

const app = createApp(App)
app.use(router)
app.provide('api', api)
app.mount('#app')

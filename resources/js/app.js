import '../sass/app.scss'
import '../sass/dictionary.scss'
import '../sass/games.scss'
import '../sass/podcasts.scss'
import '../sass/pre_qualifications.scss'
import '../sass/qcm.scss'

import { createApp } from 'vue'

const app = createApp({})

// Declare Vue.js components from folder components/ recursively
const components = import.meta.glob('./components/**/*.vue', { eager: true })
for (const path in components) {
  const component = components[path].default
  const name = path.split('/').pop().replace('.vue', '')
  app.component(name, component)
}

app.mount('#vue-app')

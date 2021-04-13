require('./bootstrap')

import Vue from 'vue'

Vue.mixin({
  data: () => ({
    debug: window.debug,
    formerAppUrl: window.formerAppUrl
  })
})

// Declare Vue.js components from folder components/ recursively
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

new Vue({
  el: '#wrap'
})

window.Vue = Vue

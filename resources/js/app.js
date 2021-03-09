import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'


// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.prototype.$http = axios


// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

window.Vue = require('vue').default;

Vue.component('landing-page', require('./components/LandingPage.vue').default);
Vue.component('leaderboard-page', require('./components/LeaderboardPage.vue').default);
Vue.component('table-component', require('./components/TableComponent.vue').default);

const app = new Vue({
    el: '#app',
});

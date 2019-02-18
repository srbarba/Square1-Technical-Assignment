// Import libraries
import Vue from 'vue'
import Router from 'vue-router'
import Meta from 'vue-meta'
import VueLazyload from 'vue-lazyload'
import LaravelVuePagination from 'laravel-vue-pagination'
import VueSwal from 'vue-swal'
import Hashids from 'Hashids'
import VueCarousel from 'vue-carousel';

Vue.use((Vue)=>{ Vue.prototype.$hashids = Vue.$hashids = new Hashids("rh943f3n49fn34", "24") })

import axios from 'axios'
import VueAxios from 'vue-axios'

Vue.use(Router)
Vue.use(Meta)
Vue.use(VueLazyload)
Vue.use(VueSwal)
Vue.use(VueCarousel);

Vue.use(VueAxios, axios)
axios.defaults.baseURL = 'http://localhost:8000/api/'

// Import state management
import { store } from './store.js';

// Import layout globally
import Layout from './views/layout/Layout'

Vue.component('app-layout', Layout)
Vue.component('app-pagination', LaravelVuePagination)

// Import pagination component globally
import Pagination from 'laravel-vue-pagination'

Vue.component('pagination', Pagination);

// Require bootstrap and axios
require('./bootstrap');

// Import app root file
import App from './views/App'

import router from './routes'

Vue.router = router

Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});

App.router = Vue.router

new Vue({ store, render: h => h(App) }).$mount('#app');

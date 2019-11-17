import Vue from 'vue'
import App from './App.vue'


import Vuesax from 'vuesax'
import 'material-icons/iconfont/material-icons.css' //Material Icons
import 'vuesax/dist/vuesax.css'; // Vuesax
Vue.use(Vuesax)

// Theme Configurations
import './themeConfig.js'

// Globally Registered Components
import './globalComponents.js'

// Styles: SCSS
import '../sass/main.scss'

// Tailwind
import '../css/main.css';

// Vue Router
import router from './router'

// Vuex Store
import store from './store/store'

// i18n
import i18n from './i18n/i18n'

// Vuesax Admin Filters
import './filters/filters'

// VeeValidate
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

// Google Maps
import * as VueGoogleMaps from 'vue2-google-maps'
Vue.use(VueGoogleMaps, {
    load: {
        // Add your API key here
        key: '',
        libraries: 'places', // This is required if you use the Auto complete plug-in
    },
})

// PrismJS
import 'prismjs'
import 'prismjs/themes/prism.css'

// Feather font icon
require('./assets/css/iconfont.css')

Vue.config.productionTip = false

new Vue({
    router,
    store,
    i18n,
    render: h => h(App)
}).$mount('#app')